@extends('layouts.app')

@section('content')

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
            Datasheet - Cadastro de Tipos de equipamentos
        </div>
        <div class="card-body">
            {!! Form::open(['action' => 'DstiposController@store']) !!}
            <div class="form-row">
                <div class="form-group col-md-6">
                    {!! Form::label('tipo', 'Tipo de equipamento') !!}
                    {!! Form::text('tipo', null, ['class' => 'form-control']); !!}
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