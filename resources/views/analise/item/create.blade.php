@extends('layouts.app')
@section('content')

<?php
foreach ($tipo_relatorios as $t) {
    $tipo_relatorio[$t->id] = $t->tipo_relatorio;
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
    {!! Form::open(['action' => 'ItensController@store']) !!}
    <div class="form-row">
        <div class="form-group col-6">
            {!! Form::label('tipo_relatorios_id', 'Tipo de relatório') !!}
            {!! Form::select('tipo_relatorios_id',$tipo_relatorio ,null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6">
            {!! Form::label('item', 'Item') !!}
            {!! Form::text('item', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection