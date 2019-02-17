@extends('layouts.app')
@section('content')

<?php
//var_dump($itens);
//exit();
foreach ($pavimentos as $p) {
    $pavimento[$p->id] = $p->pavimento;
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
    {!! Form::open(['action' => 'SetoresController@store']) !!}
    <div class="form-row">
        <div class="form-group col-6">
            {!! Form::label('pavimentos_id', 'Pavimento') !!}
            {!! Form::select('pavimentos_id',$pavimento ,null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6">
            {!! Form::label('setor', 'Setor') !!}
            {!! Form::text('setor', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::hidden('shopping', $shopping) !!}
            {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection