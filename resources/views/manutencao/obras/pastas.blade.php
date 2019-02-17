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
    Obras
  </div>
  <div class="card-body">
      @if(!is_null($nivel))
            
            <hr>
            @endif
    <div class="row">
        <div class="col-12">
            @if(isset($pastas))
            @if($pastas->isEmpty())
            <div class="alert alert-info" role="alert">
                Não há arquivos disponíveis
            </div>
            @else
            <table class="table table-bordered table-hover table-sm">
                <thead>
                <th>Cliente</th>
                </thead>
                <tbody>
                    @foreach($pastas as $pasta)
                    <tr>
                        <td><a href="{{ url('/manutencao/obras/arquivos/'.urlencode($pasta->cliente)) }}" ><i class="fas fa-folder"></i> {{ $pasta->cliente }}</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
            @else
            <div class="alert alert-info" role="alert">
                Não há arquivos disponíveis
            </div>
            @endif
        </div>
    </div>
  </div>
    </div>
</div>
@endsection