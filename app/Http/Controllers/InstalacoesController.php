<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Instalacoes;
use App\Shopping;
use App\Man_itens;
use App\Pavimentos;
use App\Setores;

class InstalacoesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function index()
    {
        //SELECT s.id, s.shopping, (SELECT COUNT(*) FROM instalacoes WHERE shoppings_id = s.id) AS instal
        //FROM shoppings AS s WHERE (SELECT COUNT(*) FROM instalacoes WHERE shoppings_id = s.id) > 0
        $shoppings = DB::table('shoppings AS s')
                ->select(DB::raw('s.id, s.shopping, (SELECT COUNT(*) FROM instalacoes WHERE shoppings_id = s.id) AS instal'))
                ->whereRaw(' (SELECT COUNT(*) FROM instalacoes WHERE shoppings_id = s.id) > 0')
                ->get();
        
        return view('manutencao.instalacoes.index', ['shoppings' => $shoppings]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $dados = array(
            'shoppings' => Shopping::all(),
            'itens' => Man_itens::all()
        );
        
        return view('manutencao.instalacoes.create', $dados);
        
    }
    
    public function pavimentos($id){
        $pavimentos = Pavimentos::where('shoppings_id', $id)->orderBy('ordem','asc')->get();
//        $pavimentos = $pavimentos->sortBy('pavimento');
        $string = '<option value="0">Escolha um pavimento</option>';
        foreach($pavimentos as $p){
            $string .= '<option value="'.$p->id.'">'.$p->pavimento.'</option>';
        }
        return $string;
    }
    
    public function setores($id){
        $setores = Setores::where('pavimentos_id', $id)->get();
        $string = '<option value="0">Sem setor</option>';
        foreach($setores as $s){
            $string .= '<option value="'.$s->id.'">'.$s->setor.'</option>';
        }
        return $string;
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
            'pavimentos_id' => 'required'
        ]);
        $instalacoes = new Instalacoes;
        $instalacoes->man_itens_id = $request->man_itens_id;
        $instalacoes->shoppings_id = $request->shoppings_id;
        $instalacoes->numero = $request->numero;
        $instalacoes->pavimentos_id = $request->pavimentos_id;
        $instalacoes->setores_id = ($request->setores_id > 0)? $request->setores_id : null;
        
        $instalacoes->save();
        
        switch($request->action){
            case 'salva':
                return redirect('manutencao/instalacoes')->with('message', 'Instalação cadastrada com sucesso');
                break;
            case 'continua':
                $request->flash();
                $dados = array(
                    'shoppings' => Shopping::all(),
                    'itens' => Man_itens::all()
                );

                return view('manutencao.instalacoes.create', $dados)->with('message', 'Instalação cadastrada com sucesso');
                break;
        }
        
//        return redirect('manutencao/instalacoes')->with('message', 'Instalação cadastrada com sucesso');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //SELECT i.*, it.item, p.pavimento, s.setor
        //FROM instalacoes AS i
        //INNER JOIN man_itens AS it ON it.id = i.man_itens_id
        //INNER JOIN pavimentos AS p ON p.id = i.pavimentos_id
        //LEFT JOIN setores AS s ON s.id = i.setores_id
        //WHERE i.shoppings_id = 6
        //ORDER BY it.item
        $instalacoes = DB::table('instalacoes AS i')
                ->join('man_itens AS it','it.id','=','i.man_itens_id')
                ->join('pavimentos AS p', 'p.id','=','i.pavimentos_id')
                ->leftJoin('setores AS s', 's.id', '=', 'i.setores_id')
                ->select('i.*','it.item','p.pavimento','s.setor')
                ->where('i.shoppings_id', $id)
                ->orderBy('it.item', 'asc')
                ->get();
        return view('manutencao.instalacoes.instalacoes', ['instalacoes' => $instalacoes]);
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
        $instalacao = Instalacoes::find($id);
        $pavimentos = Pavimentos::where('shoppings_id',$instalacao->shoppings_id)->orderBy('ordem','asc')->get();
        $setores = Setores::where('pavimentos_id',$instalacao->pavimentos_id)->get();
        $shoppings = Shopping::all();
        $itens = Man_itens::all();
        
        $dados = array(
            'instalacao' => $instalacao,
            'pavimentos' => $pavimentos,
            'setores' => $setores,
            'shoppings' => $shoppings,
            'itens' => $itens
        );
        
        return view('manutencao.instalacoes.edit', $dados);
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
            'pavimentos_id' => 'required'
        ]);
        $instalacoes = Instalacoes::find($id);
        $instalacoes->man_itens_id = $request->man_itens_id;
        $instalacoes->numero = $request->numero;
        $instalacoes->shoppings_id = $request->shoppings_id;
        $instalacoes->pavimentos_id = $request->pavimentos_id;
        $instalacoes->setores_id = ($request->setores_id > 0)? $request->setores_id : null;
        
        $instalacoes->save();
        
        return redirect('manutencao/instalacoes/'.$request->shoppings_id)->with('message', 'Instalação alterada com sucesso');
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
        $instalacoes = Instalacoes::find($id);
        $instalacoes->delete();
        
        return redirect('manutencao/instalacoes/')->with('message', 'Instalação removida com sucesso.');
    }
}
