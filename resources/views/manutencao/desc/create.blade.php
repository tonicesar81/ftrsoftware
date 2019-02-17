@extends('layouts.app')
@section('content')

<?php
//var_dump($itens);
//exit();
foreach ($itens as $i) {
    $item[$i->id] = $i->item;
}
?>
<div class="container">
    @if(isset($message))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {!! $message !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>   
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    {!! Form::open(['action' => 'Man_descController@store']) !!}
    <div class="form-row">
        <div class="form-group col-6">
            {!! Form::label('man_itens_id', 'Item de manutenção') !!}
            {!! Form::select('man_itens_id',$item ,(isset($last_item)) ? $last_item : null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6">
            {!! Form::label('descricao', 'Descrição / Recomendação') !!}
            {!! Form::text('descricao', null, ['class' => 'form-control text-uppercase']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::button('Salvar e voltar para lista', ['type' => 'submit', 'class' => 'btn btn-primary', 'value' => 'salva', 'name' => 'action']); !!}
            {!! Form::button('Salvar e continuar inserindo', ['type' => 'submit', 'class' => 'btn btn-primary', 'value' => 'continua', 'name' => 'action']); !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection