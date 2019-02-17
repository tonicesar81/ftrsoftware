@extends('layouts.app')
@section('content')

<?php
//var_dump($itens);
//exit();
foreach ($shoppings as $s) {
    $shopping[$s->id] = $s->shopping;
}
foreach ($itens as $i) {
    $item[$i->id] = $i->item;
}
foreach ($pavimentos as $p){
    $pavimento[$p->id] = $p->pavimento;
}
$setor = ['Sem setor'];
foreach ($setores as $st){
    $setor[$st->id] = $st->setor;
}
//if(!isset($setor)){
//    $setor = ['Sem setor'];
//}
//$pavimento = ['Selecione um shopping'];
//$setor = ['Sem setor'];
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
    {!! Form::open(['action' => ['InstalacoesController@update' , $instalacao->id], 'method' => 'put' ]) !!}
    <div class="form-row">
        <div class="form-group col-6">
            {!! Form::label('man_itens_id', 'Item instalado') !!}
            {!! Form::select('man_itens_id',$item ,$instalacao->man_itens_id, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6">
            {!! Form::label('shoppings_id', 'Shopping') !!}
            {!! Form::select('shoppings_id',$shopping ,$instalacao->shoppings_id, ['class' => 'form-control shopping_select']) !!}
        </div>
        <div class="form-group col-4">
            {!! Form::label('numero', 'Número') !!}
            {!! Form::number('numero', $instalacao->numero, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-4">
            {!! Form::label('pavimentos_id', 'Pavimento') !!}
            {!! Form::select('pavimentos_id',$pavimento ,$instalacao->pavimentos_id, ['class' => 'form-control pavimento_select']) !!}
        </div>
        <div class="form-group col-4">
            {!! Form::label('setores_id', 'Setor') !!}
            {!! Form::select('setores_id',$setor ,$instalacao->setores_id, ['class' => 'form-control setor_select']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
<script>
    $( ".shopping_select" ).change(function() {
        var s = $(".shopping_select").val();
        $(".pavimento_select").load("{{ url('/manutencao/instalacoes/includes/pavimento/') }}/"+s);
        $(".pavimento_select").change();
      });
    $( ".pavimento_select" ).change(function() {
        var p = $(".pavimento_select").val();
        $(".setor_select").load("{{ url('/manutencao/instalacoes/includes/setor/') }}/"+p);
        
      });  
</script>    
@endsection