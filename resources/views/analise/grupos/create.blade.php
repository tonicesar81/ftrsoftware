@extends('layouts.app')
@section('content')

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
    Grupos
  </div>
  <div class="card-body">
    {!! Form::open(['action' => 'GruposController@store']) !!}
    <div class="form-row">
        <div class="form-group col-6">
            {!! Form::label('grupo', 'Nome do grupo') !!}
            {!! Form::text('grupo',null ,['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6">
            {!! Form::label('abrev', 'Abreviatura do grupo') !!}
            {!! Form::text('abrev',null ,['class' => 'form-control text-uppercase']) !!}
            <small id="inputHelpBlock" class="form-text text-muted">
                Se nenhum valor for informado, o nome será os 3(três) primeiros caracteres do nome do grupo
            </small>
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