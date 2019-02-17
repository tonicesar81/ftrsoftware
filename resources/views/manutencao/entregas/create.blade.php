@extends('layouts.app')
@section('content')

<?php
foreach ($itens as $i) {
    $item[$i->id] = $i->item;
}
?>
<div class="container">
    @if(session('message'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {!! Session::get('message') !!}
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
    {!! Form::open(['action' => 'EntregasController@store']) !!}
    <div class="form-row">
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('titulo', 'Título do Relatório') !!}
            {!! Form::text('titulo',null ,['class' => 'form-control text-uppercase', 'placeholder' => 'RELATÓRIO DE ENTREGA DE OBRA']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('dt_entrega', 'Data de Entrega') !!}
            {!! Form::date('dt_entrega', \Carbon\Carbon::now(), ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('contratante', 'Contratante') !!}
            {!! Form::text('contratante',null ,['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('orcamento', 'Número do orçamento') !!}
            {!! Form::text('orcamento',null ,['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('endereco', 'Endereço') !!}
            {!! Form::text('endereco',null ,['class' => 'form-control']) !!}
        </div>
        
        <div id ="servicos" class="form-group col-12"></div>
        <div id="loader" class="form-group col-12"></div>
        
        <div class="form-group col-5 offset-md-3">
            <small id="servicos_helper" class="form-text text-muted">
                Escolha um serviço na lista e clique em "Adicionar +" para acrescentar ao manual
            </small>
            {!! Form::select('e_servicos',$item ,null, ['class' => 'form-control', 'id' => 'select_item', 'placeholder' => 'Escolha um serviço']) !!}
        </div>
        <div class="form-group col-1">
            <br>
            {!! Form::button('Adicionar +', ['class' => 'btn btn-primary', 'id' => 'add_item']); !!}    
        </div>
        <div class="form-group col-12">
            {!! Form::button('Salvar', ['type' => 'submit', 'class' => 'btn btn-primary', 'value' => 'salva', 'name' => 'action']); !!}
            {!! Form::button('Salvar e Inserir fotos', ['type' => 'submit', 'class' => 'btn btn-primary', 'value' => 'foto', 'name' => 'action']); !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
<script>
    $( "#add_item" ).click(function() {
        $('#loader').html('<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Carregando...</div></div>');
        var item = $('#select_item').val();
        var itemName = $('#select_item :selected').text();
        console.log(itemName);
        var campos = '<div class="form-group col-6 offset-md-3"><label>Serviço</label><input type="text" name="nome[]" value="'+itemName+'" class="form-control"></div>';
        campos += '<input type="hidden" name="servico[]" value="'+item+'" >';
        $('#servicos').append(campos);
        $('#loader').html('');
    });

    function pegaTexto(item){
        var txt = $('#txt_'+item).val();
        $('#svc_'+item).val(txt +'||'+ item + '||');
    }
    function pegaImagem(item){
        var file = $('#imagem_'+item)[0].files[0];
        var txt = $('#txt_'+item).val();
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
            var img = reader.result;
            $('#imgs_'+item).append('<div class="form-group col-4"><img src="'+img+'" class="img-fluid" /></div>');
            $('#svc_'+item).val(txt +'||'+ item + '||' + img);
        };
        
    }
    function pegaLeg(item){
        var leg = $('#leg_'+item).val();
        $('#svc_'+item).val($('#svc_'+item).val()+'||'+leg);
    }
</script>    
@endsection