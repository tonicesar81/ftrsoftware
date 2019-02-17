<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Pavimentos;
use App\Shopping;

class PavimentosController extends Controller
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
        //SELECT s.id, s.shopping, (SELECT COUNT(*) FROM pavimentos WHERE shoppings_id = s.id) AS pavs
        //FROM shoppings AS s WHERE (SELECT COUNT(*) FROM pavimentos WHERE shoppings_id = s.id) > 0
        $shoppings = DB::table('shoppings AS s')
                ->select(DB::raw('s.id, s.shopping, (SELECT COUNT(*) FROM pavimentos WHERE shoppings_id = s.id) AS pavs'))
                ->whereRaw(' (SELECT COUNT(*) FROM pavimentos WHERE shoppings_id = s.id) > 0')
                ->get();
        return view('manutencao.pavimentos.index',['shoppings' => $shoppings]);
    }
    
    public function pavimentos($id){
        //SELECT p.id, p.pavimento, (SELECT COUNT(*) FROM setores WHERE pavimentos_id = p.id) AS sets
        //FROM pavimentos AS p WHERE p.shoppings_id = 6
        $pavimentos = DB::table('pavimentos AS p')
                ->select(DB::raw('p.id, p.pavimento, (SELECT COUNT(*) FROM setores WHERE pavimentos_id = p.id) AS sets'))
                ->where('p.shoppings_id',$id)
                ->get();
        return view('manutencao.pavimentos.pavimento', ['pavimentos' => $pavimentos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('manutencao.pavimentos.create', ['shoppings' => Shopping::all()]);
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
            'pavimento' => 'required'
        ]);
        $pavimento = new Pavimentos;
        $pavimento->shoppings_id = $request->shoppings_id;
        $pavimento->pavimento = $request->pavimento;
        $pavimento->ordem = $request->ordem;
        
        $pavimento->save();
        
        return redirect('manutencao/pavimentos')->with('message', 'Pavimento incluído com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //SELECT p.id, p.pavimento, (SELECT COUNT(*) FROM setores WHERE pavimentos_id = p.id) AS sets
        //FROM pavimentos AS p WHERE p.shoppings_id = 6
        $pavimentos = DB::table('pavimentos AS p')
                ->select(DB::raw('p.id, p.pavimento, (SELECT COUNT(*) FROM setores WHERE pavimentos_id = p.id) AS sets'))
                ->where('p.shoppings_id',$id)
                ->orderBy('ordem','asc')
                ->get();
        return view('manutencao.pavimentos.pavimento', ['pavimentos' => $pavimentos, 'shopping' => $id]);
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
        $pavimento = Pavimentos::find($id);
        
        return view('manutencao.pavimentos.edit', ['pavimento' => $pavimento, 'shoppings' => Shopping::all()]);
        
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
        $pavimento = Pavimentos::find($id);
        $shopping = $pavimento->shoppings_id;
        $this->validate($request, [
            'pavimento' => 'required'
        ]);
        $pavimento->shoppings_id = $request->shoppings_id;
        $pavimento->pavimento = $request->pavimento;
        $pavimento->ordem = $request->ordem;
        $pavimento->save();
        
        return redirect('manutencao/pavimentos/'.$shopping)->with('message', 'Pavimento alterado com sucesso.');
        
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
        $pavimento = Pavimentos::find($id);
        $shopping = $pavimento->shoppings_id;
        $pavimento->delete();
        
        return redirect('manutencao/pavimentos/'.$shopping)->with('message', 'Pavimento excluído com sucesso.');
    }
}
