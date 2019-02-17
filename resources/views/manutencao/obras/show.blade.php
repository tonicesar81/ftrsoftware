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
            .img-bottom{
                text-align: center;
                vertical-align: bottom;
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
                margin-left: 0%;
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
        <title>{!! $obra->nome !!}</title>
    </head>
    <body>
        <div class="footer bg-primary">
            <strong>REV {{ $obra->numero }}</strong>
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
                    <td class="tb_borderless" >
                        <div style='width:92%;text-align:center;padding-top:30px;padding-bottom:30px;' class="px-2 bordered border-dark" >
                        <h2>{!! $obra->nome !!}</h2>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style='/*padding-top:210px;padding-bottom:100px;*/' class="tb_borderless">
                        <div style='width:85%;margin-top:50px;margin-bottom:50;padding:20px;background-color:#d9d9d9;' class="px-2 bordered border-dark bg-cliente">
                            <h4>CLIENTE: {{ strtoupper($obra->cliente) }}</h4>
                            <h4>RESPONSÁVEL: {{ $obra->contratante }}</h4>
                            <h4>CONTATO: {{ $obra->email }} - {{ $obra->telefone }}</h4>
                            <h4>DATA / NÚMERO: {{ date('d/m/Y', strtotime($obra->created_at))}} - {{ $obra->numero }}</h4>
                            <h4>TÉCNICO RESPONSÁVEL: {{$obra->name}}</h4>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <header>
            <img src="{{ public_path('storage/logos/l8CgElKDIrGdgkHLweTnbgr3FNpyCH6ewkKHUuiT.jpeg') }}" class="logo logo_ftr" />
        </header>
        <div class="container-fluid">
            <!-- class="table table-condensed" -->
            @if(!is_null($obra->introducao))
            <h3>Introdução </h3>
            {!! $obra->introducao !!}
            @endif
            
            @foreach($trabalhos as $trabalho)
            <h3>{{$trabalho->item}}</h3>
            {!! $trabalho->texto_referencia !!}
            @foreach($trabalho->vistorias as $v)
            @php $ocultar = false @endphp
            @if(!is_null($v->vistorias))
            @php $ocultar = true @endphp
            <table width="100%" style="page-break-inside: avoid;">
                <tr>
                    <th>{!! $v->item !!} - {!!$v->numero!!} - {!!$v->pavimento!!}/{!!$v->setor!!}</th>
                    <th>Status</th>
                </tr>
            @php    
            $v2 = explode(';',$v->vistorias);
            
            @endphp
            
            @foreach($v2 as $lista)
            @php $v3 = explode(':',$lista) @endphp
            <tr>
                <td>
                    {!! App\Man_desc::where('id', $v3[0])->value('descricao') !!}
                    
                </td>
                <td>
                    @php
                    switch($v3[1]){
                        case 0:
                        echo 'OK';
                        break;
                        case 1:
                        echo 'NÃO OK';
                        break;
                        case 2:
                        echo 'NÃO SE APLICA';
                        break;
                    }
                    @endphp
                    
                </td>
            </tr>
            
            @endforeach
            @php
                $imagens = App\Man_imagens::where('vistorias_id', $v->id)->get();
            @endphp
            <tr>
                <td colspan="2">
                    <table align="center">
                    @php $i = 0 @endphp
                    @foreach($imagens as $imagem)
                    @if($i == 0)
                    <tr class="tb_borderless">
                    @endif    
                        <td align="center" class="tb_borderless" style="padding-right:5px;padding-top:10px">
                            <img src="{{ $imagem->imagem}}" class="imagem" width="100">
                            <p>{{ $imagem->obs }}</p>
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
            @endif
            @endforeach
            @if(!$ocultar)
            <table width="100%">
                <tr>
                    <td>
                    @foreach($trabalho->imagens as $imagem)
                        <img src="{!! $imagem->imagem !!}" width="100" />
                        <br>
                        {!! $imagem->obs !!}                
                    @endforeach
                    </td>
                </tr>
            </table>
            @endif
            @php $ocultar = false @endphp
            {!! $trabalho->observacoes !!}
            
            @endforeach
            
            @if(!is_null($obra->conclusao))
            <h3>Conclusão </h3>
            {!! $obra->conclusao !!}
            @endif
            <table width="100%" style="page-break-inside:avoid !important;">
                <tr>
                    <td class="img-bottom tb_borderless" width="200px"><img src="{{public_path('img/assinatura.png')}}" style="max-height:100px;max-width:200px;" /></td>
                    <td class="img-bottom tb_borderless" width="200px"><img src="{{public_path('storage/'.$diretor->assinatura)}}"  style="max-height:100px;max-width:200px;" /></td>
                    
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
                    
                </tr>
                <tr>
                    
                    <td class="img-bottom tb_borderless" width="200px"><img src="{{public_path('storage/'.$obra->user_assinatura)}}"  style="max-height:100px;max-width:200px;" /></td>
                    <td class="img-bottom tb_borderless" width="200px">
                        @if(!is_null($obra->assinatura))
                        <img src="{{public_path('storage/'.$obra->assinatura)}}"  style="max-height:100px;max-width:200px;" />
                        @endif
                    </td>
                </tr>
        
                <tr>
                    <td class="table-gray">
                        <span style="text-transform: uppercase">{{ strtoupper($obra->name) }}</span>
                        <br>
                        @if(!is_null($obra->titulo))
                        {{ $obra->titulo }}
                        @else
                        {{ $obra->nivel }}
                        @endif
                    </td>
                    <td class="table-gray">
                        Assinatura do cliente
                    </td>
                    
                </tr>
            </table>
        </div>
    </body>
</html>
