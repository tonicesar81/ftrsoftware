@extends('layouts.app')
@section('content')

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
    {!! Form::open(['action' => 'Man_itensController@store']) !!}
    <div class="form-row">
        <div class="form-group col-6">
            {!! Form::label('item', 'Item de Manutenção') !!}
            {!! Form::text('item',null ,['class' => 'form-control text-uppercase']) !!}
        </div>
        <div class="form-group col-6">
            {!! Form::label('norma', 'Norma') !!}
            {!! Form::text('norma',null ,['class' => 'form-control text-uppercase']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::label('texto', 'Texto Padrão') !!}
            {!! Form::textarea('texto', null, ['class' => 'form-control summernote', 'rows' => '20', 'id' => 'summernote']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::label('observacao', 'Observação') !!}
            {!! Form::textarea('observacao', null, ['class' => 'form-control summernote', 'rows' => '20', 'id' => 'summernote']) !!}
        </div>
        <div class="form-group col-12">
            {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection