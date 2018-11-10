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
    Observações
  </div>
  <div class="card-body">
      <a class="btn btn-primary" href="{{url('/analise/obs/create')}}" role="button">Criar Observação</a>
      <hr>
    <table class="table table-bordered table-sm">
        <thead>
        <th>Item para análise</th>
        <th>Observação</th>
        <th>Figura</th>
        <th></th>
        </thead>
        <tbody>
            @foreach($lista_analises as $obs)
            <tr>
                <td>{{ $obs->tipo_relatorio }} - {{ $obs->item }}</td>
                <td>{{ $obs->lista_analise }}</td>
                <td>
                    @if($obs->figura != '')
                    <img src='{{ asset('storage/'.$obs->figura) }}' style='max-width:200px;max-height:100px;' >
                        
                    @endif
                </td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="btn btn-outline-primary btn-sm" href="{{url('/analise/obs/edit/'.$obs->id)}}" role="button" data-toggle="tooltip" data-placement="left" title="Modificar"><i class="fas fa-edit"></i></a>
                        {!! Form::open(['action' => ['ListaAnalisesController@destroy', $obs->id], 'method' => 'delete']) !!}
                        {!! Form::button('<i class="fas fa-trash-alt"></i>', ['class' => 'btn btn-outline-danger btn-sm', 'onclick' => 'return confirm(\'Você tem certeza?\')', 'type' => 'submit', 'data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'Apagar']) !!}
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