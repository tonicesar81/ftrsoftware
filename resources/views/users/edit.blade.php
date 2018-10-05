@extends('layouts.app')

@section('content')

<?php
foreach ($shoppings as $s) {
    $shopping[$s->id] = $s->shopping;
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
    <div class="card">
        <div class="card-header">
            Editar dados de {{$user->name}}
        </div>
        <div class="card-body">
            {!! Form::open(['action' => ['UsersController@update', $user->id], 'method' => 'put']) !!}
            <div class="form-row">
                <div class="col-md-6 offset-md-3">
                    {!! Form::label('name', 'Nome do Usuário (O que será exibido no sistema)') !!}
                    {!! Form::text('name', $user->name, ['class' => 'form-control']); !!}
                </div>

                <div class="col-md-6 offset-md-3">
                    {!! Form::label('username', 'Login') !!}
                    {!! Form::text('username', $user->username, ['class' => 'form-control']); !!}
                </div>        
                <div class="col-md-6 offset-md-3">
                    {!! Form::label('email', 'E-mail') !!}
                    {!! Form::email('email', $user->email, ['class' => 'form-control']); !!}
                </div>
                <div class="col-md-6 offset-md-3">
                    {!! Form::label('password', 'Senha') !!}
                    {!! Form::text('password', null, ['class' => 'form-control']); !!}
                </div>
                <div class="form-group col-md-6 offset-md-3">
                    <br>
                    Selecione um shopping no menu abaixo e clique em "Adicionar" para acrescentar a lista. Pode-se acrescentar 
                    quantos forem necessários
                </div>
                <div class="form-group col-md-4 offset-md-3">

                    {!! Form::select("sel_shopping",$shopping ,null, ["class" => "form-control mb-2 selectable"]) !!}
                </div>
                <div class="form-group col-md-2">
                    {!! Form::button('Adicionar shopping', ['class' => 'btn btn-primary', 'id' => 'shop_btn']); !!}
                </div>
                <div class="form-group col-md-6 offset-md-3" id="shoppings">
                    @foreach($tags as $tag)
                    <span class="badge badge-primary">{{ $tag->shopping }}<input name="shoppings[]" type="hidden" value="{{$tag->id}}">{!! Form::button("x", ["class" => "btn btn-sm btn-outline-info", "onClick" => "$(this).parent().remove();" ]); !!}</span>
                    @endforeach
                </div>

                <div class="form-group col-12">
                    {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
                    <a class="btn btn-secondary" href="{{url('/users')}}" role="button">Voltar</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

</div>
<script>
    $("#shop_btn").click(function () {
//        alert($(".selectable :selected").val());
        var s = $(".selectable :selected").val();
        $("#shoppings").append('<span class="badge badge-primary">' + $(".selectable :selected").text() + ' <input name="shoppings[]" type="hidden" value="' + s + '">{!! Form::button("x", ["class" => "btn btn-sm btn-outline-info", "onClick" => "$(this).parent().remove();" ]); !!}</span> ');
    });
</script>
@endsection