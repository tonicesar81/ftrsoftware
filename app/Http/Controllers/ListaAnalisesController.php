<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Lista_analises;

class ListaAnalisesController extends Controller {

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
        $this->permission();
        //SELECT itens.item,tipo_relatorios.tipo_relatorio,lista_analises.* FROM `lista_analises`
        //INNER JOIN itens ON lista_analises.itens_id = itens.id
        //INNER JOIN tipo_relatorios ON tipo_relatorios.id = itens.tipo_relatorios_id
        $lista_analises = DB::table('lista_analises')
                ->join('itens', 'lista_analises.itens_id', '=', 'itens.id')
                ->join('tipo_relatorios', 'tipo_relatorios.id', '=', 'itens.tipo_relatorios_id')
                ->select('lista_analises.*', 'itens.item', 'tipo_relatorios.tipo_relatorio')
                ->get();
        return view('analise.obs.index', ['lista_analises' => $lista_analises]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
        $this->permission();
        $itens = DB::table('itens')
                ->join('tipo_relatorios', 'tipo_relatorios.id', '=', 'itens.tipo_relatorios_id')
                ->select('itens.*', 'tipo_relatorios.tipo_relatorio')
                ->orderBy('itens.tipo_relatorios_id')
                ->get();

        return view('analise.obs.create', ['itens' => $itens]);
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
            'lista_analise' => 'required'
        ]);

        $lista_analises = new lista_analises;

        $lista_analises->itens_id = $request->itens_id;
        $lista_analises->lista_analise = $request->lista_analise;
        if ($request->hasFile('figura')) {
            $lista_analises->figura = Storage::put('figuras', $request->file('figura'));
        }
        $lista_analises->save();

        return redirect('analise/obs')->with('message', 'Nova observação agora disponível');
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
        $lista_analises = DB::table('lista_analises')
                ->join('itens', 'lista_analises.itens_id', '=', 'itens.id')
                ->join('tipo_relatorios', 'tipo_relatorios.id', '=', 'itens.tipo_relatorios_id')
                ->select('lista_analises.*', 'itens.item', 'tipo_relatorios.tipo_relatorio')
                ->where('lista_analises.id', '=', $id)
                ->first();

        $itens = DB::table('itens')
                ->join('tipo_relatorios', 'tipo_relatorios.id', '=', 'itens.tipo_relatorios_id')
                ->select('itens.*', 'tipo_relatorios.tipo_relatorio')
                ->orderBy('itens.tipo_relatorios_id')
                ->get();

        return view('analise.obs.edit', ['lista_analises' => $lista_analises, 'itens' => $itens]);
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
        $lista_analise = Lista_analises::find($id);
        $this->validate($request, [
            'lista_analise' => 'required|unique:lista_analises,lista_analise,' . $lista_analise->id
        ]);
        $lista_analise->itens_id = $request->itens_id;
        $lista_analise->lista_analise = $request->lista_analise;
        if($request->del_figura == 1){
            Storage::disk('public')->delete($lista_analise->figura);
            $lista_analise->figura = null;           
        }
        if ($request->hasFile('figura')) {
            $lista_analise->figura = Storage::disk('public')->put('figuras', $request->file('figura'));
        }
        $lista_analise->save();

        return redirect('analise/obs')->with('message', 'Observação editada com sucesso');
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
        $lista_analise = Lista_analises::find($id);

        $lista_analise->delete();

        return redirect('analise/obs')->with('message', 'Observação excluída do sistema');
    }

}
