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
    <table class="table table-bordered table-sm">
        <thead>
        <th>Pavimento <a href="{{url('/manutencao/pavimentos/create')}}" role="button" class="btn btn-sm btn-outline-secondary" title="Novo Pavimento"><i class="fas fa-plus"></i></a></th>
        <th>Setores</th>
        <th></th>
        </thead>
        <tbody>
            @foreach($pavimentos as $pavimento)
            <tr>
                <td>{{ $pavimento->pavimento }}</td>
                <td><a href="{{ url('manutencao/setores/'.$shopping) }}">{{ $pavimento->sets }}</a></td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="btn btn-outline-primary btn-sm" href="{{url('/manutencao/pavimentos/edit/'.$pavimento->id)}}" role="button" data-toggle="tooltip" data-placement="left" title="Modificar"><i class="fas fa-edit"></i></a>
                        {!! Form::open(['action' => ['PavimentosController@destroy', $pavimento->id], 'method' => 'delete']) !!}                        
                        {!! Form::button('<i class="fas fa-trash-alt"></i>', ['class' => 'btn btn-outline-danger btn-sm', 'onclick' => 'return confirm(\'Ao apagar esse pavimento, todas as dependências serão removidas. \n Deseja consinuar?\')', 'type' => 'submit', 'data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'Apagar']) !!}
                        {!! Form::close() !!}
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection