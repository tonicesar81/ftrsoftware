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
    Arquivos de referência aprovados
  </div>
  <div class="card-body">
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <select class="form-control" id="select-shopping" onchange="if (this.value) window.location.href=this.value">
                    <option value="">Escolha um shopping</option>
                    @foreach($shoppings as $shopping)
                    <option value="{{ url('/arquivos/'.$shopping->id) }}">{{$shopping->shopping}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12">
            @if(isset($shopping_select))
            <h6><a href="{{ url('/arquivos/') }}"><i class="fas fa-home"></i></a> > <a href="{{ url('/arquivos/'.$shopping_select->id) }}"><i class="fas fa-folder-open"></i> {{ $shopping_select->shopping }}</a> > <i class="fas fa-folder-open"></i> {{ $loja }}</h6>
            @endif
            @if($arquivos->isEmpty())
            <div class="alert alert-info" role="alert">
                Não há arquivos disponíveis
            </div>
            @else
            <table class="table table-bordered table-hover table-sm">
                <thead>
                @if(Auth::user()->user_levels_id <= 3)
                <th>Ações</th>
                @endif
                <th>Arquivo</th>
                <th>Tamanho (KB)</th>
                <th>Data de Aprovação</th>
                </thead>
                <tbody>
                    @foreach($arquivos as $arquivo)
                    <tr>
                        @if(Auth::user()->user_levels_id <= 3)
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                {!! Form::open(['action' => ['ArquivosController@destroy', $arquivo->id], 'method' => 'delete']) !!}
                                {!! Form::button('<i class="fas fa-trash-alt"></i>', ['class' => 'btn btn-outline-danger btn-sm', 'onclick' => 'return confirm(\'Deseja apagar esse arquivo? Essa ação não poderá ser desfeita.\')', 'type' => 'submit', 'data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'Apagar']) !!}
                                {!! Form::close() !!}
                            </div>
                        </td>
                        @endif
                        <td><a href="{{ url('/arquivos/download/'.$arquivo->id) }}">{{ $arquivo->arquivo }}</a></td>
                        <td>{{ number_format(ceil((Storage::size('public/arquivos/'.$arquivo->hash) / 1024)),0,'','.') }} KB</td>
                        <td>{{ date('d/m/Y', strtotime($arquivo->dtRecebimento)) }}</td>
                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
  </div>
    </div>
</div>
@endsection