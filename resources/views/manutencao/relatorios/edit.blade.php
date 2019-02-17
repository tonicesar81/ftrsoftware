@extends('layouts.app')
@section('content')
@include('toolbar.tools')
<?php
//var_dump($itens);
//exit();
foreach ($shoppings as $s) {
    $shopping[$s->id] = $s->shopping;
}
$serv = explode(';',$relatorio->tipo_servicos);
foreach($serv as $s){
    $servico[] = explode(':',$s);
}
//print_r($servico);
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
    {!! Form::open(['action' => ['Man_relatoriosController@update', $relatorio->id], 'method' => 'put']) !!}
    <div class="form-row">
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('nome', 'Nome / Título do relatório') !!}
            {!! Form::text('nome', $relatorio->nome, ['class' => 'form-control text-uppercase']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('shoppings_id', 'Nome do Cliente(Empresa)') !!}
            {!! Form::select('shoppings_id',$shopping ,$relatorio->shoppings_id, ['class' => 'form-control', 'id' => 'select-shopping']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('endereco', 'Endereço') !!}
            {!! Form::text('endereco', $relatorio->endereco, ['class' => 'form-control text-uppercase']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('desc_servico', 'Descrição dos Serviços') !!}
            {!! Form::text('desc_servico', $relatorio->desc_servico, ['class' => 'form-control text-uppercase']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('mes_vistoria', 'Mês da Vistoria') !!}
            {!! Form::date('mes_vistoria', $relatorio->mes_vistoria, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('numero', 'Número do Orçamento') !!}
            {!! Form::text('numero', $relatorio->numero, ['class' => 'form-control text-uppercase']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('contratante', 'Supervisor Contratante') !!}
            {!! Form::text('contratante', $relatorio->contratante, ['class' => 'form-control text-uppercase']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('contratada', 'Supervisor Contratada') !!}
            {!! Form::text('contratada', $relatorio->contratada, ['class' => 'form-control text-uppercase']) !!}
        </div>
        
        @foreach($itens as $item)
        <div class="form-check col-6">
            @php
                $item_in_array = in_array($item->id, array_column($servico, 0));
                if($item_in_array){
                    $v = true;
                    $ar = array_search($item->id, array_column($servico, 0));
                    @$o = $servico[$ar][1];
                }else{
                    $v = false;
                    $o = null;
                }
            @endphp
            {!! Form::checkbox('item[]', $item->id, $v, ['class' => 'form-check-input'] ) !!}
            {!! Form::label('item', $item->item, ['class' => 'form-check-label']) !!}            
        </div>
        <div class="form-group col-6">
            {!! Form::text('itemObs['.$item->id.']', $o, ['class' => 'form-control text-uppercase', 'placeholder' => 'Observações']) !!}
        </div>
        
        @endforeach
        <div class="form-group col-12">
            {!! Form::label('descricao', 'Descrição do Relatório') !!}
            {!! Form::textarea('descricao', $relatorio->descricao, ['class' => 'form-control', 'rows' => '20', 'id' => 'summernote']) !!}
        </div>
        <div id="vistorias" class="form-group col-12">
            @foreach($instalacoes as $instalacao)
            <div class="item_{{ $instalacao->item_id }}">
                
                <div class="form-group col-12">        
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr class="bg-primary text-white text-center">
                                <th scope="col">{{ $instalacao->item }}{{ ($instalacao->numero != null)? '-'.$instalacao->numero : '' }} ( Local: {{$instalacao->pavimento}}/{{$instalacao->setor}} )</th>
                                <th scope="col">SITUAÇÃO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($instalacao->visto as $desc)
                            <tr>
                                <td>{{$desc[0]}}</td>
                                <td>{!! Form::select('vistorias['.$instalacao->instal_id.'][]',[$desc[2].':0' => 'OK',$desc[2].':1' => 'NÃO OK',$desc[2].':2' => 'NÃO SE APLICA']  ,$desc[2].':'.$desc[1], ['class' => 'form-control form-control-sm']) !!}</td>                    
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="form-group col-6 offset-md-3">
                    {!! Form::label('imagem', 'Imagem') !!}
                    {!! Form::file('imagem', ['class' => 'form-control-file imagem', 'id' => 'imagem_'.$instalacao->instal_id, 'onchange' => 'pegaImagem('.$instalacao->instal_id.')']) !!}
                </div>
                <div id="imgs_{{ $instalacao->instal_id }}" class="form-row justify-content-center">
                    @foreach($instalacao->imagens as $imagem)    
                    <div class="form-group col-4">
                        <a class="btn btn-outline-primary btn-sm" href="#imgs_{{ $instalacao->instal_id }}" role="button" onclick="$(this).parent().remove()" data-html2canvas-ignore="true">X</a>
                        <img src="{{ $imagem[0] }}" class="img-fluid" /><input type="hidden" name="imgs[{{ $instalacao->instal_id }}][]" value="{{ $imagem[0] }}" />
                        <input type="text" class="form-control" id="obs[]" name="obs[{{ $instalacao->instal_id }}][]" placeholder="Escreva uma observação" value="{{ $imagem[1] }}">
                    </div>
                    @endforeach
                </div>
                
            </div>
            @endforeach

        </div>
        <div class="form-group col-12">
            {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
<!--<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            lang: 'pt-BR',
            minHeight: 300
        });
    });
</script>-->
<script>
    
    var editors = textboxio.replaceAll( 'textarea' );
    $( ".form-check-input" ).click(function() {
        if($(this).is(":checked")){
            var shopping = $('#select-shopping').val();
            var item = $(this).val();
            $.get("{{ url('manutencao/relatorios/instalacao/') }}/"+shopping+"/"+item, function (data) {
                    $("#vistorias").append(data);
                });
        }else{
            $("#item_"+$(this).val()).remove();
            $(".item_"+$(this).val()).remove();
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