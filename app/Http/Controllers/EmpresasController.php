<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Empresa;

class EmpresasController extends Controller {

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
        $empresas = Empresa::all();
        return view('empresas.index', ['empresas' => $empresas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
        return view('empresas.create');
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
            'empresa' => 'required|unique:empresas'
        ]);
        $empresa = new Empresa;
        $empresa->empresa = $request->empresa;
        $empresa->logo = Storage::put('logos', $request->file('logo'));
        $empresa->save();

        return redirect('empresas')->with('message', 'Empresa cadastrada com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
        $empresa = Empresa::find($id);

        return view('empresas.edit', ['empresa' => $empresa]);
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
        $empresa = Empresa::find($id);
        $this->validate($request, [
            'empresa' => 'required|unique:empresas,empresa,' . $empresa->id
        ]);

        $empresa->empresa = $request->empresa;
        if ($request->hasFile('logo')) {
            //
            Storage::delete($empresa->logo);
            //return var_dump($request->file('logo'));
            $empresa->logo = Storage::put('logos', $request->file('logo'));
        }
        //$empresa->logo = Storage::put('logos', $request->file('logo'));
        $empresa->save();

        return redirect('empresas')->with('message', 'Rede modificada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
        $empresa = Empresa::find($id);
        $empresa->delete();
        DB::table('shoppings')->where('empresas_id', '=', $id)->delete();
    }

}
