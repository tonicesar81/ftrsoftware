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
    Relatórios
  </div>
  <div class="card-body">
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <select class="form-control" id="select-shopping" onchange="if (this.value) window.location.href=this.value">
                    <option value="">Escolha um shopping</option>
                    @foreach($shoppings as $shopping)
                    <option value="{{ url('/analise/relatorios/'.$shopping->id) }}">{{$shopping->shopping}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12">
            <h6><a href="{{ url('/analise/relatorios/') }}"><i class="fas fa-home"></i></a> > <i class="fas fa-folder-open"></i> {{ $shopping_select }}</h6>
            
            @if($subpastas->isEmpty())
            <div class="alert alert-info" role="alert">
                Não há relatórios disponíveis
            </div>
            @else
            <table class="table table-bordered table-hover table-sm">
                <thead>
                <th></th>    
                <th>Loja</th>
                <th>Nº Relatórios</th>
                <th>ùltima modificação</th>
                </thead>
                <tbody>
                    @foreach($subpastas as $subpasta)
                    <tr>
                        <td>
                            @if(($subpasta->aprovados > 0) && (!is_null($nivel)))
                            <a href="{{url('/datasheets/create/'.$subpasta->id)}}" role="button" class="btn btn-sm btn-outline-secondary" title="Criar datasheet"><i class="fas fa-clipboard-list"></i></a>
                            @endif
                        </td>
                        <td><a href="{{ url('/analise/relatorios/'.$subpasta->shoppings_id.'/'.$subpasta->id) }}"><i class="fas fa-folder"></i> {{ $subpasta->loja }}</a></td>
                        <td>{{ $subpasta->rels }}</td>
                        <td>{{ date('d/m/Y - H:i:s', strtotime($subpasta->updated_at.' -3 hours')) }}</td>
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