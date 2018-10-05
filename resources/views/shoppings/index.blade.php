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
            Shoppings
        </div>
        <div class="card-body">

            <div class="row justify-content-between">
                <div class="col-md-4">
                    <a class="btn btn-primary" href="{{url('/shoppings/create')}}" role="button">+ Novo shopping</a>
                </div>
                <div class="col-md-4">
                    {!! Form::open(['action' => 'ShoppingsController@pesquisa']) !!}
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
            @if($shoppings->isEmpty())
            <div class="alert alert-primary" role="alert">
                Nenhum resultado encontrado
            </div>
            @else
            <table class="table table-bordered table-hover table-sm"  >
                <thead >
                    <tr class="bg-primary text-white">
                        <th scope="col" >Cód.</th>
                        <th scope="col" >Shopping/Cliente</th>
                        <th scope="col" >Rede</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shoppings as $shopping)
                    <tr>
                        <td>{{$shopping->id}}</td>
                        <td>{{$shopping->shopping}}</td>
                        <td>{{$shopping->empresa}}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a class="btn btn-outline-primary btn-sm" href="{{url('/shoppings/edit/'.$shopping->id)}}" role="button" data-toggle="tooltip" data-placement="left" title="Modificar"><i class="fas fa-edit"></i></a>
                                {!! Form::open(['url' => 'shoppings/'.$shopping->id, 'method' => 'delete']) !!}
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