@extends('layouts.app')

@section('content')
@include('toolbar.tools')
<?php
foreach ($shoppings as $s) {
    $shopping[$s->id] = $s->shopping;
}
//foreach ($tipo_relatorios as $t) {
//    $tipo_relatorio[$t->id] = $t->tipo_relatorio;
//}
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
    {!! Form::open(['action' => 'ProjetosController@store', 'files' => true]) !!}
    <div class="form-row">
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('shoppings_id', 'Shopping') !!}
            @if(count($shopping) > 1)
            {!! Form::select('shoppings_id',$shopping ,null, ['class' => 'form-control', 'id' => 'select-shopping']) !!}
            @else
            {!! Form::hidden('shoppings_id', key($shopping)) !!}
            {!! Form::text('shopping', $shopping[key($shopping)], ['class' => 'form-control-plaintext', 'readonly']); !!}
            @endif
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('loja', 'Nome da loja') !!}
            {!! Form::text('loja', null, ['class' => 'form-control text-uppercase']); !!}
        </div>
            <div class="form-group col-6 offset-md-3">
                 Escolha um(a) ou mais Sistema/disciplinas a serem analisadas
            </div>
        <div class="form-group col-6 offset-md-3 row">
            @foreach($tipo_relatorios as $tipo_relatorio)
            <div class="col-4 form-check">
            {!! Form::checkbox('tipo_relatorios[]', $tipo_relatorio->id, false, ['class' => 'form-check-input'] ) !!}
            {!! Form::label('tipo_relatorios', $tipo_relatorio->tipo_relatorio, ['class' => 'form-check-label']) !!}
            </div> 
            @endforeach
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('arquivo', 'Arquivo(s) para análise') !!}
            {!! Form::file('arquivo[]', ['class' => 'form-control', 'multiple']); !!}
            <small id="inputHelpBlock" class="form-text text-muted">
                Aceita múltiplos arquivos (somente DWG ou PDF. Arquivos compactados como ZIP ou RAR também são válidos)
            </small>
            <small id="inputHelpBlock" class="form-text text-muted">
                Tamanho máximo por arquivo: 30MB
            </small>
        </div>
        
        <div class="form-group col-6 offset-md-3">
            
            {!! Form::submit('Salvar', ['id' => 'btSalva', 'class' => 'btn btn-primary', 'onClick' => 'carregar();']); !!}
            
        </div>
        <div class='form-group col-6 offset-md-3'>
            <div id="loader">
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
<script>
    function carregar(){
        $('#loader').html('<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Carregando. Dependendo da sua conexão, pode demorar alguns minutos...</div></div>');
    }
    $(function () {
    $('input[type=file]').change(function () {
        var val = $(this).val().toLowerCase(),
            regex = new RegExp("(.*?)\.(pdf|dwg|zip|rar)$");
        var erros = 0;    
        if(this.files[0].size > 30000000){
            $('#fileError').prepend('<div class="alert alert-danger alert-dismissible fade show" role="alert">Tamanho do arquivo excede o limite de 30MB <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
        }   

        if (!(regex.test(val))) {
            $(this).val('');
            $('#fileError').prepend('<div class="alert alert-danger alert-dismissible fade show" role="alert">Formato de arquivo inválido <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
            //alert('Please select correct file format');
        }
    });
});
</script>
@endsection