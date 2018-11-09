<?php
//$imagem = file(public_path('/imgmemorial_page-23.jpg').'/');
//$imagecoded = base64_encode($imagem);
//var_dump($imagem);
//exit();
?>
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
                /*width:595px;*/
                margin-top: 200px; 
                /*margin-bottom: 100px;*/
                text-align: justify;
/*                background-image: url("data:image/jpeg;base64,{!! base64_encode(file_get_contents(public_path('img/').'memorial_page-23.jpg')) !!}");
                background-repeat: no-repeat; 
                background-position: center;*/
            }
                        
            body{
                font-family: Tahoma,Helvetica,sans-serif;
                font-size:11px;
/*                background-image: url("data:image/jpeg;base64,{!! base64_encode(file_get_contents(public_path('img/').'memorial_page-23.jpg')) !!}");
                background-repeat: no-repeat; 
                background-position: center;*/
            }
            
            .pagenum:before {
                content: counter(page);
            }
            .sumario{
                width:100%;
                /*margin-top:40px;*/
                margin-left: 1.5cm;
                margin-right: 1.5cm;
            }
            .dots{
                float:left;
                width:100%;
                height:15px;
                border-bottom:dotted 2px black;
                z-index:-100;
            }
            .indice{
                float:left;
                padding-bottom: 10px;
                /*height:50px;*/
                background-color:#fff;
            }
            .indice_no{
                float:right;
                text-align:right;
                padding-bottom: 10px;
                /*height:50px;*/
                background-color:#fff;
            }
            
            .marca{
                width:113%;
                margin-top:-238px;
                margin-bottom:-245px;
                margin-left:-45px;
                margin-right:-45px;
                position: fixed;
                left: 0px;
                top: 40px;
                z-index: -1000;
            }
            .capa{
                page-break-after: always;
                width:113%;
                margin-top:-200px;
                margin-bottom:-245px;
                margin-left:-45px;
                margin-right:-45px;
/*                left: 0px;
                top: 40px;*/
                /*z-index: 1000;*/
            }
            .container-fluid{
/*                margin-left: 2.5cm;
                margin-right: 2.5cm;
                margin-left: 100px;
                margin-bottom: 50px;*/
            }
            #lipsum{
               margin-left: 1.5cm;
               margin-right: 1.5cm; 
               margin-top:-50px;
               margin-bottom: 1.5cm;
               line-height: 30px;
               font-size:12px;
               
            }
        </style>
        <title>MEMORIAL</title>
    </head>
    <body>
        <div class="pagenum-container" style="bottom:0px;position:fixed;text-align:right">
                <span class="pagenum"></span>
            </div>
        <!--<img class="capa" src="data:image/jpeg;base64,{!! base64_encode(file_get_contents(public_path('img/').'memorial_page-01.jpg')) !!}" />-->
<!--        <script type="text/php">
            if (isset($pdf)) {
                $GLOBALS['capa'] = $pdf->open_object();
            }
        </script>
        <img class="capa" src="data:image/jpeg;base64,{!! base64_encode(file_get_contents(public_path('img/').'memorial_page-01.jpg')) !!}" />
        <script type="text/php">
            $pdf->close_object();
            
            $pdf->add_object($GLOBALS["capa"],"add");
            $pdf->stop_object($GLOBALS["capa"]);
        </script>-->
<!--        <div class="footer">-->
            <script type="text/php">
                if (isset($pdf)) {
                    $GLOBALS['chapters'] = array();
                    $GLOBALS['backside'] = $pdf->open_object();
                    $GLOBALS['chapno'] = 1;
                }
//                print_r($GLOBALS['chapters']);
            </script>
            
        <!--</div>-->
        
        <!--<img class="capa" src="data:image/jpeg;base64,{!! base64_encode(file_get_contents(public_path('img/').'memorial_page-01.jpg')) !!}" />-->
        <img class="marca" src="data:image/jpeg;base64,{!! base64_encode(file_get_contents(public_path('img/').'pag-bg.jpg')) !!}" />
        <div class="container-fluid">
            
            <!-- class="table table-condensed" -->
            @php
            $caps = DB::table('m_capitulos')->get();
            $i=1;
            $ic = 1;
            @endphp
        <div class="sumario">    
            <h2 style="text-align: center">SUMÁRIO</h2>                            
                @foreach($caps as $capitulo)
                <div style="font-weight:bold;">
                    <div class="dots"></div>
                    <div class="indice">{{$ic}}. {{$capitulo->capitulo}}</div>
                    <div class="indice_no">%%CH{{$i}}%%</div>
                    
                </div>
                <div style="clear:both;"></div>
                
                <!--                <li>{{$capitulo->capitulo}}...............%%CH{{$i}}%%</li>-->
                @php
                $subcap = DB::table('m_conteudos')->where([
                ['m_capitulos_id',$capitulo->id],
                ['titulo','<>',null]
                ])->get();
                @endphp
                @if(!$subcap->isEmpty())                
                
                    @php
                    $z = $ic;
                    $y = 1 ;
                    @endphp
                    @foreach($subcap as $s)
                    <div>
                        <div class="dots"></div>
                        <div class="indice">&nbsp;&nbsp;&nbsp;{{$z}}.{{$y}} - {{$s->titulo}}</div>
                        <div class="indice_no">%%CH{{$i}}%%</div>
                        
<!--                    <li>{{$s->titulo}}................%%CH{{$i}}%%</li>-->
                    
                    </div>
                    <div style="clear:both;"></div>
                    @php
                    $i++;
                    $y++;
                    @endphp
                    @endforeach
                @else
                @php
                $i++;
                @endphp
                @endif
                @php 
                $ic++;
                @endphp
                @endforeach
            
            <script type="text/php">
                $pdf->close_object();
            </script>
        </div>
            
            @php
            $capitulo = DB::table('m_capitulos')->get();
            $cp = 1;
            @endphp
            @foreach($capitulo as $c)
            <div id="lipsum" style="page-break-before: always;">
            <h2 >{{$cp}}. {{$c->capitulo}}</h2>
            @php
            $conteudo = DB::table('m_conteudos')->where('m_capitulos_id',$c->id)->get();
            @endphp
                @php $scp = 1; @endphp
                @foreach($conteudo as $content)
                    @if(!is_null($content->titulo))
                    <h3>{{$cp}}.{{$scp}} - {{$content->titulo}}</h3>
                    @endif
                    <script type="text/php">            
                        $GLOBALS['chapters'][$GLOBALS['chapno']] = $pdf->get_page_number();
                        $GLOBALS['chapno'] = $GLOBALS['chapno'] + 1;
                    </script>                    
                        {!! $content->conteudo !!}
                    @php $scp++; @endphp    
                @endforeach
                </div>
            @php $cp++; @endphp
            @endforeach            
        </div>
        
        
        <script type="text/php">
            foreach ($GLOBALS['chapters'] as $chapter => $page) {
                    $pdf->get_cpdf()->objects[$GLOBALS['backside']]['c'] = str_replace( '%%CH'.$chapter.'%%' , $page , $pdf->get_cpdf()->objects[$GLOBALS['backside']]['c'] );
            }
            $pdf->page_script('
                    if ($PAGE_NUM==1 ) {
                            $pdf->add_object($GLOBALS["backside"],"add");
                            $pdf->stop_object($GLOBALS["backside"]);
                    } 
            ');
        </script>
    </body>
</html>
