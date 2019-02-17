<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            <?php include(public_path().'/css/app.css');?>
            body{
                background-color: white !important;
            }
            .header{
                width:113%;
                margin-top:-100px;
                margin-bottom:-245px;
                margin-left:-45px;
                margin-right:-45px;
                position: fixed;
                left: 0px;
                top: 40px;
                z-index: -1000;
            }
            .bottom{
                width:113%;
                margin-top:-100px;
                margin-bottom:-245px;
                margin-left:-45px;
                margin-right:-45px;
                position: fixed;
                left: 0px;
                top: 883px;
                z-index: -1000;
                
            }
            #c-col-d{
                width:300px;
                height:1055px;
                float:right;
                margin-top:-45px;
                margin-bottom:-45px;
                margin-right:-45px;
                z-index: -999;
            }
            #titulo{
                width:600px;
                height:100px;
                border-style: solid;
                border-width: 5px;
                margin:300px auto;
                margin-bottom:200px;
                padding: 20px;
                background-color:#ccc;
                
            }
            #preencher{
                width:400px;
                float:left;
                page-break-after: always;
            }
            #texto{
                margin-top:100px;
                
                margin-left: 40px;
                margin-right: 40px;
            }
            .imagem {
                display: block;
                max-width:200px;
                max-height:200px;
                width: auto;
                height: auto;
            }
        </style>    
    </head>
    <body>
        <img class="header" src="data:image/jpeg;base64,{!! base64_encode(file_get_contents(public_path('img/').'pg_header.jpg')) !!}" />
        <img class="bottom" src="data:image/jpeg;base64,{!! base64_encode(file_get_contents(public_path('img/').'pg_bottom.jpg')) !!}" />
        <div class="container-fluid">
            <!-- capa -->
                <div id="c-col-d" class="bg-primary">
                    <div class="text-center text-white pt-5"><h1 class="display-1">{{ date('Y', strtotime($entrega->dt_entrega)) }}</h1></div>
                </div>
                <div id="titulo">
                    <h2 class="text-uppercase">{{($entrega->titulo == null)? 'RELATÓRIO DE ENTREGA DE OBRA' : $entrega->titulo }} </h2>
                </div>
                <div id="preencher">
                    Cliente: __________________________________________
                    <br>
                    Orçamento:  _______________________________________
                    <br>
                    Endereço: ________________________________________
                    <br>
                    _________________________________________________
                    <br>
                    Data: ____/____/____
                    <br><br><br><br><br>

                    _________________________________________________
                    <br>
                    <span class="text-center">Assinatura do Técnico Responsável FTR</span>
                </div>
            <!-- capa -->
            <div id="texto">
                <p class="text-justify" style="text-indent: 50px;">
                    Certificamos que a Empresa Contratada <strong>FTR PROJETOS E INSTALAÇÕES</strong>, 
                CNPJ 00.882.909/0001-10, inscrita no CBMERJ, GEM, Membro da NFPA e ABNT 
                finalizou no dia {{date('d/m/Y', strtotime($entrega->dt_entrega))}}, os seguintes serviços prestados à <strong>Empresa Contratante 
                    {{ $entrega->contratante }}:</strong> 
                </p>
                <ul>
                    @foreach($servicos as $servico)
                    <li><strong>{{ ($servico->nome == '')? $servico->item : $servico->nome }}</strong></li>
                    @endforeach
                </ul>
                <p class="text-justify">
                    Todos os serviços foram elaborados em conformidade com as definições da
                    @foreach($servicos as $servico)
                    @if($servico == $servicos->last())
                    e {{$servico->norma}}.
                    @else
                    {{$servico->norma}}, 
                    @endif
                    @endforeach
                </p> 
                <p class="text-justify">
                    OBSERVAÇÕES:
                    <br><br>
                    Após a entrega dos serviços listados acima, qualquer equipamento deste sistema que for
                    retirado será de responsabilidade de V.S.ª.
                <ol>
                    <li>
                        Cabe salientar ao cliente que, após a entrega de toda a instalação entregue com 
                    perfeito funcionamento, não teremos a responsabilidade do não funcionamento dos 
                    equipamentos. Deverá solicitar uma nova visita e será cobrada à parte.
                    </li>
                </ol>
                     	
                </p>
                <table style="margin-top:60px;page-break-after: always;">
                    <tr>
                        <td>
                            <img src="{{public_path('img/assinatura.png')}}" width="200px" style="margin-bottom:-53px;" />
                            __________________________________
                            <br>
                            <small>ENG. ANTONIO LOUREIRO FEIJÓO</small> 
                        </td>
                        <td>
                            
                            <br>
                            <br>
                            __________________________________
                            <br>
                            <small>Ass: Responsável da Contratante</small>
                        </td>
                    </tr>
                </table>
                <p style="text-align:center;margin-top:100px;"><strong>Relatório Fotográfico</strong></p>
                <table align="center">
                        @php $i = 0 @endphp
                        @foreach($imagens as $imagem)
                        @if($i == 0)
                        <tr>
                        @endif
                            @if($imagem->imagem != '')
                            <td style="padding-right:5px;padding-top:10px">
                                <img src="{{ $imagem->imagem }}" class="imagem">
                                <p>{{ ($imagem->desc == '')? $imagem->item : $imagem->desc }}</p>
                            </td>
                            @endif
                        @if($i == 2)    
                        </tr>
                        @php $i = 0 @endphp
                        @endif
                        @php $i++ @endphp
                        @endforeach                        
                    </table>
            </div>
        </div>
    </body>
</html>
