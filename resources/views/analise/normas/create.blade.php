@extends('layouts.app')
@section('content')

<?php
foreach ($grupos as $g) {
    $grupo[$g->id] = $g->grupo;
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
    {!! Form::open(['action' => 'NormasController@store']) !!}
    <div class="form-row">
        <div class="form-group col-6">
            {!! Form::label('grupos_id', 'Grupo') !!}
            {!! Form::select('grupos_id',$grupo ,null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6">
            {!! Form::label('norma', 'Norma') !!}
            {!! Form::text('norma',null ,['class' => 'form-control text-uppercase']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::label('descricao', 'Descrição') !!}
            {!! Form::text('descricao',null ,['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-12">            
            {!! Form::button('Salvar', ['type' => 'submit', 'class' => 'btn btn-primary', 'value' => 'salva', 'name' => 'action']); !!}
            {!! Form::button('Salvar e continuar inserindo', ['type' => 'submit', 'class' => 'btn btn-primary', 'value' => 'continua', 'name' => 'action']); !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
    </div>
</div>
@endsection