@extends('layouts.app')
@section('content')
<?php
foreach ($grupos as $g) {
    $grupo[$g->id] = $g->grupo;
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
    <div class="card">
  <div class="card-header">
    Disciplinas
  </div>
  <div class="card-body">
    {!! Form::open(['action' => 'TipoRelatoriosController@store']) !!}
    <div class="form-row">
        <div class="form-group col-6">
            {!! Form::label('tipo_relatorio', 'Nome do relatório') !!}
            {!! Form::text('tipo_relatorio',null ,['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-6">
            {!! Form::label('ref', 'Nome de referência no arquivo') !!}
            {!! Form::text('ref',null ,['class' => 'form-control text-uppercase']) !!}
            <small id="inputHelpBlock" class="form-text text-muted">
                Se nenhum valor for informado, o nome será os 3(três) primeiros caracteres do nome do sistema
            </small>
        </div>
        <div class="form-group col-6">
            {!! Form::label('grupos_id', 'Grupo') !!}
            {!! Form::select('grupos_id',$grupo ,null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::label('detalhamento', 'Detalhamento') !!}
            <div class="alert alert-warning">
                DICA: Utilize as seguintes variáveis padrões se achar necessário. Atenção! A seguinte regra precisa ser obedecida ( Em maiúsculo e entre chaves {DISCIPLINA}, {LOJA}, {SHOPPING}, {EMPRESA} )
            </div>
            {!! Form::textarea('detalhamento',null ,['class' => 'form-control summernote']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
        </div>
    </div>
    {!! Form::close() !!}
  </div>
    </div>
</div>
@endsection