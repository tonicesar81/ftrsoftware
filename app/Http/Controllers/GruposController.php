<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Grupos;


class GruposController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        //
        $grupos = Grupos::all();

        return view('analise.grupos.index', ['grupos' => $grupos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
        return view('analise.grupos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //

        $this->validate($request, [
            'grupo' => 'required|unique:grupos'
        ]);
        $grupos = new Grupos;
        $grupos->grupo = $request->grupo;
        if(!$request->filled('abrev')){
            $grupos->abrev = strtoupper(substr($request->grupo, 0, 3));
        }else{
            $grupos->abrev = $request->abrev;
        }
        $grupos->save();

        return redirect('analise/grupos')->with('message', 'Novo grupo cadastrado no sistema');
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
        $grupos = Grupos::find($id);

        return view('analise.grupos.edit', ['grupos' => $grupos]);
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
        $grupos = Grupos::find($id);

        $this->validate($request, [
            'grupo' => 'required|unique:grupos,grupo,' . $grupos->id
        ]);

        $grupos->grupo = $request->grupo;
        if(!$request->filled('abrev')){
            $grupos->abrev = strtoupper(substr($request->grupo, 0, 3));
        }else{
            $grupos->abrev = strtoupper($request->abrev);
        }

        $grupos->save();

        return redirect('analise/grupos')->with('message', 'Grupo alterado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
        $grupos = Grupos::find($id);

        $grupos->delete();

        return redirect('analise/grupos')->with('message', 'Grupo removido do sistema');
    }

}
