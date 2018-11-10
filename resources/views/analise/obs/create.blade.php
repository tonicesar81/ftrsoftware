@extends('layouts.app')
@section('content')

<?php
foreach ($itens as $i) {
    $item[$i->id] = $i->tipo_relatorio.' - '.$i->item;
}
?>
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
    <div class="card">
  <div class="card-header">
    Observações
  </div>
  <div class="card-body">
    {!! Form::open(['action' => 'ListaAnalisesController@store', 'files' => true]) !!}
    <div class="form-row">
        <div class="form-group col-6">
            {!! Form::label('itens_id', 'Item para análise') !!}
            {!! Form::select('itens_id',$item ,null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6">
            {!! Form::label('lista_analise', 'Observação') !!}
            {!! Form::text('lista_analise',null ,['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6">
            {!! Form::label('figura', 'Figura demonstrativa padrão') !!}
            {!! Form::file('figura', ['class' => 'form-control']); !!}
        </div>
        <div class="form-group col-12">
            {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
    </div>
</div>
@endsection