@extends('layouts.app')
@section('content')

<?php
//var_dump($itens);
//exit();
//foreach ($shoppings as $s) {
//    $shopping[$s->id] = $s->shopping;
//}
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
    {!! Form::open(['action' => 'ObrasController@store']) !!}
    <div class="form-row">
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('nome', 'Nome / Título do relatório') !!}
            {!! Form::text('nome', null, ['class' => 'form-control text-uppercase']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('mes_vistoria', 'Mês da Vistoria') !!}
            {!! Form::date('mes_vistoria', \Carbon\Carbon::now(), ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('orcamento', 'Número do Orçamento e Versão') !!}
            {!! Form::text('orcamento', null, ['class' => 'form-control text-uppercase']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('cliente', 'Nome do Cliente(Empresa)') !!}
            {!! Form::text('cliente', null, ['class' => 'form-control']) !!}            
        </div>        
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('contratante', 'Supervisor Contratante') !!}
            {!! Form::select('contratante', $contratantes  ,null, ['placeholder' => 'Selecione ou digite e aperte ENTER', 'class' => 'form-control select-responsavel']) !!} 
            <input type="hidden" name="contato" id="contato" value = "" />
            <input type="hidden" name="assinatura" id="assinatura" value = "" />
        </div>
        <div class="form-group col-md-6 offset-md-3">
            {!! Form::label('email', 'E-mail') !!}
            {!! Form::email('email', null, ['id' => 'email', 'class' => 'form-control']); !!}
        </div>
        <div class="form-group col-md-6 offset-md-3">
            {!! Form::label('telefone', 'Telefone') !!}
            {!! Form::text('telefone', null, ['id' => 'telefone', 'class' => 'form-control tel']); !!}
        </div>
        <div class="form-group col-4 offset-md-3">           
            {!! Form::label('item', 'Selecione uma disciplina') !!}
            {!! Form::select('itens',$item  ,null, ['class' => 'form-control', 'id' => 'itens']) !!}            
        </div>
        <div class="form-group col-1">           
            {!! Form::label('qnt', 'Quantidade') !!}
            {!! Form::number('qnt',null, ['class' => 'form-control']) !!}            
        </div>
        <div class="form-group col-1">
            <br />
            {!! Form::button('Adicionar +', ['class' => 'btn btn-primary addItem']); !!}
        </div>        
        <div class="form-group col-12">
            {!! Form::label('introducao', 'Introdução') !!}
            {!! Form::textarea('introducao', (isset($padrao->introducao)) ? $padrao->introducao : '' , ['class' => 'form-control summernote', 'rows' => '20', 'id' => 'summernote']) !!}
        </div>
        <div id="vistorias" class="form-group col-12">
            
        </div>
        <div class="form-group col-12">
            {!! Form::label('conclusao', 'Conclusão') !!}
            {!! Form::textarea('conclusao', (isset($padrao->conclusao)) ? $padrao->conclusao : '', ['class' => 'form-control summernote', 'rows' => '20', 'id' => 'summernote']) !!}
        </div>
        <div class="form-check col-6">
            {!! Form::checkbox('gar', true, false, ['class' => 'form-check-input'] ) !!}
            {!! Form::label('gar', 'Emitir certificado de garantia', ['class' => 'form-check-label']) !!}            
        </div>
        <div class="alert alert-warning">
            DICA: Utilize as seguintes variáveis padrões se achar necessário. Atenção! A seguinte regra precisa ser obedecida ( Em maiúsculo e entre chaves {DISCIPLINA}, {CLIENTE}, {NUMERO} )
        </div>
        <div class="form-group col-12">
            {!! Form::label('garantia', 'Certificado de garantia') !!}
            {!! Form::textarea('garantia',(isset($certificados->garantia)) ? $certificados->garantia : '' ,['class' => 'form-control summernote']) !!}
        </div>
        <div class="form-check col-6">
            {!! Form::checkbox('res', true, false, ['class' => 'form-check-input'] ) !!}
            {!! Form::label('res', 'Emitir certificado de responsabilidade', ['class' => 'form-check-label']) !!}            
        </div>
        <div class="alert alert-warning">
            DICA: Utilize as seguintes variáveis padrões se achar necessário. Atenção! A seguinte regra precisa ser obedecida ( Em maiúsculo e entre chaves {DISCIPLINA}, {CLIENTE}, {NUMERO} )
        </div>
        <div class="form-group col-12">
            {!! Form::label('responsabilidade', 'Certificado de responsabilidade') !!}
            {!! Form::textarea('responsabilidade',(isset($certificados->responsabilidade)) ? $certificados->responsabilidade : '' ,['class' => 'form-control summernote']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
<script>
    $( ".select-responsavel" ).change(function() {
//        alert( "Handler for .change() called." );
        var contatos = {!! json_encode($contatos)  !!};
        var selected = $(".select-responsavel :selected").val();
        if(selected in contatos){
            $('#contato').val($(".select-responsavel :selected").text());
            $('#email').val(contatos[selected].email);
            $('#telefone').val(contatos[selected].telefone);
            $('#assinatura').val(contatos[selected].assinatura);
        }else{
            $('#contato').val($(".select-responsavel :selected").text());
            $('#email').val('');
            $('#telefone').val('');
            $('#assinatura').val('');
        }
        
    });
//    var editors = textboxio.replaceAll( 'textarea' );
//    $( ".form-check-input" ).click(function() {
//        if($(this).is(":checked")){
//            var shopping = 8;
//            var item = 5;
//            $.get("{{ url('manutencao/obras/disciplina/') }}/"+shopping+"/"+item, function (data) {
//                    $("#vistorias").append(data);
//                });
//        }else{
//            $("#item_"+$(this).val()).remove();
//        }
//    });
    
    $( ".addItem" ).click(function() {
        var item = $('#itens').val();
        var qnt = $('#qnt').val();
        $("#itens option[value='"+item+"']").remove();
        $.get("{{ url('manutencao/obras/disciplina/') }}/"+item+"/"+qnt, function (data) {
                    $("#vistorias").append(data);
                });
    });
    
    function removeItem(id, item){
        $('#item_'+id).remove();
        $('#itens').append('<option value="'+id+'">'+item+'</option>');
    }
    
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