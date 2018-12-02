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
            Datasheet - Cadastro de localidades
        </div>
        <div class="card-body">
            {!! Form::open(['action' => ['DslocaisController@update', $local->id], 'method' => 'put']) !!}
            <div class="form-row">
                <div class="form-group col-md-6">
                    {!! Form::label('local', 'Localidade') !!}
                    {!! Form::text('local', $local->local, ['class' => 'form-control']); !!}
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