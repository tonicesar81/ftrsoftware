@extends('layouts.app')
@section('content')

<?php
//var_dump($itens);
//exit();
foreach ($shoppings as $s) {
    $shopping[$s->id] = $s->shopping;
}
//foreach ($itens as $i) {
//    $item[$i->id] = $i->item;
//}
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
    {!! Form::open(['action' => 'Man_relatoriosController@store']) !!}
    <div class="form-row">
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('nome', 'Nome / Título do relatório') !!}
            {!! Form::text('nome', null, ['class' => 'form-control text-uppercase']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('shoppings_id', 'Nome do Cliente(Empresa)') !!}
            {!! Form::select('shoppings_id',$shopping ,null, ['class' => 'form-control', 'id' => 'select-shopping']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('endereco', 'Endereço') !!}
            {!! Form::text('endereco', null, ['class' => 'form-control text-uppercase']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('desc_servico', 'Descrição dos Serviços') !!}
            {!! Form::text('desc_servico', null, ['class' => 'form-control text-uppercase']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('mes_vistoria', 'Mês da Vistoria') !!}
            {!! Form::date('mes_vistoria', \Carbon\Carbon::now(), ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('numero', 'Número do Orçamento') !!}
            {!! Form::text('numero', null, ['class' => 'form-control text-uppercase']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('contratante', 'Supervisor Contratante') !!}
            {!! Form::text('contratante', null, ['class' => 'form-control text-uppercase']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('contratada', 'Supervisor Contratada') !!}
            {!! Form::text('contratada', null, ['class' => 'form-control text-uppercase']) !!}
        </div>
        @foreach($itens as $item)
        <div class="form-check col-6">
            {!! Form::checkbox('item[]', $item->id, false, ['class' => 'form-check-input'] ) !!}
            {!! Form::label('item', $item->item, ['class' => 'form-check-label']) !!}            
        </div>
        <div class="form-group col-6">
            {!! Form::text('itemObs['.$item->id.']', null, ['class' => 'form-control text-uppercase', 'placeholder' => 'Observações']) !!}
        </div>
        @endforeach
        <div class="form-group col-12">
            {!! Form::label('descricao', 'Descrição do Relatório') !!}
            {!! Form::textarea('descricao', null, ['class' => 'form-control', 'rows' => '20', 'id' => 'summernote']) !!}
        </div>
        <div id="vistorias" class="form-group col-12">
            
        </div>
        <div class="form-group col-12">
            {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
<script>
//    var editors = textboxio.replaceAll( 'textarea' );
    $( ".form-check-input" ).click(function() {
        if($(this).is(":checked")){
            var shopping = $('#select-shopping').val();
            var item = $(this).val();
            $.get("{{ url('manutencao/relatorios/instalacao/') }}/"+shopping+"/"+item, function (data) {
                    $("#vistorias").append(data);
                });
        }else{
            $("#item_"+$(this).val()).remove();
        }
    });
       
    function pegaImagem(item){
        var file = $('#imagem_'+item)[0].files[0];
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
            var img = reader.result;
            $('#imgs_'+item).append('<div class="form-group col-4"><a class="btn btn-outline-primary btn-sm" href="#imgs_'+item+'" role="button" onclick="$(this).parent().remove()" data-html2canvas-ignore="true">X</a><img src="'+img+'" class="img-fluid" /><input type="hidden" name="imgs['+item+'][]" value="'+img+'" /><input type="text" class="form-control" id="obs[]" name="obs['+item+'][]" placeholder="Escreva uma observação"></div>');
        };
    }
</script>    
@endsection