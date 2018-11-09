@extends('layouts.app')

@section('content')

<style>
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
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    {!! Form::open(['action' => 'FigurasController@store','id' => 'meuForm', 'files' => true]) !!}
    <p class="text-center">Selecione um arquivo de imagem do seu dispositivo, ou faça um "Print Screen" e cole na caixa abaixo</p>
    <div class="form-row">
        <div class="col-12 mb-2">
            {!! Form::file('figura') !!}
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
        <div class="form-group col-12">
            {!! Form::hidden('relatorios_id', $relatorio->id) !!}
            {!! Form::hidden('figura', 'null', ['id' => 'figura']) !!}
            {!! Form::button('Salvar', ['class' => 'btn btn-primary', 'onclick' => 'salvarPrint()']); !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
<script>

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
        $("#figPrev").css("background-size", "100% 100%");
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
            $('#figura').val('teste');
            console.log(canvas);
//            document.body.appendChild(canvas);
            var data = canvas.toDataURL('image/jpg');
            $('#figura').val(data);
            console.log(data);
            //$('#figura').val('teste');
            $('#meuForm').submit();
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



</script>

@endsection