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
                margin-top: 50px; 
                margin-bottom: 50px;
            }
            div{
                witdh:100%;
                font-family:'Arial,Helvetica,Sans-serif';
                font-size: 12px;
            }
            .divisor{
                width:100%;
                min-height: 4px;
            }
            header { 
                position: fixed; 
                top: -60px; 
                left: 0px; 
                right: 0px;
            }
            table {
                border-collapse: collapse;
            }

            table, th, td {
                border: 1px solid black;
                padding: 1px;
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
                width:200px;
                height:auto;
            }
            .footer{
                bottom: 0px; 
                position: fixed;
            }
            .pagenum:before {
                content: counter(page);
            }
        </style>
        <title>RELATÓRIO DE ANÁLISE DE PROJETO</title>
    </head>
    <body>
        <div class="footer">
            <script type="text/php">
                if (isset($pdf)) {
                $x = 400;
                $y = 800;
                $text = "FTR ENGENHARIA - Página {PAGE_NUM} / {PAGE_COUNT}";
                $font = null;
                $size = 9;
                $color = array(0,0,0);
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
            <!-- class="table table-condensed" -->
            <table width="100%">
                <tr>
                    <td class="img-middle"><img src="{{ public_path('storage/'.$relatorio->logo) }}" class="logo" /></td>
                    <td class="img-middle"><img src="{{ public_path('storage/logos/l8CgElKDIrGdgkHLweTnbgr3FNpyCH6ewkKHUuiT.jpeg') }}" class="logo" /></td>
                </tr>
                <tr>
                    <td colspan="2" class="table-title">RELATÓRIO DE ANÁLISE DE PROJETO</td>
                </tr>
                <tr >
                    <td style="border-left-color:#fff;border-right-color: #fff;padding:2px;" colspan="2"></td>
                </tr>
                <tr>
                    <td class="table-gray">Nome da Loja</td>
                    <td class="text-center">{{$relatorio->loja}}</td>
                </tr>
                <tr>
                    <td class="table-gray">Shopping</td>
                    <td class="text-center">{{$relatorio->shopping}}</td>
                </tr>
                <tr>
                    <td class="table-gray">Data / Revisão</td>
                    <td class="text-center">{{ date('d/m/Y', strtotime($relatorio->created_at))}} - REV_{{sprintf('%1$02d', $relatorio->revisao)}}</td>
                </tr>
                <tr>
                    <td class="table-gray">Analista do Projeto</td>
                    <td class="text-center">{{$relatorio->name}}</td>
                </tr>
                <tr>
                    <td class="table-gray">Identificação do Arquivo</td>
                    <td class="text-center">{{$relatorio->id_arquivo}}</td>
                </tr>

            </table>
            @php $n = 1; @endphp
            @foreach($sistemas as $s)
            <div class="divisor"></div>
            <table width="100%">
                <tr>
                    <td colspan="2" class="table-title">{{ $n }}.{{ $s['tipo_nome'] }}</td>
                </tr>
                @php 
                $i =1;
                $observacoes = array();
                @endphp
                @foreach($s['itens'] as $item)
                <tr>
                    <td>{{$n}}.{{$i}} - {{ $item->item }}</td>
                    <td class="text-center">{{ $stat = ($item->sts > 0)? 'NÃO OK' : 'OK'}}</td>
                </tr>
                @php
                    foreach($item->obs as $obs){
                        $observacoes[] = $n.'.'.$i.' - '.$obs->lista_analise;
                    }
                   
                $i++ 
                @endphp
                @endforeach
            </table>
            <div class="divisor"></div>
            <table width="100%">
                <tr>
                    <td class="table-gray">
                        Observações
                    </td>
                </tr>
                <tr>
                    <td>
                        @if(!empty($observacoes))
                        <strong>PROJETO NÃO APROVADO</strong>
                        @else
                        <strong>PROJETO APROVADO</strong>
                        @endif
                    </td>
                </tr>
                @foreach ($observacoes as $analise)
                <tr>
                    <td>
                        {{ $analise }}
                    </td>
                </tr>
                    @endforeach
            </table>
            @php $n++ @endphp
            @endforeach
            @if(!$figuras->isEmpty())
            <table width="100%" >
                <tr>
                    <td class="table-gray">Figura/Exemplo demonstrativo
                    </td>
                </tr>
                <!--                width="690" height="368"-->
                
                @foreach($figuras as $figura)
                <tr>            
                    <td class="text-center">
                        
                        <img width="690" height="368" src="{{ public_path('storage/'.$figura->figura) }}"/>
                    </td>
                </tr>
                @endforeach
            </table>           
            @endif
            <div class="divisor"></div>
            <table width="100%" style="page-break-inside:avoid !important;">
                <tr>
                    <td class="img-middle"><img src="{{public_path('img/assinatura.png')}}" width="200px" /></td>
                </tr>
                <tr>
                    <td class="table-gray">DIRETOR TÉCNICO:  ANTONIO LOUREIRO FEIJÓO - ENG. MECÂNICO / SEGURANÇA DO TRABALHO 
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>
