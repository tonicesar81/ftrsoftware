<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Shopping;
use App\Projetos;
use App\ProjetosArquivos;
use App\Tipo_relatorios;

class ProjetosController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    private function getShoppings() {
        $shoppings = Shopping::whereIn('id', DB::table('users_shoppings')->where('users_id', Auth::id())->get()->pluck('shoppings_id'))->get();

        return $shoppings;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        foreach($this->getShoppings() as $shp){
            @$shopping[] .= $shp->shopping;
        }
        return print_r($shopping);
        return var_dump($this->getShoppings());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $shoppings = $this->getShoppings();
        $tipo_relatorios = Tipo_relatorios::all();
        
        return view('analise.projetos.create', ['shoppings' => $shoppings, 'tipo_relatorios' => $tipo_relatorios]);
    }
    public function addFile($id){
        $shoppings = $this->getShoppings();
        $tipo_relatorios = Tipo_relatorios::all();
        
        return view('analise.projetos.add_projeto', ['shoppings' => $shoppings, 'tipo_relatorios' => $tipo_relatorios, 'fid' => $id]);
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
        ini_set('memory_limit', '-1');
        $this->validate($request, [
            'shoppings_id' => 'required',
            'tipo_relatorios' => 'required',
            'loja' => 'required',
            'numero' => 'required'
        ]);
        
//        return var_dump($request->projetos);
        
        $projeto = new Projetos;
        $projeto->shoppings_id = $request->shoppings_id;
        $projeto->tipo_relatorios_id = $request->tipo_relatorios;
        $projeto->loja = $request->loja.' - '.$request->numero;
        
        $projeto->save();
        
        if($request->hasFile('memorial')){
            $m = $request->memorial;
           if($m->extension() != 'pdf'){
               DB::table('projetos')->where('id', '=', $projeto->id)->delete();
                return redirect('analise/projetos/create')->with('message', 'Formato de arquivo inválido');
            }else{
                $p = new ProjetosArquivos;
                $p->projetos_id = $projeto->id;
                $p->filename = $m->getClientOriginalName();
                $path = $m->store('projetos');
                $file = explode('/', $path)[1];
                $p->filepath = $file;
                $p->memorial = 1;
                $p->save();
            } 
        }
        
        foreach($request->pdf as $pdf){
            if($pdf->extension() != 'pdf'){
                DB::table('projetos')->where('id', '=', $projeto->id)->delete();
                return redirect('analise/projetos/create')->with('message', 'Formato de arquivo inválido');
            }else{
                $p = new ProjetosArquivos;
                $p->projetos_id = $projeto->id;
                $p->filename = $pdf->getClientOriginalName();
                $path = $pdf->store('projetos');
                $file = explode('/', $path)[1];
                $p->filepath = $file;
                $p->save();
            } 
        }
        if($request->hasFile('dwg')){
            foreach($request->dwg as $dwg){
                if($dwg->extension() != 'dwg'){
                    DB::table('projetos')->where('id', '=', $projeto->id)->delete();
                    return redirect('analise/projetos/create')->with('message', 'Formato de arquivo inválido');
                }else{
                    $p = new ProjetosArquivos;
                    $p->projetos_id = $projeto->id;
                    $p->filename = $dwg->getClientOriginalName();
                    $path = $dwg->store('projetos');
                    $file = explode('/', $path)[1];
                    $p->filepath = $file;
                    $p->save();
                } 
            }
        }
        return redirect('analise/projetos/create')->with('message', 'Projeto adicionado para análise com sucesso.');
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
