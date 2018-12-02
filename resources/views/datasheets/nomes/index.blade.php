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
            Datasheets - Nomes dos equipamentos
        </div>
        <div class="card-body">

            <div class="row justify-content-between">
                <div class="col-md-4">
                    <a class="btn btn-primary" href="{{url('/datasheets/nomes/create')}}" role="button">+ Novo equipamento</a>
                </div>
            </div>
            <hr>
            @if($nomes->isEmpty())
            <div class="alert alert-primary" role="alert">
                Nenhum resultado encontrado
            </div>
            @else
            <table class="table table-bordered table-hover table-sm"  >
                <thead >
                    <tr class="bg-primary text-white">
                        <th scope="col" >Disciplina</th>
                        <th scope="col" >Nome</th>
                        <th scope="col" >Nome no plural</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nomes as $nome)
                    <tr>
                        <td>{{$nome->ref}}</td>
                        <td>{{$nome->nome}}</td>
                        <td>{{$nome->nome_plural}}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a class="btn btn-outline-primary btn-sm" href="{{url('/datasheets/nomes/edit/'.$nome->id)}}" role="button" data-toggle="tooltip" data-placement="left" title="Modificar"><i class="fas fa-edit"></i></a>
                                {!! Form::open(['url' => 'datasheets/nomes/'.$nome->id, 'method' => 'delete']) !!}
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
            echo $shoppings->links();
            } catch (\Exception $e) {

            }
            @endphp
            @endif
        </div>
    </div>
</div>
@endsection