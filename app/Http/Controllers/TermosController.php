<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Termos;
use App\Man_itens;

class TermosController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    private function permission(){
        if(!in_array(Auth::user()->user_levels_id,[1,6])){
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
        $termos = DB::table('termos as t')
                ->join('man_itens as i', 'i.id', '=', 't.man_itens_id')
                ->select('t.*','i.item as man_item')
                ->get();
        return view('manutencao.termos.index',['termos' => $termos]);
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
        return view('manutencao.termos.create', ['sistemas' => Man_itens::all()]);
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
        if(empty($request->item)){
            return redirect('manutencao/termos/create')->with('message','Nenhum termo foi criado');
        }else{
            $termos = $request->input('item');
            $verificacoes = $request->input('verificacao');
//            return var_dump($termos);
            foreach($termos as $k => $t){
                $termo = new Termos;
                $termo->man_itens_id = $request->man_itens_id;
                $termo->verificacao = $verificacoes[$k];
                $termo->item = $termos[$k];
                if(!is_null($termo->item)){
                    $termo->save();
                }
            }
        }
        return redirect('manutencao/termos')->with('message', 'Termo(s) criado(s) com sucesso');
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
        $termo = Termos::find($id);
        return view('manutencao.termos.edit',['termo' => $termo, 'sistemas' => Man_itens::all()]);
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
        $this->validate($request, [
            'item' => 'required'
        ]);
        $this->permission();
        $termo = Termos::find($id);
        $termo->man_itens_id = $request->man_itens_id;
        $termo->item = $request->item;
        $termo->verificacao = $request->verificacao;
        
        $termo->save();
        
        return redirect('manutencao/termos')->with('message', 'Termo modificado com sucesso');
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
        $termo = Termos::find($id);
        $termo->delete();
        
        return redirect('manutencao/termos')->with('message', 'Termo excluído com sucesso');
        
    }
}
