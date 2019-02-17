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
    {!! Form::open(['action' => 'TermosController@store']) !!}
    <div class="form-row">
        <div class="form-group col-8 offset-md-2">
            {!! Form::label('man_itens_id', 'Sistema') !!}
            {!! Form::select('man_itens_id',$sistema ,null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6 offset-md-2">
            {!! Form::label('termo', 'Item') !!}
            {!! Form::text('item[]',null ,['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-2">
            {!! Form::label('verificacao', 'Tipo de verificação') !!}
            {!! Form::select('verificacao[]',['v' => 'Visual', 'f' => 'Funcionamento'] ,null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-12" id="itens">
            
        </div>
        <div class="form-group col-6 offset-md-2">
            {!! Form::button('+ Adicionar mais itens', ['class' => 'btn btn-primary', 'onClick' => 'addItem();']); !!}
        </div>
        <div class="form-group col-12">
            {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
<script>
    function addItem(){
        $('#itens').before('<div class="form-group col-6 offset-md-2">{!! Form::text("item[]",null ,["class" => "form-control"]) !!}</div><div class="form-group col-2">{!! Form::select("verificacao[]",["v" => "Visual", "f" => "Funcionamento"] ,null, ["class" => "form-control"]) !!}</div>');
    }
</script>    
@endsection
