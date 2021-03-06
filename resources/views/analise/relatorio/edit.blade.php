@extends('layouts.app')

@section('content')

<?php
foreach ($shoppings as $s) {
    $shopping[$s->id] = $s->shopping;
}
foreach ($tipo_relatorios as $t) {
    $tipo_relatorio[$t->id] = $t->tipo_relatorio;
}
$current = url()->current();
$array = explode('/', $current);
$action = (!in_array('revisao',$array))? 'update' : 'saveRevisao';
?>
<style>
    /*    .modal-lg {
            max-width: 80%;
        }*/
    .target {
        border: solid 1px #aaa;
        min-height: 200px;
        width: 300px;
        margin-top: 1em;
        border-radius: 5px;
        /* cursor: pointer; */
        transition: 300ms all;
        position: relative;
    }

    .contain {
        background-size: cover;
        position: relative;
        z-index: 10;
        top: 0px;
        left: 0px;
    }
    textarea {
        background:transparent;
        outline:none;
        border:none;
        width:200px;
        font-size:11px;
        font-weight:bold;
        color:#17a2b8;
    }

    .dragg{
        width:200px;
    }



    .spinEffect{
        transform: rotate(45deg);
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
    }
    .active {
        box-shadow: 0px 0px 10px 10px rgba(0,0,255,.4);
    }
</style>
<div class="container">
    {!! Session::get('message') !!}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
<div class="card">
  <div class="card-header">
    Relatórios
  </div>
    @php
    if(isset($projeto_arquivos)){
        $p_array = array();
        foreach($projeto_arquivos as $arq){
            if(is_null($arq->memorial)){
                $p_array[] = $arq->filename;
            }
        }
        $arquivos = implode(' / ', $p_array);
    }
    @endphp
  <div class="card-body">
    {!! Form::open(['action' => ['RelatoriosController@'.$action, $relatorio->id], 'method' => 'put']) !!}
    <div class="form-row">
        <div class="form-group col-4">
            {!! Form::label('loja', 'Nome da loja') !!}
            {!! Form::text('loja', $relatorio->loja, ['class' => 'form-control text-uppercase']) !!}
        </div>
        <div class="form-group col-4">
            {!! Form::label('shoppings_id', 'Shopping') !!}
            {!! Form::select('shoppings_id',$shopping ,$relatorio->shoppings_id, ['class' => 'form-control', 'id' => 'select-shopping']) !!}
        </div>
        <div class="form-group col-4">
            {!! Form::label('id_arquivo', 'Identificação do arquivo') !!}
            {!! Form::text('id_arquivo', (isset($arquivos)) ? $arquivos : $relatorio->id_arquivo, ['class' => 'form-control']) !!}
        </div>
        <div class="alert alert-warning">
            DICA: Utilize as seguintes variáveis padrões se achar necessário. Atenção! A seguinte regra precisa ser obedecida ( Em maiúsculo e entre chaves {DISCIPLINA}, {LOJA}, {SHOPPING}, {EMPRESA} )
        </div>
        <div class="form-group col-12">
            {!! Form::label('objetivo', 'Objetivo') !!}
            {!! Form::textarea('objetivo',$objetivo ,['class' => 'form-control summernote']) !!}
        </div>
        <div class="form-group col-12">
            @php $n = 0; @endphp
            @foreach($sistemas as $s)
            @php $n++; @endphp
            <table class="table" id="table_{{$n}}">
                <thead>
                    <tr class="bg-primary text-white">
                        <th  colspan="2" >{{$n}}.{!! $s['tipo_nome'] !!}
                        {!! Form::hidden('tipo_relatorios_id[]', $s['tipo'], ['class' => 'form-control']) !!}    
                         @if($n > 1)
                            <a class="btn btn-danger btn-sm" href="#" role="button" onclick="apaga({{$n}});">X</a>
                         @endif   
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $i=1;
                    $analise = explode(',',$relatorio->analise);
                    @endphp
                    @foreach($s['itens'] as $item)
                    @php
                     //$none = ($item->sts > 0)? '' : 'd-none';
                     if(($item->sts > 0) || ($item->comentario != null)){
                        $none = '';
                     }else{
                        $none = 'd-none';
                     }
                    @endphp
                    <tr>
                        <td width='60%'>
                            {{$n}}.{{$i}} - {!! $item->item !!} {{ ($item->id == 60) ? '(SOMENTE PARA SHOPPING TIJUCA)' : '' }}
                        </td>
                        <td>
                            {!! Form::radio('ok-'.$item->id, '0', (($item->sts > 0) || ($item->comentario != null))? false : true, ['onclick' => 'abreOk('.$item->id.')']) !!} OK
                            {!! Form::radio('ok-'.$item->id, '1', (($item->sts > 0) || ($item->comentario != null))? true : false, ['onclick' => 'abreNaoOk('.$item->id.')']) !!} NÃO OK
                            <div id='{{$item->id}}' class="bg-obs {{ $none }}">
                                @foreach($item->obs as $ob)
                                @php 
                                $check = (in_array($ob->id,$analise))? true : false;
                                @endphp
                                <div class="form-check">
                                {!! Form::checkbox('obs[]', $ob->id, $check, ['class' => 'form-check-input', 'onclick' => 'mostraFigura("#obs-fig-'.$ob->id.'")'])!!}
                                {!! Form::label('obs', $ob->lista_analise, ['class' => 'form-check-label']) !!}
                                    <div id="obs-fig-{{$ob->id}}" class="border border-primary {{ (!$check)? 'd-none' : ''}}">
                                    {!! Form::button('+ Figura', ['class' => 'btn btn-primary btn-sm', 'data-toggle' => 'modal', 'data-target' => '#exampleModal', 'onClick' => 'figuraObs('.$ob->id.')']); !!}
                                        <div id='figuras-{{$ob->id}}'>
                                            @if(($ob->figura != '') && ($action != 'update'))
                                            <div style="float:left;cursor:pointer;">
                                                <img src='{{ asset('storage/'.$ob->figura) }}' style='max-width:100px;max-height:50px;' title="Clique na imagem para apagar" onclick="$(this).parent().remove();" >
                                                {!! Form::hidden('ob-figura-'.$ob->id,  $ob->figura) !!}
                                            </div>
                                            @endif
                                            @if((empty($ob->figuras)) && ($ob->figura != '') && ($action == 'update'))
                                            <div style="float:left;cursor:pointer;">
                                                <img src='{{ asset('storage/'.$ob->figura) }}' style='max-width:100px;max-height:50px;' title="Clique na imagem para apagar" onclick="$(this).parent().remove();" >
                                                {!! Form::hidden('ob-figura-'.$ob->id,  $ob->figura) !!}
                                            </div>
                                            @endif
                                            @if($action == 'update')
                                            @foreach($ob->figuras as $fg)
                                            @if($ob->figura != $fg->figura)
                                            <!--<img src='{{ asset('storage/'.$ob->figura) }}' style='max-width:100px;max-height:50px;' >-->
                                            <!--{!! Form::hidden('ob-figura-'.$ob->id,  $ob->figura) !!}-->
                                            @endif
                                            <div style="float:left;cursor:pointer;">
                                                <img src='{{ asset('storage/'.$fg->figura) }}' style='max-width:100px;max-height:50px;' title="Clique na imagem para apagar" onclick="$(this).parent().remove();" >
                                                {!! Form::hidden('figuras-'.$ob->id.'[]',  $fg->figura) !!}
                                            </div>
                                            @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                <div class="form-group">
                                    @php $name = 'comm_'.$item->id; @endphp
                                    {!! Form::label('comm_'.$item->id , 'Outra observação') !!}
                                    {!! Form::textarea('comm_'.$item->id, $item->comentario, ['class' => 'form-control text-uppercase', 'rows' => '3']) !!}
                                    {!! Form::button('+ Figura', ['class' => 'btn btn-primary btn-sm', 'data-toggle' => 'modal', 'data-target' => '#exampleModal', 'onClick' => 'figuraObs('.$item->id.', true)']); !!}
                                    <div id='c-figuras-{{$item->id}}'>
                                        @if($action == 'update')
                                        @foreach($item->c_figuras as $c_fig)
                                        <img src='{{ asset('storage/'.$c_fig->figura) }}' style='max-width:100px;max-height:50px;' >
                                        {!! Form::hidden('c-figuras-'.$item->id.'[]',  $c_fig->figura) !!}
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @php
                    $i++;
                    @endphp                    
                    @endforeach
                </tbody>
            </table>
            @php
            $detarray = explode('{BARRA}', $relatorio->detalhamento);
            @endphp
            <div class="form-group col-12">
                {!! Form::label('detalhamento', 'Detalhamento') !!}
                {!! Form::textarea('detalhamento[]',$detarray[$n - 1] ,['class' => 'form-control summernote']) !!}
            </div> 
            @endforeach
            <div id="x-sys"></div>
            <div id="loader"></div>
            <hr />
            <div class="form-group col-12 form-row">
                <div class="col">                
                    {!! Form::select('tipos',$tipo_relatorio ,null, ['class' => 'form-control', 'id' => 'tipos']) !!}
                </div>
                <div class="col">
                    {!! Form::button('Adicionar sistema ao relatório +', ['class' => 'btn btn-primary', 'id' => 'add_tipo']); !!}    
                </div>
            </div>
        </div>
        <div class="form-group col-12">
            {!! Form::label('adicional', 'Comentários adicionais') !!}
            {!! Form::textarea('adicional', @$adicional->mensagem, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-12">
            <div class="form-check">
                {!! Form::checkbox('ressalva', '1', ($relatorio->ressalva == 1) ? true : false, ['class' => 'form-check-input'])!!}
                {!! Form::label('ressalva', 'Aprovar com ressalva', ['class' => 'form-check-label']) !!}
            </div>
        </div>
        <div class="form-group col-12">
            {!! Form::label('consideracao', 'Considerações Finais') !!}
            {!! Form::textarea('consideracao',$consideracao ,['class' => 'form-control summernote']) !!}
        </div>
        <div class="form-group">
            {!! Form::hidden('projetos_id', $projeto_id, ['class' => 'form-control']) !!}
            {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
        </div>
    </div>
    {!! Form::close() !!}
  </div>
</div>
</div>
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog mw-100" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body container">
                {!! Form::open(['action' => 'FigurasController@store','id' => 'meuForm', 'files' => true]) !!}
                <p class="text-center">Selecione um arquivo de imagem do seu dispositivo, ou faça um "Print Screen" e cole na caixa abaixo</p>
                <div class="form-row">
                    <div class="col-12 mb-2">
                        {!! Form::file('figura', ['id' => 'figura', 'onchange' => 'getBase64()']) !!}
                    </div>
                    <div id="fig-tool" class="btn-group col-12 d-none" role="group" aria-label="Basic example">
                        <a class="btn btn-primary" href="#" role="button" onclick="criaSeta(0);"><i class="fas fa-arrow-left"></i></a>
                        <a class="btn btn-primary" href="#" role="button" onclick="criaSeta(90);"><i class="fas fa-arrow-up"></i></a>
                        <a class="btn btn-primary" href="#" role="button" onclick="criaSeta(180);"><i class="fas fa-arrow-right"></i></a>
                        <a class="btn btn-primary" href="#" role="button" onclick="criaSeta(-90);"><i class="fas fa-arrow-down"></i></a>
                        <a class="btn btn-primary" href="#" role="button" onclick="criaTexto();">T</a>
                    </div>
                    <div class="col-12 mb-2">
                        <div id="figPrev" class="target mx-auto">
                        </div>
                    </div>
                    
                </div>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="salvarPrint()">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script>
    var analise = '';
    var comm = false;
    function figuraObs(obs, c = false){
        analise = obs;
        comm = c;
    }
    $('#exampleModal').on('hidden.bs.modal', function (event) {
        $('#figPrev').removeAttr('style');
        $('#fig-tool').addClass('d-none');
        $('#figPrev').removeClass('active');
        $('#figura').val('');
        comm = false;

    })
</script>
<script>
    var inc = {{$n}};
    function abreNaoOk(id) {
        $('#' + id).css('display:block');
        $('#' + id).removeClass('d-none');
    }
    function abreOk(id) {
        $('#' + id).addClass('d-none');
        $('#' + id + ' input').prop('checked', false);
        $('#' + id + ' textarea').val('');
    }
    $( "#add_tipo" ).click(function() {
        $('#loader').html('<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Carregando...</div></div>');
        var tipo = $('#tipos').val();
        inc++;
        $.ajax({
            url: '{{url('/analise/relatorios/create')}}/' + tipo + '/' + inc
        }).done(function(data) {
            $('#loader').html('');
            $('#x-sys').append(data);
        });
        //alert( tipo );
        
      });
    function apaga(id){
        //alert('check');
        //$(this).parent().remove();
        $( "#table_"+id ).remove();
        inc--;
    }  
</script>
<script>
    function getBase64() {
        var file    = $("#figura")[0].files[0];
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
//            console.log(reader.result.width);
//            console.log(reader.result);
            var img = new Image();
            img.src = reader.result;
            
            img.onload = function() {
                var w = img.width;
                var h = img.height;
                console.log(img.width);   // This will print out the width.
                $("#figPrev").css("width", w+"px"); //max 1140px
                $("#figPrev").css("height", h+"px"); //max 608px
            }
            
            $("#figPrev").css("background-size", "100% 100%");
            $("#figPrev").css("background-image", "url(" + reader.result + ")");
            $('#fig-tool').removeClass("d-none");
        };
        reader.onerror = function (error) {
            console.log('Error: ', error);
        };
    }

//----------------- print -------------------

    (function ($) {
        var defaults;
        $.event.fix = (function (originalFix) {
            return function (event) {
                event = originalFix.apply(this, arguments);
                if (event.type.indexOf('copy') === 0 || event.type.indexOf('paste') === 0) {
                    event.clipboardData = event.originalEvent.clipboardData;
                }
                return event;
            };
        })($.event.fix);
        defaults = {
            callback: $.noop,
            matchType: /image.*/
        };
        return $.fn.pasteImageReader = function (options) {
            if (typeof options === "function") {
                options = {
                    callback: options
                };
            }
            options = $.extend({}, defaults, options);
            return this.each(function () {
                var $this, element;
                element = this;
                $this = $(this);
                return $this.bind('paste', function (event) {
                    var clipboardData, found;
                    found = false;
                    clipboardData = event.clipboardData;
                    return Array.prototype.forEach.call(clipboardData.types, function (type, i) {
                        var file, reader;
                        if (found) {
                            return;
                        }
                        if (type.match(options.matchType) || clipboardData.items[i].type.match(options.matchType)) {
                            file = clipboardData.items[i].getAsFile();
                            reader = new FileReader();
                            reader.onload = function (evt) {
                                return options.callback.call(element, {
                                    dataURL: evt.target.result,
                                    event: evt,
                                    file: file,
                                    name: file.name
                                });
                            };
                            reader.readAsDataURL(file);
                            snapshoot();
                            return found = true;
                        }
                    });
                });
            });
        };
    })(jQuery);



    $("html").pasteImageReader(function (results) {
        var dataURL, filename;
        filename = results.filename, dataURL = results.dataURL;
        $data.text(dataURL);
        $size.val(results.file.size);
        $type.val(results.file.type);
        $test.attr('href', dataURL);
        var img = document.createElement('img');
        img.src = dataURL;
        var w = img.width;
        var h = img.height;
        $width.val(w);
        $height.val(h);

        $("#figPrev").css("width", "1140px");
        $("#figPrev").css("height", "608px");
        $("#figPrev").css("background-size", "100% auto");
        $('#fig-tool').removeClass("d-none");
        return $(".active").css({
            backgroundImage: "url(" + dataURL + ")"
        }).data({'width': w, 'height': h});


    });

    var $data, $size, $type, $test, $width, $height;
    $(function () {
        $data = $('.data');
        $size = $('.size');
        $type = $('.type');
        $test = $('#test');
        $width = $('#width');
        $height = $('#height');
        $('.target').on('click', function () {
            var $this = $(this);
            var bi = $this.css('background-image');
            if (bi != 'none') {
                $data.text(bi.substr(4, bi.length - 6));
            }


            $('.active').removeClass('active');
            $this.addClass('active');



        })
    })


    function copy(text) {
        var t = document.getElementById('base64')
        t.select()
        try {
            var successful = document.execCommand('copy')
            var msg = successful ? 'successfully' : 'unsuccessfully'
            alert('Base64 data coppied ' + msg + ' to clipboard')
        } catch (err) {
            alert('Unable to copy text')
        }
    }

    //--------------------------------------------------

//    document.getElementById('meuForm').addEventListener("submit", function () {
//        //var canvas = document.getElementById("myCanvasImage");
//        //var image = canvas.toDataURL(); // data:image/png....
//        //document.getElementById('base64').value = image;
//        $('#figura').val('data');
//        html2canvas($('#figPrev').get(0)).then(function (canvas) {
//            console.log(canvas);
//            document.body.appendChild(canvas);
//            var data = canvas.toDataURL('image/jpg');
//            $('#figura').val(data);
//            console.log(data);
//            //$('#figura').val('teste');
//            //$('#meuForm').submit();
//        });
//    }, false);

    function salvarPrint() {
        //$('#figura').val('teste');

        html2canvas($('#figPrev').get(0)).then(function (canvas) {
            
//            $('#figura').val('teste');
            console.log(canvas);
//            document.body.appendChild(canvas);
            var data = canvas.toDataURL('image/jpg');
//            $('#figura').val(data);
            var seletor = 'figuras-';
            if(comm){
                seletor = 'c-figuras-';
            }
            $('#'+seletor+analise).append('<img src="'+data+'" style="max-width:100px;max-height:50px;" />');
            $('#'+seletor+analise).append('<input name="'+seletor+analise+'[]" type="hidden" value="'+data+'">');
            console.log(data);
            $('#exampleModal').modal('hide');
            //$('#figura').val('teste');
//            $('#meuForm').submit();
        });

    }
    function criaSeta(a) {
        $('#figPrev').prepend('<div id="dragThis" class="dragg row" style="width:auto;float:left;position:absolute;cursor:move"></div>');
        $('#dragThis').append('<a class="btn btn-outline-primary btn-sm" href="#" role="button" onclick="$(this).parent().remove()" data-html2canvas-ignore="true">X</a>&nbsp;');
        $('#dragThis').append('<div class="col-12"><img id="seta" src="/img/flecha.png" /></div>');
        $('#seta').rotate(a);
        $(".dragg").draggable({containment: "#figPrev", scroll: false});

    }
    function criaTexto() {
        $('#figPrev').prepend('<div id="dragThis" class="dragg" style="width:auto;float:left;position:absolute;cursor:move"></div>');
        $('#dragThis').prepend('<div><span class="text-info"><i class="fas fa-arrows-alt" data-html2canvas-ignore="true"></i></span><textarea>text!</textarea></div>');
        $('#dragThis').append('<a class="btn btn-outline-primary btn-sm" href="#" role="button" onclick="$(this).parent().remove()" data-html2canvas-ignore="true">X</a>');
        $(".dragg").draggable({containment: "#figPrev", scroll: false});
    }
    function mostraFigura(id){
        $( id ).toggleClass( "d-none" );
    }


</script>
@endsection