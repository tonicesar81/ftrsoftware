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
    {!! Form::open(['action' => 'TipoRelatoriosController@store']) !!}
    <div class="form-row">
        <div class="form-group col-6">
            {!! Form::label('tipo_relatorio', 'Nome do relatório') !!}
            {!! Form::text('tipo_relatorio',null ,['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6">
            {!! Form::label('ref', 'Nome de referência no arquivo') !!}
            {!! Form::text('ref',null ,['class' => 'form-control text-uppercase']) !!}
            <small id="inputHelpBlock" class="form-text text-muted">
                Se nenhum valor for informado, o nome será os 3(três) primeiros caracteres do nome do sistema
            </small>
        </div>
        <div class="form-group col-12">
            {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection