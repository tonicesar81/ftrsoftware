<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Dstipo;

class DstiposController extends Controller
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
        return view('datasheets.tipos.index',['tipos' => Dstipo::all()]);
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
        return view('datasheets.tipos.create');
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
            'tipo' => 'required'
        ]);
        $tipo = new Dstipo;
        $tipo->tipo = $request->tipo;
        
        $tipo->save();
        
        return redirect('datasheets/tipos')->with('message', 'Tipo de equipamento cadastrado com sucesso');
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
        $tipo = Dstipo::find($id);
        return view('datasheets.tipos.edit',['tipo' => $tipo]);
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
            'tipo' => 'required'
        ]);
        $tipo = Dstipo::find($id);
        $tipo->tipo = $request->tipo;
        
        $tipo->save();
        
        return redirect('datasheets/tipos')->with('message', 'Tipo de equipamento alterado com sucesso');
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
        $tipo = Dstipo::find($id);
        
        $tipo->delete();
        
        return redirect('datasheets/tipos')->with('message', 'Tipo de equipamento excluído com sucesso');
    }
}
