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
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <select class="form-control" id="select-shopping" onchange="if (this.value) window.location.href=this.value">
                    <option value="">Escolha um shopping</option>
                    @foreach($shoppings as $shopping)
                    <option value="{{ url('/analise/projetos/'.$shopping->id) }}">{{$shopping->shopping}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12">
            @if(isset($shopping_select))
            <h6><a href="{{ url('/analise/projetos/') }}"><i class="fas fa-home"></i></a> > <a href="{{ url('/analise/projetos/'.$shopping_select->id) }}"><i class="fas fa-folder-open"></i> {{ $shopping_select->shopping }}</a> > <i class="fas fa-folder-open"></i> {{ $loja }}</h6>
            @endif
            @if($projetos->isEmpty())
            <div class="alert alert-info" role="alert">
                Não há arquivos disponíveis
            </div>
            @else
            <table class="table table-bordered table-hover table-sm">
                <thead>
                
                <th>Ações</th>
                
                <th>Arquivo</th>
                <th>Tamanho (KB)</th>
                <th>Sistema</th>
                <th>Data de envio</th>
                </thead>
                <tbody>
                    @foreach($projetos as $projeto)
                    <tr>
                        
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                {!! Form::open(['action' => ['ProjetosController@destroy', $projeto->id], 'method' => 'delete']) !!}
                                {!! Form::button('<i class="fas fa-trash-alt"></i>', ['class' => 'btn btn-outline-danger btn-sm', 'onclick' => 'return confirm(\'Deseja apagar esse arquivo? Essa ação não poderá ser desfeita.\')', 'type' => 'submit', 'data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'Apagar']) !!}
                                {!! Form::close() !!}
                            </div>
                        </td>
                        
                        <td>
                            <a href="{{ url('analise/projetos/download/'.$projeto->id) }}" @if($projeto->name != null) title="Último download por {{$projeto->name}}" @endif >
                               @if(($projeto->baixado == null) && (Auth::user()->user_levels_id != 99))
                               <strong>{{ $projeto->arquivo }}</strong> <span class="badge badge-danger">Novo!</span>
                               @else 
                               {{ $projeto->arquivo }}
                               @endif
                            </a>
                        </td>
                        <td>{{ number_format(ceil((Storage::size('projetos/'.$projeto->hash) / 1024)),0,'','.') }} KB</td>
                        <td>{{ $projeto->sistema }}</td>
                        <td>{{ date('d/m/Y - H:i:s', strtotime($projeto->created_at.' -3 hours')) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
@endsection