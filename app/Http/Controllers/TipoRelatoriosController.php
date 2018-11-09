<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Tipo_relatorios;
use App\Itens;

class TipoRelatoriosController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private function permission(){
        $nivel = DB::table('user_dados')->where('users_id', Auth::id())->value('user_levels_id');
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
        $tipo_relatorios = Tipo_relatorios::all();

        return view('analise.sistema.index', ['tipo_relatorios' => $tipo_relatorios]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
        $this->permission();
        return view('analise.sistema.create');
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
            'tipo_relatorio' => 'required|unique:tipo_relatorios'
        ]);
        $tipo_relatorios = new Tipo_relatorios;
        $tipo_relatorios->tipo_relatorio = $request->tipo_relatorio;
        if(!$request->filled('ref')){
            $tipo_relatorios->ref = strtoupper(substr($request->tipo_relatorio, 0, 3));
        }else{
            $tipo_relatorios->ref = $request->ref;
        }
        $tipo_relatorios->save();

        return redirect('analise/sistema')->with('message', 'Novo tipo de relatório cadastrado no sistema');
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
        $tipo_relatorios = Tipo_relatorios::find($id);

        return view('analise.sistema.edit', ['tipo_relatorios' => $tipo_relatorios]);
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
        $tipo_relatorios = Tipo_relatorios::find($id);

        $this->validate($request, [
            'tipo_relatorio' => 'required|unique:tipo_relatorios,tipo_relatorio,' . $tipo_relatorios->id
        ]);

        $tipo_relatorios->tipo_relatorio = $request->tipo_relatorio;
        if(!$request->filled('ref')){
            $tipo_relatorios->ref = strtoupper(substr($request->tipo_relatorio, 0, 3));
        }else{
            $tipo_relatorios->ref = strtoupper($request->ref);
        }

        $tipo_relatorios->save();

        return redirect('analise/sistema')->with('message', 'Tipo de relatório alterado');
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
        $tipo_relatorios = Tipo_relatorios::find($id);

        $tipo_relatorios->delete();

        return redirect('analise/sistema')->with('message', 'Tipo de relatório removido do sistema');
    }

}
