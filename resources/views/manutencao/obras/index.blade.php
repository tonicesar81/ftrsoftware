@extends('layouts.app')
@section('content')
<?php
$tipos = [
    1 => 'Projeto',
    2 => 'Memorial descritivo',
    3 => 'Memorial de cálculo',
    4 => 'Arquivo de medição',
    5 => 'PMOC'
];
$certs  = [
    1 => 'Certificado de Garantia',
    2 => 'Certificado de Responsabilidade'
];
?>
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
    Obras - Arquivos e relatórios
  </div>
  <div class="card-body">
    <div class="row">
        <div class="col-12">
            
            
            <h6><a href="{{ url('/manutencao/obras/') }}"><i class="fas fa-home"></i></a> > <a href="{{ url('/manutencao/obras/arquivos/'.urlencode($cliente)) }}"><i class="fas fa-folder-open"></i> {!! urldecode($cliente) !!}</a></h6>
            <div class="col-md-4">
                    <a class="btn btn-primary" href="{{url('/manutencao/arquivos/create/'.$obra_id)}}" role="button">+ Novo arquivo</a>
                </div>
            @if($arquivos->isEmpty())
            <div class="alert alert-info" role="alert">
                Não há arquivos disponíveis
            </div>
            @else
            <table class="table table-bordered table-hover table-sm">
                <thead>
                @if(!is_null($nivel))
                <th>Ações</th>
                @endif
                <th>Arquivo</th>
                <th>Data de Cadastro</th>
                </thead>
                <tbody>
                    
                    @foreach($arquivos as $arquivo)
                    <tr>
                        @if(!is_null($nivel))
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                {!! Form::open(['action' => ['ObrasController@destroy', $arquivo->id], 'method' => 'delete']) !!}
                                {!! Form::button('<i class="fas fa-trash-alt"></i>', ['class' => 'btn btn-outline-danger btn-sm', 'onclick' => 'return confirm(\'Deseja apagar esse arquivo? Essa ação não poderá ser desfeita.\')', 'type' => 'submit', 'data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'Apagar']) !!}
                                {!! Form::close() !!}
                            </div>
                        </td>
                        @endif
                        <td>
                            @switch($arquivo->classe)
                                @case('arquivo')
                            <a href="{{ url('/manutencao/arquivos/download/'.$arquivo->id) }}">{{ $tipos[$arquivo->tipo] }}</a>
                                @break
                                @case('relatorio')
                            <a href="{{ url('/manutencao/obras/'.$arquivo->id) }}" target="_blank">{{ $arquivo->nome }}</a>  
                                @break
                                @case('certificado')
                            <a href="{{ url('/manutencao/obras/certificado/'.$arquivo->id) }}" target="_blank">{{ $certs[$arquivo->tipo] }}</a>      
                                @break
                                @case('manual')
                            <a href="{{ url('/manutencao/entregas/pdf/'.$arquivo->id) }}" target="_blank">Manual de Entrega de Obra</a>
                                @break
                            @endswitch    
                        </td>
                        <td>{{ date('d/m/Y', strtotime($arquivo->created_at)) }}</td>
                        
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