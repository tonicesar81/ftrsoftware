@extends('layouts.app')
@section('content')

<?php
foreach ($pavimentos as $p) {
    $pavimento[$p->id] = $p->pavimento;
    $shopping = $p->shoppings_id;
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
    {!! Form::open(['action' => ['SetoresController@update' , $setor->id], 'method' => 'put' ]) !!}
    <div class="form-row">
        <div class="form-group col-6">
            {!! Form::label('pavimentos_id', 'Pavimento') !!}
            {!! Form::select('pavimentos_id',$pavimento ,$setor->pavimentos_id, ['class' => 'form-control']) !!}
        </div>
         <div class="form-group col-6">
            {!! Form::label('setor', 'Setor') !!}
            {!! Form::text('setor',$setor->setor ,['class' => 'form-control']) !!}
            
        </div>
        <div class="form-group col-12">
            {!! Form::hidden('shopping', $shopping) !!}
            {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection