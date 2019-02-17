@extends('layouts.app')
@section('content')

<?php
    $cap[0] = 'Nenhum';
foreach ($capitulos as $c) {
    $cap[$c->capitulo] = $c->capitulo.'. '.$c->titulo;
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
    {!! Form::open(['action' => ['ManualCapitulosController@update' , $capitulo->id], 'method' => 'put' ]) !!}
    <div class="form-row">
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('sistema', 'Sistema instalado') !!}
            {!! Form::text('sistema', $item->item, ['class' => 'form-control-plaintext', 'readonly' => true]) !!}
        </div>
        
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('titulo', 'Título do capítulo') !!}
            {!! Form::text('titulo', $capitulo->titulo, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('capitulo', 'Número do capítulo') !!}
            {!! Form::number('capitulo', $capitulo->capitulo, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6 offset-md-3">
            {!! Form::label('manual_capitulos_id', 'Capítulo pertencente (SE for um sub-capítulo)') !!}
            {!! Form::select('manual_capitulos_id',$cap ,$capitulo->manual_capitulos_id, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::label('conteudo', 'Conteúdo') !!}
            {!! Form::textarea('conteudo',$capitulo->conteudo ,['class' => 'form-control', 'id' => 'summernote']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::hidden('man_itens_id', $item->id) !!}
            {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            lang: 'pt-BR',
            minHeight: 300
        });
    });
</script>
@endsection