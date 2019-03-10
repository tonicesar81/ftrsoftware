@extends('layouts.app')

@section('content')


<div class="container">
    <div id="fileError"></div>
    @if (Session::has('message'))
    <div class="alert alert-danger">{!! Session::get('message') !!}</div>
    @endif
    
    
    @if (Session::has('sucesso'))
    <div class="alert alert-success">{!! Session::get('sucesso') !!}</div>
    @endif

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
            Novo projeto - 2º Passo: Cadastro de Arquivos para Análise
        </div>
        <div class="card-body">
            {!! Form::open(['action' => ['ProjetosController@storeArquivos' , $projeto], 'files' => true]) !!}
            <div class="form-row">
                <div class="form-group col-8 offset-md-2">
                    Arquivos para análise
                </div>
                <div class="form-group col-8 offset-md-2 row">
                    @foreach($arquivos as $arquivo)
                    {!! $arquivo->filename !!}
                    <br>
                    @endforeach
                </div>
                <div class="form-group col-8 offset-md-2 row">
                    <div class="col-6">
                    {!! Form::label('pdf', 'Versão em PDF (Obrigatório)') !!}
                    {!! Form::file('pdf', ['class' => 'form-control pdf', 'onChange' => 'validate(this.value, \'pdf\')']); !!}
                    </div>
                    <div class="col-6">
                    {!! Form::label('pdf', 'Versão em DWG (Obrigatório)') !!}
                    {!! Form::file('dwg', ['class' => 'form-control dwg', 'onChange' => 'validate(this.value, \'dwg\')']); !!}
                    </div>
                </div>                    
                <div class="form-group col-12">            
                    {!! Form::button('Finalizar e cadastrar projeto', ['type' => 'submit', 'class' => 'btn btn-primary', 'value' => 'salva', 'name' => 'action', 'onClick' => 'carregar();']); !!}
                    {!! Form::button('Salvar arquivos e continuar inserindo', ['type' => 'submit', 'class' => 'btn btn-primary', 'value' => 'continua', 'name' => 'action', 'onClick' => 'carregar();']); !!}
                </div>
                <div class='form-group col-8 offset-md-2'>
                    <div id="loader">
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

</div>
<script>
    
    var i = 1;
    function carregar() {
        $('#loader').html('<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Carregando. Dependendo da sua conexão, pode demorar alguns minutos...</div></div>');
    }
    function addFile(id) {
        var ht = '<div class="row"><div class="col-5 offset-md-1">{!! Form::file("pdf[]", ["class" => "form-control", "required" => true, "onChange" => "validate(this.value, \'pdf\')"]); !!}</div><div class="col-5">{!! Form::file("dwg[]", ["class" => "form-control", "onChange" => "validate(this.value, \'dwg\')"]); !!}</div>{!! Form::button("x", ["class" => "btn btn-sm btn-outline-info", "onClick" => "$(this).parent().remove();" ]); !!}</div>';
        $('#file-' + id).append(ht);
    }
//    function addDisciplina(id) {
//        $.get('{{ url("/projetos/addFile") }}/' + id, function (result) {
//            $('#disciplinas').append(result);
//        });
//        i++;
////        $('#disciplinas').load('{{ url("/projetos/addFile") }}/' + id);
//    }
    function validate(file, type) {
        var ext = file.split(".");
        ext = ext[ext.length-1].toLowerCase();      
        var arrayExtensions = ["jpg" , "jpeg", "png", "bmp", "gif"];
        var arr = type.split(",");
        if(jQuery.inArray(ext, arr) === -1) {
            $('#fileError').prepend('<div class="alert alert-danger alert-dismissible fade show" role="alert">Formato de arquivo inválido <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
        }
//        if (ext != type) {
////            alert("Wrong extension type.");
//            $('#fileError').prepend('<div class="alert alert-danger alert-dismissible fade show" role="alert">Formato de arquivo inválido <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
//        }
    }
    
    function pegaImagem(){
        var file = $('#obsFile')[0].files[0];
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
            var img = reader.result;
            $('#obsImgs').append('<div class="form-group col-4"><a class="btn btn-outline-primary btn-sm" href="#obsImgs" role="button" onclick="$(this).parent().remove()" data-html2canvas-ignore="true">X</a><img src="'+img+'" class="img-fluid" /><input type="hidden" name="obsImg[]" value="'+img+'" /></div>');
        };
        $('#obsFile').val('');
    }
    
//    $(function () {
//        $('.memorial').change(function () {
//            var val = $(this).val().toLowerCase(),
//                    regex = new RegExp("(.*?)\.(pdf)$");
//            var erros = 0;
//            if (this.files[0].size > 50000000) {
//                $('#fileError').prepend('<div class="alert alert-danger alert-dismissible fade show" role="alert">Tamanho do arquivo excede o limite de 30MB <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
//            }
//
//            if (!(regex.test(val))) {
//                $(this).val('');
//                $('#fileError').prepend('<div class="alert alert-danger alert-dismissible fade show" role="alert">Formato de arquivo inválido <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
//                //alert('Please select correct file format');
//            }
//        });
//        $('.pdf').change(function () {
//            var val = $(this).val().toLowerCase(),
//                    regex = new RegExp("(.*?)\.(pdf)$");
//            var erros = 0;
//            if (this.files[0].size > 50000000) {
//                $('#fileError').prepend('<div class="alert alert-danger alert-dismissible fade show" role="alert">Tamanho do arquivo excede o limite de 30MB <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
//            }
//
//        });
//        $('.dwg').change(function () {
//            var val = $(this).val().toLowerCase(),
//                    regex = new RegExp("(.*?)\.(dwg)$");
//            var erros = 0;
//            if (this.files[0].size > 50000000) {
//                $('#fileError').prepend('<div class="alert alert-danger alert-dismissible fade show" role="alert">Tamanho do arquivo excede o limite de 30MB <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
//            }
//
//        });
//        $('input[type=file]').change(function () {
//            var val = $(this).val().toLowerCase(),
//                    regex = new RegExp("(.*?)\.(pdf|dwg|zip|rar)$");
//            var erros = 0;
//            if (this.files[0].size > 50000000) {
//                $('#fileError').prepend('<div class="alert alert-danger alert-dismissible fade show" role="alert">Tamanho do arquivo excede o limite de 30MB <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
//            }
//
//            if (!(regex.test(val))) {
//                $(this).val('');
//                $('#fileError').prepend('<div class="alert alert-danger alert-dismissible fade show" role="alert">Formato de arquivo inválido <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
//                //alert('Please select correct file format');
//            }
//        });
//    });
</script>
@endsection