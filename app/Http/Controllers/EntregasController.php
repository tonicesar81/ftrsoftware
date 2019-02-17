<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Entregas;
use App\Man_itens;
use App\E_servicos;
use App\User_dados;
use PDF;

class EntregasController extends Controller {

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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
        $this->permission();
        return view('manutencao.entregas.index', ['entregas' => Entregas::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
        $this->permission();
        return view('manutencao.entregas.create', ['itens' => Man_itens::all()]);
    }

    public function servico($id) { //id = entregas_id
        $this->permission();
//        return $id;
        $servico = Entregas::find($id);
        $itens = DB::table('man_itens')
                ->whereIn('id', explode(',', $servico->itens))
                ->get();
        return view('manutencao.entregas.servico', ['itens' => $itens, 'entrega' => $id]);
    }

    public function storeServ(Request $request) {
        $this->permission();
//        return 'teste';
        $this->validate($request, [
            'depois' => 'required'
        ]);
        $servico = new E_servicos;
        $servico->entregas_id = $request->entregas_id;
        $servico->man_itens_id = $request->man_itens_id;
        $servico->antes = $request->antes;
        $servico->depois = $request->depois;
        $servico->nome = implode('|',[($request->has('nome_antes'))?$request->nome_antes:' ',$request->nome_depois]);
        $servico->save();

        

        switch ($request->action) {
            case 'salva':
                return redirect('manutencao/entregas')->with('message', 'Relatório de entrega criado com sucesso.');
                break;
            case 'continua':
//                return var_dump($request->servico);
                return redirect('manutencao/entregas/servico/' . $request->entregas_id);
                break;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
        $this->permission();
        $entrega = new Entregas;
        
        $this->validate($request, [
            
            'dt_entrega' => 'required',
            'endereco' => 'required',
            'contratante' => 'required',
            'orcamento' => 'required'
        ]);

        $entrega->titulo = $request->titulo;
        $entrega->dt_entrega = $request->dt_entrega;
        $entrega->contratante = $request->contratante;
        $entrega->endereco = $request->endereco;
        $entrega->orcamento = $request->orcamento;
        if ($request->has('nome')) {
            $entrega->item_name = implode(';', (array) $request->nome);
        }
        if (!$request->has('servico')) {
            return redirect('manutencao/entregas/create')->with('message', 'Adicione pelo menos um serviço a ser entregue.');
        }
        $entrega->itens = implode(',', (array) $request->servico);
        $entrega->save();

        switch ($request->action) {
            case 'salva':
                return redirect('manutencao/entregas')->with('message', 'Relatório de entrega criado com sucesso.');
                break;
            case 'foto':
//                return var_dump($request->servico);
                return redirect('manutencao/entregas/servico/' . $entrega->id);
                break;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
        abort(404);
    }

    public function pdf($id) {

        $this->permission();
        $entrega = Entregas::find($id);
        $servicos = DB::table('e_servicos as s')
                ->join('man_itens as i', 'i.id', '=', 's.man_itens_id')
                ->select('s.*', 'i.item', 'i.norma')
                ->where('s.entregas_id', $id)
                ->groupBy('s.man_itens_id')
                ->get();
        $imagens = DB::table('e_servicos as s')
                ->join('man_itens as i', 'i.id', '=', 's.man_itens_id')
                ->select('s.*', 'i.item')
                ->where('s.entregas_id', $id)
                ->get();
        $array = [
            'entrega' => $entrega,
            'servicos' => $servicos,
            'imagens' => $imagens
        ];
        $capitulos = DB::table('manual_capitulos as c')
                ->join('man_itens as i', 'i.id', '=', 'c.man_itens_id')
                ->whereIn('c.man_itens_id',explode(',',$entrega->itens))
                ->select('c.*','i.item')
                ->get();
        // add a page
//        return var_dump($capitulos);
        PDF::SetMargins(-20, -20, -20, 0);
        PDF::AddPage();
        
        $html = '';
//        $html = '<style>'
//                . '.pad { padding-top:100px;}'
//                . '</style>';
//        $html .= '<table width="100%" height="100%">'
//                . '<tr>'
//                . '<td height="100" width="85%"></td>'
//                . '<td height="100" width="15%" bgcolor="#1f497d"></td>'
//                . '</tr>'
//                . '<tr >'
//                . '<td height="100" width="85%" align="center"><img width="300" src="'.public_path('storage/logos/l8CgElKDIrGdgkHLweTnbgr3FNpyCH6ewkKHUuiT.jpeg').'"></td>'
//                . '<td height="100" width="15%" bgcolor="#1f497d"></td>'
//                . '</tr>'
//                . '<tr>'
//                . '<td height="100" width="85%"></td>'
//                . '<td height="100" width="15%" bgcolor="#1f497d"></td>'
//                . '</tr>'
//                . '<tr>'
//                . '<td  width="85%" align="center" valign="bottom" height="50" border="1" ><h1>'.strtoupper(($entrega->titulo == null)? 'RELATÓRIO DE ENTREGA DE OBRA' : $entrega->titulo).'</h1></td>'
//                . '<td  width="15%" bgcolor="#1f497d"></td>'
//                . '</tr>'
//                . '</table>';
        $html = '<div class="container-fluid">
            <table width="100%" style="page-break-after:always;border: 0px !important; margin-top:-50px !important;" class="tb_borderless">
                <tr>
                    <td class="tb_borderless" height="50"></td>
                    <td  rowspan="4" class="bg-primary tb_borderless" width="20%"></td>
                </tr>
                <tr>
                    <td width="80%" align="center" style="padding:100px;" class="tb_borderless"><img src="C:\xampp\htdocs\ftrsoftware\public\storage/logos/l8CgElKDIrGdgkHLweTnbgr3FNpyCH6ewkKHUuiT.jpeg" class="logo_capa" /></td>
                    
                </tr>
                <tr>
                    <td class="tb_borderless" >
                        <div style="width:92%;text-align:center;padding-top:30px;padding-bottom:30px;" class="px-2 bordered border-dark" >
                        <h2>TESTE INCLUSAO</h2>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="/*padding-top:210px;padding-bottom:100px;*/" class="tb_borderless">
                        <div style="width:85%;margin-top:50px;margin-bottom:50;padding:20px;background-color:#d9d9d9;" class="px-2 bordered border-dark bg-cliente">
                            <h4>CLIENTE: BR MALLS</h4>
                            <h4>SHOPPING: SHOPPING TIJUCA</h4>
                            <h4>CONTATO: Tijucano (tijucando@outlettijuca.com.br - (21) 2222-2221)</h4>
                            <h4>DATA / NÚMERO: 03/01/2019 - 2</h4>
                            <h4>TÉCNICO RESPONSÁVEL: Toni Cesar</h4>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
';
//        $html = '<table width="90%>'
//                . '<tr>'
//                    . '<td width="80%>'
//                            . '<table width="100%">'
//                                . '<tr>'
//                                    . '<td height="100" ></td>'
//                                . '</tr>'
//                                . '<tr >'
//                                    . '<td height="100" ><img src="'.public_path('storage/logos/l8CgElKDIrGdgkHLweTnbgr3FNpyCH6ewkKHUuiT.jpeg').'"></td>'
//                                . '</tr>'
//                            . '</table>'
//                    . '</td>'
//                    . '<td width="20%" bgcolor="#1f497d"></td>'
//                . '</tr>'
//                . '</table>';
        $logo = public_path('img/ftr_logo.jpeg');
        $cor_capa = public_path('img/capa_manual.jpg');
        PDF::SetAutoPageBreak(false);
//        PDF::SetMargins(30, 40, 30, 30);
        PDF::Image($logo, 40, 60, 105, 65, '', '', '', false, 72, '', false, false, 0);
        PDF::Image($cor_capa, 170, 10, 25, 270, '', '', '', false, 72, '', false, false, 0);
        PDF::setPageMark();
        PDF::Cell( 110, 0, '', 0, 0,'C', 0,'', 0, false,'T', 'T' );
        PDF::SetTextColor(255,255,255);
        PDF::SetFontSize(30);
//        PDF::Cell( 100, 130, date('Y',strtotime($entrega->dt_entrega)), 0, 0,'C', 0,'', 0, false,'T', 'T' );
        PDF::ln();
        PDF::Cell( 20, 0, '', 0, 0,'L', 0,'', 0, false,'T', 'T' );
        PDF::SetFillColor(255,255,255);
        PDF::SetTextColor(0,0,0);
        PDF::SetFontSize(14);
        PDF::SetCellPadding( 10 );
//        PDF::Cell( 150, 30, strtoupper(($entrega->titulo == null)? 'RELATÓRIO DE ENTREGA DE OBRA' : $entrega->titulo), 1, 0,'C', 1,'', 0, false,'T', 'M' );
        PDF::MultiCell(145, 30, strtoupper(($entrega->titulo == null)? 'MANUAL DE ENTREGA DE OBRA' : $entrega->titulo), 1, 'C', 1, 1, '12' ,'155', true);
        PDF::ln();
        PDF::SetFontSize(10);
        PDF::SetFillColor(217,217,217);
//        / MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
        PDF::SetMargins(-20, -20, -20, 0);
        PDF::MultiCell(145, 5, 'CLIENTE: '.$entrega->contratante."\n\nMÊS: ".date('m/Y',strtotime($entrega->dt_entrega)), 1, 'L', 1, 1, '12' ,'195', true);
        PDF::SetCellPadding( 0 );
        PDF::SetFontSize(12);

//        PDF::writeHTML($html, true, false, true, false, '');

        PDF::SetMargins(30, 40, 30, 30);

        PDF::setFooterCallback(function($pdf) {            
            if($pdf->PageNo() > 1){
                // Get the current page break margin
//                $bMargin = $pdf->getBreakMargin();

                // Get current auto-page-break mode
//                $auto_page_break = $pdf->AutoPageBreak;

                // Disable auto-page-break
                $pdf->SetAutoPageBreak(true, 50);
//                $top_img = public_path('/img/pg_header.jpg');
//                $pdf->Image($top_img, 5, 0, 210, 40, '', '', '', false, 300, '', false, false, 0);
//                $bottom_img = public_path('/img/pg_bottom.jpg');
//                $pdf->Image($bottom_img, 1, 247, 210, 50, '', '', '', false, 300, '', false, false, 0);
//                $pdf->SetFooterMargin(60);
                // Restore the auto-page-break status
//                $pdf->SetAutoPageBreak($auto_page_break, $bMargin);

                // Set the starting point for the page content
//                $pdf->setPageMark();
                // Position at 15 mm from bottom    
                $pdf->SetY(-15);
                // Set font
                $pdf->SetFont('helvetica', 'L', 8);
                // Page number
                $pdf->Cell(0, 10, $pdf->getAliasNumPage(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
            }
        });
//        PDF::SetFont('times', '', 12);
        $bottom_img = public_path('/img/pg_bottom.jpg');
//        PDF::AddPage();
//        PDF::Image($bottom_img, 0, 0, 210, 50, '', '', '', false, 300, '', false, false, 0);
        foreach ($capitulos as $capitulo) {
//              PDF::SetMargins(40, 30, 40, 30);
              PDF::SetAutoPageBreak(true, 50);
//            PDF::AddPage();
//            PDF::Image($bottom_img, 0, 0, 210, 50, '', '', '', false, 300, '', false, false, 0);
            
            if ($capitulo->manual_capitulos_id == 0) {
                PDF::AddPage();
//                PDF::Image($bottom_img, 0, 0, 210, 50, '', '', '', false, 300, '', false, false, 0);
                
                $titulo = $capitulo->capitulo.'. '.$capitulo->item.' - '.$capitulo->titulo;
                PDF::Bookmark($titulo, 0, 0, '', '');
                $html = '<h4>' . $titulo . '</h4>';
                $html .= $capitulo->conteudo;
//                $html = view('manutencao.entregas.pagina',['titulo' => $titulo, 'texto' => $capitulo->conteudo]);
            } else {
//                PDF::Image($bottom_img, 0, 0, 210, 50, '', '', '', false, 300, '', false, false, 0);
                PDF::Bookmark($capitulo->manual_capitulos_id . '.' . $capitulo->capitulo . ' ' . $capitulo->titulo, 1, 0, '', '');
                $html = '<h5>' . $capitulo->manual_capitulos_id . '.' . $capitulo->capitulo . '. ' . $capitulo->titulo . '</h5>';
                $html .= $capitulo->conteudo;
            }
//            PDF::writeHTMLCell( 0, 0, $html);
//            PDF::setCellPaddings(30,30,30,30);
//            PDF::Image($bottom_img, 0, 0, 210, 50, '', '', '', false, 300, '', false, false, 0);
            PDF::writeHTML($html, true, false, true, false, '');
        }
        $l = DB::table('manual_capitulos')
                ->where('manual_capitulos_id', 0)
                ->get();

        $lastChap = $l->last();
        //foto de antes e depois
        PDF::AddPage();
        $html = '<br>';
        PDF::Bookmark(($lastChap->capitulo + 1).'. Relatório Fotográfico', 0, 0, '', '');
        $html = '<h4>' . ($lastChap->capitulo + 1).'. RELATÓRIO FOTOGRÁFICO</h4>';
        foreach(explode(',',$entrega->itens) as $item){
            $fotos = DB::table('e_servicos as s')
                    ->join('man_itens as i','i.id','=','s.man_itens_id')
                    ->where([
                                ['man_itens_id', '=', $item],
                                ['entregas_id', '=', $id],
                            ])
                    ->get();
            $itemName = DB::table('man_itens')->where('id',$item)->value('item');
            $html .= '<table border="1" cellpadding="10">'
                    . '<tr>'
                    . '<th colspan="2" align="center">'.$itemName.'</th>'
                    . '</tr>'
                    . '<tr>'
                    . '<td align="center">ANTES</td>'
                    . '<td align="center">DEPOIS</td>'
                    . '</tr>';
            foreach($fotos as $foto){
                $foto_antes = (!is_null($foto->antes))?'<img src="'.$foto->antes.'">':'';
                $html .= '<tr>'
                        . '<td align="center" width="50%">'.$foto_antes.'<br>'.explode('|',$foto->nome)[0].'</td>'
                        . '<td align="center" width="50%"><img src="'.$foto->depois.'"><br>'.explode('|', $foto->nome)[1].'</td>'
                        . '</tr>';
            }
            $html .= '</table>';
            $html .= '<p></p>';
        }
//        return var_dump($html);
        PDF::writeHTML($html, true, false, true, false, '');
        //------ Foto
        
        //termo de entrega
        PDF::AddPage();
        PDF::Bookmark(($lastChap->capitulo + 2) . '. Termo de Entrega de Instalação', 0, 0, '', '');
        $html = '<h4>' . ($lastChap->capitulo + 2) . '. Termo de Entrega de Instalação' . '</h4>';
        PDF::writeHTML($html, true, false, true, false, '');
//        $entrega = Entregas::find(27);
        PDF::Cell(0, 10, 'Orçamento: ' . $entrega->orcamento, 0, 1, 'L');
        PDF::Cell(0, 10, 'Cliente: ' . $entrega->contratante, 0, 1, 'L');
        PDF::Cell(0, 10, 'Endereço: ' . $entrega->endereco, 0, 1, 'L');
        PDF::Cell(0, 10, 'Data: ' . date('d/m/Y', strtotime($entrega->dt_entrega)), 0, 1, 'L');

        foreach(explode(',',$entrega->itens) as $item){
            
            $item_name = DB::table('man_itens')->where('id',$item)->value('item');
            PDF::Cell(0, 10, $item_name, 0, 1, 'C');
            
            $termos = DB::table('termos')
                    ->where([
                        ['verificacao', '=', 'v'],
                        ['man_itens_id', '=', $item],
                    ])
                    ->get();
            if(!$termos->isEmpty()){
                $html = '<table border="1">'
                        . '<tr>'
                        . '<th colspan="3" align="center">VERIFICAÇÃO VISUAL DO SISTEMA</th>'
                        . '</tr>';
                $html .= '<tr>'
                        . '<td width="80%" align="center">ITEM</td>'
                        . '<td width="10%" align="center">OK</td>'
                        . '<td width="10%" align="center">NÃO</td>'
                        . '</tr>';
                foreach ($termos as $termo) {
                    $html .= '<tr>'
                            . '<td>' . $termo->item . '</td>'
                            . '<td></td>'
                            . '<td></td>'
                            . '</tr>';
                }
                $html .= '</table>';

                PDF::writeHTML($html, true, false, true, false, '');
            }
//            PDF::AddPage();

            $termos = DB::table('termos')
                    ->where([
                        ['verificacao', '=', 'f'],
                        ['man_itens_id', '=', $item],
                    ])
                    ->get();
            if(!$termos->isEmpty()){
                $html = '<table border="1">'
                        . '<tr>'
                        . '<th colspan="3" align="center">VERIFICAÇÃO DE FUNCIONAMENTO DO SISTEMA</th>'
                        . '</tr>';
                $html .= '<tr>'
                        . '<td width="80%" align="center">ITEM</td>'
                        . '<td width="10%" align="center">OK</td>'
                        . '<td width="10%" align="center">NÃO</td>'
                        . '</tr>';
                foreach ($termos as $termo) {
                    $html .= '<tr>'
                            . '<td>' . $termo->item . '</td>'
                            . '<td></td>'
                            . '<td></td>'
                            . '</tr>';
                }
                $html .= '</table>';

                PDF::writeHTML($html, true, false, true, false, '');
//                PDF::AddPage();
            }
            PDF::AddPage();
        }

        $html = '<p class="western" align="justify" style="margin-bottom: 0.35cm; line-height: 115%">
<font face="Calibri, sans-serif"><font style="font-size: 11pt"><font color="#333333"><font face="Arial Narrow, sans-serif">OBSERVAÇÕES:
____________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________</font></font></font></font></p><p class="western" align="justify" style="text-indent: 1.25cm; margin-bottom: 0.35cm; line-height: 115%">
<font face="Calibri, sans-serif"><font style="font-size: 11pt"><font color="#333333"><font face="Arial Narrow, sans-serif">Após
a entrega dos serviços conforme termo acima assinado por
V.Sªs, qualquer equipamento deste sistema que for retirado será de
responsabilidade de V.S.ª.</font></font></font></font></p><p class="western" align="justify" style="margin-bottom: 0.35cm; line-height: 115%">
<font face="Calibri, sans-serif"><font style="font-size: 11pt"><font color="#333333"><font face="Arial Narrow, sans-serif">1)
	Cabe salientar ao cliente que, após a entrega de toda a instalação
entregue com perfeito funcionamento, não teremos a responsabilidade
do não funcionamento dos equipamentos. Deverá solicitar uma nova
visita e será cobrada à parte. </font></font></font></font>
</p><p class="western" style="margin-bottom: 0.35cm; line-height: 115%"><br>
<br>

</p><p class="western" style="margin-bottom: 0.35cm; line-height: 115%"><br>
<br>

</p><p class="western" style="margin-bottom: 0.35cm; line-height: 115%"><br>
<br>

</p><p class="western" style="margin-bottom: 0.35cm; line-height: 115%"><br>
<br>

</p><p class="western" style="margin-bottom: 0.35cm; line-height: 115%"><br>
<br>

</p>';
        $html .= '<font style="font-size: 9pt">___________________________________
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;____________________________________';
        $html .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Responsável
Contratada&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Carimbo e Ass: Responsável da Contratante</font>';
        PDF::writeHTML($html, true, false, true, false, '');
//        PDF::cell
        // add a new page for TOC
        PDF::addTOCPage();

// write the TOC title
        PDF::SetFont('', 'B', 16);
        PDF::MultiCell(0, 0, 'SUMÁRIO', 0, 'C', 0, 1, '', '', true, 0);
        PDF::Ln();

        PDF::SetFont('dejavusans', '', 10);

// add a simple Table Of Content at first page
// (check the example n. 59 for the HTML version)
        PDF::addTOC(2, 'courier', '.', 'INDEX', 'B', array(128, 0, 0));

// end of TOC page
        PDF::endTOCPage();

// ---------------------------------------------------------
//Close and output PDF document
        PDF::Output('manual_teste.pdf', 'I');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
        $this->permission();
        $entrega = Entregas::find($id);
        $entrega->delete();

        return redirect('manutencao/entregas')->with('message', 'Relatório de entrega excluído com sucesso');
    }

}
