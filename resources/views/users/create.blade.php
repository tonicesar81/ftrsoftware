@extends('layouts.app')

@section('content')

<?php
$current = url()->current();
$arr_route = explode('/', $current);

if(!in_array('funcionarios',$arr_route)){
   foreach ($shoppings as $s) {
    $shopping[$s->id] = $s->shopping;
   }
}else{
   foreach ($levels as $l) {
    $level[$l->id] = $l->nivel;
   }
   foreach ($tipo_relatorios as $t) {
       if($t->id == 3){
           $tipo_relatorio[$t->id] = 'SPK - EXTINTORES';
       }else{
           $tipo_relatorio[$t->id] = $t->ref;
       }
   }
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
            {!! Form::open(['action' => 'UsersController@store', 'files' => true]) !!}
            <div class="form-row">
                <div class="form-group col-md-6 offset-md-3">
                    {!! Form::label('name', 'Nome do Usuário (O que será exibido no sistema)') !!}
                    {!! Form::text('name', null, ['class' => 'form-control']); !!}
                </div>
                <div class="form-group col-md-6 offset-md-3">
                    {!! Form::label('username', 'Login') !!}
                    {!! Form::text('username', null, ['class' => 'form-control']); !!}
                </div>
                @if(in_array('funcionarios',$arr_route))
                {!! Form::hidden('funcionario', true); !!}
                <div class="form-group col-md-6 offset-md-3">
                    {!! Form::label('user_levels_id', 'Nível de acesso') !!}
                    {!! Form::select("user_levels_id",$level ,null, ["class" => "form-control"]) !!}
                </div>
                <div class="col-md-6 offset-md-3">
                    {!! Form::label('titulo', 'Título/cargo do usuário (O que será exibido abaixo da assinatura)') !!}
                    {!! Form::text('titulo', null, ['class' => 'form-control']); !!}
                </div>
                <div class="col-md-6 offset-md-3 form-group">
                    <br>
                    {!! Form::label('assinatura', 'Assinatura'); !!}
                    <br>
                    {!! Form::file('assinatura', null, ['class' => 'form-control-file']); !!}
                </div>
                @endif
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
                @if(!in_array('funcionarios',$arr_route))
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
                @else
                <div class="form-group col-md-6 offset-md-3">
                    Selecione abaixo uma disciplina que deseja receber notificações por e-mail em caso de envio de projetos para análise e clique em "Adicionar".
                    Pode-se acrescentar quantos forem necessários.
                    <br>
                    IMPORTANTE! É necessário ter um e-mail cadastrado para receber as notificações
                </div>
                <div class="form-group col-md-4 offset-md-3">
                    
                    {!! Form::select("sel_disciplinas",$tipo_relatorio ,null, ["class" => "form-control mb-2 sel_disc"]) !!}
                </div>
                <div class="form-group col-md-2">
                    {!! Form::button('Adicionar disciplina', ['class' => 'btn btn-primary', 'id' => 'disciplina_btn']); !!}
                </div>
                <div class="form-group col-md-6 offset-md-3" id="disciplinas">

                </div>
                @endif
                <div class="form-group col-12">
                    {!! Form::submit('Salvar', ['class' => 'btn btn-primary']); !!}
                    <a class="btn btn-secondary" href="{{url('/users')}}" role="button">Voltar</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

</div>
@if(!in_array('funcionarios',$arr_route))
<script>
    $("#shop_btn").click(function () {
//        alert($(".selectable :selected").val());
        var s = $(".selectable :selected").val();
        $("#shoppings").append('<span class="badge badge-primary">'+$(".selectable :selected").text()+' <input name="shoppings[]" type="hidden" value="'+s+'">{!! Form::button("x", ["class" => "btn btn-sm btn-outline-info", "onClick" => "$(this).parent().remove();" ]); !!}</span> ');
    });
</script>
@else
<script>
    $("#disciplina_btn").click(function () {
//        alert($(".selectable :selected").val());
        var s = $(".sel_disc :selected").val();
        $("#disciplinas").append('<span class="badge badge-primary">'+$(".sel_disc :selected").text()+' <input name="disciplinas[]" type="hidden" value="'+s+'">{!! Form::button("x", ["class" => "btn btn-sm btn-outline-info", "onClick" => "$(this).parent().remove();" ]); !!}</span> ');
    });
</script>
@endif
@endsection