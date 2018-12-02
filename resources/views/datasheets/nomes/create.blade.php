@extends('layouts.app')

@section('content')
<?php
foreach ($tipo_relatorios as $t) {
    $tipo_relatorio[$t->id] = $t->ref;
}
?>

<div class="container">
    {!! Session::get('message') !!}
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
            Datasheet - Cadastro de equipamento - Nome
        </div>
        <div class="card-body">
            {!! Form::open(['action' => 'DsnomesController@store']) !!}
            <div class="form-row">
                <div class="form-group offset-md-3 col-md-6">
                    {!! Form::label('tipo_relatorios_id', 'Disciplina do equipamento') !!}
                    {!! Form::select('tipo_relatorios_id',$tipo_relatorio ,null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label('nome', 'Nome do equipamento') !!}
                    {!! Form::text('nome', null, ['class' => 'form-control']); !!}
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label('nome_plural', 'Nome do equipamento no plural') !!}
                    {!! Form::text('nome_plural', null, ['class' => 'form-control']); !!}
                </div>
                <div class="form-group col-12">
                    {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
                    <a class="btn btn-secondary" href="{{ url()->previous() }}" role="button">Voltar</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
    
@endsection