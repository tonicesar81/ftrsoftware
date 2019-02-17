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
        <th>Pavimento</th>
        <th>Setor <a href="{{url('/manutencao/setores/create/'.$shopping)}}" role="button" class="btn btn-sm btn-outline-secondary" title="Novo Setor"><i class="fas fa-plus"></i></a></th>
        <th></th>
        </thead>
        <tbody>
            @foreach($setores as $setor)
            <tr>
                <td>{{ $setor->pavimento }}</td>
                <td>{{ $setor->setor }}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="btn btn-outline-primary btn-sm" href="{{url('/manutencao/setores/edit/'.$setor->id)}}" role="button" data-toggle="tooltip" data-placement="left" title="Modificar"><i class="fas fa-edit"></i></a>
                        {!! Form::open(['action' => ['SetoresController@destroy', $setor->id], 'method' => 'delete']) !!}                        
                        {!! Form::button('<i class="fas fa-trash-alt"></i>', ['class' => 'btn btn-outline-danger btn-sm', 'onclick' => 'return confirm(\'Deseja apagar esse setor? Essa ação não poderá ser desfeita.\')', 'type' => 'submit', 'data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'Apagar']) !!}
                        {!! Form::close() !!}
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection