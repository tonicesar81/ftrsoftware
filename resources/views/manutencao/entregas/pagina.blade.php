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
            <?php // include(public_path().'/css/app.css');?>
            body{
                background-color: white !important;
            }
            .header{
                /*width:113%;*/
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
                /*width:113%;*/
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
                /*background-image: url("{!! public_path('/img/pg_header.jpg') !!}");*/
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
        <!--<img class="header" src="data:image/jpeg;base64,{!! base64_encode(file_get_contents(public_path('img/').'pg_header.jpg')) !!}" />-->
        
        <div class="container-fluid">
            
            <div id="texto">
                <h4>{!! $titulo !!}</h4>
                <p>
                    {!! $texto !!}
                </p>
            </div>
        </div>
        <!--<img class="bottom" src="data:image/jpeg;base64,{!! base64_encode(file_get_contents(public_path('img/').'pg_bottom.jpg')) !!}" />-->
    </body>
</html>
