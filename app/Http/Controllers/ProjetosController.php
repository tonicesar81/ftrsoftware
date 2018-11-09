<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Shopping;
use App\Projetos;
use App\ProjetosArquivos;
use App\Tipo_relatorios;
use App\Relatorios;
use App\User_dados;
use App\Mail\NewProjeto;

class ProjetosController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    private function nivel(){
        $nivel = User_dados::where('users_id', Auth::id())->value('user_levels_id');
        if(!is_null($nivel)){
            return $nivel;
        }else{
            return null;
        }
    }
    
    private function getShoppings() {
        $shoppings = Shopping::whereIn('id', DB::table('users_shoppings')->where('users_id', Auth::id())->get()->pluck('shoppings_id'))->get();

        return $shoppings;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       
//        SELECT p.*, s.shopping, t.tipo_relatorio FROM projetos AS p
//        INNER JOIN shoppings AS s ON s.id = p.shoppings_id
//        INNER JOIN tipo_relatorios AS t ON p.tipo_relatorios_id = t.id
//        WHERE p.id NOT IN (SELECT projetos_id FROM relatorios WHERE projetos_id = p.id)
        if(!is_null($this->nivel())){
            $projetos = DB::table('projetos as p')
                ->join('shoppings as s', 's.id', '=', 'p.shoppings_id')
                ->join('tipo_relatorios as t', 't.id', '=', 'p.tipo_relatorios_id')
                ->select('p.*','s.shopping','t.ref')
                ->whereRaw('p.id NOT IN (SELECT projetos_id FROM relatorios WHERE projetos_id = p.id)')
                ->get();
        }else{
            $projetos = DB::table('projetos as p')
                ->join('shoppings as s', 's.id', '=', 'p.shoppings_id')
                ->join('tipo_relatorios as t', 't.id', '=', 'p.tipo_relatorios_id')
                ->select('p.*','s.shopping','t.ref')
                ->whereRaw('p.id NOT IN (SELECT projetos_id FROM relatorios WHERE projetos_id = p.id)')
                ->whereIn('p.shoppings_id',$this->getShoppings())    
                ->get();
        }
        foreach($projetos as $projeto){
            $projetos->map(function ($projeto) {
                $arquivos = DB::table('projetos_arquivos')
                        ->select('id','filename')
                        ->where('projetos_id',$projeto->id)
                        ->get();
                $projeto->arquivos = $arquivos;
                return $projeto;
            });
        }
        $tipo_relatorios = Tipo_relatorios::where('id','<>',4)->get();
        
        $array = [
            'projetos' => $projetos, 
            'tipo_relatorios' => $tipo_relatorios, 
            'active' => null,
            'nivel' => $this->nivel()
            ];
        return view('analise.projetos.index', $array);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
        $shoppings = $this->getShoppings();
        $tipo_relatorios = Tipo_relatorios::all();

        return view('analise.projetos.create', ['shoppings' => $shoppings, 'tipo_relatorios' => $tipo_relatorios]);
    }

    public function addFile($id) {
        $shoppings = $this->getShoppings();
        $tipo_relatorios = Tipo_relatorios::all();

        return view('analise.projetos.add_projeto', ['shoppings' => $shoppings, 'tipo_relatorios' => $tipo_relatorios, 'fid' => $id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
        ini_set('memory_limit', '-1');
        $this->validate($request, [
            'shoppings_id' => 'required',
            'tipo_relatorios' => 'required',
            'loja' => 'required',
            'numero' => 'required'
        ]);

//        return var_dump($request->projetos);

        $projeto = new Projetos;
        $projeto->shoppings_id = $request->shoppings_id;
        $projeto->tipo_relatorios_id = $request->tipo_relatorios;
        $projeto->loja = $request->loja . ' - ' . $request->numero;

        $projeto->save();

        if ($request->hasFile('memorial')) {
            $m = $request->memorial;
            if ($m->extension() != 'pdf') {
                DB::table('projetos')->where('id', '=', $projeto->id)->delete();
                return redirect('analise/projetos/create')->with('message', 'Formato de arquivo inválido');
            } else {
                $p = new ProjetosArquivos;
                $p->projetos_id = $projeto->id;
                $p->filename = $m->getClientOriginalName();
                $path = $m->store('projetos');
                $file = explode('/', $path)[1];
                $p->filepath = $file;
                $p->memorial = 1;
                $p->save();
            }
        }

        foreach ($request->pdf as $pdf) {
            if ($pdf->extension() != 'pdf') {
                DB::table('projetos')->where('id', '=', $projeto->id)->delete();
                return redirect('analise/projetos/create')->with('message', 'Formato de arquivo inválido');
            } else {
                $p = new ProjetosArquivos;
                $p->projetos_id = $projeto->id;
                $p->filename = $pdf->getClientOriginalName();
                $path = $pdf->store('projetos');
                $file = explode('/', $path)[1];
                $p->filepath = $file;
                $p->save();
            }
        }
        if ($request->hasFile('dwg')) {
            foreach ($request->dwg as $dwg) {
                if ($dwg->extension() != 'dwg') {
                    DB::table('projetos')->where('id', '=', $projeto->id)->delete();
                    return redirect('analise/projetos/create')->with('message', 'Formato de arquivo inválido');
                } else {
                    $p = new ProjetosArquivos;
                    $p->projetos_id = $projeto->id;
                    $p->filename = $dwg->getClientOriginalName();
                    $path = $dwg->store('projetos');
                    $file = explode('/', $path)[1];
                    $p->filepath = $file;
                    $p->save();
                }
            }
        }
        
        //Logica para notificação por e-mail
        $shopping = DB::table('shoppings')->where('id', $request->shoppings_id)->value('shopping');
        
//        SELECT u.email FROM users_disciplinas AS d
//        INNER JOIN users AS u ON d.users_id = u.id
//        WHERE d.tipo_relatorios_id = 7
        $emails = DB::Table('users_disciplinas as d')
                ->join('users as u', 'd.users_id', '=', 'u.id')
                ->where('d.tipo_relatorios_id', $request->tipo_relatorios)
                ->pluck('u.email');
        try{
            Mail::to($emails)->send(new NewProjeto($shopping,$request->loja));
        } catch (\Exception $e) {
            return redirect('analise/projetos/create')->with('message', 'Projeto adicionado para análise com sucesso.');
        }
//        Mail::to(['analise01@ftrengenhariarj.com.br'])->send(new NewProjeto($shopping,$request->loja));
        //analise01@ftrengenhariarj.com.br
        
        return redirect('analise/projetos/create')->with('message', 'Projeto adicionado para análise com sucesso.');
    }
    
    private function removeAccents($str) {
        $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή', '¹', '²', '³', 'ª', 'º');
        $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η', '1', '2', '3', 'a', 'o');
        return urldecode(str_replace($a, $b, $str));
    }
    
    public function download($id) {
        //return('check');
        $projeto = ProjetosArquivos::find($id);
        
        $arquivo = $this->removeAccents($projeto->filename);
        return Storage::download('projetos/' . $projeto->filepath, $arquivo);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
        $projetos = DB::table('projetos as p')
                ->join('shoppings as s', 's.id', '=', 'p.shoppings_id')
                ->join('tipo_relatorios as t', 't.id', '=', 'p.tipo_relatorios_id')
                ->select('p.*','s.shopping','t.ref')
                ->whereRaw('p.id NOT IN (SELECT projetos_id FROM relatorios WHERE projetos_id = p.id)')
                ->where('p.tipo_relatorios_id', $id)
                ->get();
        
        foreach($projetos as $projeto){
            $projetos->map(function ($projeto) {
                $arquivos = DB::table('projetos_arquivos')
                        ->select('id','filename')
                        ->where('projetos_id',$projeto->id)
                        ->get();
                $projeto->arquivos = $arquivos;
                return $projeto;
            });
        }
        $tipo_relatorios = Tipo_relatorios::where('id','<>',4)->get();
        
        return view('analise.projetos.index', ['projetos' => $projetos, 'tipo_relatorios' => $tipo_relatorios, 'active' => $id, 'nivel' => $this->nivel()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function revisao($id){
        $relatorio = Relatorios::find($id);
        $loja = explode(' - ', $relatorio->loja)[0];
        $numero = explode(' - ', $relatorio->loja)[1];
        $shoppings_id = $relatorio->shoppings_id;
        $tipo_relatorios_id = (in_array( 3, explode( ',', $relatorio->tipo_relatorios_id))) ? 3 : $relatorio->tipo_relatorios_id;
        
        $shoppings = $this->getShoppings();
        $tipo_relatorios = Tipo_relatorios::all();
        
        $data = [
            'relatorio_id' => $relatorio->id,
            'loja' => $loja,
            'numero' => $numero,
            'shoppings_id' => $shoppings_id,
            'tipo_relatorios_id' => $tipo_relatorios_id,
            'shoppings' => $shoppings,
            'tipo_relatorios' => $tipo_relatorios
        ];
        
        return view('analise.projetos.revisao', $data);
    }
    public function storeRevisao(Request $request, $id){
        ini_set('memory_limit', '-1');
        $this->validate($request, [
            'shoppings_id' => 'required',
            'tipo_relatorios' => 'required',
            'loja' => 'required',
            'numero' => 'required'
        ]);
        
        $revisao = DB::table('relatorios')->where('id', $id)->value('revisao');
        
        $projeto = new Projetos;
        $projeto->shoppings_id = $request->shoppings_id;
        $projeto->tipo_relatorios_id = $request->tipo_relatorios;
        $projeto->revisao = $revisao + 1;
        $projeto->loja = $request->loja . ' - ' . $request->numero;
        $projeto->referencia = $id;
        $projeto->save();
        
        if ($request->hasFile('memorial')) {
            $m = $request->memorial;
            if ($m->extension() != 'pdf') {
                DB::table('projetos')->where('id', '=', $projeto->id)->delete();
                return redirect('analise/projetos/create')->with('message', 'Formato de arquivo inválido');
            } else {
                $p = new ProjetosArquivos;
                $p->projetos_id = $projeto->id;
                $p->filename = $m->getClientOriginalName();
                $path = $m->store('projetos');
                $file = explode('/', $path)[1];
                $p->filepath = $file;
                $p->memorial = 1;
                $p->save();
            }
        }

        foreach ($request->pdf as $pdf) {
            if ($pdf->extension() != 'pdf') {
                DB::table('projetos')->where('id', '=', $projeto->id)->delete();
                return redirect('analise/projetos/create')->with('message', 'Formato de arquivo inválido');
            } else {
                $p = new ProjetosArquivos;
                $p->projetos_id = $projeto->id;
                $p->filename = $pdf->getClientOriginalName();
                $path = $pdf->store('projetos');
                $file = explode('/', $path)[1];
                $p->filepath = $file;
                $p->save();
            }
        }
        if ($request->hasFile('dwg')) {
            foreach ($request->dwg as $dwg) {
                if ($dwg->extension() != 'dwg') {
                    DB::table('projetos')->where('id', '=', $projeto->id)->delete();
                    return redirect('analise/projetos/create')->with('message', 'Formato de arquivo inválido');
                } else {
                    $p = new ProjetosArquivos;
                    $p->projetos_id = $projeto->id;
                    $p->filename = $dwg->getClientOriginalName();
                    $path = $dwg->store('projetos');
                    $file = explode('/', $path)[1];
                    $p->filepath = $file;
                    $p->save();
                }
            }
        }
        return redirect('analise/projetos/create')->with('message', 'Projeto adicionado para análise com sucesso.');
    }
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}
