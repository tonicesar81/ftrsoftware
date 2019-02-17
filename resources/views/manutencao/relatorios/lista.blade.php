@extends('layouts.app')
@section('content')
@include('toolbar.tools')
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
        <th>Mês</th>
        <th></th>
        </thead>
        <tbody>
            @foreach($relatorios as $relatorio)
            <tr>
                <td>{{ $relatorio->id }}</td>
                <td>{{ date('m/Y', strtotime($relatorio->mes_vistoria)) }}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="btn btn-outline-primary btn-sm" href="{{url('/manutencao/relatorios/'.$relatorio->id)}}" role="button" data-toggle="tooltip" data-placement="left" title="Ver PDF" target='_blank'><i class="fas fa-file-pdf"></i></a>
                        <a class="btn btn-outline-primary btn-sm" href="{{url('/manutencao/relatorios/duplicar/'.$relatorio->id)}}" role="button" data-toggle="tooltip" data-placement="left" title="Cria um novo relatório com base neste relatório"><i class="fas fa-copy"></i></a>
                        <a class="btn btn-outline-primary btn-sm" href="{{url('/manutencao/relatorios/edit/'.$relatorio->id)}}" role="button" data-toggle="tooltip" data-placement="left" title="Modificar"><i class="fas fa-edit"></i></a>
                        {!! Form::open(['action' => ['Man_relatoriosController@destroy', $relatorio->id], 'method' => 'delete']) !!}                        
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