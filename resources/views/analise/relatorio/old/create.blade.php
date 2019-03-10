@extends('layouts.app')

@section('content')
@include('toolbar.tools')
<?php
foreach ($shoppings as $s) {
    $shopping[$s->id] = $s->shopping;
}
foreach ($tipo_relatorios as $t) {
    $tipo_relatorio[$t->id] = $t->tipo_relatorio;
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

    {!! Form::open(['action' => 'RelatoriosController@store']) !!}
    <div class="form-row">
        <div class="form-group col-4">
            {!! Form::label('loja', 'Nome da loja') !!}
            {!! Form::text('loja', null, ['class' => 'form-control text-uppercase']) !!}
        </div>
        <div class="form-group col-4">
            {!! Form::label('shoppings_id', 'Shopping') !!}
            {!! Form::select('shoppings_id',$shopping ,null, ['class' => 'form-control', 'id' => 'select-shopping']) !!}
        </div>
        <div class="form-group col-4">
            {!! Form::label('id_arquivo', 'Identificação do arquivo') !!}
            {!! Form::text('id_arquivo', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-12">
            <table class="table">
                <thead>
                    <tr class="bg-primary text-white">
                        <th  colspan="2" >1. {!! $relatorio->tipo_relatorio !!}</th>
                        {!! Form::hidden('tipo_relatorios_id[]', $relatorio->id, ['class' => 'form-control']) !!}
                    </tr>
                </thead>
                <tbody>
                    @php
                    $i=1;
                    @endphp
                    @foreach($itens as $item)
                    <tr>
                        <td width='60%'>
                            1.{{$i}} - {!! $item->item !!} {{ ($item->id == 60) ? '(SOMENTE PARA SHOPPING TIJUCA)' : '' }}
                        </td>
                        <td>
                            {!! Form::radio('ok-'.$item->id, '0', true, ['onclick' => 'abreOk('.$item->id.')']) !!} OK
                            {!! Form::radio('ok-'.$item->id, '1', false, ['onclick' => 'abreNaoOk('.$item->id.')']) !!} NÃO OK
                            <div id='{{$item->id}}' class="bg-obs d-none">
                                @foreach($item->obs as $ob)
                                <div class="form-check">
                                {!! Form::checkbox('obs[]', $ob->id, false, ['class' => 'form-check-input'])!!}
                                {!! Form::label('obs', $ob->lista_analise, ['class' => 'form-check-label']) !!}
                                </div>
                                @endforeach
                                <div class="form-group">
                                    @php $name = 'comm_'.$item->id; @endphp
                                    {!! Form::label('comm_'.$item->id , 'Outra observação') !!}
                                    {!! Form::textarea('comm_'.$item->id, null, ['class' => 'form-control text-uppercase', 'rows' => '3']) !!}
                                </div>
                            </div>
                        </td>
                    </tr>
                    @php
                    $i++;
                    @endphp
                    @endforeach
                </tbody>
            </table>
            <div id="x-sys"></div>
            <div id="loader"></div>
            <hr />
            <div class="form-group col-12 form-row">
                <div class="col">                
                    {!! Form::select('tipos',$tipo_relatorio ,null, ['class' => 'form-control', 'id' => 'tipos']) !!}
                </div>
                <div class="col">
                    {!! Form::button('Adicionar sistema ao relatório +', ['class' => 'btn btn-primary', 'id' => 'add_tipo']); !!}    
                </div>
            </div>
        </div>
        <div class="form-group col-12">
            {!! Form::label('adicional', 'Comentários adicionais') !!}
            {!! Form::textarea('adicional', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-12">
            <div class="form-check">
                {!! Form::checkbox('ressalva', '1', false, ['class' => 'form-check-input'])!!}
                {!! Form::label('ressalva', 'Aprovar com ressalva', ['class' => 'form-check-label']) !!}
            </div>
        </div>
        <div class="form-group col-12">
            
            {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
        </div>
        
    </div>
    {!! Form::close() !!}
</div>
<script>
    var inc = 1;
    function abreNaoOk(id) {
        $('#' + id).css('display:block');
        $('#' + id).removeClass('d-none');
    }
    function abreOk(id) {
        $('#' + id).addClass('d-none');
        $('#' + id + ' input').prop('checked', false);
        $('#' + id + ' textarea').val('');
    }
    $( "#add_tipo" ).click(function() {
        $('#loader').html('<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Carregando...</div></div>');
        var tipo = $('#tipos').val();
        inc++;
        $.ajax({
            url: '{{url('/analise/relatorios/create')}}/' + tipo + '/' + inc
        }).done(function(data) {
            $('#loader').html('');
            $('#x-sys').append(data);
        });
        //alert( tipo );
        
      });
    function apaga(id){
        //alert('check');
        //$(this).parent().remove();
        $( "#table_"+id ).remove();
        inc--;
    }  
</script>
@endsection