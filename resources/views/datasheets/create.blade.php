@extends('layouts.app')

@section('content')
<?php
foreach ($shoppings as $s) {
    $shopping[$s->id] = $s->shopping;
}
foreach ($nomes as $n) {
    $nome[$n->id] = $n->nome;
}
foreach ($tipos as $t) {
    $tipo[$t->id] = $t->tipo;
}
foreach ($locais as $l) {
    $local[$l->id] = $l->local;
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
            Datasheet - Cadastro
        </div>
        <div class="card-body">
            {!! Form::open(['action' => 'DatasheetsController@store']) !!}
            <div class="form-row">
                <div class="form-group col-md-4">
                    {!! Form::label('shoppings_id', 'Shopping') !!}
                    {!! Form::select("shoppings_id",$shopping ,null, ["class" => "form-control mb-2 selectable"]) !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('loja', 'Nome da loja') !!}
                    {!! Form::text('loja', null, ['class' => 'form-control']); !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('numero', 'Número de identificação') !!}
                    {!! Form::text('numero', null, ['class' => 'form-control']); !!}
                </div>
                
                <div id="equips" class="form-group col-12 row">
                    <hr />
                    <div class="form-group col-md-3">
                        {!! Form::label('quantidade', 'Quantidade') !!}
                        {!! Form::number('quantidade[]', null, ['class' => 'form-control']); !!}
                    </div>
                    <div class="form-group col-md-3">
                        {!! Form::label('dsnomes_id', 'Equipamento') !!}
                        {!! Form::select('dsnomes_id[]',$nome ,null, ['class' => 'form-control selectable']) !!}
                    </div>
                    <div class="form-group col-md-3">
                        {!! Form::label('dstipos_id', 'Tipo') !!}
                        {!! Form::select('dstipos_id[]',$tipo ,null, ['class' => 'form-control selectable']) !!}
                    </div>
                    <div class="form-group col-md-3">
                        {!! Form::label('dslocais_id', 'Localidade') !!}
                        {!! Form::select('dslocais_id[]',$local ,null, ['class' => 'form-control selectable']) !!}
                    </div>
                </div>
                <div class="form-group col-12">
                    {!! Form::button('+ Adicionar equipamento', ['class' => 'btn btn-primary', 'onclick' => 'addoption()']); !!}
                </div>
                <div class="form-group col-12">
                    {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
                    <a class="btn btn-secondary" href="{{ url()->previous() }}" role="button">Voltar</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<script>
    function addoption(){
        var campo = '<div class="form-group col-md-2">{!! Form::label("quantidade", "Quantidade") !!}{!! Form::number("quantidade[]", null, ["class" => "form-control"]); !!}</div>';
        campo += '<div class="form-group col-md-3">{!! Form::label("dsnomes_id", "Equipamento") !!}{!! Form::select("dsnomes_id[]",$nome ,null, ["class" => "form-control selectable"]) !!}</div>';
        campo += '<div class="form-group col-md-3">{!! Form::label("dstipos_id", "Tipo") !!}{!! Form::select("dstipos_id[]",$tipo ,null, ["class" => "form-control selectable"]) !!}</div>';
        campo += '<div class="form-group col-md-3">{!! Form::label("dslocais_id", "Localidade") !!}{!! Form::select("dslocais_id[]",$local ,null, ["class" => "form-control selectable"]) !!}</div>';
        campo += '{!! Form::button("x", ["class" => "btn btn-sm btn-outline-info", "onClick" => "$(this).parent().remove();" ]); !!}';
        $('#equips').append('<div class="form-group col-12 row">'+campo+'</div>');
    }
</script>    
@endsection