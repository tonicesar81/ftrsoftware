<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\ManualCapitulos;
use App\Man_itens;
use App\Entregas;
use PDF;

//use App\Classes\PDFWatermark;

class ManualCapitulosController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    private function permission() {
        if (Auth::user()->user_levels_id > 3 && Auth::user()->user_levels_id != 6) {
            abort(403);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
        $this->permission();
        //SELECT i.*,
        //(SELECT COUNT(*) FROM manual_capitulos as c WHERE c.man_itens_id = i.id AND c.manual_capitulos_id = 0) as capitulos 
        //FROM man_itens as i
        $capitulos = DB::table('man_itens as i')
                ->select(DB::raw('i.*, (SELECT COUNT(*) FROM manual_capitulos as c WHERE c.man_itens_id = i.id AND c.manual_capitulos_id = 0) as capitulos'))
                ->get();
        return view('manutencao.manuais.index',['capitulos' => $capitulos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id) {
        //

        $this->permission();
        $item = Man_itens::find($id);
        $capitulos = DB::table('manual_capitulos')
                ->select('capitulo', 'titulo')
                ->where([
                    ['man_itens_id', '=', $id],
                    ['manual_capitulos_id', '=', 0],
                ])
                ->get();
        return view('manutencao.manuais.create', ['item' => $item, 'capitulos' => $capitulos]);
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
            'titulo' => 'required'
        ]);
        $capitulo = new ManualCapitulos;
        $capitulo->man_itens_id = $request->man_itens_id;
        $capitulo->manual_capitulos_id = $request->manual_capitulos_id;
        $capitulo->titulo = $request->titulo;
        $capitulo->capitulo = $request->capitulo;
        $capitulo->conteudo = $request->conteudo;

        $capitulo->save();

        return redirect('manutencao/capitulos/'.$capitulo->man_itens_id)->with('message','Capítulo inserido com sucesso');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
        $this->permission();
        $capitulo = DB::table('manual_capitulos as c')
                ->join('man_itens as i','i.id','=','c.man_itens_id')
                ->select('c.*','i.item')
                ->where('c.man_itens_id',$id)
//                ->orderBy('c.manual_capitulos_id')
//                ->orderBy('c.capitulo')
                ->get();
        
        return view('manutencao.manuais.lista',['capitulos' => $capitulo]);

        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
        $this->permission();
        $capitulo = ManualCapitulos::find($id);
        $item = Man_itens::find($capitulo->man_itens_id);
        $capitulos = DB::table('manual_capitulos')
                ->select('capitulo', 'titulo')
                ->where([
                    ['man_itens_id', '=', $capitulo->man_itens_id],
                    ['manual_capitulos_id', '=', 0],
                ])
                ->get();
        return view('manutencao.manuais.edit', ['item' => $item, 'capitulos' => $capitulos, 'capitulo' => $capitulo]);
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
        $this->permission();
        $this->validate($request, [
            'titulo' => 'required'
        ]);
        $capitulo = ManualCapitulos::find($id);
        $capitulo->man_itens_id = $request->man_itens_id;
        $capitulo->manual_capitulos_id = $request->manual_capitulos_id;
        $capitulo->titulo = $request->titulo;
        $capitulo->capitulo = $request->capitulo;
        $capitulo->conteudo = $request->conteudo;

        $capitulo->save();
        return redirect('manutencao/capitulos/'.$capitulo->man_itens_id)->with('message','Capítulo editado com sucesso');
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
        $capitulo = ManualCapitulos::find($id);
        $capitulo->delete();
        return redirect('manutencao/capitulos')->with('message','Capítulo excluído com sucesso');
    }

}
