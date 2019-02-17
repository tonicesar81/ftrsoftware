@extends('layouts.app')
@section('content')

<?php
//var_dump($itens);
//exit();
foreach ($shoppings as $s) {
    $shopping[$s->id] = $s->shopping;
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
    {!! Form::open(['action' => ['PavimentosController@update' , $pavimento->id], 'method' => 'put' ]) !!}
    <div class="form-row">
        <div class="form-group col-6">
            {!! Form::label('shoppings_id', 'Shopping') !!}
            {!! Form::select('shoppings_id',$shopping ,$pavimento->shoppings_id, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6">
            {!! Form::label('pavimento', 'Pavimento') !!}
            {!! Form::text('pavimento', $pavimento->pavimento, ['class' => 'form-control']) !!}
        </div>
         <div class="form-group col-6">
            {!! Form::label('ordem', 'Ordem (Opcional)') !!}
            {!! Form::number('ordem', $pavimento->ordem, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection