<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\User_dados;
use App\Obras_arquivos;
use App\Obras;
use App\Shopping;

class Obras_arquivosController extends Controller
{
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($obras_id)
    {
        //
        $this->permission();
        $obra = Obras::find($obras_id);
        
        return view('manutencao.arquivos.create',['obra' => $obra]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if(!$request->hasFile('arquivo')){
            return redirect('manutencao/arquivos/create/'.$request->obras_id)->with('message', 'Nenhum arquivo foi enviado');
        }
        $arquivo = new Obras_arquivos;
        $arquivo->obras_id = $request->obras_id;
        $arquivo->tipo = $request->tipo;
        $arq = $request->file('arquivo');
        $arquivo->arquivo = $arq->getClientOriginalName();
        $arquivo->hash = $arq->store('obras');
        $arquivo->save();
        
        return redirect('manutencao/arquivos/create/'.$request->obras_id)->with('message', 'Arquivo enviado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
