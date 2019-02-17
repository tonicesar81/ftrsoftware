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
        <th>Cód.</th>
        <th>Instalação</th>
        <th>Pavimento</th>
        <th>Setor</th>
        <th></th>
        </thead>
        <tbody>
            @foreach($instalacoes as $instalacao)
            <tr>
                <td>{{ $instalacao->id }}</td>
                <td>{{ $instalacao->item }}{{ ($instalacao->numero != null)? '-'.$instalacao->numero : '' }}</td>
                <td>{{ $instalacao->pavimento }}</td>
                <td>{{ (is_null($instalacao->setor))? 'Sem setor' :  $instalacao->setor}}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="btn btn-outline-primary btn-sm" href="{{url('/manutencao/instalacoes/edit/'.$instalacao->id)}}" role="button" data-toggle="tooltip" data-placement="left" title="Modificar"><i class="fas fa-edit"></i></a>
                        {!! Form::open(['action' => ['InstalacoesController@destroy', $instalacao->id], 'method' => 'delete']) !!}                        
                        {!! Form::button('<i class="fas fa-trash-alt"></i>', ['class' => 'btn btn-outline-danger btn-sm', 'onclick' => 'return confirm(\'Ao apagar esse item, todas as dependências serão removidas. \n Deseja consinuar?\')', 'type' => 'submit', 'data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'Apagar']) !!}
                        {!! Form::close() !!}
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection