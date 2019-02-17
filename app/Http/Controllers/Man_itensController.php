<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Man_itens;

class Man_itensController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('manutencao.itens.index',['itens' => Man_itens::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('manutencao.itens.create');
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
        $this->validate($request, [
            'item' => 'required|unique:man_itens'
        ]);
        $man_item = new Man_itens;
        $man_item->item = strtoupper($request->item);
        $man_item->norma = strtoupper($request->norma);
        $man_item->texto = $request->texto;
        $man_item->observacao = $request->observacao;
        $man_item->save();
        
        return redirect('manutencao/itens')->with('message','Item de manutenção cadastrado com sucesso');
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
        $item = Man_itens::find($id);
        return view('manutencao.itens.edit',['item' => $item]);
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
        $item = Man_itens::find($id);
        $this->validate($request, [
            'item' => 'required|unique:man_itens,item,' . $item->id
        ]);
        $item->item = strtoupper($request->item);
        $item->norma = strtoupper($request->norma);
        $item->texto = $request->texto;
        $man_item->observacao = $request->observacao;
        $item->save();
        
        return redirect('manutencao/itens')->with('message','Item de manutenção alterado com sucesso');
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
        $item = Man_itens::find($id);
        $item->delete();
        
        return redirect('manutencao/itens')->with('message','Item de manutenção excluído com sucesso');
    }
}
