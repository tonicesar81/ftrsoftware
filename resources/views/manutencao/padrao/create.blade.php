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
    Textos padrão para obras
  </div>
  <div class="card-body">
    {!! Form::open(['action' => 'ObrasTextosPadraoController@store']) !!}
    <div class="form-row">
        <div class="form-group col-12">
            {!! Form::label('introducao', 'Introdução') !!}
            {!! Form::textarea('introducao',(isset($padrao->introducao)) ? $padrao->introducao : '' ,['class' => 'form-control summernote']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::label('conclusao', 'Conclusão') !!}
            {!! Form::textarea('conclusao',(isset($padrao->conclusao)) ? $padrao->conclusao : '' ,['class' => 'form-control summernote']) !!}
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