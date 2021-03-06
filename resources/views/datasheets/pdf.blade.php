<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>DATASHEET SIMPLIFICADO DA OPERAÇÃO</title>
        <style>
            @page{
                font-family:'Arial,Helvetica,Sans-serif';
                font-size: 12px;
                margin: 0cm 0cm;
                
            }
            /**
            * Define the real margins of the content of your PDF
            * Here you will fix the margins of the header and footer
            * Of your background image.
            **/
            body {
                margin-top:    3.5cm;
                margin-bottom: 1cm;
                margin-left:   1cm;
                margin-right:  1cm;
            }
            /** 
            * Define the width, height, margins and position of the watermark.
            **/
            #watermark {
                position: fixed;
                bottom:   0px;
                left:     0px;
                /** The width and height may change 
                    according to the dimensions of your letterhead
                **/
                width:    21.2cm; /** 21.8 **/
                height:   30cm;   /** 28 **/

                /** Your watermark should be behind every content**/
                z-index:  1000;
            }
            .logo{
                width:100px;
                max-height:100px; 
/*                height:auto;*/
            }
            .bg-primary{
                color: white;
                background-color: #1f497d;
            }
        </style>
    </head>
    <body>
        <div id="watermark">
            <img src="{{ public_path('img/ftr-marca-1.png') }}" height="100%" width="100%" />
        </div>
        <table width="100%">
            <tr>
                <td>
                    <img src="{{ public_path('storage/'.$datasheet->logo) }}" class="logo" />
                </td>
                <td align="right">
                    <img src="{{ public_path('storage/logos/l8CgElKDIrGdgkHLweTnbgr3FNpyCH6ewkKHUuiT.jpeg') }}" class="logo logo_ftr" />
                </td>
            </tr>
            <tr>
                <td>
                    Data de Emissão: {!! date('d/m/Y', strtotime($datasheet->created_at)) !!}
                </td>
                <td></td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td align="center">
                    <strong>DATASHEET SIMPLIFICADO DA OPERAÇÃO</strong>
                </td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td align="center" width="33%">
                    {!! $datasheet->shopping !!}
                </td>
                <td align="center" width="33%">
                    {!! $datasheet->loja !!}
                </td>
                <td align="center" width="33%">
                    {!! $datasheet->numero !!}
                </td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td align="center" class="bg-primary">
                    Listagem de Equipamentos
                </td>
            </tr>
        </table>
        @foreach($disciplinas as $disciplina)
        <table width="100%">
            <tr>
                <td align="center"><strong>{!! $disciplina->tipo_relatorio !!}</strong></td>
            </tr>
            @foreach($detalhes as $detalhe)
                @if($detalhe->tipo_relatorios_id == $disciplina->id)
                <tr>
                    <td>
                        {!! sprintf('%1$02d', $detalhe->quantidade) !!} {!! ($detalhe->quantidade > 1) ? $detalhe->nome_plural : $detalhe->nome !!} {!! $detalhe->tipo !!} na(s) localidade(s) {!! $detalhe->local !!}
                    </td>
                </tr>
                @endif
            @endforeach
        </table>
        @endforeach
    </body>
</html>
