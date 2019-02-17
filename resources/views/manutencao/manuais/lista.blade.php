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
        <th>Itens de manutenção</th>
        <th>Capítulos</th>
        <th></th>
        </thead>
        <tbody>
            @foreach($capitulos as $capitulo)
            <tr>
                <td>{{ $capitulo->item }} - {{ ($capitulo->manual_capitulos_id != 0) ? $capitulo->manual_capitulos_id.'.' : '' }}{{ $capitulo->capitulo }}. {{ $capitulo->titulo }}</td>
                <td>{{ $capitulo->titulo }}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="btn btn-outline-primary btn-sm" href="{{url('/manutencao/capitulos/edit/'.$capitulo->id)}}" role="button" data-toggle="tooltip" data-placement="left" title="Editar capítulo"><i class="fas fa-edit"></i></a>
                        {!! Form::open(['action' => ['ManualCapitulosController@destroy', $capitulo->id], 'method' => 'delete']) !!}
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