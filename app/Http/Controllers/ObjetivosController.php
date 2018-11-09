<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ObjetivosController extends Controller
{
    private function permission(){
        $nivel = DB::table('user_dados')->where('users_id', Auth::id())->value('user_levels_id');
        if(is_null($nivel)){
            abort(403, 'Acesso Negado');
        }
    }
    public function __construct() {
        $this->middleware('auth');
        $this->permission();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        //
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $objetivos = DB::table('objetivos')
                ->select('*')
                ->first();
        return view('objetivos.create', ['objetivos' => $objetivos]);
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
        $objetivo = $request->objetivo;
        $detalhamento = $request->detalhamento;
        $consideracao = $request->consideracao;
        
        DB::table('objetivos')
                ->where('id',1)
                ->update(['objetivo' => $objetivo,
                         'detalhamento' => $detalhamento,
                         'consideracao' => $consideracao
                        ]);
        return redirect('textos/create')->with('message', 'Textos padrões editados com sucesso');
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
        abort(404);
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
        abort(404);
                
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
