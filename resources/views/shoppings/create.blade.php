@extends('layouts.app')

@section('content')
<?php
foreach ($empresas as $e) {
    $empresa[$e->id] = $e->empresa;
}
?>
<?php
foreach ($users as $u) {
    $user[$u->id] = $u->name;
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
            Novo Shopping
        </div>
        <div class="card-body">
            {!! Form::open(['action' => 'ShoppingsController@store']) !!}
            <div class="form-row">
                <div class="form-group col-md-6">
                    {!! Form::label('empresas_id', 'Rede') !!}
                    {!! Form::select('empresas_id',$empresa ,null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label('shopping', 'Shopping/Cliente') !!}
                    {!! Form::text('shopping', null, ['class' => 'form-control']); !!}
                </div>
                
                <div class="form-group col-12">
                    {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
                    <a class="btn btn-secondary" href="{{url('/shoppings')}}" role="button">Voltar</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
    
@endsection