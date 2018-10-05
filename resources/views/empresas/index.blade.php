@extends('layouts.app')
@section('content')
<div class="container">
    {{ Session::get('message') }}
    <div class="card">
        <div class="card-header">
            Empresas
        </div>
        <div class="card-body">
            <a class="btn btn-primary" href="{{url('/empresas/create')}}" role="button">Inserir nova rede</a>
            <hr>
            <table class="table table-bordered table-hover table-sm">
                <thead >
                    <tr class="bg-primary text-white">
                        <th scope="col">Empresa</th>
                        <th scope="col">Logo</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($empresas as $empresa)
                    <tr>
                        <td class="text-right"><img src="{{ asset('storage/'.$empresa->logo) }}" width="auto" height="25px" ></td>
                        <td class="align-middle" width='90%'>{{ $empresa->empresa }}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a class="btn btn-outline-primary btn-sm" href="{{url('/empresas/edit/'.$empresa->id)}}" role="button" data-toggle="tooltip" data-placement="left" title="Modificar"><i class="fas fa-edit"></i></a>
                                {!! Form::open(['url' => 'empresas/'.$empresa->id, 'method' => 'delete']) !!}
                                {!! Form::button('<i class="fas fa-trash-alt"></i>', ['class' => 'btn btn-outline-danger btn-sm', 'onclick' => 'return confirm(\'Ao excluir uma rede, todos os clientes vinculados ao mesmo, também serão excluídos. Deseja continuar?\')', 'type' => 'submit', 'data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'Apagar']) !!}
                                <!--<button class="btn btn-outline-danger btn-sm" type="submit" onClick="return confirm('Você tem certeza?')" name="name"></button>-->
                                {!! Form::close() !!}
                            </div>
                        </td>                
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection