<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use App\Relatorios;
use App\Shopping;
use App\Itens;
use App\Lista_analises;
use App\Figuras;
use App\Comentarios;
use App\Tipo_relatorios;
use App\Mail\NewRelatorio;
use App\Ressalvas;
use App\Objetivos;
use App\Projetos;
use App\ProjetosArquivos;
use App\Detalhamentos;
use App\Arquivos;
use App\Classes\PDFWatermark;
use App\Classes\PDFWatermarker;
use App\User_dados;

class RelatoriosController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    private function permission(){
        $nivel = DB::table('user_dados')->where('users_id', Auth::id())->value('user_levels_id');
        if(is_null($nivel)){
            abort(403, 'Acesso Negado');
        }
    }

    private function getShoppings() {
        if (is_null($this->nivel())) {
            $shoppings_id = Shopping::whereIn('id', DB::table('users_shoppings')->where('users_id', Auth::id())->get()->pluck('shoppings_id'))->get();
            $shoppings = DB::table('shoppings')
                    ->select('*')
                    ->whereIn('id', $shoppings_id)
                    ->orderBy('shopping', 'asc')
                    ->get();
        } else {
            $shoppings = DB::table('shoppings')
                    ->select('*')
                    ->orderBy('shopping', 'asc')
                    ->get();
        }

        return $shoppings;
    }

    private function dataExtensa($date) {
        $dia = date('d', strtotime($date));
        $mes_n = date('n', strtotime($date));
        $ano = date('Y', strtotime($date));

        $mes = [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro'
        ];

        return 'Rio de Janeiro, ' . $dia . ' de ' . $mes[$mes_n] . ' de ' . $ano;
    }
    
    private function base64_to_jpeg($base64_string) {
        // open the output file for writing
        $output_file = md5(uniqid(rand(), true));
//        $ifp = fopen(storage_path('app\public\figuras\\' . $output_file . '.jpg'), 'wb');
        $ifp = fopen(public_path('storage\figuras\\' . $output_file . '.jpg'), 'wb');
//        return public_path('storage\\' . $output_file . '.jpg');
        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode(',', $base64_string);

        // we could add validation here with ensuring count( $data ) > 1
        fwrite($ifp, base64_decode($data[1]));

        //imagejpeg($ifp, public_path('figura/' . $output_file), 100);
        // clean up the file resource
        fclose($ifp);


        return $output_file . '.jpg';
    }

    public function index() {
//        return "Hello!";
//        $this->permission();
        if ((Auth::user()->user_levels_id > 3) && (Auth::user()->user_levels_id != 99)) {
            abort(403);
        }
        $shoppings = $this->getShoppings();
//        SELECT shoppings.shopping,
//        (SELECT COUNT(DISTINCT loja) FROM relatorios WHERE relatorios.shoppings_id = shoppings.id) AS lojas,
//        relatorios.created_at,relatorios.updated_at
//        FROM shoppings
//        LEFT JOIN relatorios ON relatorios.shoppings_id = shoppings.id
//        -- WHERE (SELECT COUNT(DISTINCT loja) FROM relatorios WHERE relatorios.shoppings_id = shoppings.id) > 0
//        GROUP BY shopping
//        ORDER BY updated_at DESC
        $user_shoppings = DB::table('users_shoppings')->where('users_id',Auth::id())->pluck('shoppings_id');
        $shop = array();
        foreach($user_shoppings as $us){
            $shop[] = $us;
        }
        
//        return var_dump($user_shoppings);
        if (is_null($this->nivel())) {
            $pastas = DB::table('shoppings')
                    ->leftJoin('relatorios', 'relatorios.shoppings_id', '=', 'shoppings.id')
                    ->select(DB::raw('shoppings.id,shoppings.shopping,(SELECT COUNT(DISTINCT loja) FROM relatorios WHERE relatorios.shoppings_id = shoppings.id) AS lojas, (SELECT updated_at FROM relatorios WHERE shoppings_id = shoppings.id ORDER BY updated_at DESC LIMIT 0,1) AS updated_at'))
                    ->whereRaw('(SELECT COUNT(DISTINCT loja) FROM relatorios WHERE relatorios.shoppings_id = shoppings.id) > 0 AND relatorios.shoppings_id IN (' . implode(',',$shop) . ')')
                    ->groupby('shopping')
                    ->orderby('updated_at', 'DESC')
                    ->get();
        } else {
            $pastas = DB::table('shoppings')
                    ->leftJoin('relatorios', 'relatorios.shoppings_id', '=', 'shoppings.id')
                    ->select(DB::raw('shoppings.id,shoppings.shopping,(SELECT COUNT(DISTINCT loja) FROM relatorios WHERE relatorios.shoppings_id = shoppings.id) AS lojas, (SELECT updated_at FROM relatorios WHERE shoppings_id = shoppings.id ORDER BY updated_at DESC LIMIT 0,1) AS updated_at'))
                    ->whereRaw('(SELECT COUNT(DISTINCT loja) FROM relatorios WHERE relatorios.shoppings_id = shoppings.id) > 0')
                    ->groupby('shopping')
                    ->orderby('updated_at', 'DESC')
                    ->get();
        }
        return view('analise.relatorio.pastas', ['pastas' => $pastas, 'shoppings' => $shoppings]);
//        return $pastas;
    }

    public function index_1() {
        //
//        config(['app.timezone' => 'America/Sao_Paulo']);
        $this->permission();
        $empresa = Auth::user()->empresas_id;

        $shoppings = DB::table('shoppings')
                ->select('*');


        if ($empresa != 2) {
            $shoppings->where('empresas_id', $empresa);
        }

        $shoppings->orderBy('shopping', 'asc');

        //return $shoppings->get();
//        $shoppings = Shopping::where('empresas_id', $empresa)
//                ->orderBy('shopping', 'asc')
//                ->get();

        $relatorios = DB::table('relatorios')
                ->join('tipo_relatorios', 'tipo_relatorios.id', 'relatorios.tipo_relatorios_id')
                ->select('tipo_relatorios.tipo_relatorio', 'relatorios.*')
                ->orderBy('loja', 'asc')
                ->get();

        foreach ($relatorios as $relatorio) {

            $relatorios->map(function ($relatorio) {
                $first = Lista_analises::whereIn('id', explode(',', $relatorio->analise));
                $obs = DB::table('comentarios')
                        ->select('id', 'itens_id', 'comentario as lista_analise', 'created_at', 'updated_at')
                        ->where('relatorios_id', $relatorio->id)
//                        ->whereIn('itens_id', explode(',', $relatorio->analise))
                        ->union($first)
                        ->get();
                $relatorio->obs = $obs;
                return $relatorio;
            });
            //$itens->put('obs', $obs);
            //$item->push($obs);
        }

        return view('analise.relatorio.index', ['relatorios' => $relatorios, 'shoppings' => $shoppings->get()]);
//        return $relatorios;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id, $inc = null) {
        //
//        $id = 1;
        $this->permission();
        
        $dados = DB::table('user_dados')->where('users_id', Auth::id())->first();
        if(is_null($dados->assinatura)){
            return redirect()->back()->with('message', 'Não é possível analisar esse projeto pois você ainda não cadastrou a sua assinatura.'); 
        }
        
        $projeto = DB::table('projetos as p')
                ->join('shoppings as s', 's.id', '=', 'p.shoppings_id')
                ->join('tipo_relatorios as t', 't.id', '=', 'p.tipo_relatorios_id')
                ->select('p.*', 't.tipo_relatorio', 's.shopping')
                ->where('p.id', $id)
                ->first();
        $itens = Itens::where('tipo_relatorios_id', $projeto->tipo_relatorios_id)->get();
        foreach ($itens as $item) {

            $itens->map(function ($item) {
                $obs = Lista_analises::where('itens_id', $item->id)->get();
                $item['obs'] = $obs;
                return $item;
            });
        }
        $tipo_r = DB::table('tipo_relatorios')
                ->where('id',$projeto->tipo_relatorios_id)
                ->first();
        //$relatorios = Relatorios::where('id','=',$id)->first();
//        $relatorios = DB::table('tipo_relatorios')->where('id', $id)->first();
        $arquivos = DB::table('projetos_arquivos')
                ->where('projetos_id', $id)
                ->whereNull('memorial')
                ->where('filename', 'like', '%.dwg')
//                ->where([
//                    ['projetos_id', '=', $id],
//                    ['memorial', '<>', 1],
//                ])
                ->get();
        $array = [
            'projeto' => $projeto,
            'arquivos' => $arquivos,
            'itens' => $itens,
            'shoppings' => DB::table('shoppings')->select('*')->get(),
//            'relatorio' => $relatorios,
            'objetivos' => Objetivos::find(1),
            'tipo_relatorios' => Tipo_relatorios::all(),
            'tipo_r' => $tipo_r,
            'inc' => $inc
        ];
        $view = (isset($inc)) ? 'analise.relatorio.inc' : 'analise.relatorio.create';
        return view($view, $array);
    }

    public function disciplina($id, $inc = null) {
        //
//        $id = 1;
        $this->permission();
        $itens = Itens::where('tipo_relatorios_id', $id)->get();
        foreach ($itens as $item) {

            $itens->map(function ($item) {
                $obs = Lista_analises::where('itens_id', $item->id)->get();
                $item['obs'] = $obs;
                return $item;
            });
        }
        $tipo_r = DB::table('tipo_relatorios')
                ->where('id',$id)
                ->first();
        //$relatorios = Relatorios::where('id','=',$id)->first();
        $relatorios = DB::table('tipo_relatorios')->where('id', $id)->first();

        $array = [
            'itens' => $itens,
            'shoppings' => DB::table('shoppings')->select('*')->get(),
            'relatorio' => $relatorios,
            'tipo_relatorios' => Tipo_relatorios::all(),
            'tipo_r' => $tipo_r,
            'inc' => $inc
        ];
        $view = (isset($inc)) ? 'analise.relatorio.inc' : 'analise.relatorio.create';
        return view($view, $array);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
        $this->permission();
        $this->validate($request, [
            'loja' => 'required'
        ]);
        $relatorio = new Relatorios;
        $relatorio->loja = strtoupper($request->loja);
        $relatorio->shoppings_id = $request->shoppings_id;
        $relatorio->users_id = Auth::id();
        $relatorio->id_arquivo = $request->id_arquivo;
        $relatorio->tipo_relatorios_id = implode(',', $request->tipo_relatorios_id);
        $relatorio->projetos_id = $request->projetos_id;
        $relatorio->objetivo = $request->objetivo;
//        $relatorio->detalhamento = $request->detalhamento;
        $detalhamento = '';
        foreach($request->detalhamento as $det){
            $detalhamento .= '{BARRA}'.$det;
        }
        $relatorio->detalhamento = $detalhamento;
        $relatorio->consideracao = $request->consideracao;

        if ($request->obs != null) {
            $relatorio->analise = implode(',', $request->obs);
        }

        if ($request->ressalva != null) {
            $relatorio->ressalva = 1;
        }
        
        

        $relatorio->save();

        $relatorio_id = $relatorio->id;
        
        if(($request->obs == null) || ($request->ressalva != null)){
            $arquivos = ProjetosArquivos::where([
                                                    ['projetos_id', '=', $request->projetos_id],
                                                    ['filepath', 'like', '%.pdf']
                                                ]
                                                )->get();
            foreach($arquivos as $arquivo){
                
                if($arquivo->memorial == 1){
                    $watermark = new PDFWatermark(public_path('img/ftr-marca-2.png'));
                    //Set the position
                    $watermark->setPosition('topright');
                    //Specify the path to the existing pdf, the path to the new pdf file, and the watermark object
                    $watermarker = new PDFWatermarker(storage_path('app/projetos/' . $arquivo->filepath), storage_path('app/public/arquivos/' . $arquivo->filepath), $watermark);
                }else{
                    $watermark = new PDFWatermark(public_path('img/ftr-marca-1.png'), storage_path('app/projetos/' . $arquivo->filepath));
                    $watermarker = new PDFWatermarker(storage_path('app/projetos/' . $arquivo->filepath), public_path('storage/arquivos/' . $arquivo->filepath), $watermark);
                }
                $arq = new Arquivos;
                $arq->shoppings_id = $request->shoppings_id;
                $arq->loja = strtoupper($request->loja);
                $arq->arquivo = $arquivo->filename;
                $arq->hash = $arquivo->filepath;
                $arq->dtRecebimento = $arquivo->created_at;
                $arq->save();
                
                //Set page range. Use 1-based index.
                $watermarker->setPageRange(1);

                //Save the new PDF to its specified location
                $watermarker->savePdf();
                
            }
        }

        if ($request->obs != null) {
            foreach ($request->obs as $obs) {
                $lista_analise = Lista_analises::find($obs);
    //            DB::table('detalhamentos')
    //                    ->insert([
    //                        'relatorios_id' => $relatorio_id,
    //                        'itens_id' => $lista_analise->itens_id,
    //                        'texto' => $lista_analise->lista_analise,
    //                        'st' => 1
    //            ]);
                $detalhamento = new Detalhamentos;
                $detalhamento->relatorios_id = $relatorio_id;
                $detalhamento->itens_id = $lista_analise->itens_id;
                $detalhamento->lista_analises_id = $obs;
                $detalhamento->texto = $lista_analise->lista_analise;
                $detalhamento->st = 1;
                $detalhamento->save();

                if($request->has('ob-figura-'.$obs)){
                    $figura = new Figuras;
                    $figura->relatorios_id = $relatorio_id;
                    $figura->itens_id = $lista_analise->itens_id;
                    $figura->lista_analises_id = $obs;
                    $figura->detalhamentos_id = $detalhamento->id;
                    $figura->figura = $request->input('ob-figura-'.$obs);
                    $figura->save();
                }
                if($request->has('figuras-'.$obs)){
                    foreach($request->input('figuras-'.$obs) as $fig){
                        $figura = new Figuras;
                        $figura->relatorios_id = $relatorio_id;
                        $figura->itens_id = $lista_analise->itens_id;
                        $figura->lista_analises_id = $obs;
                        $figura->detalhamentos_id = $detalhamento->id;
                        $base64_str = substr($fig, strpos($fig, ",") + 1);

                        //decode base64 string
                        $image = base64_decode($base64_str);
                        $output = 'figuras/'.md5(uniqid(rand(), true)).'.png';
                        Storage::disk('public')->put($output, $image);
                        $figura->figura = $output;
                        //$storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();

                        $figura->save();
                    }
                }
            }
        }
        if ($request->adicional != '') {
            $ressalva = new Ressalvas;
            $ressalva->relatorios_id = $relatorio_id;
            $ressalva->mensagem = $request->adicional;
            $ressalva->save();
        }

//        $itens = Itens::Where('tipo_relatorios_id',$request->tipo_relatorios_id)->get();
        $itens = Itens::WhereIn('tipo_relatorios_id', $request->tipo_relatorios_id)->get();
        //wherein

        $i = 0;
        foreach ($itens as $item) {
            //return 'check';
            if ($request->filled('comm_' . $item->id)) {
                $comentario = new Comentarios;
                $comentario->relatorios_id = $relatorio_id;
                $comentario->itens_id = $item->id;
                $comentario->comentario = strtoupper($request->input('comm_' . $item->id));
                //
                $comentario->save();
                
//                DB::table('detalhamentos')
//                        ->insert([
//                            'relatorios_id' => $relatorio_id,
//                            'itens_id' => $item->id,
//                            'texto' => strtoupper($request->input('comm_' . $item->id)),
//                            'st' => 1
//                ]);
                
                $detalhamento = new Detalhamentos;
                $detalhamento->relatorios_id = $relatorio_id;
                $detalhamento->itens_id = $item->id;
                $detalhamento->texto = strtoupper($request->input('comm_' . $item->id));
                $detalhamento->st = 1;
                $detalhamento->save();
                
                if($request->has('c-figuras-'.$item->id)){
                    foreach($request->input('c-figuras-'.$item->id) as $cfig){
                        $figura = new Figuras;
                        $figura->relatorios_id = $relatorio_id;
                        $figura->itens_id = $item->id;
                        $figura->detalhamentos_id = $detalhamento->id;
                        $base64_str = substr($cfig, strpos($cfig, ",") + 1);

                        //decode base64 string
                        $image = base64_decode($base64_str);
                        $output = 'figuras/'.md5(uniqid(rand(), true)).'.png';
                        Storage::disk('public')->put($output, $image);
                        $figura->figura = $output;
                        //$storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();

                        $figura->save();
                    }
                }
            }
            $i++;
        }
//        SELECT u.email FROM users AS u
//        INNER JOIN users_shoppings AS s
//        ON s.users_id = u.id
//        WHERE s.shoppings_id = 6
        $cli = DB::table('users as u')
                ->join('users_shoppings as s','s.users_id','=','u.id')
                ->where('s.shoppings_id', $request->shoppings_id)
                ->pluck('u.email');

        $clientes = $cli->toArray();
        $sistema = DB::table('tipo_relatorios')
                ->whereIn('id', $request->tipo_relatorios_id)
                ->pluck('ref')
                ->implode(' ');
        $shopping = DB::table('shoppings')->where('id', $request->shoppings_id)->value('shopping');
        $rev = '00';

        try{
            Mail::to($clientes)->send(new NewRelatorio($shopping,$request->loja,$sistema,$rev));
        } catch (Exception $e) {
            return redirect('analise/relatorios')->with('message', 'Novo relatório criado com sucesso. O e-mail de notificação não pode ser enviado');
        }
        return redirect('analise/relatorios')->with('message', 'Novo relatório criado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
//        $this->permission();
        if ((Auth::user()->user_levels_id > 3) && (Auth::user()->user_levels_id != 99)) {
            abort(403);
        }
 

        if ((Auth::user()->user_levels_id == 99) && (!in_array($id, explode(',', Auth::user()->shoppings)))) {
            abort(403);
        }

        $shoppings = $this->getShoppings();
        $my_shoppings = array();
        foreach($shoppings as $shopping){
            $my_shoppings[] = $shopping->id;
        }
        if((is_null($this->nivel())) && (!in_array($id, $my_shoppings))){
            abort(403);
        }
        $shopping_select = DB::table('shoppings')->where('id', $id)->value('shopping');
//        SELECT *,(SELECT COUNT(*) FROM relatorios WHERE loja = r1.loja) AS rels 
//        FROM `relatorios` r1 WHERE r1.shoppings_id = 6 GROUP BY r1.loja ORDER BY r1.updated_at DESC
        $subpastas = DB::table('relatorios as r1')
                ->select(DB::raw('r1.id,r1.shoppings_id,r1.loja,(SELECT COUNT(*) FROM relatorios WHERE loja = r1.loja AND shoppings_id = ' . $id . ') AS rels, (SELECT COUNT(*) FROM relatorios	WHERE loja = r1.loja AND shoppings_id = ' . $id . ' AND analise IS NULL) AS aprovados, (SELECT updated_at FROM relatorios WHERE shoppings_id = r1.shoppings_id AND loja = r1.loja ORDER BY updated_at DESC LIMIT 0,1) AS updated_at'))
                ->where('r1.shoppings_id', $id)
                ->groupby('r1.loja')
                ->orderby('updated_at', 'desc')
                ->get();
        return view('analise.relatorio.subpastas', ['nivel' => $this->nivel(), 'subpastas' => $subpastas, 'shoppings' => $shoppings, 'shopping_select' => $shopping_select]);
//        return $subpastas;
    }

    public function lista($id, $loja) {
        //
//        $this->permission();
        if ((Auth::user()->user_levels_id > 3) && (Auth::user()->user_levels_id != 99)) {
            abort(403);
        }

        if ((Auth::user()->user_levels_id == 99) && (!in_array($id, explode(',', Auth::user()->shoppings)))) {
            abort(403);
        }
        
        $shoppings = $this->getShoppings();
        $my_shoppings = array();
        foreach($shoppings as $shopping){
            $my_shoppings[] = $shopping->id;
        }
        if((is_null($this->nivel())) && (!in_array($id, $my_shoppings))){
            abort(403);
        }

        $loja = DB::table('relatorios')->where('id', $loja)->value('loja');
        $relatorios = DB::table('relatorios')
                ->join('tipo_relatorios', 'tipo_relatorios.id', 'relatorios.tipo_relatorios_id')
                ->select('tipo_relatorios.tipo_relatorio', 'relatorios.*')
                ->where('shoppings_id', $id)
                ->where('loja', $loja)
                ->orderBy('loja', 'asc')
                ->get();

        $shopping_select = DB::table('shoppings')
                ->where('id', $id)
                ->first();

        foreach ($relatorios as $relatorio) {

            $relatorios->map(function ($relatorio) {
//                $first = Lista_analises::whereIn('id', explode(',', $relatorio->analise));
                $first = DB::table('lista_analises')
                        ->whereIn('id', explode(',', $relatorio->analise))
                        ->select('id','itens_id', 'lista_analise', 'created_at', 'updated_at');
//                $first = Lista_analises::whereRaw('itens_id = ' . $item->id . ' AND lista_analises.id IN(' . $relatorio->analise . ')');
                $obs = DB::table('comentarios')
                        ->select('id', 'itens_id', 'comentario as lista_analise', 'created_at', 'updated_at')
                        ->where('relatorios_id', $relatorio->id)
//                        ->where('itens_id',$item->id)
                        ->union($first)
                        ->get();

                $relatorio->obs = $obs;
                return $relatorio;
            });
            //$itens->put('obs', $obs);
            //$item->push($obs);
        }
        $array = array(
            'relatorios' => $relatorios,
            'shoppings' => $this->getShoppings(),
            'shopping_select' => $shopping_select,
            'loja' => $loja,
            'nivel' => $this->nivel()
        );
        return view('analise.relatorio.index', $array);
    }

    public function pdf($id) {
        $shoppings = $this->getShoppings();
        $my_shoppings = array();
        foreach($shoppings as $shopping){
            $my_shoppings[] = $shopping->id;
        }
        if((is_null($this->nivel())) && (!in_array($id, $my_shoppings))){
            abort(403);
        }
//        SELECT relatorios.*, shoppings.shopping, users.name, empresas.logo FROM relatorios
//        INNER JOIN shoppings ON shoppings.id = relatorios.shoppings_id
//        INNER JOIN empresas ON empresas.id = shoppings.empresas_id
//        INNER JOIN users ON users.id = relatorios.users_id
//        WHERE relatorios.id = 1
        $relatorio = DB::table('relatorios')
                ->join('shoppings', 'shoppings.id', '=', 'relatorios.shoppings_id')
                ->join('empresas', 'empresas.id', '=', 'shoppings.empresas_id')
                ->join('users', 'users.id', '=', 'relatorios.users_id')
                ->join('user_dados', 'user_dados.users_id', '=', 'relatorios.users_id')
                ->join('tipo_relatorios', 'tipo_relatorios.id', '=', 'relatorios.tipo_relatorios_id')
                ->select('relatorios.*', 'shoppings.shopping', 'users.name', 'empresas.logo', 'empresas.empresa', 'tipo_relatorios.tipo_relatorio', 'user_dados.titulo', 'user_dados.assinatura')
                ->where('relatorios.id', $id)
                ->first();

        $adicional = Ressalvas::where('relatorios_id', $id)->first();

//        return var_dump($ressalva);

        if ((Auth::user()->user_levels_id == 99) && (!in_array($relatorio->shoppings_id, explode(',', Auth::user()->shoppings)))) {
            abort(403, 'Acesso Negado!');
        }
//        SELECT lista_analises.*,itens.*,tipo_relatorios.* FROM `lista_analises` 
//        INNER JOIN itens ON itens.id = lista_analises.itens_id
//        INNER JOIN tipo_relatorios ON tipo_relatorios.id = itens.tipo_relatorios_id
//        WHERE lista_analises.id IN (1,2)

        if ($relatorio->analise == null) {
            $relatorio->analise = 0;
        }
        $analises = DB::table('lista_analises')
                ->join('itens', 'itens.id', '=', 'lista_analises.itens_id')
                ->select('lista_analises.lista_analise')
                ->whereIn('lista_analises.id', explode(',', $relatorio->analise))
                ->get();

        //SELECT itens.*,
        //(SELECT COUNT(*) FROM lista_analises WHERE itens_id = itens.id) AS obs 
        //FROM itens WHERE itens.tipo_relatorios_id = 1

        $tipo = explode(',', $relatorio->tipo_relatorios_id);
        $sistemas = array();
        $refs = array();
//        $norms = array();
//        SELECT * FROM normas WHERE grupos_id IN (SELECT grupos_id FROM tipo_relatorios WHERE id IN (1))
//        $normas = DB::table('normas')->where(DB::raw('grupos_id IN (SELECT grupos_id FROM tipo_relatorios WHERE id IN ('.$relatorio->tipo_relatorios_id.'))'))->get();
        //SELECT g.grupo FROM grupos as g
//            INNER JOIN tipo_relatorios as t ON t.grupos_id = g.id
//            WHERE t.id IN (1,3)
        $grupo = DB::table('grupos as g')
                ->join('tipo_relatorios as t', 't.grupos_id', '=', 'g.id')
                ->whereIn('t.id', $tipo)
                ->value('grupo');
        $normas = DB::select(DB::raw('SELECT * FROM normas WHERE grupos_id IN (SELECT grupos_id FROM tipo_relatorios WHERE id IN (' . $relatorio->tipo_relatorios_id . '))'));
//        return var_dump($normas);
        //start loop
        for ($i = 0; $i < count($tipo); $i++) {
            $tipo_nome = DB::table('tipo_relatorios')->where('id', $tipo[$i])->value('tipo_relatorio');
            $tipo_ref = DB::table('tipo_relatorios')->where('id', $tipo[$i])->value('ref');
//            $normas = DB::table('normas')->where('tipo_relatorios_id',$tipo[$i])->get();
            if ($relatorio->shoppings_id == 6) {
                $itens = DB::table('itens')
                        ->select(DB::raw('itens.*,((SELECT COUNT(*) FROM lista_analises WHERE itens_id = itens.id AND lista_analises.id IN(' . $relatorio->analise . ')) + (SELECT COUNT(*) FROM comentarios WHERE itens_id = itens.id AND relatorios_id = ' . $relatorio->id . ')) AS sts'))
                        ->where('itens.tipo_relatorios_id', $tipo[$i])
                        ->get();
            } else {
                $itens = DB::table('itens')
                        ->select(DB::raw('itens.*,((SELECT COUNT(*) FROM lista_analises WHERE itens_id = itens.id AND lista_analises.id IN(' . $relatorio->analise . ')) + (SELECT COUNT(*) FROM comentarios WHERE itens_id = itens.id AND relatorios_id = ' . $relatorio->id . ')) AS sts'))
                        ->where('itens.tipo_relatorios_id', $tipo[$i])
                        ->where('itens.id', '<>', 60)
                        ->get();
            }
            //        return $relatorio->tipo_relatorios_id;
//            if($relatorio->shoppings_id != 6){ 
//                $itens->forget('id');
//            }
            //        $itens = Itens::where('tipo_relatorios_id', $relatorio->tipo_relatorios_id)->get();

            foreach ($itens as $item) {

                $itens->map(function ($item) use($relatorio) {
//                    if(!is_null($relatorio->analise_old)){
//                        $analises = implode(',',array_merge(explode(',',$relatorio->analise_old),explode(',',$relatorio->analise)));
//                    }else{
//                        $analises = $relatorio->analise;
//                    }
                    $analises = $relatorio->analise;
//                    $first = Lista_analises::whereRaw('itens_id = ' . $item->id . ' AND lista_analises.id IN(' . $analises . ')');
                    $first = DB::table('lista_analises')
                            ->whereRaw('itens_id = ' . $item->id . ' AND lista_analises.id IN(' . $analises . ')')
                            ->select('id', 'itens_id', 'lista_analise', 'created_at', 'updated_at');
                    $obs = DB::table('comentarios')
                            ->select('id', 'itens_id', 'comentario as lista_analise', 'created_at', 'updated_at')
                            ->where('relatorios_id', $relatorio->id)
                            ->where('itens_id', $item->id)
                            ->union($first)
                            ->get();
                    $item->obs = $obs;
                    //        SELECT d.*, i.tipo_relatorios_id FROM detalhamentos AS d
                    //        INNER JOIN itens AS i ON i.id = d.itens_id
                    //        WHERE relatorios_id = 8
                    //        ORDER BY i.tipo_relatorios_id, d.itens_id
                    $comentarios = DB::table('detalhamentos as d')
                            ->join('itens as i', 'i.id', '=', 'd.itens_id')
                            ->where('d.relatorios_id', $relatorio->id)
                            ->where('d.itens_id', $item->id)
                            ->select('d.id as det_id', 'd.*')
//                            ->orderBy('i.tipo_relatorios_id, d.itens_id', 'asc')
                            ->get();
                    
                    $item->comentarios = $comentarios;
//                    if($relatorio->shoppings_id != 6){ 
//                        if ($item->id != 12) { //60 no servidor
//                            $item->pull('id');
//                        }
//                    }
                    return $item;
                });

                //$itens->put('obs', $obs);
                //$item->push($obs);
            }
            $refs[$i] = $tipo_ref;
            $sistemas[$i] = ['tipo_nome' => $tipo_nome, 'itens' => $itens];
//            $norms[$i] = $normas;
        }

//        foreach($sistemas as $sistema){
//            print_r($sistema);
//        }
//        exit();
        //return $sistemas;
        $figuras = Figuras::where('relatorios_id', $id)->get();
        //end loop

        $texto = Objetivos::find(1);

        $objetivo = (is_null($relatorio->objetivo)) ? $texto->objetivo : $relatorio->objetivo;
        $detalhamento = (is_null($relatorio->detalhamento)) ? $texto->detalhamento : $relatorio->detalhamento;
        $consideracao = (is_null($relatorio->consideracao)) ? $texto->consideracao : $relatorio->consideracao;

        $variaveis = array('{DISCIPLINA}', '{LOJA}', '{SHOPPING}', '{EMPRESA}');
        $repor = array(implode(' - ', $refs), $relatorio->loja, $relatorio->shopping, $relatorio->empresa);
        $objetivo = str_replace($variaveis, $repor, $objetivo);
//        $detalhamento = str_replace($variaveis, $repor, $detalhamento);
        $consideracao = str_replace($variaveis, $repor, $consideracao);

        $nivel = DB::table('user_dados as d')
                ->join('user_levels as l', 'l.id', '=', 'd.user_levels_id')
                ->where('d.users_id',$relatorio->users_id)
                ->value('l.nivel');
        
        $diretor = DB::table('users as u')
                ->join('user_dados as d', 'd.users_id', '=', 'u.id')
                ->join('user_levels as l', 'l.id', '=', 'd.user_levels_id')
                ->select('u.id','u.name','d.titulo','d.assinatura','l.nivel')
                ->where('u.id', 9)
                ->first();

        $data = array(
            'relatorio' => $relatorio,
            'nivel' => $nivel,
            'diretor' => $diretor,
            'disciplina' => implode(' - ', $refs),
//            'itens' => $itens,
            'refs' => $refs,
            'objetivo' => $objetivo,
            'detalhamento' => $detalhamento,
            'consideracao' => $consideracao,
            'figuras' => $figuras,
            'sistemas' => $sistemas,
            'adicional' => $adicional,
            'normas' => $normas,
            'grupo' => $grupo,
            'dtExtensa' => $this->dataExtensa($relatorio->created_at)

//            'analises' => $analises
        );




        $filename = 'RELATORIO DE ANÃ�LISE DE PROJETOS ';
        $filename .= implode(' - ', $refs);
        $filename .= ' REV ' . sprintf('%1$02d', $relatorio->revisao);
        $filename .= ' - ' . date('d-m-Y', strtotime($relatorio->created_at));
        $filename .= ' - ' . $relatorio->shopping . ' - ' . $relatorio->loja . '.pdf';
//        $filename = 'RELATORIO DE ANÃ�LISE DE PROJETOS '.implode(' - ', $refs).' REV '.sprintf('%1$02d', $relatorio->revisao).' - '.date('d-m-Y',$relatorio->created_at).' - '.$relatorio->shopping.' - '.$relatorio->loja.'.pdf';
        //$pdf = \App::make('dompdf.wrapper');
        $pdf = \DOMPDF::loadView('analise.pdf.show', $data);
        $pdf->getDomPDF()->set_option("enable_php", true);
        //$pdf->loadView('analise.pdf.show', $data);
        //$pdf->loadHTML('your view here ');
        return $pdf->setPaper('a4', 'portrait')->stream();
//        return $pdf->setPaper('a4', 'portrait')->download($filename);
//        return $pdf->stream();
//        return view('analise.pdf.show', $data);
    }

    public function pdf_old($id) {
//        SELECT relatorios.*, shoppings.shopping, users.name, empresas.logo FROM relatorios
//        INNER JOIN shoppings ON shoppings.id = relatorios.shoppings_id
//        INNER JOIN empresas ON empresas.id = shoppings.empresas_id
//        INNER JOIN users ON users.id = relatorios.users_id
//        WHERE relatorios.id = 1
        $relatorio = DB::table('relatorios')
                ->join('shoppings', 'shoppings.id', '=', 'relatorios.shoppings_id')
                ->join('empresas', 'empresas.id', '=', 'shoppings.empresas_id')
                ->join('users', 'users.id', '=', 'relatorios.users_id')
                ->join('tipo_relatorios', 'tipo_relatorios.id', '=', 'relatorios.tipo_relatorios_id')
                ->select('relatorios.*', 'shoppings.shopping', 'users.name', 'empresas.logo', 'tipo_relatorios.tipo_relatorio')
                ->where('relatorios.id', $id)
                ->first();

        if ((Auth::user()->user_levels_id == 99) && (!in_array($relatorio->shoppings_id, explode(',', Auth::user()->shoppings)))) {
            abort(403, 'Acesso Negado!');
        }
//        SELECT lista_analises.*,itens.*,tipo_relatorios.* FROM `lista_analises` 
//        INNER JOIN itens ON itens.id = lista_analises.itens_id
//        INNER JOIN tipo_relatorios ON tipo_relatorios.id = itens.tipo_relatorios_id
//        WHERE lista_analises.id IN (1,2)

        if ($relatorio->analise == null) {
            $relatorio->analise = 0;
        }
        $analises = DB::table('lista_analises')
                ->join('itens', 'itens.id', '=', 'lista_analises.itens_id')
                ->select('lista_analises.lista_analise')
                ->whereIn('lista_analises.id', explode(',', $relatorio->analise))
                ->get();

        //SELECT itens.*,
        //(SELECT COUNT(*) FROM lista_analises WHERE itens_id = itens.id) AS obs 
        //FROM itens WHERE itens.tipo_relatorios_id = 1

        $tipo = explode(',', $relatorio->tipo_relatorios_id);
        $sistemas = array();
        $refs = array();
        //start loop
        for ($i = 0; $i < count($tipo); $i++) {
            $tipo_nome = DB::table('tipo_relatorios')->where('id', $tipo[$i])->value('tipo_relatorio');
            $tipo_ref = DB::table('tipo_relatorios')->where('id', $tipo[$i])->value('ref');
            if ($relatorio->shoppings_id == 6) {
                $itens = DB::table('itens')
                        ->select(DB::raw('itens.*,((SELECT COUNT(*) FROM lista_analises WHERE itens_id = itens.id AND lista_analises.id IN(' . $relatorio->analise . ')) + (SELECT COUNT(*) FROM comentarios WHERE itens_id = itens.id AND relatorios_id = ' . $relatorio->id . ')) AS sts'))
                        ->where('itens.tipo_relatorios_id', $tipo[$i])
                        ->get();
            } else {
                $itens = DB::table('itens')
                        ->select(DB::raw('itens.*,((SELECT COUNT(*) FROM lista_analises WHERE itens_id = itens.id AND lista_analises.id IN(' . $relatorio->analise . ')) + (SELECT COUNT(*) FROM comentarios WHERE itens_id = itens.id AND relatorios_id = ' . $relatorio->id . ')) AS sts'))
                        ->where('itens.tipo_relatorios_id', $tipo[$i])
                        ->where('itens.id', '<>', 60)
                        ->get();
            }
            //        return $relatorio->tipo_relatorios_id;
//            if($relatorio->shoppings_id != 6){ 
//                $itens->forget('id');
//            }
            //        $itens = Itens::where('tipo_relatorios_id', $relatorio->tipo_relatorios_id)->get();

            foreach ($itens as $item) {

                $itens->map(function ($item) use($relatorio) {
                    $first = Lista_analises::whereRaw('itens_id = ' . $item->id . ' AND lista_analises.id IN(' . $relatorio->analise . ')');
                    $obs = DB::table('comentarios')
                            ->select('id', 'itens_id', 'comentario as lista_analise', 'created_at', 'updated_at')
                            ->where('relatorios_id', $relatorio->id)
                            ->where('itens_id', $item->id)
                            ->union($first)
                            ->get();
                    $item->obs = $obs;
//                    if($relatorio->shoppings_id != 6){ 
//                        if ($item->id != 12) { //60 no servidor
//                            $item->pull('id');
//                        }
//                    }
                    return $item;
                });

                //$itens->put('obs', $obs);
                //$item->push($obs);
            }
            $refs[$i] = $tipo_ref;
            $sistemas[$i] = ['tipo_nome' => $tipo_nome, 'itens' => $itens];
        }

//        foreach($sistemas as $sistema){
//            print_r($sistema);
//        }
//        exit();
        //return $sistemas;
        $figuras = Figuras::where('relatorios_id', $id)->get();
        //end loop
        $data = array(
            'relatorio' => $relatorio,
//            'itens' => $itens,
            'figuras' => $figuras,
            'sistemas' => $sistemas
//            'analises' => $analises
        );
        $filename = 'RELATORIO DE ANÁLISE DE PROJETOS ';
        $filename .= implode(' - ', $refs);
        $filename .= ' REV ' . sprintf('%1$02d', $relatorio->revisao);
        $filename .= ' - ' . date('d-m-Y', strtotime($relatorio->created_at));
        $filename .= ' - ' . $relatorio->shopping . ' - ' . $relatorio->loja . '.pdf';
//        $filename = 'RELATORIO DE ANÁLISE DE PROJETOS '.implode(' - ', $refs).' REV '.sprintf('%1$02d', $relatorio->revisao).' - '.date('d-m-Y',$relatorio->created_at).' - '.$relatorio->shopping.' - '.$relatorio->loja.'.pdf';
        //$pdf = \App::make('dompdf.wrapper');
        $pdf = \DOMPDF::loadView('analise.pdf.show', $data);
        $pdf->getDomPDF()->set_option("enable_php", true);
        //$pdf->loadView('analise.pdf.show', $data);
        //$pdf->loadHTML('your view here ');
        return $pdf->setPaper('a4', 'portrait')->download($filename);
//        return $pdf->stream();
//        return view('analise.pdf.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function edit($id){
//        
//    }



    public function edit($id, $projeto_id = null) {
        //
        $this->permission();
        $dados = DB::table('user_dados')->where('users_id', Auth::id())->first();
        if(is_null($dados->assinatura)){
            return redirect()->back()->with('message', 'Não é possível analisar esse projeto pois você ainda não cadastrou a sua assinatura.'); 
        }
        $relatorio = DB::table('relatorios')
                ->join('tipo_relatorios', 'tipo_relatorios.id', '=', 'relatorios.tipo_relatorios_id')
                ->select('relatorios.*', 'tipo_relatorios.tipo_relatorio')
                ->where('relatorios.id', $id)
                ->first();
        //return $relatorio;
        //$itens = Itens::Where('tipo_relatorios_id', $relatorio->tipo_relatorios_id)->get();
        $relatorio->analise = ($relatorio->analise == null) ? 0 : $relatorio->analise;

        $tipo = explode(',', $relatorio->tipo_relatorios_id);
        $sistemas = array();

        for ($i = 0; $i < count($tipo); $i++) {
            $tipo_nome = DB::table('tipo_relatorios')->where('id', $tipo[$i])->value('tipo_relatorio');

            $itens = DB::table('itens')
                    ->select(DB::raw('itens.*,(SELECT COUNT(*) FROM lista_analises WHERE itens_id = itens.id AND lista_analises.id IN(' . $relatorio->analise . ')) AS sts'))
                    //                ->where('itens.tipo_relatorios_id', $relatorio->tipo_relatorios_id)
                    ->where('itens.tipo_relatorios_id', $tipo[$i])
                    ->get();

            //return $itens;
            foreach ($itens as $item) {

                $itens->map(function ($item) use($id) {
                    $obs = Lista_analises::where('itens_id', $item->id)->get();
                    $item->obs = $obs;
                    foreach($obs as $o){
                        $obs->map(function ($o) use($id) {
                            $figuras = Figuras::where([
                                    ['relatorios_id', '=', $id],
                                    ['lista_analises_id', '=', $o->id]
                                    ])->get();
                            $o->figuras = $figuras;
                            return $o;
                        });
                    }
                    $comentario = Comentarios::where([
                                ['relatorios_id', '=', $id],
                                ['itens_id', '=', $item->id]
                            ])->value('comentario');
                    
                    $figuras = Figuras::where([
                                ['relatorios_id', '=', $id],
                                ['itens_id', '=', $item->id],
                                ['lista_analises_id', '=', null]
                            ])->get();
                    $item->comentario = $comentario;
                    $item->c_figuras = $figuras;
                    
                        
                    return $item;
                });
            }
            $sistemas[$i] = ['tipo_nome' => $tipo_nome, 'itens' => $itens, 'tipo' => $tipo[$i]];
        }
//        return $itens;
        $objetivos = Objetivos::find(1);
        $objetivo = ($relatorio->objetivo == null)? $objetivos->objetivo : $relatorio->objetivo;
        $detalhamento = ($relatorio->detalhamento == null)? $objetivos->detalhamento : $relatorio->detalhamento;
        $consideracao = ($relatorio->consideracao == null)? $objetivos->consideracao : $relatorio->consideracao;
        $array = [
            'relatorio' => $relatorio,
            'itens' => $itens,
            'shoppings' => Shopping::all(),
            'tipo_relatorios' => Tipo_relatorios::all(),
            'sistemas' => $sistemas,
            'objetivo' => $objetivo,
            'detalhamento' => $detalhamento,
            'consideracao' => $consideracao,
            'adicional' => Ressalvas::find($id),
            'projeto_id' => $projeto_id
        ];
        if(!is_null($projeto_id)){
//            $p_arquivos = ProjetosArquivos::where([
//                ['projetos_id', '=', $projeto_id],
//                ['memorial', '<>', 1]
//            ])->get();
            $p_arquivos = DB::table('projetos_arquivos')
                    ->select('*')
                    ->where([
                        ['projetos_id', '=', $projeto_id]
                    ])->get();
            $array['projeto_arquivos'] = $p_arquivos;
            
        }
//        return var_dump($p_arquivos);
        return view('analise.relatorio.edit', $array);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $this->permission();
        $this->validate($request, [
            'loja' => 'required'
        ]);
        $relatorio = Relatorios::find($id);
        $relatorio->loja = strtoupper($request->loja);
        //$relatorio->revisao = $old_relatorio->revisao + 1;
        $relatorio->shoppings_id = $request->shoppings_id;
        $relatorio->users_id = Auth::id();
        $relatorio->id_arquivo = $request->id_arquivo;
        $relatorio->tipo_relatorios_id = implode(',', $request->tipo_relatorios_id);
        $relatorio->objetivo = $request->objetivo;
        $detalhamento = '';
        foreach($request->detalhamento as $det){
            $detalhamento .= '{BARRA}'.$det;
        }
        $relatorio->detalhamento = $detalhamento;
        $relatorio->consideracao = $request->consideracao;

        if ($request->obs != null) {
            $relatorio->analise = implode(',', $request->obs);
        } else {
            $relatorio->analise = $request->obs;
        }
        
        if ($request->ressalva != null) {
            $relatorio->ressalva = 1;
        }else{
            $relatorio->ressalva = null;
        }
        
        if ($request->adicional != '') {
            DB::table('ressalvas')->where('relatorios_id', '=', $id)->delete();
            $ressalva = new Ressalvas;
            $ressalva->relatorios_id = $id;
            $ressalva->mensagem = $request->adicional;
            $ressalva->save();
        }else{
            DB::table('ressalvas')->where('relatorios_id', '=', $id)->delete();
        }
        
//        $figuras_old = Figuras::where('relatorios_id', $id)->get();
        
        $detalhamentos_old = DB::table('detalhamentos')
                ->where('relatorios_id', $id)
                ->get();
        $old = array();
        $new = array();
        foreach($detalhamentos_old as $d){
            $old[] = [$d->itens_id, $d->texto, $d->lista_analises_id];
        }
//        $new = array();
        $figuras = array();
        if ($request->obs != null) {
            foreach ($request->obs as $obs) {
                $lista_analise = Lista_analises::find($obs);
                
                if($request->has('ob-figura-'.$obs)){
                    $figuras[] = [
                        'relatorio_id' => $id,
                        'lista_analises_id' => $obs,
                        'itens_id' => $lista_analise->itens_id,
                        'figura' => $request->input('ob-figura-'.$obs)
                    ];
                }
                if($request->has('figuras-'.$obs)){
                    foreach($request->input('figuras-'.$obs) as $fig){
                        if(strpos($fig, 'data:image/png;base64') !== false){
                            $base64_str = substr($fig, strpos($fig, ",") + 1);

                            //decode base64 string
                            $image = base64_decode($base64_str);
                            $output = 'figuras/'.md5(uniqid(rand(), true)).'.png';
                            Storage::disk('public')->put($output, $image);
//                            $figura = new Figuras;
//                            $figura->relatorios_id = $id;
//                            $figura->lista_analises_id = $obs;
//                            $figura->itens_id = $lista_analise->itens_id;
//                            $figura->figura = $output;
                            //$storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
                            $pic = $output;
//                            $figura->save();
                        }else{
                            $pic = $fig;
                        }
                        $figuras[] = [
                            'relatorio_id' => $id,
                            'lista_analises_id' => $obs,
                            'itens_id' => $lista_analise->itens_id,
                            'figura' => $pic
                        ];
                    }
                }
                $new[] = [$lista_analise->itens_id, $lista_analise->lista_analise, $obs];
            }
        }

        $itens = Itens::WhereIn('tipo_relatorios_id', $request->tipo_relatorios_id)->get();
//        return $itens;
        $i = 0;
        foreach ($itens as $item) {
            //return 'check';
            $comm = Comentarios::Where([
                        ['relatorios_id', '=', $relatorio->id],
                        ['itens_id', '=', $item->id]
                    ])
                    ->first();
//            return var_dump($comm);
            if ($request->filled('comm_' . $item->id)) {

//                return var_dump($comm);
                if (!$comm) {
                    $comentario = new Comentarios;
                } else {
                    $comentario = Comentarios::find($comm->id);
                }
                $comentario->relatorios_id = $relatorio->id;
                $comentario->itens_id = $item->id;
                $comentario->comentario = strtoupper($request->input('comm_' . $item->id));
                //
                $comentario->save();
                
                if($request->has('c-figuras-'.$item->id)){
                    foreach($request->input('c-figuras-'.$item->id) as $cfig){
                        if(strpos($cfig, 'data:image/png;base64') !== false){
                            $base64_str = substr($cfig, strpos($cfig, ",") + 1);

                            //decode base64 string
                            $image = base64_decode($base64_str);
                            $output = 'figuras/'.md5(uniqid(rand(), true)).'.png';
                            Storage::disk('public')->put($output, $image);
//                            $figura = new Figuras;
//                            $figura->relatorios_id = $id;
//                            $figura->lista_analises_id = null;
//                            $figura->itens_id = $lista_analise->itens_id;
//                            $figura->figura = $output;
                            //$storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
                            $cpic = $output;
//                            $figura->save();
                        }else{
                            $cpic = $cfig;
                        }
                        $figuras[] = [
                            'relatorio_id' => $id,
                            'lista_analises_id' => null,
                            'itens_id' => $item->id,
                            'figura' => $cpic
                        ];
                    }
                }
                
                $new[] = [$comentario->itens_id, $comentario->comentario, null];
//                Comentarios::updateOrCreate(
//                   [
//                       'relatorios_id' => $relatorio->id,
//                       'itens_id' => $item->id,
//                       'comentario' => $request->input('comm_'.$i)
//                   ]
//                );
                //$comentario->updateOrCreate();
            } else {
//                return var_dump($comm);
                if ($comm) {
                    $comentario = Comentarios::find($comm->id);
                    $comentario->delete();
                }
            }
            $i++;
        }
        DB::table('detalhamentos')->where('relatorios_id', '=', $id)->delete();
        if (!empty($new)){
            foreach($old as $o){
                if(in_array($o, $new)){
                    $st = 2;
                }else{
                    $st = 3;
                }
//                DB::table('detalhamentos')
//                        ->insert([
//                            'relatorios_id' => $id,
//                            'itens_id' => $o[0],
//                            'texto' =>$o[1],
//                            'st' => $st
//                ]);
                $detalhamento = new Detalhamentos;
                $detalhamento->relatorios_id = $id;
                $detalhamento->itens_id = $o[0];
                $detalhamento->lista_analises_id = $o[2];
                $detalhamento->texto = $o[1];
                $detalhamento->st = $st;
                $detalhamento->save();
                
                foreach($figuras as $f){
                    if(($f['itens_id'] == $o[0]) && ($f['lista_analises_id'] == $o[2])){
                        $fg = new Figuras;
                        $fg->relatorios_id = $id;
                        $fg->lista_analises_id = $f['lista_analises_id'];
                        $fg->itens_id = $o[0];
                        $fg->detalhamentos_id = $detalhamento->id;
                        $fg->figura = $f['figura'];
                        $fg->save();
                        
                    }
                }
                
            }
            foreach($new as $n){
                if(!in_array($n, $old)){
//                   DB::table('detalhamentos')
//                        ->insert([
//                            'relatorios_id' => $id,
//                            'itens_id' => $n[0],
//                            'texto' =>$n[1],
//                            'st' => 1
//                    ]);
                    $detalhamento = new Detalhamentos;
                    $detalhamento->relatorios_id = $id;
                    $detalhamento->itens_id = $n[0];
                    $detalhamento->lista_analises_id = $n[2];
                    $detalhamento->texto = $n[1];
                    $detalhamento->st = 1;
                    $detalhamento->save();
                    
                    foreach($figuras as $f){
                        if(($f['itens_id'] == $n[0]) && ($f['lista_analises_id'] == $n[2])){
                            $fg = new Figuras;
                            $fg->relatorios_id = $id;
                            $fg->lista_analises_id = $f['lista_analises_id'];
                            $fg->itens_id = $n[1];
                            $fg->detalhamentos_id = $detalhamento->id;
                            $fg->figura = $f['figura'];
                            $fg->save();

                        }
                    }
                }
            }
        }else{
            foreach($old as $o){
//                DB::table('detalhamentos')
//                    ->insert([
//                        'relatorios_id' => $id,
//                        'itens_id' => $o[0],
//                        'texto' =>$o[1],
//                        'st' => 3
//                ]);
                $detalhamento = new Detalhamentos;
                $detalhamento->relatorios_id = $id;
                $detalhamento->itens_id = $o[0];
                $detalhamento->lista_analises_id = $o[2];
                $detalhamento->texto = $o[1];
                $detalhamento->st = 3;
                $detalhamento->save();
                
                foreach($figuras as $f){
                    if(($f['itens_id'] == $o[0]) && ($f['lista_analises_id'] == $o[2])){
                        $fg = new Figuras;
                        $fg->relatorios_id = $id;
                        $fg->lista_analises_id = $f['lista_analises_id'];
                        $fg->itens_id = $o[0];
                        $fg->detalhamentos_id = $detalhamento->id;
                        $fg->figura = $f['figura'];
                        $fg->save();
                        
                    }
                }
            }
        }
        
        $relatorio->save();

        return redirect('analise/relatorios')->with('message', 'Relatório editado com sucesso');
    }

    public function saveRevisao(Request $request, $id) {
        //
        $this->permission();
        $this->validate($request, [
            'loja' => 'required'
        ]);
        $old_relatorio = Relatorios::find($id);
        $relatorio = new Relatorios;
        $relatorio->loja = strtoupper($request->loja);
        $relatorio->revisao = $old_relatorio->revisao + 1;
        $relatorio->referencia = $id;
        $relatorio->shoppings_id = $request->shoppings_id;
        $relatorio->users_id = Auth::id();
        $relatorio->id_arquivo = $request->id_arquivo;
        $relatorio->tipo_relatorios_id = implode(',', $request->tipo_relatorios_id);
        $relatorio->objetivo = $request->objetivo;
        $detalhamento = '';
        foreach($request->detalhamento as $det){
            $detalhamento .= '{BARRA}'.$det;
        }
        $relatorio->detalhamento = $detalhamento;
        $relatorio->consideracao = $request->consideracao;
        $relatorio->projetos_id = $request->projetos_id;

        if ($request->obs != null) {
            $relatorio->analise = implode(',', $request->obs);
        }
        
        if ($request->ressalva != null) {
            $relatorio->ressalva = 1;
        }

        $relatorio->save();

        $relatorio_id = $relatorio->id;
        
        if(($request->obs == null) || ($request->ressalva != null)){
            $arquivos = ProjetosArquivos::where([
                                                    ['projetos_id', '=', $request->projetos_id],
                                                    ['filepath', 'like', '%.pdf']
                                                ]
                                                )->get();
            foreach($arquivos as $arquivo){
                
                if($arquivo->memorial == 1){
                    $watermark = new PDFWatermark(public_path('img/ftr-marca-2.png'));
                    //Set the position
                    $watermark->setPosition('topright');
                    //Specify the path to the existing pdf, the path to the new pdf file, and the watermark object
                    $watermarker = new PDFWatermarker(storage_path('app/projetos/' . $arquivo->filepath), storage_path('app/public/arquivos/' . $arquivo->filepath), $watermark);
                }else{
                    $watermark = new PDFWatermark(public_path('img/ftr-marca-1.png'), storage_path('app/projetos/' . $arquivo->filepath));
                    $watermarker = new PDFWatermarker(storage_path('app/projetos/' . $arquivo->filepath), public_path('storage/arquivos/' . $arquivo->filepath), $watermark);
                }
                $arq = new Arquivos;
                $arq->shoppings_id = $request->shoppings_id;
                $arq->loja = strtoupper($request->loja);
                $arq->arquivo = $arquivo->filename;
                $arq->hash = $arquivo->filepath;
                $arq->dtRecebimento = $arquivo->created_at;
                $arq->save();
                
                //Set page range. Use 1-based index.
                $watermarker->setPageRange(1);

                //Save the new PDF to its specified location
                $watermarker->savePdf();
                
            }
        }
        
        $detalhamentos_old = DB::table('detalhamentos')
                ->where('relatorios_id', $id)
                ->get();
        $old = array();
        $new = array();
        foreach($detalhamentos_old as $d){
            $old[] = [$d->itens_id, $d->texto, $d->lista_analises_id];
        }
//        $new = array();
        $figuras = array();

        if ($request->obs != null) {
            foreach ($request->obs as $obs) {
                $lista_analise = Lista_analises::find($obs);
                
                if($request->has('ob-figura-'.$obs)){
                    $figuras[] = [
                        'relatorio_id' => $relatorio_id,
                        'lista_analises_id' => $obs,
                        'itens_id' => $lista_analise->itens_id,
                        'figura' => $request->input('ob-figura-'.$obs)
                    ];
                }
                if($request->has('figuras-'.$obs)){
                    foreach($request->input('figuras-'.$obs) as $fig){
                        if(strpos($fig, 'data:image/png;base64') !== false){
                            $base64_str = substr($fig, strpos($fig, ",") + 1);

                            //decode base64 string
                            $image = base64_decode($base64_str);
                            $output = 'figuras/'.md5(uniqid(rand(), true)).'.png';
                            Storage::disk('public')->put($output, $image);
//                            $figura = new Figuras;
//                            $figura->relatorios_id = $id;
//                            $figura->lista_analises_id = $obs;
//                            $figura->itens_id = $lista_analise->itens_id;
//                            $figura->figura = $output;
                            //$storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
                            $pic = $output;
//                            $figura->save();
                        }else{
                            $pic = $fig;
                        }
                        $figuras[] = [
                            'relatorio_id' => $id,
                            'lista_analises_id' => $obs,
                            'itens_id' => $lista_analise->itens_id,
                            'figura' => $pic
                        ];
                    }
                }
                $new[] = [$lista_analise->itens_id, $lista_analise->lista_analise, $obs];
            }
        }
//        else{
//            foreach($old as $o){
//                DB::table('detalhamentos')
//                    ->insert([
//                        'relatorios_id' => $relatorio_id,
//                        'itens_id' => $o[0],
//                        'texto' =>$o[1],
//                        'st' => 3
//                ]);
//            }
//        }
        
        
        if ($request->adicional != '') {
            $ressalva = new Ressalvas;
            $ressalva->relatorios_id = $relatorio_id;
            $ressalva->mensagem = $request->adicional;
            $ressalva->save();
        }

        $itens = Itens::WhereIn('tipo_relatorios_id', $request->tipo_relatorios_id)->get();

        $i = 0;
        foreach ($itens as $item) {
            //return 'check';
            if ($request->filled('comm_' . $item->id)) {
                $comentario = new Comentarios;
                $comentario->relatorios_id = $relatorio_id;
                $comentario->itens_id = $item->id;
                $comentario->comentario = strtoupper($request->input('comm_' . $item->id));
                //
                $comentario->save();
                
                if($request->has('c-figuras-'.$item->id)){
                    foreach($request->input('c-figuras-'.$item->id) as $cfig){
                        if(strpos($cfig, 'data:image/png;base64') !== false){
                            $base64_str = substr($cfig, strpos($cfig, ",") + 1);

                            //decode base64 string
                            $image = base64_decode($base64_str);
                            $output = 'figuras/'.md5(uniqid(rand(), true)).'.png';
                            Storage::disk('public')->put($output, $image);
//                            $figura = new Figuras;
//                            $figura->relatorios_id = $id;
//                            $figura->lista_analises_id = null;
//                            $figura->itens_id = $lista_analise->itens_id;
//                            $figura->figura = $output;
                            //$storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
                            $cpic = $output;
//                            $figura->save();
                        }else{
                            $cpic = $cfig;
                        }
                        $figuras[] = [
                            'relatorio_id' => $relatorio_id,
                            'lista_analises_id' => null,
                            'itens_id' => $item->id,
                            'figura' => $cpic
                        ];
                    }
                }
                
                $new[] = [$comentario->itens_id, $comentario->comentario, null];                                
            }
            $i++;
        }
        
        if (!empty($new)){
            foreach($old as $o){
                if(in_array($o, $new)){
                    $st = 2;
                }else{
                    $st = 3;
                }
                $detalhamento = new Detalhamentos;
                $detalhamento->relatorios_id = $relatorio_id;
                $detalhamento->itens_id = $o[0];
                $detalhamento->lista_analises_id = $o[2];
                $detalhamento->texto = $o[1];
                $detalhamento->st = $st;
                $detalhamento->save();
                
                foreach($figuras as $f){
                    if(($f['itens_id'] == $o[0]) && ($f['lista_analises_id'] == $o[2])){
                        $fg = new Figuras;
                        $fg->relatorios_id = $relatorio_id;
                        $fg->lista_analises_id = $f['lista_analises_id'];
                        $fg->itens_id = $o[0];
                        $fg->detalhamentos_id = $detalhamento->id;
                        $fg->figura = $f['figura'];
                        $fg->save();
                        
                    }
                }
            }
            foreach($new as $n){
                if(!in_array($n, $old)){
                   $detalhamento = new Detalhamentos;
                    $detalhamento->relatorios_id = $relatorio_id;
                    $detalhamento->itens_id = $n[0];
                    $detalhamento->lista_analises_id = $n[2];
                    $detalhamento->texto = $n[1];
                    $detalhamento->st = 1;
                    $detalhamento->save();
                    
                    foreach($figuras as $f){
                        if(($f['itens_id'] == $n[0]) && ($f['lista_analises_id'] == $n[2])){
                            $fg = new Figuras;
                            $fg->relatorios_id = $relatorio_id;
                            $fg->lista_analises_id = $f['lista_analises_id'];
                            $fg->itens_id = $n[0];
                            $fg->detalhamentos_id = $detalhamento->id;
                            $fg->figura = $f['figura'];
                            $fg->save();

                        }
                    }
                }
            }
        }else{
            foreach($old as $o){
                $detalhamento = new Detalhamentos;
                $detalhamento->relatorios_id = $relatorio_id;
                $detalhamento->itens_id = $o[0];
                $detalhamento->lista_analises_id = $o[2];
                $detalhamento->texto = $o[1];
                $detalhamento->st = 3;
                $detalhamento->save();
                
                foreach($figuras as $f){
                    if(($f['itens_id'] == $o[0]) && ($f['lista_analises_id'] == $o[2])){
                        $fg = new Figuras;
                        $fg->relatorios_id = $relatorio_id;
                        $fg->lista_analises_id = $f['lista_analises_id'];
                        $fg->itens_id = $o[0];
                        $fg->detalhamentos_id = $detalhamento->id;
                        $fg->figura = $f['figura'];
                        $fg->save();
                        
                    }
                }
            }
        }
//        SELECT u.email FROM users AS u
//        INNER JOIN users_shoppings AS s
//        ON s.users_id = u.id
//        WHERE s.shoppings_id = 6
        $cli = DB::table('users as u')
                ->join('users_shoppings as s','s.users_id','=','u.id')
                ->where('s.shoppings_id', $request->shoppings_id)
                ->pluck('u.email');

        $clientes = $cli->toArray();
        $sistema = DB::table('tipo_relatorios')
                ->whereIn('id', $request->tipo_relatorios_id)
                ->pluck('ref')
                ->implode(' ');
        $shopping = DB::table('shoppings')->where('id', $request->shoppings_id)->value('shopping');

        $rev = sprintf('%1$02d', $relatorio->revisao);
        try{
            Mail::to($clientes)->send(new NewRelatorio($shopping,$request->loja,$sistema,$rev));
        } catch (Exception $e) {
            return redirect('analise/relatorios')->with('message', 'Nova revisão criada com sucesso. O e-mail de notificação não pode ser enviado');
        }
//        Mail::to($clientes)->send(new NewRelatorio($shopping,$request->loja,$sistema,$rev));

        return redirect('analise/relatorios')->with('message', 'Nova revisão criada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
        $this->permission();
        $relatorio = Relatorios::find($id);

        $relatorio->delete();

        return redirect('analise/relatorios')->with('message', 'Relatório apagado do sistema');
    }

}
