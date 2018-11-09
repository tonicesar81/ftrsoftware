<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Itens;
use App\Tipo_relatorios;

class ItensController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private function permission(){
        $nivel = DB::table('user_dados')->where('users_id', Auth::id())->value('user_levels_id');
//        return var_dump($nivel);
        if(is_null($nivel)){
            abort(403, 'Acesso Negado');
        }
    }
    public function __construct() {
        $this->middleware('auth');
        
    }

    public function index() {
        //
        $this->permission();
        $itens = DB::table('itens')
                ->join('tipo_relatorios', 'tipo_relatorios.id', '=', 'itens.tipo_relatorios_id')
                ->select('itens.*', 'tipo_relatorios.tipo_relatorio')
                ->orderBy('itens.tipo_relatorios_id')
                ->get();
//        $itens = Itens::all();
//        return $itens;
        return view('analise.item.index', ['itens' => $itens]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
        $this->permission();
        $tipo_relatorios = Tipo_relatorios::all();

        return view('analise.item.create', ['tipo_relatorios' => $tipo_relatorios]);
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
            'item' => 'required'
        ]);

        $itens = new Itens;

        $itens->tipo_relatorios_id = $request->tipo_relatorios_id;
        $itens->item = $request->item;

        $itens->save();

        return redirect('analise/item')->with('message', 'Novo item para análise criado');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
        abort(404);
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
        $tipo_relatorios = Tipo_relatorios::all();

        return view('analise.item.edit', ['tipo_relatorios' => Tipo_relatorios::all(), 'itens' => Itens::find($id)]);
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
        $itens = Itens::find($id);
        $this->validate($request, [
            'item' => 'required'
        ]);
        $itens->tipo_relatorios_id = $request->tipo_relatorios_id;
        $itens->item = $request->item;

        $itens->save();

        return redirect('analise/item')->with('message', 'Item para análise editado com sucesso');
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
        $itens = Itens::find($id);

        $itens->delete();

        return redirect('analise/item')->with('message', 'Item para análise excluído do sistema');
    }

}
