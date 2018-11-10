@extends('layouts.app')

@section('content')

<?php
foreach ($shoppings as $s) {
    $shopping[$s->id] = $s->shopping;
}
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
    {!! Form::open(['action' => 'ArquivosController@store', 'files' => true]) !!}
    <div class="form-row">
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('shoppings_id', 'Shopping') !!}
            {!! Form::select('shoppings_id',$shopping ,null, ['class' => 'form-control', 'id' => 'select-shopping']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('loja', 'Nome da loja') !!}
            {!! Form::text('loja', null, ['class' => 'form-control text-uppercase']); !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('dtRecebimento', 'Data de aprovação do projeto') !!}
            {!! Form::date('dtRecebimento', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('arquivo', 'Arquivo(s) PDF') !!}
            {!! Form::file('arquivo[]', ['class' => 'form-control', 'multiple']); !!}
            <small id="inputHelpBlock" class="form-text text-muted">
                Aceita múltiplos arquivos (somente PDF)
            </small>
        </div>
        <div class="form-group col-6 offset-md-3">
            <h5>Escolha um tipo de marca d'água</h5>
            <img src="{{ url('img/ftr-marca-1.png') }}" style="width:100px;height:auto;" />
            {!! Form::radio('marca', '1', true) !!}
            <img src="{{ url('img/ftr-marca-2.png') }}" style="width:100px;height:auto;" />
            {!! Form::radio('marca', '2') !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            <h5>Caso já exista um arquivo nessa pasta com o mesmo nome</h5>
            <div class="form-check">
            {!! Form::radio('salvar_como', '1', true, ['class' => 'form-check-input']) !!}
            {!! Form::label('salvar_como', 'Substituir o arquivo', ['class' => 'form-check-label']) !!}
            </div>
            <div class="form-check">
            {!! Form::radio('salvar_como', '0', false, ['class' => 'form-check-input']) !!}
            {!! Form::label('salvar_como', 'Manter o arquivo e adicionar data de envio. Ex: RELATORIO__'.date('Y_m_d_H_i_s', strtotime('-3 hours')).'.pdf', ['class' => 'form-check-label']) !!}
            </div>
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
</script>
@endsection