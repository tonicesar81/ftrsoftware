<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Setores;
use App\Shopping;
use App\Pavimentos;

class SetoresController extends Controller
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
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //
        $pavimentos = Pavimentos::where('shoppings_id', $id)->get();
        
        return view('manutencao.setores.create', ['pavimentos' => $pavimentos, 'shopping' => $id]);
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
            'setor' => 'required'
            ]);
        $setor = new Setores;
        $setor->pavimentos_id = $request->pavimentos_id;
        $setor->setor = $request->setor;
        $setor->save();
        
        return redirect('manutencao/setores/'.$request->shopping)->with('message', 'Setor cadastrado com sucesso');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //SELECT p.pavimento,s.* FROM setores AS s
        //INNER JOIN pavimentos AS p ON p.id = s.pavimentos_id
        //WHERE p.shoppings_id = 6
        $setores = DB::table('setores AS s')
                ->join('pavimentos AS p', 'p.id','=','s.pavimentos_id')
                ->where('p.shoppings_id',$id)
                ->select('p.pavimento','s.*')
                ->get();
        return view('manutencao.setores.index', ['setores' => $setores, 'shopping' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //SELECT * FROM `pavimentos` WHERE `shoppings_id` = (SELECT shoppings_id FROM pavimentos WHERE id = 1)
        $setor = Setores::find($id);
        $pavimentos = Pavimentos::whereRaw('shoppings_id = (SELECT shoppings_id FROM pavimentos WHERE id = '.$setor->pavimentos_id.')')->get();
//        return $pavimentos;
        return view('manutencao.setores.edit', ['setor' => $setor, 'pavimentos' => $pavimentos]);
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
            'setor' => 'required'
            ]);
        $setor = Setores::find($id);
        $setor->pavimentos_id = $request->pavimentos_id;
        $setor->setor = $request->setor;
        
        $setor->save();
        
        return redirect('manutencao/setores/'.$request->shopping)->with('message', 'Setor alterado com sucesso');
        
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
        $setor = Setores::find($id);
        $shopping = DB::table('pavimentos')->where('id',$setor->pavimentos_id)->value('shoppings_id');
        $setor->delete();
        return redirect('manutencao/setores/'.$shopping)->with('message', 'Setor excluído com sucesso');
    }
}
