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
        <th>Contratante</th>
        <th>Data de Entrega</th>
        <th></th>
        </thead>
        <tbody>
            @foreach($entregas as $entrega)
            <tr>
                <td>{{ $entrega->contratante }}</td>
                <td>{{ date('d/m/Y', strtotime($entrega->dt_entrega)) }}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="btn btn-outline-primary btn-sm" href="{{url('/manutencao/entregas/pdf/'.$entrega->id)}}" role="button" data-toggle="tooltip" data-placement="left" title="Ver PDF" target='_blank'><i class="fas fa-file-pdf"></i></a>
                        <a class="btn btn-outline-primary btn-sm" href="{{url('/manutencao/entregas/servico/'.$entrega->id)}}" role="button" data-toggle="tooltip" data-placement="left" title="Inserir fotos" target='_blank'><i class="fas fa-images"></i></a>
                        {!! Form::open(['action' => ['EntregasController@destroy', $entrega->id], 'method' => 'delete']) !!}
                        {!! Form::button('<i class="fas fa-trash-alt"></i>', ['class' => 'btn btn-outline-danger btn-sm', 'onclick' => 'return confirm(\'Tem certeza que deseja apagar esse relatório? \n Essa ação não poderá ser desfeita.\')', 'type' => 'submit', 'data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'Apagar']) !!}
                        {!! Form::close() !!}
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection