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
$current = url()->current();
$array = explode('/', $current);
$action = (!in_array('revisao',$array))? 'update' : 'saveRevisao';
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

    {!! Form::open(['action' => ['RelatoriosController@'.$action, $relatorio->id], 'method' => 'put']) !!}
    <div class="form-row">
        <div class="form-group col-4">
            {!! Form::label('loja', 'Nome da loja') !!}
            {!! Form::text('loja', $relatorio->loja, ['class' => 'form-control text-uppercase']) !!}
        </div>
        <div class="form-group col-4">
            {!! Form::label('shoppings_id', 'Shopping') !!}
            {!! Form::select('shoppings_id',$shopping ,$relatorio->shoppings_id, ['class' => 'form-control', 'id' => 'select-shopping']) !!}
        </div>
        <div class="form-group col-4">
            {!! Form::label('id_arquivo', 'Identificação do arquivo') !!}
            {!! Form::text('id_arquivo', $relatorio->id_arquivo, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-12">
            @php $n = 0; @endphp
            @foreach($sistemas as $s)
            @php $n++; @endphp
            <table class="table" id="table_{{$n}}">
                <thead>
                    <tr class="bg-primary text-white">
                        <th  colspan="2" >{{$n}}.{!! $s['tipo_nome'] !!}
                        {!! Form::hidden('tipo_relatorios_id[]', $s['tipo'], ['class' => 'form-control']) !!}    
                         @if($n > 1)
                            <a class="btn btn-danger btn-sm" href="#" role="button" onclick="apaga({{$n}});">X</a>
                         @endif   
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $i=1;
                    $analise = explode(',',$relatorio->analise);
                    @endphp
                    @foreach($s['itens'] as $item)
                    @php
                     //$none = ($item->sts > 0)? '' : 'd-none';
                     if(($item->sts > 0) || ($item->comentario != null)){
                        $none = '';
                     }else{
                        $none = 'd-none';
                     }
                    @endphp
                    <tr>
                        <td width='60%'>
                            {{$n}}.{{$i}} - {!! $item->item !!} {{ ($item->id == 60) ? '(SOMENTE PARA SHOPPING TIJUCA)' : '' }}
                        </td>
                        <td>
                            {!! Form::radio('ok-'.$item->id, '0', (($item->sts > 0) || ($item->comentario != null))? false : true, ['onclick' => 'abreOk('.$item->id.')']) !!} OK
                            {!! Form::radio('ok-'.$item->id, '1', (($item->sts > 0) || ($item->comentario != null))? true : false, ['onclick' => 'abreNaoOk('.$item->id.')']) !!} NÃO OK
                            <div id='{{$item->id}}' class="bg-obs {{ $none }}">
                                @foreach($item->obs as $ob)
                                @php 
                                $check = (in_array($ob->id,$analise))? true : false;
                                @endphp
                                <div class="form-check">
                                {!! Form::checkbox('obs[]', $ob->id, $check, ['class' => 'form-check-input'])!!}
                                {!! Form::label('obs', $ob->lista_analise, ['class' => 'form-check-label']) !!}
                                </div>
                                @endforeach
                                <div class="form-group">
                                    @php $name = 'comm_'.$item->id; @endphp
                                    {!! Form::label('comm_'.$item->id , 'Outra observação') !!}
                                    {!! Form::textarea('comm_'.$item->id, $item->comentario, ['class' => 'form-control text-uppercase', 'rows' => '3']) !!}
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
            
            @endforeach
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
            {!! Form::textarea('adicional', $adicional, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-12">
            <div class="form-check">
                {!! Form::checkbox('ressalva', '1', (is_null($relatorio->ressalva)) ? false : true, ['class' => 'form-check-input'])!!}
                {!! Form::label('ressalva', 'Aprovar com ressalva', ['class' => 'form-check-label']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
<script>
    var inc = {{$n}};
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