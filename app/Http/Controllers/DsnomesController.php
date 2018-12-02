<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Tipo_relatorios;
use App\Dsnomes;

class DsnomesController extends Controller
{
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
    
    public function index()
    {
        //
        $this->permission();
        $dsnomes = DB::table('dsnomes as d')
                ->join('tipo_relatorios as t','t.id','=','d.tipo_relatorios_id')
                ->select('t.ref','d.*')
                ->get();
        return view('datasheets.nomes.index',['nomes' => $dsnomes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $this->permission();
        return view('datasheets.nomes.create', ['tipo_relatorios' => Tipo_relatorios::all()]);
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
        $this->permission();
        $this->validate($request, [
            'nome' => 'required'
        ]);
        $dsnome = new Dsnomes;
        $dsnome->tipo_relatorios_id = $request->tipo_relatorios_id;
        $dsnome->nome = $request->nome;
        $dsnome->nome_plural = $request->nome_plural;
        
        $dsnome->save();
        
        return redirect('datasheets/nomes')->with('message', 'Equipamento cadastrado com sucesso.');
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
        $nome = Dsnomes::find($id);
        
        return view('datasheets.nomes.edit',['nome' => $nome, 'tipo_relatorios' => Tipo_relatorios::all()]);
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
        $this->permission();
        $this->validate($request, [
            'nome' => 'required'
        ]);
        
        $nome = Dsnomes::find($id);
        
        $nome->tipo_relatorios_id = $request->tipo_relatorios_id;
        $nome->nome = $request->nome;
        $nome->nome_plural = $request->nome_plural;
        
        $nome->save();
        
        return redirect('datasheets/nomes')->with('message', 'Equipamento alterado com sucesso.');
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
        $nome = Dsnomes::find($id);
        
        $nome->delete();
        
        return redirect('datasheets/nomes')->with('message', 'Equipamento exluído com sucesso.');
    }
}
