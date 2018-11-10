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
    Normas
  </div>
  <div class="card-body">
      <a class="btn btn-primary" href="{{url('/analise/normas/create')}}" role="button">Criar norma</a>
      <hr>
    <table class="table table-bordered table-sm">
        <thead>
        <th>Grupo</th>
        <th>Normas</th>
        <th>Descrição</th>
        <th></th>
        </thead>
        <tbody>
            @foreach($normas as $norma)
            <tr>
                <td>{{ $norma->grupo }}</td>
                <td>{{ $norma->norma }}</td>
                <td>{{ $norma->descricao }}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="btn btn-outline-primary btn-sm" href="{{url('/analise/normas/edit/'.$norma->id)}}" role="button" data-toggle="tooltip" data-placement="left" title="Modificar"><i class="fas fa-edit"></i></a>
                        {!! Form::open(['action' => ['NormasController@destroy', $norma->id], 'method' => 'delete']) !!}
                        {!! Form::button('<i class="fas fa-trash-alt"></i>', ['class' => 'btn btn-outline-danger btn-sm', 'onclick' => 'return confirm(\'Ao apagar esse elemento, todas as dependências serão removidas. \n Deseja continuar?\')', 'type' => 'submit', 'data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'Apagar']) !!}
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