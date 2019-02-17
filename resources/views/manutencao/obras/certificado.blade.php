<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
 if($certificado->tipo == 1){
     $c = 'GARANTIA';
 }else{
     $c = 'RESPONSABILIDADE';
 }
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>CERTIFICADO DE {!! $c !!}</title>
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
                margin-top:    2.5cm;
                margin-bottom: 1cm;
                margin-left:   1cm;
                margin-right:  1cm;
            }
            
            header { 
                position: fixed; 
                top: 0px; 
                left: 0px; 
                right: 0px;
                z-index: -1000;
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
                width:300px;
                /*max-height:100px;*/ 
/*                height:auto;*/
            }
            .bg-primary{
                color: white;
                background-color: #1f497d;
            }
        </style>
    </head>
    <header>
            <img src="{{ public_path('img/certificado.jpg') }}" width="100%" />
    </header>
    <body>
<!--        <table width="100%">
            <tr>
                <td>
                    
                </td>
            </tr>
            <tr>
                <td>
                    Data de Emissão: {!! date('d/m/Y', strtotime($certificado->created_at)) !!}
                </td>
            </tr>
        </table>-->
        <table width="100%">
            <tr>
                <td align="center">
                    <img src="{{ public_path('storage/logos/l8CgElKDIrGdgkHLweTnbgr3FNpyCH6ewkKHUuiT.jpeg') }}" class="logo" />
                </td>
            </tr>
            <tr>
                <td align="center">
                    <h1>CERTIFICADO DE {{ $c }}</h1>
                </td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td align="center" width="100%">
                    {!! $texto !!}
                </td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td align="center" width="100%">
                    {!! $data !!}
                </td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td align="center" width="50%">
                    <img src="{{public_path('img/assinatura.png')}}" style="max-height:100px;max-width:200px;" />
                </td>
                <td align="center" width="50%">
                    <img src="{{public_path('storage/'.$diretor->assinatura)}}"  style="max-height:100px;max-width:200px;" />
                </td>
            </tr>
            <tr>
                <td align="center">
                    ANTONIO LOUREIRO FEIJÓO
                    <br>
                    Eng. Mecânico / Segurança do Trabalho
                </td>
                <td align="center">
                    <span style="text-transform: uppercase">{{ strtoupper($diretor->name) }}</span>
                        <br>
                        @if(!is_null($diretor->titulo))
                        {{ $diretor->titulo }}
                        @else
                        {{ $diretor->nivel }}
                        @endif
                </td>
            </tr>
        </table>        
    </body>
</html>
