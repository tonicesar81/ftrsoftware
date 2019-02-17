@extends('layouts.app')
@section('content')

<?php
//var_dump($itens);
//exit();
foreach ($pavimentos as $p) {
    $pavimento[$p->id] = $p->pavimento;
}
if(!isset($pavimento)){
    $pavimento = ['0' => 'Nenhum pavimento cadastrado'];
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
    {!! Form::open(['action' => 'EstruturasController@store']) !!}
    <div class="form-row">
        <div class="form-group col-6">
            {!! Form::label('shopping', 'Shopping') !!}
            {!! Form::text('shopping', $shopping->shopping, ['class' => 'form-control-plaintext', 'readonly' => true]) !!}
        </div>
        <div class="form-group col-6">
            {!! Form::label('endereco', 'Endereço') !!}
            {!! Form::text('endereco', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::label('abastecimento', 'Descrição do primeiro abastecimento') !!}
            {!! Form::textarea('abastecimento', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::label('abastecimento', 'Descrição do primeiro abastecimento') !!}
            {!! Form::textarea('abastecimento', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6">
            {!! Form::label('pavimentos_id', 'Localização do Abastecimento de água') !!}
            {!! Form::select('pavimentos_id',$pavimento ,null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::label('reservatorio', 'Reservatório de água') !!}
            {!! Form::textarea('reservatorio', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::label('fonte', 'Fontes de Energia') !!}
            {!! Form::textarea('fonte', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::label('cmi', 'CMI - Funcionamento dos Abastecimentos de Água e Meios de Acionamento') !!}
            {!! Form::textarea('cmi', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::hidden('shoppings_id', $shopping->id) !!}
            {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
<script>
//    var tb = document.createElement('script');
//    tb.src = "{{ url('/js/textboxio/textboxio.js') }}";
//    document.getElementsByTagName('head')[0].appendChild(tb);
    
//    var editor = textboxio.replace('#abastecimento');
    var editors = textboxio.replaceAll( 'textarea' );
</script>    
@endsection