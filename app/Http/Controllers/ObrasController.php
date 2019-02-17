<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\User_dados;
use App\Obras;
use App\Man_imagens;
use App\Man_itens;
use App\Man_desc;
use App\Shopping;
use App\Instalacoes;
use App\Vistorias;
use App\Obras_certificados;
use App\Obras_certificados_padrao;
use App\Obras_textos_padrao;
use App\Obras_arquivos;
use App\Empresa;

class ObrasController extends Controller
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
    
    private function getShoppings_id() {
        $shoppings = Shopping::whereIn('id', DB::table('users_shoppings')->where('users_id', Auth::id())->pluck('shoppings_id'))->get();

        return $shoppings;
    }

    private function permission(){
        $nivel = DB::table('user_dados')->where('users_id', Auth::id())->value('user_levels_id');
        if(is_null($nivel)){
            abort(403, 'Acesso Negado');
        }
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
    
    private function dataExtensa($date) {
        $dia = date('d', strtotime($date));
        $mes_n = date('n', strtotime($date));
        $ano = date('Y', strtotime($date));

        $mes = [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro'
        ];

        return 'Rio de Janeiro, ' . $dia . ' de ' . $mes[$mes_n] . ' de ' . $ano;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $shoppings = $this->getShoppings();
        
        $sh_array = array();
        foreach($this->getShoppings_id() as $s){
            $sh_array[] = $s->id;
        }
//        return var_dump($sh_array);
//        SELECT s.*,
//        (SELECT COUNT(*) FROM obras_arquivos as oa WHERE oa.shoppings_id = s.id) + (SELECT COUNT(*) FROM obras as o WHERE o.shoppings_id = s.id) as arquivos 
//        FROM shoppings as s
//        WHERE (SELECT COUNT(*) FROM obras_arquivos as oa WHERE oa.shoppings_id = s.id) + (SELECT COUNT(*) FROM obras as o WHERE o.shoppings_id = s.id) > 0
//        AND s.id IN (6)
        $shops = implode(',', $sh_array);
        
//        SELECT cliente FROM obras
//        GROUP BY cliente
//        ORDER BY created_at DESC;
//        if (is_null($this->nivel())) {
//            $pastas = DB::table('shoppings as s')
//                    ->select(DB::raw('s.*,(SELECT COUNT(*) FROM obras_arquivos as oa WHERE oa.shoppings_id = s.id) + (SELECT COUNT(*) FROM obras as o WHERE o.shoppings_id = s.id) as arquivos'))
//                    ->whereRaw('(SELECT COUNT(*) FROM obras_arquivos as oa WHERE oa.shoppings_id = s.id) + (SELECT COUNT(*) FROM obras as o WHERE o.shoppings_id = s.id) > 0 AND s.id IN (' . $shops . ')')
//                    ->get();
//        } else {
//            $pastas = DB::table('shoppings as s')
//                    ->select(DB::raw('s.*,(SELECT COUNT(*) FROM obras_arquivos as oa WHERE oa.shoppings_id = s.id) + (SELECT COUNT(*) FROM obras as o WHERE o.shoppings_id = s.id) as arquivos'))
//                    ->whereRaw('(SELECT COUNT(*) FROM obras_arquivos as oa WHERE oa.shoppings_id = s.id) + (SELECT COUNT(*) FROM obras as o WHERE o.shoppings_id = s.id) > 0')
//                    ->get();
//        }
        if(!is_null($this->nivel())){
            $pastas = DB::table('obras')
                    ->groupBy('cliente')
                    ->orderBy('created_at', 'desc')
                    ->select('cliente')
                    ->get();
        }
        return view('manutencao.obras.pastas', ['pastas' => $pastas, 'nivel' => $this->nivel()]);
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
        return view('manutencao.obras.instalacao', ['instalacoes' => $instalacoes, 'item' => $item]);
    }
    
    public function disciplina($id, $qnt = 1){
        $itens = Man_itens::find($id);
        
        $checklists = DB::table('man_descs')->where('man_itens_id', $id)->get();
        
        return view('manutencao.obras.disciplina', ['item' => $itens, 'checklists' => $checklists, 'qnt' => $qnt]);
        
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
//        SELECT m.* FROM man_itens as m
//        INNER JOIN instalacoes as i on m.id = i.man_itens_id
//        WHERE i.shoppings_id = 6
//        GROUP BY m.id
//        $itens = DB::table('man_itens as m')
//                ->join('instalacoes as i', 'm.id', '=', 'i.man_itens_id')
//                ->select('m.*')
//                ->where('i.shoppings_id', $id)
//                ->groupBy('m.id')
//                ->get();
        $itens = Man_itens::all();
        $certificados = Obras_certificados_padrao::find(1);
        $textos_padrao = Obras_textos_padrao::find(1);
//        SELECT u.id, u.name
//        FROM users as u
//        INNER JOIN users_responsaveis as r
//        ON r.users_id = u.id
        $responsaveis = DB::table('users as u')
                ->join('users_responsaveis as r', 'r.users_id', '=', 'u.id')
                ->select('u.id', 'u.name', 'u.email', 'r.telefone', 'r.assinatura')
                ->get();
        foreach($responsaveis as $r){
            $contratantes[$r->id] = $r->name;
        }
        foreach($responsaveis as $c){
            $contatos[$c->id] = ['email' => $c->email, 'telefone' => $c->telefone, 'assinatura' => $c->assinatura];
        }
//        return var_dump($contratantes);
        return view('manutencao.obras.create', ['contatos' => $contatos, 'itens' => $itens, 'contratantes' => $contratantes, 'certificados' => $certificados, 'padrao' => $textos_padrao ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        
        $this->validate($request, [
            'nome' => 'required',
            'cliente' => 'required',
            'email' => 'required',
            'telefone' => 'required',
            'mes_vistoria' => 'required',
            'numero' => 'required',
            'contratante' => 'required',
            
        ]);
        
        $obra = new Obras;
        $obra->nome = strtoupper($request->nome);
        $obra->cliente = $request->cliente;
        $obra->contratante = $request->contato;
        $obra->email = $request->email;
        $obra->telefone = $request->telefone;
        $obra->assinatura = $request->assinatura;
        $obra->users_id = Auth::id();
        $obra->numero = $request->orcamento;
        $obra->introducao = $request->introducao;
        $obra->conclusao = $request->conclusao;
//        return var_dump($obra);
        $obra->save();
        
        foreach ($request->itens as $item){
            
            $texto_referencia = $request->texto_referencia[$item];
            $observacoes = $request->observacoes[$item];
            
            DB::table('obras_textos')->insert(
                ['obras_id' => $obra->id, 'man_itens_id' => $item, 'texto_referencia' => $texto_referencia, 'observacoes' => $observacoes]
            );
           $i = 0;
           while($request->numero){
               if(array_key_exists($item.'-'.$i, $request->numero)){
                    $visto = new Vistorias;
                    $visto->obras_id = $obra->id;
                    $visto->man_itens_id = $item;
                    $visto->numero = $request->numero[$item.'-'.$i];
                                                                                                            $visto->pavimento = $request->pavimento[$item.'-'.$i];
                    $visto->setor = $request->setor[$item.'-'.$i];
//                    return var_dump($request->ocultar);
                    $request->ocultar = (isset($request->ocultar)) ? $request->ocultar : array();
                    if(!in_array($item, $request->ocultar)){
                        $visto->vistorias = implode(';', $request->vistorias[$item.'-'.$i]);
                    }
                    $visto->save();
                    if(isset($request->imgs[$item.'-'.$i])){
                        $imagens = array_combine($request->obs[$item.'-'.$i], $request->imgs[$item.'-'.$i]);
                        foreach($imagens as $k => $v){
                            $imagem = new Man_imagens;
                            $imagem->vistorias_id = $visto->id;
                            $imagem->imagem = $v;
                            $imagem->obs = $k;
                            $imagem->save();
                        }
                    }
                    $i++;
               }else{
                   break;
               }
           }
        }
        if($request->gar)
        {
            $certificado = new Obras_certificados;
            
            $certificado->obras_id = $obra->id;
            $certificado->man_itens_id = implode(';', $request->itens);
            $certificado->tipo = 1;
            $certificado->certificado = $request->garantia;            
            
            $certificado->save();
        }
        if($request->res)
        {
            $certificado = new Obras_certificados;
            
            $certificado->obras_id = $obra->id;
            $certificado->man_itens_id = implode(';', $request->itens);
            $certificado->tipo = 2;
            $certificado->certificado = $request->responsabilidade;            
            
            $certificado->save();
        }
        return redirect('manutencao/obras')->with('message', 'Relatório criado com sucesso');
    }
//    public function store(Request $request)
//    {
//        $this->validate($request, [
//            'nome' => 'required',
//            'cliente' => 'required',
//            'email' => 'required',
//            'telefone' => 'required',
//            'mes_vistoria' => 'required',
//            'numero' => 'required',
//            'contratante' => 'required',
//            
//        ]);
//        $array = array();
////        foreach($request->itemObs as $k => $v){
////            if(!is_null($v)){
////                $array[] .= $k.':'.$v;
////            }
////        }if(count($array) > 0){
////            $servicos = implode(';',$array);
////        }else{
////            $servicos = implode(';',$request->item);
////        }
////        print_r($array);
////        exit();
//        $obra = new Obras;
//        $obra->nome = strtoupper($request->nome);
//        $obra->shoppings_id = $request->shoppings_id;
//        $obra->users_id = Auth::id();
//        $obra->numero = $request->numero;
//        $obra->introducao = $request->introducao;
//        $obra->conclusao = $request->conclusao;
//        $obra->save();
//        
//        foreach($request->item as $item){
//            DB::table('obras_textos')
//                ->insert(
//                    [
//                        'obras_id' => $obra->id,
//                        'man_itens_id' => $item,
//                        'texto_referencia' => $request->texto_referencia[$item],
//                        'observacoes' => $request->observacoes[$item]
//                    ]
//                );
//        }
//        
//        foreach($request->vistorias as $k => $v){
//            $item = Instalacoes::where('id', $k)->value('man_itens_id');
//            $vistoria = new Vistorias;
//            $vistoria->obras_id = $obra->id;
//            $vistoria->instalacoes_id = $k;
////            exit(var_dump($request->ocultar));
//            $ocultos = (is_null($request->ocultar)) ? array() : $request->ocultar;
//            if(!in_array($item, $ocultos)){
//                $vistoria->vistorias = implode(';',$v);
//            }
//            $vistoria->save();
//            
//            if(isset($request->imgs[$k])){
//                $imagens = array_combine($request->obs[$k], $request->imgs[$k]);
//                foreach($imagens as $ki => $vi){
//                    $imagem = new Man_imagens;
//                    $imagem->vistorias_id = $vistoria->id;
//                    $imagem->imagem = $vi;
//                    $imagem->obs = $ki;
//                    $imagem->save();
//                }
//            }
//        }
//        if($request->gar || $request->res){
//            $certificado = new Obras_certificados;
//            
//            $certificado->obras_id = $obra->id;
//            $certificado->man_itens_id = implode(';', $request->item);
//            $certificado->garantia = $request->garantia;
//            $certificado->responsabilidade = $request->responsabilidade;
//            
//            $certificado->save();
//        }
//        if($request->gar)
//        {
//            $certificado = new Obras_certificados;
//            
//            $certificado->obras_id = $obra->id;
//            $certificado->man_itens_id = implode(';', $request->item);
//            $certificado->tipo = 1;
//            $certificado->certificado = $request->garantia;            
//            
//            $certificado->save();
//        }
//        if($request->res)
//        {
//            $certificado = new Obras_certificados;
//            
//            $certificado->obras_id = $obra->id;
//            $certificado->man_itens_id = implode(';', $request->item);
//            $certificado->tipo = 2;
//            $certificado->certificado = $request->responsabilidade;            
//            
//            $certificado->save();
//        }
//        return redirect('manutencao/obras')->with('message', 'Relatório de manutenção criado com sucesso');
//        return $relatorio->id;
//    }
    
    public function certificado($id){
        $certificado = Obras_certificados::find($id);
        $man_itens_id = explode(';',$certificado->man_itens_id);
        $disciplinas = Man_itens::whereIn('id',$man_itens_id)->get();
        $disciplina_arr = array();
        
        $diretor = DB::table('users as u')
                ->join('user_dados as d', 'd.users_id', '=', 'u.id')
                ->join('user_levels as l', 'l.id', '=', 'd.user_levels_id')
                ->where('d.user_levels_id', 2)
                ->select('u.name', 'd.titulo', 'd.assinatura', 'l.nivel')
                ->first();
        
        foreach($disciplinas as $disciplina){
            $disciplina_arr[] = $disciplina->item;
        };
        $obra = Obras::where('id', $certificado->obras_id)->first();
//        $cliente = Shopping::where('id', $obra->shoppings_id)->first();
        $numero = $obra->numero;
        
//        $logo = Empresa::where('id', $cliente->empresas_id)->value('logo');
        
        $variaveis = [
            '{DISCIPLINA}',
            '{CLIENTE}', 
            '{NUMERO}'
        ];
        $replace = [
            implode(',', $disciplina_arr),
            $obra->cliente,
            $numero,
        ];
        $texto = str_replace($variaveis, $replace, $certificado->certificado);
        
        $data = [
            'certificado' => $certificado,
            'texto' => $texto,
            'diretor' => $diretor,
            'data' => $this->dataExtensa($obra->created_at)
        ];
        
        $pdf = \DOMPDF::loadView('manutencao.obras.certificado', $data);
        $pdf->getDomPDF()->set_option("enable_php", true);
        //$pdf->loadView('analise.pdf.show', $data);
        //$pdf->loadHTML('your view here ');
        return $pdf->setPaper('a4', 'landscape')->stream();
    }

    public function arquivos($id){
//        $shopping_select = DB::table('shoppings')->where('id', $id)->first();
//        SELECT id, NULL AS nome, NULL AS numero,tipo,hash,created_at,'arquivo' as classe
//        FROM obras_arquivos
//        WHERE shoppings_id = 6 
//        UNION
//        SELECT id,nome,numero, NULL AS tipo, NULL AS hash,created_at, 'relatorio' as classe
//        FROM obras
//        WHERE shoppings_id = 6
//        UNION
//        SELECT id,null as nome,null as numero, tipo, null as hash, created_at, 'certificado' as classe 
//        FROM obras_certificados
//        WHERE obras_id IN (SELECT id FROM obras WHERE shoppings_id = 6)
//        UNION
//        SELECT id,titulo as nome,orcamento as numero,null as tipo,null as hash, created_at, 'manual' as classe
//        FROM entregas WHERE shoppings_id = 6
//        ORDER BY created_at DESC
        
//SELECT id, null AS nome, NULL AS numero,tipo,hash,created_at,'arquivo' as classe
//FROM obras_arquivos
//WHERE obras_id IN (SELECT id FROM obras WHERE cliente = 'bom lugar')
//UNION
//SELECT id,nome,numero, NULL AS tipo, NULL AS hash,created_at, 'relatorio' as classe
//FROM obras
//WHERE cliente = 'bom lugar'
//UNION
//SELECT id,null as nome,null as numero, tipo, null as hash, created_at, 'certificado' as classe 
//FROM obras_certificados
//WHERE obras_id IN (SELECT id FROM obras WHERE cliente = 'bom lugar')
//ORDER BY created_at DESC

        $first = DB::table('obras')
                ->select(DB::raw('id,nome,numero, NULL AS tipo, NULL AS hash,created_at, \'relatorio\' as classe'))
                ->where('cliente', urldecode($id));
        $second = DB::table('obras_certificados')
                ->select(DB::raw('id,null as nome,null as numero, tipo, null as hash, created_at, \'certificado\' as classe'))
                ->whereRaw('obras_id IN (SELECT id FROM obras WHERE cliente = \''.urldecode($id).'\')');
//        $third  = DB::table('entregas')
//                ->select(DB::raw('id,titulo as nome,orcamento as numero,null as tipo,null as hash, created_at, \'manual\' as classe'))
//                ->where('shoppings_id', $id);
        $arquivos = DB::table('obras_arquivos')
                ->select(DB::raw('id, NULL AS nome, NULL AS numero,tipo,hash,created_at,\'arquivo\' as classe'))
                ->whereRaw('obras_id IN (SELECT id FROM obras WHERE cliente = \''.urldecode($id).'\')')
                ->union($first)
                ->union($second)
//                ->union($third)
                ->orderBy('created_at', 'desc')
                ->get();
        $obra_id = DB::table('obras')
                ->select('*')
                ->where('cliente', urldecode($id))
                ->orderBy('id', 'DESC')
                ->value('id');
//        return var_dump($obra_id);
        $array = array(
            'cliente' => $id,
            'obra_id' => $obra_id,
            'arquivos' => $arquivos,
//            'shoppings' => $this->getShoppings(),
            'nivel' => $this->nivel(),
//            'shopping_select'  => $shopping_select
        );
        
        return view('manutencao.obras.index', $array);
    }
    
    public function download($id) {
        //return('check');
        $arquivo = Obras_arquivos::find($id);
        
        return Storage::download($arquivo->hash, $arquivo->arquivo);
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
//        SELECT u.name, s.shopping, e.empresa, o.*
//        FROM obras as o
//        INNER JOIN users as u ON o.users_id = u.id
//        INNER JOIN shoppings as s ON s.id = o.shoppings_id
//        INNER JOIN empresas as e ON e.id = s.empresas_id
//        WHERE o.id = 1
        $obra = DB::table('obras as o')
                ->join('users as u', 'o.users_id', '=', 'u.id')
                ->join('user_dados as d', 'd.users_id', '=', 'o.users_id')
                ->join('user_levels as l', 'l.id', '=', 'd.user_levels_id')
//                ->join('shoppings as s', 's.id', '=', 'o.shoppings_id')
//                ->join('empresas as e', 'e.id', '=', 's.empresas_id')
                ->select('u.name', 'd.titulo', 'd.assinatura as user_assinatura', 'l.nivel', 'o.*')
                ->where('o.id', $id)
                ->first();
        $trabalhos = DB::table('obras_textos as t')
                ->join('man_itens as i','t.man_itens_id', '=', 'i.id')
                ->select('i.item', 't.*')
                ->where('t.obras_id', $id)
                ->get();
//        SELECT u.name,d.titulo,d.assinatura,l.nivel FROM users as u
//        INNER JOIN user_dados as d ON d.users_id = u.id
//        INNER JOIN user_levels as l ON l.id = d.user_levels_id
//        WHERE d.user_levels_id = 2
        $diretor = DB::table('users as u')
                ->join('user_dados as d', 'd.users_id', '=', 'u.id')
                ->join('user_levels as l', 'l.id', '=', 'd.user_levels_id')
                ->where('d.user_levels_id', 2)
                ->select('u.name', 'd.titulo', 'd.assinatura', 'l.nivel')
                ->first();
//        SELECT u.name,u.email,r.telefone,r.assinatura FROM users as u
//        INNER JOIN users_responsaveis as r on r.users_id = u.id
//        INNER JOIN users_shoppings as s on s.users_id = u.id
//        WHERE s.shoppings_id = 6
//        $contato = DB::table('users as u')
//                ->join('users_responsaveis as r', 'r.users_id', '=', 'u.id')
//                ->join('users_shoppings as s', 's.users_id', '=', 'u.id')
//                ->where('s.shoppings_id', $obra->shoppings_id)
//                ->select('u.name', 'u.email', 'r.telefone', 'r.assinatura')
//                ->first();
        
        foreach($trabalhos as $trabalho){
            $trabalhos->map(function ($trabalho) use ($id){
                $instalacoes = DB::table('vistorias as v')
//                    ->join('instalacoes as ins', 'ins.id', '=', 'v.instalacoes_id')
                    ->join('man_itens as i', 'i.id', '=', 'v.man_itens_id')
//                    ->join('pavimentos as p', 'p.id', '=', 'ins.pavimentos_id')
//                    ->leftJoin('setores as s', 's.id', '=', 'ins.setores_id')
                    ->select('v.*', 'i.item', 'i.id as item_id')
                    ->where([
                        ['v.obras_id', $id],
                        ['i.id', $trabalho->man_itens_id]
                    ])
                    ->orderBy('v.id', 'asc')
                    ->get();
                
                $trabalho->vistorias = $instalacoes;
//                SELECT i.imagem, i.obs, s.man_itens_id
//                FROM Man_imagens as i
//                INNER JOIN vistorias as v on i.vistorias_id = v.id
//                INNER JOIN instalacoes as s on v.instalacoes_id = s.id
//                WHERE s.man_itens_id = 1
                $imagens = DB::table('Man_imagens as i')
                        ->join('vistorias as v', 'i.vistorias_id', '=', 'v.id')
//                        ->join('instalacoes as s', 'v.instalacoes_id', '=', 's.id')
                        ->select('i.imagem', 'i.obs')
                        ->where([
//                            ['s.man_itens_id', $trabalho->man_itens_id],
                            ['i.vistorias_id', 'v.id'],
                            ['v.obras_id', $id]
                                ])
                        ->get();
                $trabalho->imagens = $imagens;
                return $trabalho;
            });
            
           // 
        }
        $data = [
            'obra' => $obra,
            'trabalhos' => $trabalhos,
//            'contato' => $contato,
            'diretor' => $diretor
        ];
//        return var_dump($trabalhos);
//        return view('manutencao.obras.show', $data);
        $pdf = \DOMPDF::loadView('manutencao.obras.show', $data);
        $pdf->getDomPDF()->set_option("enable_php", true);
        //$pdf->loadView('analise.pdf.show', $data);
        //$pdf->loadHTML('your view here ');
        return $pdf->setPaper('a4', 'portrait')->stream();
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
