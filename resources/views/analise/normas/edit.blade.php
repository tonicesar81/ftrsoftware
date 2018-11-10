@extends('layouts.app')
@section('content')

<?php
foreach ($tipo_relatorios as $t) {
    $tipo_relatorio[$t->id] = $t->tipo_relatorio;
}
?>
<div class="container">
    @if(isset($message))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {!! $message !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>   
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
    Normas
  </div>
  <div class="card-body">
    {!! Form::open(['action' => ['NormasController@update' , $norma->id], 'method' => 'put' ]) !!}
    <div class="form-row">
        <div class="form-group col-6">
            {!! Form::label('tipo_relatorios_id', 'Tipo de relatório') !!}
            {!! Form::select('tipo_relatorios_id',$tipo_relatorio ,$norma->tipo_relatorios_id, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6">
            {!! Form::label('norma', 'Norma') !!}
            {!! Form::text('norma',$norma->norma ,['class' => 'form-control text-uppercase']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::label('descricao', 'Descrição') !!}
            {!! Form::text('descricao',$norma->descricao ,['class' => 'form-control']) !!}
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