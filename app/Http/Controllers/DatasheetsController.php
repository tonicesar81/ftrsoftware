<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Shopping;
use App\Dsnomes;
use App\Dstipo;
use App\Dslocais;
use App\Datasheets;
use App\Dsdetalhes;
use App\User_dados;

class DatasheetsController extends Controller
{
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
    
    private function getShoppings_id() {
        $shoppings = Shopping::whereIn('id', DB::table('users_shoppings')->where('users_id', Auth::id())->pluck('shoppings_id'))->get();

        return $shoppings;
    }
    
    private function getShoppings() {
        if (is_null($this->nivel())) {
            $shoppings = DB::table('shoppings')
                    ->select('*')
                    ->whereIn('id', $this->getShoppings_id())
                    ->orderBy('shopping', 'asc')
                    ->get();
        } else {
            $shoppings = DB::table('shoppings')
                    ->select('*')
                    ->orderBy('shopping', 'asc')
                    ->get();
        }

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
        $sh_array = array();
        foreach($this->getShoppings_id() as $s){
            $sh_array[] = $s->id;
        }
        if(is_null($this->nivel())){
            $datasheets = DB::table('datasheets as d')
                    ->join('shoppings as s', 's.id', '=', 'd.shoppings_id')
                    ->select('s.shopping', 'd.*')
                    ->whereIn('d.shoppings_id',$sh_array)
                    ->get();
        }else{
            $datasheets = DB::table('datasheets as d')
                    ->join('shoppings as s', 's.id', '=', 'd.shoppings_id')
                    ->select('s.shopping', 'd.*')
                    ->get();      
        }
        
        return view('datasheets.index',['datasheets' => $datasheets, 'shoppings' => $this->getShoppings(), 'nivel' => $this->nivel()]);
    }
    
    public function pesquisa(Request $request)
    {
        //
        $sh_array = array();
        foreach($this->getShoppings_id() as $s){
            $sh_array[] = $s->id;
        }
        if(is_null($this->nivel())){
            $datasheets = DB::table('datasheets as d')
                    ->join('shoppings as s', 's.id', '=', 'd.shoppings_id')
                    ->select('s.shopping', 'd.*')
                    ->whereIn('d.shoppings_id',$sh_array)
                    ->get();
        }else{
            $datasheets = DB::table('datasheets as d')
                    ->join('shoppings as s', 's.id', '=', 'd.shoppings_id')
                    ->select('s.shopping', 'd.*')
                    ->where('d.shoppings_id', $id)
                    ->get();
                  
        }
        
        return view('datasheets.index',['datasheets' => $datasheets, 'shoppings' => $this->getShoppings(), 'nivel' => $this->nivel()]);
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
        $array = [
            'nomes' => Dsnomes::all(),
            'tipos' => Dstipo::all(),
            'locais' => Dslocais::all(),
            'shoppings' => Shopping::all()
        ];
        
        return view('datasheets.create', $array);
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
            'loja' => 'required',
            'numero' => 'required'
        ]);
        
        $check = DB::table('datasheets')
                ->where([
                    ['shoppings_id', '=', $request->shoppings_id],
                    ['loja', '=', $request->loja],
                    ['numero', '=', $request->numero]
                ])->first();
        if(!$check->isEmpty()){
            return redirect('datasheets/create')->with('message','Datasheet já existe.');
        }
        $datasheet = new Datasheets;
        $datasheet->shoppings_id = $request->shoppings_id;
        $datasheet->loja = $request->loja;
        $datasheet->numero = $request->numero;
        $datasheet->save();
        
        for($i=0;$i<count($request->quantidade);$i++){
            $dsdetalhes = new Dsdetalhes;
            $dsdetalhes->datasheets_id = $datasheet->id;
            $dsdetalhes->quantidade = $request->quantidade[$i];
            $dsdetalhes->dsnomes_id = $request->dsnomes_id[$i];
            $dsdetalhes->dstipos_id = $request->dstipos_id[$i];
            $dsdetalhes->dslocais_id = $request->dslocais_id[$i];
            $dsdetalhes->save();
        }
        return redirect('datasheets')->with('message','Datasheet criado com sucesso.');
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
//        SELECT e.logo, s.shopping, d.*
//        FROM datasheets as d
//        INNER JOIN shoppings as s ON s.id = d.shoppings_id
//        INNER JOIN empresas as e ON e.id = s.empresas_id
//        WHERE d.id = 1
        $datasheet = DB::table('datasheets as d')
                ->join('shoppings as s', 's.id', '=', 'd.shoppings_id')
                ->join('empresas as e', 'e.id', '=', 's.empresas_id')
                ->select('e.logo', 's.shopping', 'd.*')
                ->where('d.id', $id)
                ->first();
        
//        SELECT n.nome, n.nome_plural, n.tipo_relatorios_id, 
//        p.tipo, l.local, d.*
//        FROM
//        dsdetalhes AS d
//        INNER JOIN dsnomes AS n ON n.id = d.dsnomes_id
//        INNER JOIN dstipos AS p ON p.id = d.dstipos_id
//        INNER JOIN dslocais as l on l.id = d.dslocais_id
//        WHERE d.datasheets_id = 1
        $detalhes = DB::table('dsdetalhes as d')
                ->join('dsnomes as n','n.id','=','d.dsnomes_id')
                ->join('dstipos as p','p.id','=','d.dstipos_id')
                ->join('dslocais as l','l.id','=','d.dslocais_id')
                ->select('n.nome','n.nome_plural','n.tipo_relatorios_id','p.tipo','l.local','d.*')
                ->where('d.datasheets_id', $id)
                ->get();
//        SELECT t.tipo_relatorio, t.id
//        FROM dsdetalhes as d
//        INNER JOIN dsnomes AS n ON n.id = d.dsnomes_id
//        INNER JOIN tipo_relatorios AS t ON t.id = n.tipo_relatorios_id
//        WHERE d.datasheets_id = 1
//        GROUP BY t.id
        $disciplinas = DB::table('dsdetalhes as d')
                ->join('dsnomes as n', 'n.id', '=', 'd.dsnomes_id')
                ->join('tipo_relatorios as t','t.id', '=', 'n.tipo_relatorios_id')
                ->select('t.tipo_relatorio','t.id')
                ->where('d.datasheets_id', $id)
                ->groupBy('t.id')
                ->get();
        
        $data = ['datasheet' => $datasheet, 'disciplinas' => $disciplinas, 'detalhes' => $detalhes];
        
        $pdf = \DOMPDF::loadView('datasheets.pdf', $data);
        $pdf->getDomPDF()->set_option("enable_php", true);
        //$pdf->loadView('analise.pdf.show', $data);
        //$pdf->loadHTML('your view here ');
        return $pdf->setPaper('a4', 'portrait')->stream();
//        return view('datasheets.pdf', ['datasheet' => $datasheet, 'disciplinas' => $disciplinas, 'detalhes' => $detalhes]);
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
        $datasheet = Datasheets::find($id);
        $detalhes = Dsdetalhes::where('datasheets_id', $id)->get();
        
        $array = [
            'datasheet' => $datasheet, 
            'detalhes' => $detalhes,
            'nomes' => Dsnomes::all(),
            'tipos' => Dstipo::all(),
            'locais' => Dslocais::all(),
            'shoppings' => Shopping::all()
        ];
        
        return view('datasheets.edit',$array);
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
            'loja' => 'required',
            'numero' => 'required'
        ]);
        
        if(count($request->quantidade) == 0){
            return redirect('datasheets/edit/'.$id)->with('message', 'É necessário ao menos um equipamento para gerar o datasheet');
        }
        
        $datasheet = Datasheets::find($id);
        $datasheet->shoppings_id = $request->shoppings_id;
        $datasheet->loja = $request->loja;
        $datasheet->numero = $request->numero;
        $datasheet->save();
        
        DB::table('dsdetalhes')->where('datasheets_id', '=', $id)->delete();
        
        for($i=0;$i<count($request->quantidade);$i++){
            $dsdetalhes = new Dsdetalhes;
            $dsdetalhes->datasheets_id = $datasheet->id;
            $dsdetalhes->quantidade = $request->quantidade[$i];
            $dsdetalhes->dsnomes_id = $request->dsnomes_id[$i];
            $dsdetalhes->dstipos_id = $request->dstipos_id[$i];
            $dsdetalhes->dslocais_id = $request->dslocais_id[$i];
            $dsdetalhes->save();
        }
        
        return redirect('datasheets')->with('message','Datasheet modificado com sucesso.');
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
        $datasheet = Datasheets::find($id);
        $datasheet->delete();
        
        return redirect('datasheets/')->with('message','Datasheet excluída com sucesso.');
    }
}
