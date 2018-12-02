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
