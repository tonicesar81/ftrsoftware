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
    <div class="card">
        <div class="card-header">
            Usuários
        </div>
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col-md-4">
                    <a class="btn btn-primary" href="{{url('/users/create')}}" role="button">+ Novo usuário</a>
                </div>
                <div class="col-md-4">
                    {!! Form::open(['action' => 'UsersController@pesquisa']) !!}
                    <div class="form-row">
                        <div class="form-group col-10">
                            {!! Form::text('pesquisa', null, ['class' => 'form-control', 'placeholder' => 'Pesquisar...']); !!}
                        </div>
                        <div class="form-group col-2">
                            {!! Form::button('<i class="fas fa-search"></i>', ['class' => 'btn btn-outline-primary', 'title' => 'Pesquisar', 'type' => 'submit']); !!}                        
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <hr>
            @if($users->isEmpty())
            <div class="alert alert-primary" role="alert">
                Nenhum resultado encontrado
            </div>
            @else
            <table class="table table-bordered table-hover table-sm"  >
                <thead >
                    <tr class="bg-primary text-white">
                        <th scope="col" >Cód.</th>
                        <th scope="col" >Nome</th>
                        <th scope="col" >Shopping(s)</th>
                        <th scope="col" >Perfil</th>
                        <th scope="col" >Data de Cadastro</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td>
                            @foreach($user->shoppings as $s)
                            <span class="badge badge-primary">{{ $s->shopping }}</span>
                            @endforeach
                        </td>

                        <td></td>
                        <td>{{ date('d/m/Y', strtotime($user->created_at)) }}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a class="btn btn-outline-primary btn-sm" href="{{url('/users/edit/'.$user->id)}}" role="button" data-toggle="tooltip" data-placement="left" title="Modificar"><i class="fas fa-edit"></i></a>
                                {!! Form::open(['url' => '/users/'.$user->id, 'method' => 'delete']) !!}
                                {!! Form::button('<i class="fas fa-trash-alt"></i>', ['class' => 'btn btn-outline-danger btn-sm', 'onclick' => 'return confirm(\'Você tem certeza?\')', 'type' => 'submit', 'data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'Apagar']) !!}
                                <!--<button class="btn btn-outline-danger btn-sm" type="submit" onClick="return confirm('Você tem certeza?')" name="name"></button>-->
                                {!! Form::close() !!}
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @php
            try{
            echo $users->links();
            } catch (\Exception $e) {

            }
            @endphp
            @endif
        </div>
    </div>
</div>
@endsection