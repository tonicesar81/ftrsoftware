<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Man_relatorios;
use App\Shopping;
use App\Man_itens;
use App\Vistorias;
use App\Man_desc;
use App\Man_imagens;
use PDF;
use WPDF;

class Man_relatoriosController extends Controller
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
        //SELECT s.id, s.shopping, (SELECT COUNT(*) FROM instalacoes WHERE shoppings_id = s.id) AS instal
        //FROM shoppings AS s WHERE (SELECT COUNT(*) FROM instalacoes WHERE shoppings_id = s.id) > 0
        $shoppings = DB::table('shoppings AS s')
                ->select(DB::raw('s.id, s.shopping, (SELECT COUNT(*) FROM man_relatorios WHERE shoppings_id = s.id) AS r'))
                ->whereRaw(' (SELECT COUNT(*) FROM man_relatorios WHERE shoppings_id = s.id) > 0')
                ->get();
        
        return view('manutencao.relatorios.index', ['shoppings' => $shoppings]);
    }
    
    public function lista($id){
        $relatorios = DB::table('man_relatorios')
                ->where('shoppings_id', $id)
                ->get();
        return view('manutencao.relatorios.lista', ['relatorios' => $relatorios]);
    }
    
    public function instalacao($shopping,$item){
        //SELECT inst.*, pv.pavimento, st.setor, it.item 
        //FROM `instalacoes` AS inst 
        //INNER JOIN pavimentos AS pv ON pv.id = inst.pavimentos_id 
        //LEFT JOIN setores AS st ON st.id = inst.setores_id 
        //INNER JOIN man_itens AS it ON it.id = inst.man_itens_id 
        //WHERE inst.shoppings_id = 6 AND inst.man_itens_id = 1
//        $instalacao = Man_itens::find($id);
        $instalacoes = DB::table('instalacoes AS inst')
                ->join('pavimentos AS pv', 'pv.id', '=', 'inst.pavimentos_id')
                ->leftJoin('setores AS st', 'st.id', '=', 'inst.setores_id')
                ->join('man_itens AS it', 'it.id', '=', 'inst.man_itens_id')
                ->select('inst.*','pv.pavimento','st.setor','it.item')
//                ->where('inst.shoppings_id', $shopping)
                ->where([
                    ['inst.shoppings_id', '=' ,$shopping],
                    ['inst.man_itens_id', '=', $item]
                        ])
                ->orderBy('pv.ordem', 'asc')
                ->get();
        
        foreach ($instalacoes as $instalacao) {

            $instalacoes->map(function ($instalacao) {
                $descs = Man_desc::where('man_itens_id', $instalacao->man_itens_id)->get();
                $instalacao->descs = $descs;
                return $instalacao;
            });
            //$itens->put('obs', $obs);
            //$item->push($obs);
        }
//        return $instalacoes;        
        //$descs = Man_desc::where('man_itens_id', $id)->get();
        return view('manutencao.relatorios.instalacao', ['instalacoes' => $instalacoes, 'item' => $item]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('manutencao.relatorios.create', ['shoppings' => Shopping::all(), 'itens' => Man_itens::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nome' => 'required',
            'endereco' => 'required',
            'desc_servico' => 'required',
            'mes_vistoria' => 'required',
            'numero' => 'required',
            'contratante' => 'required',
            'contratada' => 'required',
            'item' => 'required'
        ]);
        $array = array();
        foreach($request->itemObs as $k => $v){
            if(!is_null($v)){
                $array[] .= $k.':'.$v;
            }
        }if(count($array) > 0){
            $servicos = implode(';',$array);
        }else{
            $servicos = implode(';',$request->item);
        }
//        print_r($array);
//        exit();
        $relatorio = new Man_relatorios;
        $relatorio->nome = strtoupper($request->nome);
        $relatorio->shoppings_id = $request->shoppings_id;
        $relatorio->endereco = $request->endereco;
        $relatorio->desc_servico = $request->desc_servico;
        $relatorio->mes_vistoria = $request->mes_vistoria;
        $relatorio->numero = $request->numero;
        $relatorio->contratante = $request->contratante;
        $relatorio->contratada = $request->contratada;
        $relatorio->tipo_servicos = $servicos;
        $relatorio->descricao = $request->descricao;
        $relatorio->save();
        
        foreach($request->vistorias as $k => $v){
            $vistoria = new Vistorias;
            $vistoria->man_relatorios_id = $relatorio->id;
            $vistoria->instalacoes_id = $k;
            $vistoria->vistorias = implode(';',$v);
            $vistoria->save();
            
            if(isset($request->imgs[$k])){
                $imagens = array_combine($request->obs[$k], $request->imgs[$k]);
                foreach($imagens as $ki => $vi){
                    $imagem = new Man_imagens;
                    $imagem->vistorias_id = $vistoria->id;
                    $imagem->imagem = $vi;
                    $imagem->obs = $ki;
                    $imagem->save();
                }
            }
        }
        return redirect('manutencao/relatorios')->with('message', 'Relatório de manutenção criado com sucesso');
        return $relatorio->id;
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
//        echo date('h:i:s') . "\n";
//        ini_set('max_execution_time', 3000);
//        return WPDF::loadFile('http://www.github.com')->inline('github.pdf');
        $relatorio = DB::table('man_relatorios as r')
                ->join('shoppings as s', 's.id', '=', 'r.shoppings_id')
                ->join('empresas as e', 'e.id', '=', 's.empresas_id')
                ->select('s.shopping','e.logo', 'r.*')
                ->where('r.id',$id)
                ->first();
//        var_dump($relatorio);
//        echo '<br>';
        $serv_array = explode(';',$relatorio->tipo_servicos);
        $servicos = array();
        foreach($serv_array as $serv){
//            $s = array();
            if(strpos($serv,':')){
                $s = explode(':', $serv);
                $tipo = DB::table('man_itens')
                        ->where('id',$s[0])->value('item');
                $servicos[$tipo] = $s[1];
            }else{
                $tipo = DB::table('man_itens')
                    ->where('id',$serv)->value('item');
                $servicos[$tipo] = ' ';
            }
//            $tipo = DB::table('man_itens')
//                    ->where('id',$s[0])->value('item');
//            $servicos[$tipo] = $s[1];
        }
        
//        SELECT v.*, i.item FROM `vistorias` as v
//        INNER JOIN instalacoes AS ins ON ins.id = v.instalacoes_id
//        INNER JOIN man_itens AS i ON i.id = ins.man_itens_id
//        WHERE v.man_relatorios_id = 31
        
        $instalacoes = DB::table('vistorias as v')
                ->join('instalacoes as ins', 'ins.id', '=', 'v.instalacoes_id')
                ->join('man_itens as i', 'i.id', '=', 'ins.man_itens_id')
                ->join('pavimentos as p', 'p.id', '=', 'ins.pavimentos_id')
                ->leftJoin('setores as s', 's.id', '=', 'ins.setores_id')
                ->select('v.*', 'i.item', 'i.id as item_id', 'p.pavimento', 's.setor', 'ins.numero')
                ->where('v.man_relatorios_id', $id)
                ->orderBy('v.id', 'asc')
                ->get();
        
        foreach ($instalacoes as $instalacao) {

            $instalacoes->map(function ($instalacao) {
            $vistoria = array();
            $imagens = array();
            foreach(explode(';', $instalacao->vistorias) as $v){
                //k:v
                $array = explode(':', $v);
                $descricao = Man_desc::where('id', $array[0])->value('descricao');
                $array[0] = $descricao;
                $vistoria[] = $array;
                
            }
            
            $imgs = Man_imagens::where('vistorias_id', $instalacao->id)->get();
//            echo $instalacao->id;
//            echo '<br>';
            foreach($imgs as $img){
                $array = array($img->imagem, $img->obs);
                $imagens[] = $array;
            }
            
            $instalacao->visto = $vistoria;
            $instalacao->imagens = $imagens;
            return $instalacao;

            });
            
        }
//        echo date('h:i:s') . "\n";
//        exit();
//        $filename = 'RELATORIO DE ANÁLISE DE PROJETOS ';
//        $filename .= implode(' - ', $refs);
//        $filename .= ' REV '.sprintf('%1$02d', $relatorio->revisao);
//        $filename .= ' - '.date('d-m-Y',strtotime($relatorio->created_at));
//        $filename .= ' - '.$relatorio->shopping.' - '.$relatorio->loja.'.pdf';
//        $filename = 'RELATORIO DE ANÁLISE DE PROJETOS '.implode(' - ', $refs).' REV '.sprintf('%1$02d', $relatorio->revisao).' - '.date('d-m-Y',$relatorio->created_at).' - '.$relatorio->shopping.' - '.$relatorio->loja.'.pdf';
        //$pdf = \App::make('dompdf.wrapper');
//        $pdf = \DOMPDF::loadView('manutencao.relatorios.pdf', ['relatorio' => $relatorio, 'servicos' => $servicos, 'instalacoes' => $instalacoes]);
//        $pdf->getDomPDF()->set_option("enable_php", true);
        
//        return $pdf->setPaper('a4', 'portrait')->download($filename);
//        set_time_limit(3000);
        return WPDF::loadView('manutencao.relatorios.pdf', ['relatorio' => $relatorio, 'servicos' => $servicos, 'instalacoes' => $instalacoes])->inline('relatorio_manutencao.pdf');
//        return $pdf->stream();
   
//        print_r($servicos);
//        echo '<br>';
        
        
        
//        return view('manutencao.relatorios.pdf', ['relatorio' => $relatorio, 'servicos' => $servicos, 'instalacoes' => $instalacoes]);
//        var_dump($instalacoes);
    }
    public function pdf($id){
//        ini_set('max_execution_time', 3000);
        $relatorio = DB::table('man_relatorios as r')
                ->join('shoppings as s', 's.id', '=', 'r.shoppings_id')
                ->join('empresas as e', 'e.id', '=', 's.empresas_id')
                ->select('s.shopping','e.logo', 'r.*')
                ->where('r.id',$id)
                ->first();

        $serv_array = explode(';',$relatorio->tipo_servicos);
        $servicos = array();
        foreach($serv_array as $serv){
            if(strpos($serv,':')){
                $s = explode(':', $serv);
                $tipo = DB::table('man_itens')
                        ->where('id',$s[0])->value('item');
                $servicos[$tipo] = $s[1];
            }else{
                $tipo = DB::table('man_itens')
                    ->where('id',$serv)->value('item');
                $servicos[$tipo] = ' ';
            }
        }
        
//        SELECT v.*, i.item FROM `vistorias` as v
//        INNER JOIN instalacoes AS ins ON ins.id = v.instalacoes_id
//        INNER JOIN man_itens AS i ON i.id = ins.man_itens_id
//        WHERE v.man_relatorios_id = 31
        
        $instalacoes = DB::table('vistorias as v')
                ->join('instalacoes as ins', 'ins.id', '=', 'v.instalacoes_id')
                ->join('man_itens as i', 'i.id', '=', 'ins.man_itens_id')
                ->join('pavimentos as p', 'p.id', '=', 'ins.pavimentos_id')
                ->leftJoin('setores as s', 's.id', '=', 'ins.setores_id')
                ->select('v.*', 'i.item', 'i.id as item_id', 'p.pavimento', 's.setor', 'ins.numero')
                ->where('v.man_relatorios_id', $id)
                ->orderBy('v.id', 'asc')
                ->get();
        
        foreach ($instalacoes as $instalacao) {

            $instalacoes->map(function ($instalacao) {
            $vistoria = array();
            $imagens = array();
            foreach(explode(';', $instalacao->vistorias) as $v){
                //k:v
                $array = explode(':', $v);
                $descricao = Man_desc::where('id', $array[0])->value('descricao');
                $array[0] = $descricao;
                $vistoria[] = $array;
                
            }
            
            $imgs = Man_imagens::where('vistorias_id', $instalacao->id)->get();
            
            foreach($imgs as $img){
                $array = array($img->imagem, $img->obs);
                $imagens[] = $array;
            }
            
            $instalacao->visto = $vistoria;
            $instalacao->imagens = $imagens;
            return $instalacao;

            });
            
        }
        
        PDF::AddPage();
        PDF::SetFontSize(10);
        PDF::SetTextColor(255,255,255);
        PDF::SetFont('','B');
        PDF::SetFillColor(31,73,125);
        PDF::Cell( 0, 8, 'Relatorio de Manutenção', 1, 0,'C', 1,'', 0, false,'T', 'M' );
        PDF::ln();
        PDF::SetTextColor(0,0,0);
        PDF::SetFillColor(255,255,255);
        PDF::Cell( 95, 8, 'Nome do Cliente(Empresa)', 1, 0,'L', 1,'', 0, false,'T', 'M' );
        PDF::SetFont('');
        PDF::Cell( 95, 8, $relatorio->shopping, 1, 0,'L', 1,'', 0, false,'T', 'M' );
        PDF::ln();
        PDF::SetFont('','B');
        PDF::Cell( 95, 8, 'Endereço', 1, 0,'L', 1,'', 0, false,'T', 'M' );
        PDF::SetFont('');
        PDF::Cell( 95, 8, $relatorio->endereco, 1, 0,'L', 1,'', 1, false,'T', 'M' );
        PDF::ln();
        PDF::SetFont('','B');
        PDF::Cell( 95, 8, 'Descrição dos Serviços', 1, 0,'L', 1,'', 0, false,'T', 'M' );
        PDF::SetFont('');
        PDF::Cell( 95, 8, strtoupper($relatorio->desc_servico), 1, 0,'L', 1,'', 0, false,'T', 'M' );
        PDF::ln();
        PDF::SetFont('','B');
        PDF::Cell( 95, 8, 'Mês da Vistoria', 1, 0,'L', 1,'', 0, false,'T', 'M' );
        PDF::SetFont('');
        PDF::Cell( 95, 8, date('m/Y', strtotime($relatorio->mes_vistoria)), 1, 0,'L', 1,'', 0, false,'T', 'M' );
        PDF::ln();
        PDF::SetFont('','B');
        PDF::Cell( 95, 8, 'Número do Orçamento', 1, 0,'L', 1,'', 0, false,'T', 'M' );
        PDF::SetFont('');
        PDF::Cell( 95, 8, $relatorio->numero, 1, 0,'L', 1,'', 0, false,'T', 'M' );
        PDF::ln();
        PDF::SetFont('','B');
        PDF::Cell( 95, 8, 'Supervisor Contratante', 1, 0,'L', 1,'', 0, false,'T', 'M' );
        PDF::SetFont('');
        PDF::Cell( 95, 8, strtoupper($relatorio->contratante), 1, 0,'L', 1,'', 0, false,'T', 'M' );
        PDF::ln();
        PDF::SetFont('','B');
        PDF::Cell( 95, 8, 'Supervisor Contratada', 1, 0,'L', 1,'', 0, false,'T', 'M' );
        PDF::SetFont('');
        PDF::Cell( 95, 8, strtoupper($relatorio->contratada), 1, 0,'L', 1,'', 0, false,'T', 'M' );
        PDF::ln();
        PDF::SetTextColor(255,255,255);
        PDF::SetFontSize(10);
        PDF::SetFont('','B');
        PDF::SetFillColor(31,73,125);
        PDF::Cell( 0, 8, 'Tipos de Serviço', 1, 0,'C', 1,'', 0, false,'T', 'M' );
        PDF::ln();
        PDF::SetTextColor(0,0,0);
        PDF::SetFillColor(255,255,255);
        PDF::SetFont('');
        foreach($servicos as $k => $v){
            PDF::Cell( 95, 8, $k, 1, 0,'C', 1,'', 1, false,'T', 'M' );
            PDF::Cell( 95, 8, strtoupper($v), 1, 0,'L', 1,'', 1, false,'T', 'M' );
            PDF::ln();
        }
//        PDF::writeHTML($relatorio->descricao, true, false, true, false, '');
//        PDF::Output('manual_teste.pdf', 'I');
//        exit();
        foreach($instalacoes as $instalacao){
//            PDF::SetAutoPageBreak(true);
//            $instalacao->item }}{{ ($instalacao->numero != null)? '-'.$instalacao->numero : '' }} - {{ $instalacao->pavimento }}/{{ $instalacao->setor }}
            PDF::SetTextColor(255,255,255);
            PDF::SetFont('','B');
            PDF::SetFillColor(31,73,125);
            $numero = ($instalacao->numero != null)? '-'.$instalacao->numero : '';
            PDF::Cell( 0, 8, $instalacao->item.$numero.' - '.$instalacao->pavimento.'/'.$instalacao->setor, 1, 0,'C', 1,'', 0, false,'T', 'M' );
            foreach($instalacao->visto as $vistoria){
                PDF::ln();
                PDF::SetTextColor(0,0,0);
                PDF::SetFillColor(255,255,255);
                PDF::SetFont('');
                PDF::Cell( 160, 8, $vistoria[0], 1, 0,'L', 1,'', 1, false,'T', 'M' );
                switch($vistoria[1]){
                    case 0:
                        $status = 'OK';
                        break;
                    case 1:
                        $status = 'NÃO OK';
                        break;
                    case 2:
                        $status = 'NÃO SE APLICA';
                        break;
                }
                PDF::Cell( 30, 8, $status, 1, 0,'C', 1,'', 1, false,'T', 'M' );
            }
            PDF::ln();
            $i = 0;
            foreach($instalacao->imagens as $imagem){
                $html = '<img src="'.$imagem[0].'" width="150">';
                $html .= '<br>'.$imagem[1];
                PDF::writeHTML($html, false, false, true, false, 'C');
//                PDF::writeHTMLCell(100, 50, '', '', $html); // ( $w, $h, $x, $y, $html = '', $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '', $autopadding = true )
//                PDF::image($imagem[0]);
                $i++;
            }
            $lh = PDF::getLastH();
            PDF::ln($lh+20);
        }
        //Close and output PDF document
        PDF::Output('manual_teste.pdf', 'I');
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
        $relatorio = Man_relatorios::find($id);
        
        $instalacoes = DB::table('vistorias as v')
                ->join('instalacoes as ins', 'ins.id', '=', 'v.instalacoes_id')
                ->join('man_itens as i', 'i.id', '=', 'ins.man_itens_id')
                ->join('pavimentos as p', 'p.id', '=', 'ins.pavimentos_id')
                ->leftJoin('setores as s', 's.id', '=', 'ins.setores_id')
                ->select('v.*', 'i.item', 'i.id as item_id', 'p.pavimento', 's.setor', 'ins.id as instal_id', 'ins.numero')
                ->where('v.man_relatorios_id', $id)
                ->orderBy('v.id', 'asc')
                ->get();
        
        foreach ($instalacoes as $instalacao) {

            $instalacoes->map(function ($instalacao) {
            $vistoria = array();
            $imagens = array();
            foreach(explode(';', $instalacao->vistorias) as $v){
                //k:v
                $array = explode(':', $v);
                $descricao = Man_desc::where('id', $array[0])->first();
                $array[0] = $descricao->descricao;
                $array[] = $descricao->id;
                $vistoria[] = $array;
                
            }
            
            $imgs = Man_imagens::where('vistorias_id', $instalacao->id)->get();
//            echo $instalacao->id;
//            echo '<br>';
            foreach($imgs as $img){
                $array = array($img->imagem, $img->obs);
                $imagens[] = $array;
            }
            
            $instalacao->visto = $vistoria;
            $instalacao->imagens = $imagens;
            return $instalacao;

            });
            
        }
        $array = [
            'relatorio' => $relatorio, 
            'shoppings' => Shopping::all(), 
            'itens' => Man_itens::all(),
            'instalacoes' => $instalacoes
                ];
        return view('manutencao.relatorios.edit', $array);
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
            'nome' => 'required',
            'endereco' => 'required',
            'desc_servico' => 'required',
            'mes_vistoria' => 'required',
            'numero' => 'required',
            'contratante' => 'required',
            'contratada' => 'required',
            'item' => 'required'
        ]);
        $array = array();
        foreach($request->itemObs as $k => $v){
            if(!is_null($v)){
                $array[] .= $k.':'.$v;
            }
        }if(count($array) > 0){
            $servicos = implode(';',$array);
        }else{
            $servicos = implode(';',$request->item);
        }
//        print_r($array);
//        exit();
        $relatorio = Man_relatorios::find($id);
        $relatorio->nome = strtoupper($request->nome);
        $relatorio->shoppings_id = $request->shoppings_id;
        $relatorio->endereco = $request->endereco;
        $relatorio->desc_servico = $request->desc_servico;
        $relatorio->mes_vistoria = $request->mes_vistoria;
        $relatorio->numero = $request->numero;
        $relatorio->contratante = $request->contratante;
        $relatorio->contratada = $request->contratada;
        $relatorio->tipo_servicos = $servicos;
        $relatorio->descricao = $request->descricao;
        $relatorio->save();
        
        DB::table('vistorias')->where('man_relatorios_id', $id)->delete();
//        var_dump($request->vistorias);
//        exit();
        foreach($request->vistorias as $k => $v){
            $vistoria = new Vistorias;
            $vistoria->man_relatorios_id = $relatorio->id;
            $vistoria->instalacoes_id = $k;
            $vistoria->vistorias = implode(';',$v);
            $vistoria->save();
//            $log = '';
            if(isset($request->imgs[$k])){
//                $log .= $k.'<br>';
                $imagens = array_combine($request->obs[$k], $request->imgs[$k]);
                foreach($imagens as $ki => $vi){
                    $imagem = new Man_imagens;
                    $imagem->vistorias_id = $vistoria->id;
                    $imagem->imagem = $vi;
                    $imagem->obs = $ki;
                    $imagem->save();
                }
            }
        }
//        return $request->imgs;
        return redirect('manutencao/relatorios')->with('message', 'Relatório de manutenção atualizado com sucesso');
    }
    
    public function duplicar($id)
    {
        //
        $relatorio = Man_relatorios::find($id);
        
        $instalacoes = DB::table('vistorias as v')
                ->join('instalacoes as ins', 'ins.id', '=', 'v.instalacoes_id')
                ->join('man_itens as i', 'i.id', '=', 'ins.man_itens_id')
                ->join('pavimentos as p', 'p.id', '=', 'ins.pavimentos_id')
                ->leftJoin('setores as s', 's.id', '=', 'ins.setores_id')
                ->select('v.*', 'i.item', 'i.id as item_id', 'p.pavimento', 's.setor', 'ins.id as instal_id', 'ins.numero')
                ->where('v.man_relatorios_id', $id)
                ->orderBy('v.id', 'asc')
                ->get();
        
        foreach ($instalacoes as $instalacao) {

            $instalacoes->map(function ($instalacao) {
            $vistoria = array();
            $imagens = array();
            foreach(explode(';', $instalacao->vistorias) as $v){
                //k:v
                $array = explode(':', $v);
                $descricao = Man_desc::where('id', $array[0])->first();
                $array[0] = $descricao->descricao;
                $array[] = $descricao->id;
                $vistoria[] = $array;
                
            }
            
            $imgs = Man_imagens::where('vistorias_id', $instalacao->id)->get();
//            echo $instalacao->id;
//            echo '<br>';
            foreach($imgs as $img){
                $array = array($img->imagem, $img->obs);
                $imagens[] = $array;
            }
            
            $instalacao->visto = $vistoria;
            $instalacao->imagens = $imagens;
            return $instalacao;

            });
            
        }
        $array = [
            'relatorio' => $relatorio, 
            'shoppings' => Shopping::all(), 
            'itens' => Man_itens::all(),
            'instalacoes' => $instalacoes
                ];
        return view('manutencao.relatorios.duplicar', $array);
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
        $relatorio = Man_relatorios::find($id);
        $relatorio->delete();
        return redirect('manutencao/relatorios')->with('message', 'Relatório excluído com sucesso');
    }
}
