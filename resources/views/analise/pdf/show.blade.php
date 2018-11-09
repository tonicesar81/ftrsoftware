<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta http-equiv="content-type" content="application/pdf" charset="UTF-8"><!--
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
         Bootstrap CSS 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">-->
        <style>
            @page {
                margin-top: 100px; 
                margin-bottom: 50px;
            }
            div{
                witdh:100%;
                font-family:'Arial,Helvetica,Sans-serif';
                font-size: 12px;
            }
            .logo_capa{
                width:400px;
                height:auto;
            }
            .divisor{
                width:100%;
                min-height: 4px;
            }
            header { 
                position: fixed; 
                top: -80px; 
                left: 0px; 
                right: 0px;
            }
            footer { 
                position: fixed; 
                bottom: -60px; 
                left: 0px; 
                right: 0px; 
                background-color: lightblue; 
                height: 50px; 
            }
            table {
                border-collapse: collapse;
            }

            table, th, td {
                border: 1px solid black;
                padding: 1px;
            }
            table, .tb_borderless{
                border: 0px !important;
            }
            .table_header{
                position: fixed; 
                top: -60px; 
                left: 0px; 
                right: 0px;
            }
            .img-middle{
                text-align: center;
                vertical-align: middle;
                padding: 5px;
            }
            .table-title{
                background-color: #1f497d;
                text-align: center;
                color: white;
                font-weight: bold;
            }
            .table-gray{
                background-color: #d9d9d9;
                text-align: center;
            }
            .text-center{
                text-align: center;
            }
            .figura{
                width:100%;
                height:100%;
            }
            .logo{
                width:100px;
                max-height:100px; 
/*                height:auto;*/
            }
            .logo_ftr {
                display: block;
                margin-left: 70%;
                margin-right: 0px;
            }
            .footer{
                padding:5px;
                bottom: 0px; 
                position: fixed;
                /*background-color: lightblue;*/
            }
            .pagenum:before {
                content: counter(page);
            }
            .bg-primary{
                color: white;
                background-color: #1f497d;
            }
            .bg-secondary{
                background-color: #d9d9d9;
            }
            .bg-success{
                background-color: #28a745;
                color: white;
            }
            .bg-warning{
                background-color: #ffc107;
            }
            .bg-danger{
                background-color: #dc3545;
                color: white;
            }
            .bordered{
                border-style: solid;
                border-width: 1px;
            }
        </style>
        <?php
//             include(public_path().'/css/app.css');
            
            ?>
        <title>RELATÓRIO DE ANÁLISE DE PROJETO</title>
    </head>
    <body>
        <div class="footer bg-primary">
            <strong>REV {{sprintf('%1$02d', $relatorio->revisao)}}</strong>
            <script type="text/php">
                if (isset($pdf)) {
                $x = 400;
                $y = 808;
                $text = "FTR ENGENHARIA - Página {PAGE_NUM} / {PAGE_COUNT}";
                $font = null;
                $size = 9;
                $color = array(255,255,255);
                $word_space = 0.0;  //  default
                $char_space = 0.0;  //  default
                $angle = 0.0;   //  default
                $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
                }
            </script>
<!--            <div class="pagenum-container">Page <span class="pagenum"></span>
            </div>-->
        </div>
        <div class="container-fluid">
            <table width="100%" style="page-break-after:always;border: 0px !important; margin-top:-50px !important;" class="tb_borderless">
                <tr>
                    <td class="tb_borderless" height="50"></td>
                    <td  rowspan="4" class='bg-primary tb_borderless' width='20%'></td>
                </tr>
                <tr>
                    <td width='80%' align="center" style='padding:100px;' class="tb_borderless"><img src="{{ public_path('storage/logos/l8CgElKDIrGdgkHLweTnbgr3FNpyCH6ewkKHUuiT.jpeg') }}" class="logo_capa" /></td>
                    
                </tr>
                <tr>
                    <td class="" style='text-align:center;padding-top:30px;padding-bottom:30px;' >
                        <h2>RELATÓRIO DE ANÁLISE DE PROJETO</h2>
                        <h2>{{ $disciplina }}</h2>
                    </td>
                </tr>
                <tr>
                    <td style='/*padding-top:210px;padding-bottom:100px;*/' class="tb_borderless">
                        <div style='margin-top:50px;margin-bottom:50;padding:20px;background-color:#d9d9d9;' class="px-2 bordered border-dark bg-cliente">
                            <h4>CLIENTE: {{ strtoupper($relatorio->empresa) }}</h4>
                            <h4>SHOPPING: {{ $relatorio->shopping }}</h4>
                            <h4>LOJA: {{$relatorio->loja}}</h4>
                            <h4>DATA / REVISÃO: {{ date('d/m/Y', strtotime($relatorio->created_at))}} - REV_{{sprintf('%1$02d', $relatorio->revisao)}}</h4>
                            <h4>ANALISTA DO PROJETO: {{$relatorio->name}}</h4>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <header>
            <img src="{{ public_path('storage/'.$relatorio->logo) }}" class="logo" />
            <img src="{{ public_path('storage/logos/l8CgElKDIrGdgkHLweTnbgr3FNpyCH6ewkKHUuiT.jpeg') }}" class="logo logo_ftr" />
        </header>
        <div class="container-fluid">
            <!-- class="table table-condensed" -->
            
            
            @php $c = 0; @endphp
            <h3>{{$c = $c + 1}}. Objetivo</h3>
            <p>
                {!! $objetivo !!}
            </p>
            
            <h3>{{$c = $c + 1}}. Documentos de Referencia</h3>           
            <table width="100%">
                <tr>
                    <th colspan="2" class="table-title">{{$c}}.1 Normas de Referência ({{$grupo}})</th>
                </tr>
                @foreach($normas as $norma)
                <tr>
                    <td class="bg-secondary" width="30%">{{$norma->norma}}</td>
                    <td>{{$norma->descricao}}</td>
                </tr>
                @endforeach
            </table>
            <div class="divisor"></div>
            <table width="100%">
                <tr>
                    <th colspan="2" class="table-title">{{$c}}.2 Documentação técnica</th>
                </tr>
                <tr>
                    <td class="table-gray" width="30%">Identificação do Arquivo</td>
                    <td class="text-center">{{$relatorio->id_arquivo}}</td>
                </tr>
            </table>
            <h3>{{$c = $c + 1}}. Análise de projetos</h3>
            @php $n = 1; @endphp
            @foreach($sistemas as $s)
            
            <div class="divisor"></div>
            <table width="100%" style="page-break-inside: avoid;">
                <tr>
                    <td colspan="2" class="table-title">{{$c}}.{{ $n }}. Check list dos itens verificados</td>
<!--                    <td colspan="2" class="table-title">{{$c}}.{{ $n }}.{{ $s['tipo_nome'] }}</td>-->
                </tr>
                @php 
                $i =1;
                $comm = array();
                $ok = 0;
                @endphp
                @foreach($s['itens'] as $item)
                <tr>
                    <td>{{$c}}.{{$n}}.{{$i}} - {{ $item->item }}</td>
                    <td class="text-center" width="10%"><img src="{{ $stat = ($item->sts > 0)? public_path('img/not-ok.png') : public_path('img/ok.png')}}" width="16px" style="margin:5px;" /></td>
                </tr>
                @php
                    $x = 1;
                    
                    foreach($item->comentarios as $comentario){
                        switch($comentario->st){
                            case 1:
                            $st = 'g';
                            $ok = 1;
                            break;
                            case 2:
                            $st = 'o';
                            $ok = 1;
                            break;
                            case 3:
                            $st = 'b';
                            break;
                        }
                        $comm[] = [$c.'.'.$n.'.'.$i.'.'.$x.' - '.$comentario->texto, $st];
                        $fd[$comentario->det_id] = $c.'.'.$n.'.'.$i.'.'.$x;
                        $x++;
                       
                    }
                $i++ 
                @endphp
                @endforeach
                
            </table>
            <div class="divisor"></div>
            <table width="100%" style="page-break-inside: avoid; margin-bottom:30px;">
                <tr>
                    <td class="table-title">
                       
                        Detalhamento básico do desenvolvimento dos serviços
                    </td>
                </tr>
                <tr>
                    <td class="tb_borderless">
                        <p>
                            @php
                            $variaveis = array('{DISCIPLINA}', '{LOJA}', '{SHOPPING}', '{EMPRESA}');
                            $repor = array($refs[$n - 1], $relatorio->loja, $relatorio->shopping, $relatorio->empresa);
                            @endphp
                            {!! str_replace($variaveis, $repor, $detalhamento) !!}
                        </p>
                    </td>
                </tr>
                <tr>
                    @if($ok > 0)
                    $class = (!is_null($relatorio->ressalva)) ? 'bg-warning' : 'bg-danger' !!}
                    @else
                    $class = 'bg-success'
                    @endif
                    
                        @if($ok > 0)
                        {!! (!is_null($relatorio->ressalva)) ? '<td class="bg-warning text-center"><h3>PROJETO APROVADO COM RESSALVA</h3></td>' : '<td class="bg-danger text-center"><h3>PROJETO NÃO APROVADO</h3></td>' !!}
                        @else
                        <td class="bg-success text-center"><h3>PROJETO APROVADO</h3></td>
                        @endif
                    </td>
                </tr>
            </table>
            
            <div class="divisor"></div>
            @if(!empty($comm))
            <table width="100%">
                <tr>
                    <td class="table-title" colspan="2">
                       Comentários técnicos complementares e ressalvas
                    </td>
                    <td class="table-title" width="10%">
                        Status
                    </td>
                </tr>
                @foreach ($comm as $ana)
                
                <tr>
                    <td class="bg-primary tb_borderless" width="10%">
                        
                    </td>
                    <td>
                        {{ $ana[0] }}
                    </td>
                    <td align="center">
                        <img src="{{public_path('img/'.$ana[1].'-dot.png')}}" width="10px" />
                    </td>
                </tr>
                    @endforeach
                    
            </table>
            <table width="100%">
                <tr>
                    <td class="tb_borderless" width="25%">ST (STATUS)</td>
                    <td class="tb_borderless" width="25%"><img src="{{public_path('img/g-dot.png')}}" width="10px" /> ITEM NOVO</td>
                    <td class="tb_borderless" width="25%"><img src="{{public_path('img/o-dot.png')}}" width="10px" /> ITEM PENDENTE</td>
                    <td class="tb_borderless" width="25%"><img src="{{public_path('img/b-dot.png')}}" width="10px" /> ITEM RESOLVIDO</td>                    
                </tr>
            </table>
            @endif
            
            @php $n++ @endphp
            @endforeach
            
            @if(!$figuras->isEmpty())
            
            <h3>{{$c = $c + 1}}. Anexos</h3> 
            
                @php 
                $fn = 1;
                $dt_id = 0;
                @endphp
                @foreach($figuras as $figura)
                @php
                    if($figura->detalhamentos_id == $dt_id){
                        $fn = $fn + 1;
                        $dt_id = $figura->detalhamentos_id;
                    }else{
                        $fn = 1;
                        $dt_id = $figura->detalhamentos_id;
                    }
                @endphp
            <table width="100%"  >
                <tr class="tb_borderless">
                    <td class="tb_borderless" style="page-break-after: avoid;">
                    </td>
                </tr>
                <tr style="page-break-after: avoid;">            
                    <td class="text-center">
                        
                        <img style="max-width:690px;max-height:368px" src="{{ public_path('storage/'.$figura->figura) }}"/>
                        <br>
                        <i>{!! $fd[$figura->detalhamentos_id] !!}. Figura {!! $fn !!}</i>
                    </td>
                </tr>
            </table>    
                @endforeach
                       
            @endif
            
            @if(!is_null($adicional))
            
            <table width="100%" style="page-break-inside: avoid; margin-bottom:30px;margin-top:30px;">
                <tr>
                    <td class="table-title">
                        Informações adicionais
                    </td>
                </tr>
                <tr>
                    <td>
                        {!! $adicional->mensagem !!}
                    </td>
                </tr>
            </table>
            @endif
            
            <h3>{{$c = $c + 1}}. Considerações Finais</h3>
            <p>
                {!! $consideracao !!}
            </p>
            <div class="divisor"></div>
            <p>{!! $dtExtensa !!}</p>
            <table width="100%" style="page-break-inside:avoid !important;">
                <tr>
                    <td class="img-middle tb_borderless" width="200px"><img src="{{public_path('img/assinatura.png')}}" style="max-height:100px;max-width:200px;" /></td>
                    <td class="img-middle tb_borderless" width="200px"><img src="{{public_path('storage/'.$diretor->assinatura)}}"  style="max-height:100px;max-width:200px;" /></td>
                    @if($relatorio->users_id != $diretor->id)
                    <td class="img-middle tb_borderless" width="200px"><img src="{{public_path('storage/'.$relatorio->assinatura)}}" style="max-height:100px;max-width:200px;" /></td>
                    @endif
                </tr>
        
                <tr>
                    <td class="table-gray">
                        ANTONIO LOUREIRO FEIJÓO
                        <br>
                        Eng. Mecânico / Segurança do Trabalho
                    </td>
                    <td class="table-gray">
                        <span style="text-transform: uppercase">{{ strtoupper($diretor->name) }}</span>
                        <br>
                        @if(!is_null($diretor->titulo))
                        {{ $diretor->titulo }}
                        @else
                        {{ $diretor->nivel }}
                        @endif
                    </td>
                    @if($relatorio->users_id != $diretor->id)
                    <td class="table-gray">
                        <span style="text-transform: uppercase">{{ strtoupper($relatorio->name) }}</span>
                        <br>
                        @if(!is_null($relatorio->titulo))
                        {{ $relatorio->titulo }}
                        @else
                        {{ $nivel }}
                        @endif
                    </td>
                    @endif
                </tr>
            </table>
<!--            <table width="100%" style="page-break-inside:avoid !important;">
                <tr>
                    <td class="img-middle"><img src="{{public_path('img/assinatura.png')}}" width="200px" /></td>
                </tr>
                <tr>
                    <td class="table-gray">DIRETOR TÉCNICO:  ANTONIO LOUREIRO FEIJÓO - ENG. MECÂNICO / SEGURANÇA DO TRABALHO 
                    </td>
                </tr>
            </table>-->
        </div>
    </body>
</html>
