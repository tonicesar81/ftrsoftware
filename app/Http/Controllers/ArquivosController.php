<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Arquivos;
use App\Classes\PDFWatermark;
use App\Classes\PDFWatermarker;
use App\Shopping;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\User_dados;

class ArquivosController extends Controller {

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
    
    private function getShoppings_id() {
        $shoppings = Shopping::whereIn('id', DB::table('users_shoppings')->where('users_id', Auth::id())->pluck('shoppings_id'))->get();

        return $shoppings;
    }

    private function permission(){
        $nivel = DB::table('user_dados')->where('users_id', Auth::id())->value('user_levels_id');
        if(is_null($nivel)){
            abort(403, 'Acesso Negado');
        }
    }

    private function getShoppings() {
        if (is_null($this->nivel())) {
            $shoppings = DB::table('shoppings')
                    ->select('*')
                    ->whereIn('id', $this->getShoppings_id())
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

    public function index() {
//        return 'Pagina inicial de arquivos';
        $shoppings = $this->getShoppings();
        
        $sh_array = array();
        foreach($this->getShoppings_id() as $s){
            $sh_array[] = $s->id;
        }
//        return var_dump($sh_array);
        $shops = implode(',', $sh_array);
        if (is_null($this->nivel())) {
            $pastas = DB::table('shoppings')
                    ->leftJoin('arquivos', 'arquivos.shoppings_id', '=', 'shoppings.id')
                    ->select(DB::raw('shoppings.id,shoppings.shopping,(SELECT COUNT(DISTINCT loja) FROM arquivos WHERE arquivos.shoppings_id = shoppings.id) AS lojas, (SELECT created_at FROM arquivos WHERE shoppings_id = shoppings.id ORDER BY created_at DESC LIMIT 0,1) AS created_at'))
                    ->whereRaw('(SELECT COUNT(DISTINCT loja) FROM arquivos WHERE arquivos.shoppings_id = shoppings.id) > 0 AND arquivos.shoppings_id IN (' . $shops . ')')
                    ->groupby('shopping')
                    ->orderby('created_at', 'DESC')
                    ->get();
        } else {
            $pastas = DB::table('shoppings')
                    ->leftJoin('arquivos', 'arquivos.shoppings_id', '=', 'shoppings.id')
                    ->select(DB::raw('shoppings.id,shoppings.shopping,(SELECT COUNT(DISTINCT loja) FROM arquivos WHERE arquivos.shoppings_id = shoppings.id) AS lojas, (SELECT created_at FROM arquivos WHERE shoppings_id = shoppings.id ORDER BY created_at DESC LIMIT 0,1) AS created_at'))
                    ->whereRaw('(SELECT COUNT(DISTINCT loja) FROM arquivos WHERE arquivos.shoppings_id = shoppings.id) > 0')
                    ->groupby('shopping')
                    ->orderby('created_at', 'DESC')
                    ->get();
        }
        return view('arquivos.pastas', ['pastas' => $pastas, 'shoppings' => $shoppings, 'nivel' => $this->nivel()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
        $this->permission();
        return view('arquivos.create', ['shoppings' => DB::table('shoppings')->orderBy('shopping', 'asc')->get()]);
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

        $this->permission();
        $this->validate($request, [
            'shoppings_id' => 'required',
            'loja' => 'required',
            'dtRecebimento' => 'required'
        ]);

        if ($request->hasFile('arquivo')) {
            foreach ($request->arquivo as $a) {
                if ($a->extension() != 'pdf') {
                    return redirect('/arquivos/create')->with('message', 'Envie somente arquivos PDF');
                }
            }
        } else {
            return redirect('/arquivos/create')->with('message', 'Nenhum arquivo foi enviado');
        }

        foreach ($request->file('arquivo') as $a) {
            $arquivo = new Arquivos;
            $arquivo->shoppings_id = $request->shoppings_id;
            $arquivo->loja = strtoupper($request->loja);
            $arquivo->dtRecebimento = $request->dtRecebimento;


            $db_arq = DB::table('arquivos')->where([
                        ['shoppings_id', '=', $request->shoppings_id],
                        ['loja', '=', strtoupper($request->loja)],
                        ['dtRecebimento', '=', $request->dtRecebimento],
                        ['arquivo', '=', $a->getClientOriginalName()]
                    ])->first();

            if ($request->salvar_como == 0) {
                if ($db_arq->isEmpty()) {
                    $filename = $a->getClientOriginalName();
                } else {
                    $f = explode('.', $a->getClientOriginalName());
                    $filename = $f[0] . '__' . date('Y_m_d_H_i_s', strtotime('-3 hours')) . '.' . $f[1];
                }
                $path = $a->store('arquivos');
                $file = explode('/', $path)[1];
                $arquivo->hash = $file;
                $arquivo->arquivo = $filename;
                $arquivo->save();
            } else {
                $filename = $a->getClientOriginalName();
                if (!empty($db_arq)) {
                    $path = $a->storeAs('arquivos', $db_arq->hash);
                    $file = explode('/', $path)[1];

                    DB::table('arquivos')
                            ->where('id', $db_arq->id)
                            ->update([
                                'hash' => $file
                    ]);
                } else {
                    $path = $a->store('arquivos');
                    $file = explode('/', $path)[1];
                    $arquivo->hash = $file;
                    $arquivo->arquivo = $filename;
                    $arquivo->save();
                }
            }

            $file = explode('/', $path)[1];

            if ($request->marca == 1) {
                $watermark = new PDFWatermark(public_path('img/ftr-marca-1.png'), public_path('storage/arquivos/' . $file));
                $watermarker = new PDFWatermarker(public_path('storage/arquivos/' . $file), public_path('storage/arquivos/' . $file), $watermark);
            } else {
                $watermark = new PDFWatermark(public_path('img/ftr-marca-2.png'));
                //Set the position
                $watermark->setPosition('topright');
                //Specify the path to the existing pdf, the path to the new pdf file, and the watermark object
                $watermarker = new PDFWatermarker(public_path('storage/arquivos/' . $file), public_path('storage/arquivos/' . $file), $watermark);
            }
            //Set page range. Use 1-based index.
            $watermarker->setPageRange(1);

            //Save the new PDF to its specified location
            $watermarker->savePdf();
        } //endforeach
        return redirect('arquivos')->with('message', 'Arquivo salvo com sucesso');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
        if ((Auth::user()->user_levels_id == 99) && (!in_array($id, explode(',', Auth::user()->shoppings)))) {
            abort(403);
        }
        $shoppings = $this->getShoppings_id();
        $my_shoppings = array();
        foreach($shoppings as $shop){
            $my_shoppings[] = $shop->shoppings_id;
        }
        if((is_null($this->nivel())) && (!in_array($id, $my_shoppings))){
            abort(403);
        }
        $shoppings = $this->getShoppings();

        $shopping_select = DB::table('shoppings')->where('id', $id)->value('shopping');

        $subpastas = DB::table('arquivos as ar')
                ->select(DB::raw('ar.id,ar.shoppings_id,ar.loja,(SELECT COUNT(*) FROM arquivos WHERE loja = ar.loja AND shoppings_id = ' . $id . ') AS arq, (SELECT created_At FROM arquivos WHERE shoppings_id = ar.shoppings_id AND loja = ar.loja ORDER BY created_at DESC LIMIT 0,1) AS created_at'))
                ->where('ar.shoppings_id', $id)
                ->groupby('ar.loja')
                ->orderby('created_at', 'desc')
                ->get();
        return view('arquivos.subpastas', ['subpastas' => $subpastas, 'shoppings' => $shoppings, 'shopping_select' => $shopping_select, 'nivel' => $this->nivel()]);
    }

    public function lista($id, $loja) {
        if ((Auth::user()->user_levels_id == 99) && (!in_array($id, explode(',', Auth::user()->shoppings)))) {
            abort(403);
        }
        $shoppings = $this->getShoppings_id();
        $my_shoppings = array();
        foreach($shoppings as $shop){
            $my_shoppings[] = $shop->shoppings_id;
        }
        if((is_null($this->nivel())) && (!in_array($id, $my_shoppings))){
            abort(403);
        }
        $loja = DB::table('arquivos')->where('id', $loja)->value('loja');
        $shopping_select = DB::table('shoppings')
                ->where('id', $id)
                ->first();
        //SELECT * FROM arquivos WHERE loja = 'BURGER KING' AND shoppings_id = 6 ORDER BY dtRecebimento DESC
        $arquivos = DB::table('arquivos')
                ->where([
                    ['loja', '=', $loja],
                    ['shoppings_id', '=', $id]
                ])->orderBy('dtRecebimento', 'DESC')
                ->get();
        $array = array(
            'arquivos' => $arquivos,
            'shoppings' => $this->getShoppings(),
            'shopping_select' => $shopping_select,
            'loja' => $loja,
            'nivel' => $this->nivel()
        );

        return view('arquivos.index', $array);
    }

    public function download($id) {
        //return('check');
        $arquivo = Arquivos::find($id);
        if ((Auth::user()->user_levels_id == 99) && (!in_array($arquivo->shoppings_id, explode(',', Auth::user()->shoppings)))) {
            abort(403);
        }
        return Storage::download('public/arquivos/' . $arquivo->hash, $arquivo->arquivo);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
        abort(404);
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
        abort(403);
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
        $arquivo = Arquivos::find($id);
        Storage::delete('arquivos/' . $arquivo->hash);
        $arquivo->delete();

        return redirect('arquivos')->with('message', 'Arquivo excluído com sucesso');
    }

}
