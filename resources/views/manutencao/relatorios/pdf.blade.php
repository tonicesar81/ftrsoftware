<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="content-type" content="application/pdf; charset=UTF-8" />
        <title></title>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <style>
            <?php
            // include(public_path().'/css/app.css');
            
            ?>
            body{
                background-color: white !important;
            }
            .logo{
                width:200px;
                height:auto;
            }
            .logo_capa{
                width:400px;
                height:auto;
            }
            .divisor{
                width:100%;
                min-height: 50px;
            }
            .imagem {
                display: block;
                max-width:200px;
                max-height:200px;
                width: auto;
                height: auto;
            }
            .bg-cliente{
                background-color: rgb(150, 150, 150);
            }
        </style> 
    </head>
    <body>
        <div class="container-fluid">
            <table width="100%" style="page-break-after:always;height:1280px">
                <tr>
                    <td width='80%' align="center" style='padding:100px;'><img src="{{ public_path('storage/logos/l8CgElKDIrGdgkHLweTnbgr3FNpyCH6ewkKHUuiT.jpeg') }}" class="logo_capa" /></td>
                    <td  rowspan="3" class='bg-primary' width='20%'></td>
                </tr>
                <tr>
                    <td >
                        <div style='padding-top:50px;padding-bottom:50px;' class="border border-dark bg-light text-center text-uppercase">
                            <h2>{{ $relatorio->nome }}</h2>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style='padding-top:210px;padding-bottom:100px;'>
                        <div style='padding-top:30px;padding-bottom:30px;' class="px-2 border border-dark bg-cliente">
                            <h4>CLIENTE: {{ $relatorio->shopping }}</h4>
                            <h4>MÊS: {{ date('m/Y', strtotime($relatorio->mes_vistoria)) }}</h4>
                        </div>
                    </td>
                </tr>
            </table>
            <br>
        <table class="table table-borderless table-sm border border-dark" width="100%" align="center">
            <tr >
                <td class="border border-dark text-center"><img src="{{ public_path('storage/logos/l8CgElKDIrGdgkHLweTnbgr3FNpyCH6ewkKHUuiT.jpeg') }}" class="logo" /></td>
                <td class="border border-dark text-center"><img src="{{ public_path('storage/'.$relatorio->logo) }}" class="logo" /></td>
            </tr>
            <tr>
                <td colspan="2" class="border border-dark bg-primary text-white text-center"><strong>Relatorio de Manutenção</strong></td>
            </tr>
            <tr>
                <td class="border border-dark bg-light"><strong>Nome do Cliente(Empresa)</strong></td>
                <td class="border border-dark">{{ $relatorio->shopping }}</td>
            </tr>
            <tr>
                <td class="border border-dark bg-light"><strong>Endereço</strong></td>
                <td class="border border-dark">{{ strtoupper($relatorio->endereco) }}</td>
            </tr>
            <tr>
                <td class="border border-dark bg-light"><strong>Descrição dos Serviços</strong></td>
                <td class="border border-dark">{{ strtoupper($relatorio->desc_servico) }}</td>
            </tr>
            <tr>
                <td class="border border-dark bg-light"><strong>Mês da Vistoria</strong></td>
                <td class="border border-dark">{{ date('m/Y', strtotime($relatorio->mes_vistoria)) }}</td>
            </tr>
            <tr>
                <td class="border border-dark bg-light"><strong>Número do Orçamento</strong></td>
                <td class="border border-dark ">{{ $relatorio->numero }}</td>
            </tr>
            <tr>
                <td class="border border-dark bg-light"><strong>Supervisor Contratante</strong></td>
                <td class="border border-dark">{{ strtoupper($relatorio->contratante) }}</td>
            </tr>
            <tr>
                <td class="border border-dark bg-light"><strong>Supervisor Contratada</strong></td>
                <td class="border border-dark">{{ strtoupper($relatorio->contratada) }}</td>
            </tr>
            <tr>
                <td colspan="2" class="border border-dark bg-primary text-white text-center"><strong>Tipos de Serviço</strong></td>
            </tr>
            @foreach($servicos as $k => $v)
            <tr>
                <td class="border border-dark text-center">{{ $k }}</td>
                <td class="border border-dark">{{ strtoupper($v) }}</td>
            </tr>
            @endforeach
        </table>
        {!! $relatorio->descricao !!}    
        @foreach($instalacoes as $instalacao)
        
        <table class=" border border-dark" style="page-break-inside:avoid !important;margin-top:30px;" width="100%">
            <tr>
                <td colspan="2" class="border border-dark bg-primary text-white text-center"><strong>{{ $instalacao->item }}{{ ($instalacao->numero != null)? '-'.$instalacao->numero : '' }} - {{ $instalacao->pavimento }}/{{ $instalacao->setor }}</strong></td>
            </tr>
            @foreach($instalacao->visto as $vistoria)
            <tr>
                <td class="border border-dark" style="width:80% !important;"><small>{{ $vistoria[0] }}</small></td>
                <td class="border border-dark text-center" style=""><small>
                    @switch($vistoria[1])
                        @case(0)
                        OK
                        @break
                        @case(1)
                        NÃO OK
                        @break
                        @case(2)
                        NÃO SE APLICA
                        @break
                    @endswitch
                    </small>
                </td>
            </tr>
            @endforeach
            <tr>
                <td colspan="2" style="padding-left:-10px;padding-right:-20px;">
                    <table align="center">
                        @php $i = 0 @endphp
                        @foreach($instalacao->imagens as $imagem)
                        @if($i == 0)
                        <tr>
                        @endif    
                            <td align="center" style="padding-right:5px;padding-top:10px">
                                <img src="{{ $imagem[0]}}" class="imagem">
                                <p>{{ $imagem[1] }}</p>
                            </td>
                        @if($i == 2)    
                        </tr>
                        @php $i = 0 @endphp
                        @endif
                        @php $i++ @endphp
                        @endforeach                        
                    </table>
                </td>
            </tr>
        </table>
        
        @endforeach
        
        <table align="center" style="page-break-inside:avoid !important;margin-top:50px;">
            <tr>
                <td align="center">Atenciosamente,</td>
            </tr>
            <tr>
                <td align="center"><img src="{{public_path('img/assinatura.png')}}" width="200px" /></td>
            </tr>
            <tr>
                <td>DIRETOR TÉCNICO: ENG. ANTONIO LOUREIRO FEIJÓO</td>
            </tr>
        </table>
        </div>
    </body>
</html>
