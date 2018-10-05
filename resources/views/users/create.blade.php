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
            Cadastrar usuário
        </div>
        <div class="card-body">
            {!! Form::open(['action' => 'UsersController@store']) !!}
            <div class="form-row">
                <div class="form-group col-md-6 offset-md-3">
                    {!! Form::label('name', 'Nome do Usuário (O que será exibido no sistema)') !!}
                    {!! Form::text('name', null, ['class' => 'form-control']); !!}
                </div>
                <div class="form-group col-md-6 offset-md-3">
                    {!! Form::label('username', 'Login') !!}
                    {!! Form::text('username', null, ['class' => 'form-control']); !!}
                </div>

                <div class="form-group col-md-6 offset-md-3">
                    {!! Form::label('email', 'E-mail') !!}
                    {!! Form::email('email', null, ['class' => 'form-control']); !!}
                </div>
                <div class="form-group col-md-6 offset-md-3">
                    {!! Form::label('password', 'Senha') !!}
                    {!! Form::text('password', null, ['class' => 'form-control']); !!}
                    <small id="inputHelpBlock" class="form-text text-muted">
                        Se nenhum valor for informado, a senha padrão será "123456"
                    </small>
                </div>
                <div class="form-group col-md-6 offset-md-3">
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
        $("#shoppings").append('<span class="badge badge-primary">'+$(".selectable :selected").text()+' <input name="shoppings[]" type="hidden" value="'+s+'">{!! Form::button("x", ["class" => "btn btn-sm btn-outline-info", "onClick" => "$(this).parent().remove();" ]); !!}</span> ');
    });
</script>
@endsection