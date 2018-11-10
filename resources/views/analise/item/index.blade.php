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
    Itens
  </div>
  <div class="card-body">
      <a class="btn btn-primary" href="{{url('/analise/item/create')}}" role="button">Criar item</a>
      <hr>
    <table class="table table-bordered table-sm">
        <thead>
        <th>Tipo de relatório</th>
        <th>Itens para análise</th>
        <th></th>
        </thead>
        <tbody>
            @foreach($itens as $item)
            <tr>
                <td>{{ $item->tipo_relatorio }}</td>
                <td>{{ $item->item }}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="btn btn-outline-primary btn-sm" href="{{url('/analise/item/edit/'.$item->id)}}" role="button" data-toggle="tooltip" data-placement="left" title="Modificar"><i class="fas fa-edit"></i></a>
                        {!! Form::open(['action' => ['ItensController@destroy', $item->id], 'method' => 'delete']) !!}
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