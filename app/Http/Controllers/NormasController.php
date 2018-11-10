<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Tipo_relatorios;
use App\Grupos;
use App\Normas;

class NormasController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    private function permission(){
        if(Auth::user()->user_levels_id > 4){
            abort(403);
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
        $this->permission();
        $normas = DB::table('normas as n')
                ->join('grupos as g', 'g.id', '=', 'n.grupos_id')
                ->select('n.*','g.grupo')
                ->get();
        return view('analise.normas.index', ['normas' => $normas]);
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
//        return var_dump(Grupos::all());
        return view('analise.normas.create', ['grupos' => Grupos::all()]);
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
            'norma' => 'required',
            'descricao' => 'required'
        ]);
        
        $norma = new Normas;
        $norma->grupos_id = $request->grupos_id;
        $norma->norma = $request->norma;
        $norma->descricao = $request->descricao;
        
        $norma->save();
        
        switch($request->action){
            case 'salva':
                return redirect('analise/normas')->with('message', 'Norma cadastrada com sucesso');
                break;
            case 'continua':
                $request->flash();
                $dados = array(
                    'grupos' => Grupos::all()
                );

                return view('analise.normas.create', $dados)->with('message', 'Norma cadastrada com sucesso');
                break;
        }
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
        $this->permission();
        return view('analise.normas.edit',[
            'norma' => Normas::find($id),
            'tipo_relatorios' => Tipo_relatorios::all()
        ]);
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
            'norma' => 'required',
            'descricao' => 'required'
        ]);
        
        $norma = Normas::find($id);
        $norma->tipo_relatorios_id = $request->tipo_relatorios_id;
        $norma->norma = $request->norma;
        $norma->descricao = $request->descricao;
        
        $norma->save();
        
        return redirect('analise/normas')->with('message', 'Norma alterada com sucesso');
       
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
        $this->permission();
        $norma = Normas::find($id);
        
        $norma->delete();
        return redirect('analise/normas')->with('message', 'Norma excluída com sucesso');
    }
}
