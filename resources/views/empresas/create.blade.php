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
            Empresas
        </div>
        <div class="card-body">
            {!! Form::open(['action' => 'EmpresasController@store', 'files' => true]) !!}
            <div class="form-row">
                <div class="form-group col-6">
                    {!! Form::label('empresa', 'Nome da empresa') !!}
                    {!! Form::text('empresa', null, ['class' => 'form-control']); !!}
                </div>
                <div class="form-group col-6">
                    {!! Form::label('logo', 'Logotipo da empresa') !!}
                    {!! Form::file('logo', ['class' => 'form-control']); !!}
                </div>
                <div class="form-group">
                    {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
                    <a class="btn btn-secondary" href="{{url('/empresas')}}" role="button">Voltar</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

</div>
@endsection