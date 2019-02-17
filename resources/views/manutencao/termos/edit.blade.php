@extends('layouts.app')
@section('content')

<?php
foreach ($sistemas as $s) {
    $sistema[$s->id] = $s->item;
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
    {!! Form::open(['action' => ['TermosController@update', $termo->id], 'method' => 'put']) !!}
    <div class="form-row">
        <div class="form-group col-8 offset-md-2">
            {!! Form::label('man_itens_id', 'Sistema') !!}
            {!! Form::select('man_itens_id',$sistema ,$termo->man_itens_id, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6 offset-md-2">
            {!! Form::label('termo', 'Item') !!}
            {!! Form::text('item',$termo->item ,['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-2">
            {!! Form::label('verificacao', 'Tipo de verificação') !!}
            {!! Form::select('verificacao',['v' => 'Visual', 'f' => 'Funcionamento'] ,$termo->verificacao, ['class' => 'form-control']) !!}
        </div>
        
        <div class="form-group col-12">
            {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
  
@endsection
