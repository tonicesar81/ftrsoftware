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
    {!! Form::open(['action' => 'EntregasController@storeServ']) !!}
    <div class="form-row">
        <div class="form-group col-6 offset-md-3">
            {!! Form::select('man_itens_id',$item ,null, ['class' => 'form-control', 'id' => 'select_item', 'placeholder' => 'Escolha um serviço']) !!}
        </div>
<div class="form-group col-6">
    {!! Form::label('antes', 'Foto de Antes') !!}
    {!! Form::file('img', ['class' => 'form-control-file imagem', 'id' => 'imagem_antes', 'onchange' => "pegaImagem('antes')"]) !!}
</div>
<div class="form-group col-6">
    {!! Form::label('depois', 'Foto de Depois') !!}
    {!! Form::file('img', ['class' => 'form-control-file imagem', 'id' => 'imagem_depois', 'onchange' => "pegaImagem('depois')"]) !!}
</div>  
<div id="imgs_antes" class="form-group col-6">
    
</div>
<div id="imgs_depois" class="form-group col-6">
    
</div>        
<div class="form-group col-6">
    {!! Form::label('desc', 'Legenda da foto/Descrição do serviço(antes)') !!}
    {!! Form::text('nome_antes',null ,['class' => 'form-control', 'placeholder' => 'Descreva']) !!}
</div>


<div class="form-group col-6">
    {!! Form::label('desc', 'Legenda da foto/Descrição do serviço(depois)') !!}
    {!! Form::text('nome_depois',null ,['class' => 'form-control', 'placeholder' => 'Descreva']) !!}
</div>


<div>
    {!! Form::hidden('entregas_id', $entrega) !!}
    {!! Form::hidden('antes', 'null', ['id' => 'svc_antes']) !!}
    {!! Form::hidden('depois', 'null', ['id' => 'svc_depois']) !!}
</div>
<div class="form-group col-12">
    {!! Form::button('Salvar', ['type' => 'submit', 'class' => 'btn btn-primary', 'value' => 'salva', 'name' => 'action']); !!}
    {!! Form::button('Salvar e continuar', ['type' => 'submit', 'class' => 'btn btn-primary', 'value' => 'continua', 'name' => 'action']); !!}
</div>        
</div>
    {!! Form::close() !!}
</div>
<script>
    function pegaImagem(item){
        var file = $('#imagem_'+item)[0].files[0];
//        var txt = $('#txt_'+item).val();
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
            var img = reader.result;
            $('#imgs_'+item).html('<img src="'+img+'" class="img-fluid" />');
            $('#svc_'+item).val(img);
        };
        
    }
</script>
@endsection