@extends('layouts.app')
@section('content')

<?php
foreach ($itens as $i) {
    $item[$i->id] = $i->item;
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
    {!! Form::open(['action' => ['Man_descController@update', $desc->id], 'method' => 'put' ]) !!}
    <div class="form-row">
        <div class="form-group col-6">
            {!! Form::label('man_itens_id', 'Item de manutenção') !!}
            {!! Form::select('man_itens_id',$item ,$desc->man_itens_id, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6">
            {!! Form::label('descricao', 'Descrição / Recomendação') !!}
            {!! Form::text('descricao', $desc->descricao, ['class' => 'form-control text-uppercase']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection