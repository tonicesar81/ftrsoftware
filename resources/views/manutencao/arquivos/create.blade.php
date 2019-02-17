@extends('layouts.app')

@section('content')
<?php
$tipos = [
    '1' => 'Projeto',
    '2' => 'Memorial descritivo',
    '3' => 'Memorial de cálculo',
    '4' => 'Arquivo de medição',
    '5' => 'PMOC'
];
?>
<div class="container">
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
    {!! Form::open(['action' => 'Obras_arquivosController@store', 'files' => true]) !!}
    <div class="form-row">
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('cliente', 'Cliente') !!}
            {!! Form::text('cliente', $obra->cliente, ['class' => 'form-control-plaintext text-uppercase', 'readonly' => true]); !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('tipo', 'Tipo de arquivo') !!}
            {!! Form::select('tipo',$tipos ,null, ['class' => 'form-control']) !!}
        </div>
        
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('arquivo', 'Arquivo') !!}
            {!! Form::file('arquivo', ['class' => 'form-control']); !!}
            <small id="inputHelpBlock" class="form-text text-muted">
                Arquivos aceitos: PDF, Word, Excel e DWG
            </small>
        </div>
        
        <div class="form-group col-6 offset-md-3">
            <input type="hidden" name="obras_id" value="{{$obra->id}}" />
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
</script>
@endsection