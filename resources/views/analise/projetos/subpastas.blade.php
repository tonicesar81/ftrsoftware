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
                    <option value="{{ url('/arquivos/'.$shopping->id) }}">{{$shopping->shopping}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12">
            <h6><a href="{{ url('/analise/projetos/') }}"><i class="fas fa-home"></i></a> > <i class="fas fa-folder-open"></i> {{ $shopping_select }}</h6>
            
            @if($subpastas->isEmpty())
            <div class="alert alert-info" role="alert">
                Não há arquivos disponíveis
            </div>
            @else
            <table class="table table-bordered table-hover table-sm">
                <thead>
                <th>Loja</th>
                <th>Nº Arquivos</th>
                <th>ùltima modificação</th>
                </thead>
                <tbody>
                    @foreach($subpastas as $subpasta)
                    <tr>
                        <td><a href="{{ url('/analise/projetos/'.$subpasta->shoppings_id.'/'.$subpasta->id) }}"><i class="fas fa-folder"></i> {{ $subpasta->loja }}</a></td>
                        <td>{{ $subpasta->proj }}</td>
                        <td>{{ date('d/m/Y - H:i:s', strtotime($subpasta->created_at.' -3 hours')) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
@endsection