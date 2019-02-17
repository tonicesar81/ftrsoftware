@extends('layouts.app')
@section('content')

<div class="container">
    @if(session('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
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
    <div class="card">
  <div class="card-header">
    Textos padrão para certificados
  </div>
  <div class="card-body">
    {!! Form::open(['action' => 'Obras_certificados_padraoController@store']) !!}
    <div class="form-row">
        <div class="alert alert-warning">
            DICA: Utilize as seguintes variáveis padrões se achar necessário. Atenção! A seguinte regra precisa ser obedecida ( Em maiúsculo e entre chaves {DISCIPLINA}, {CLIENTE}, {NUMERO} )
        </div>
        <div class="form-group col-12">
            {!! Form::label('garantia', 'Certificado de garantia') !!}
            {!! Form::textarea('garantia',(isset($certificados->garantia)) ? $certificados->garantia : '' ,['class' => 'form-control summernote']) !!}
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
    </div>
</div>
@endsection