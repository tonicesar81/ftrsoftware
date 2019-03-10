@extends('layouts.app')

@section('content')

<?php
foreach ($shoppings as $s) {
    $shopping[$s->id] = $s->shopping;
}
foreach ($tipo_relatorios as $t) {
    $tipo_relatorio[$t->id] = $t->tipo_relatorio;
}
$tipo_relatorio = [
    1 => 'Detecção e alarme',
    3 => 'Rede de sprinklers - Extintores',
    5 => 'Sistema Fixo de Combate a Incêndio',
    6 => 'Sistema de Exaustão Mecânica',
    7 => 'Análise de Gás',
    8 => 'Hidrantes',
    9 => 'Sistema de Ar Condicionado'
];
?>
<div class="container">
    <div id="fileError"></div>
    @if (Session::has('message'))
    <div class="alert alert-danger">{!! Session::get('message') !!}</div>
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
            Novo projeto(Revisão) - 1º Passo: Cadastro de Projeto
        </div>
        <div class="card-body">
            {!! Form::open(['action' => ['ProjetosController@storeRevisao', $relatorio_id], 'files' => true, 'method' => 'put']) !!}
            <div class="form-row">
                <div class="form-group col-8 offset-md-2">
                    {!! Form::label('shoppings_id', 'Shopping') !!}
                    @if(count($shopping) > 1)
                    {!! Form::select('shoppings_id',$shopping ,$shoppings_id, ['class' => 'form-control', 'id' => 'select-shopping']) !!}
                    @else
                    {!! Form::hidden('shoppings_id', key($shopping)) !!}
                    {!! Form::text('shopping', $shopping[key($shopping)], ['class' => 'form-control-plaintext', 'readonly']); !!}
                    @endif
                </div>
                <div class="form-group col-6 offset-md-2">
                    {!! Form::label('loja', 'Nome da loja') !!}
                    {!! Form::text('loja', $loja, ['class' => 'form-control-plaintext text-uppercase', 'readonly']); !!}
                </div>
                <div class="form-group col-2">
                    {!! Form::label('numero', 'Número da loja') !!}
                    {!! Form::text('numero', $numero, ['class' => 'form-control-plaintext text-uppercase', 'readonly']); !!}
                </div>
                <div class="form-group col-8 offset-md-2">

                    {!! Form::label('tipo_relatorios', 'Disciplina a ser analisada') !!}
                    {!! Form::text('numero', $tipo_relatorio[$tipo_relatorios_id], ['class' => 'form-control-plaintext', 'readonly']); !!}                    
                </div>
                <div class="form-group col-8 offset-md-2">
                    {!! Form::label('memorial', 'Memorial') !!}
                    {!! Form::file('memorial', ['class' => 'form-control memorial', 'onChange' => 'validate(this.value, \'pdf\')']); !!}
                    <small id="inputHelpBlock" class="form-text text-muted">
                        (somente PDF)
                    </small>
                    <small id="inputHelpBlock" class="form-text text-muted">
                        Tamanho máximo por arquivo: 50MB
                    </small>
                </div>
                <div class="form-group col-8 offset-md-2">
                    {!! Form::label('arquitetura', 'Projeto de arquitetura') !!}
                    {!! Form::file('arquitetura', ['class' => 'form-control memorial', 'onChange' => 'validate(this.value, \'pdf,dwg\')']); !!}
                    <small id="inputHelpBlock" class="form-text text-muted">
                        (Opcional. Somente PDF ou DWG)
                    </small>
                    <small id="inputHelpBlock" class="form-text text-muted">
                        Tamanho máximo por arquivo: 50MB
                    </small>
                </div>
                
                
                <div class="form-group col-8 offset-md-2">
                    {!! Form::label('observacao', 'Observações do projetista') !!}
                    {!! Form::textarea('observacao', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-6 offset-md-3">
                    {!! Form::label('imagem', 'Imagens ilustrativas') !!}
                    {!! Form::file('obsFile', ['class' => 'form-control-file imagem', 'id' => 'obsFile', 'onchange' => "pegaImagem()"]) !!}
                </div>
                <div class="form-group col-8 offset-md-2" id="obsImgs">

                </div>
                <div class="form-group col-8 offset-md-2">
                    {!! Form::label('infra', 'Informações sobre Infraestrutura da loja') !!}
                    {!! Form::textarea('infra', null, ['class' => 'form-control']) !!}
                </div>
                
                
                <div class="form-group col-8 offset-md-2">
                    {!! Form::submit('Salvar', ['id' => 'btSalva', 'class' => 'btn btn-primary', 'onClick' => 'carregar();']); !!}
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

        if (ext != type) {
//            alert("Wrong extension type.");
            $('#fileError').prepend('<div class="alert alert-danger alert-dismissible fade show" role="alert">Formato de arquivo inválido <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
        }
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