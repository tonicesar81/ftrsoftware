<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Man_desc;
use App\Man_itens;

class Man_descController extends Controller
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
        $desc = DB::table('man_descs as d')
                ->join('man_itens as i','i.id','=','d.man_itens_id')
                ->select('d.*','i.item')
                ->get();
        return view('manutencao.desc.index',['desc' => $desc]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $itens = Man_itens::all();
        return view('manutencao.desc.create', ['itens' => $itens]);
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
            'descricao' => 'required'
        ]);
        $man_desc = new Man_desc;
        
        $man_desc->man_itens_id = $request->man_itens_id;
        $man_desc->descricao = strtoupper($request->descricao);
        
        $man_desc->save();
        switch ($request->action) {
            case 'salva':
                return redirect('manutencao/desc')->with('message','Descrição cadastrada com sucesso.');
                break;
            case 'continua':
                $itens = Man_itens::all();
                return view('manutencao.desc.create', ['itens' => $itens, 'last_item' => $man_desc->man_itens_id, 'message' => 'Descrição cadastrada com sucesso.']);
                break;
        }
//        return redirect('manutencao/desc')->with('message','Descrição cadastrada com sucesso.');
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
        return view('manutencao.desc.edit',['itens' => Man_itens::all(), 'desc' => Man_desc::find($id)]);
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
            'descricao' => 'required'
        ]);
        $desc = Man_desc::find($id);
        $desc->man_itens_id = $request->man_itens_id;
        $desc->descricao = strtoupper($request->descricao);
        
        $desc->save();
        
        return redirect('manutencao/desc')->with('message','Descrição alterada com sucesso.');
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
        $desc = Man_desc::find($id);
        $desc->delete();
        return redirect('manutencao/desc')->with('message','Descrição excluída com sucesso.');
    }
}
