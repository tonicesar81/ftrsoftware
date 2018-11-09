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
        <th>Tipo de Relatório</th>
        <th>Nome de referência</th>
        </thead>
        <tbody>
            @foreach($tipo_relatorios as $tipo)
            <tr>
                <td>{{ $tipo->tipo_relatorio }}</td>
                <td>{{ $tipo->ref }}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="btn btn-outline-primary btn-sm" href="{{url('/analise/sistema/edit/'.$tipo->id)}}" role="button" data-toggle="tooltip" data-placement="left" title="Modificar"><i class="fas fa-edit"></i></a>
                        {!! Form::open(['action' => ['TipoRelatoriosController@destroy', $tipo->id], 'method' => 'delete']) !!}
                        {!! Form::button('<i class="fas fa-trash-alt"></i>', ['class' => 'btn btn-outline-danger btn-sm', 'onclick' => 'return confirm(\'Ao apagar esse elemento, todas as dependências serão removidas. \n Deseja consinuar?\')', 'type' => 'submit', 'data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'Apagar']) !!}
                        {!! Form::close() !!}
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection