@extends('layouts.app')
@section('content')
<?php
$tipo_relatorio = [
    1 => 'DET E ALARME',
    3 => 'SPK - EXTINTORES',
//    4 => 'EXTINTORES',
    5 => 'CO2 SAP',
    6 => 'EXAUST',
    7 => 'GAS',
    8 => 'HIDRANTES',
    9 => 'HVAC'
];
?>
<div class="container">
    
    <div class="card">
        <div class="card-header">
            Detalhes do Projeto
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    Shopping : {{ $shopping }}
                </div>
                <div class="col-md-4">
                    Loja: {{ strtoupper($projeto->loja) }}
                </div>
                <div class="col-md-4">
                    Revisao: {{ $projeto->revisao }}
                </div>
            </div>
            <hr>
            @if(!is_null($nivel))
            {!! Form::open(['action' => ['ProjetosController@update', $projeto->id], 'method' => 'put']) !!}
            <div class="form-row">
                @if(session('message'))
                <div class="form-group col-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {!! Session::get('message') !!}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>    
                @endif
                
                <div class="form-group col-8">
                    <p><strong>Análises Solicitadas</strong></p>
                </div>
                <div class="form-group col-8 row">      
                    @foreach($tipo_relatorio as $k => $v)
                    <div class="form-check col-6">
                    {!! Form::checkbox('tipo_relatorios[]', $k, (in_array($k, explode(',',$projeto->tipo_relatorios_id))) ? true : false , ['class' => 'form-check-input'] ) !!}
                    {!! Form::label('tipo_relatorios', $v, ['class' => 'form-check-label']) !!}                                        
                    </div>
                    @if(in_array($k, explode(',',$projeto->tipo_relatorios_id)))
                    <div class="col-2">
                        
                    </div>
                    @else
                    <div class="col-2"></div>
                    @endif
                    @endforeach
                    <!--</div>-->                    
                </div>
                <div class="form-group col-12">
                    {!! Form::submit('Alterar', ['class' => 'btn btn-primary']); !!}
                </div>
                
            </div>
            {!! Form::close() !!}
            @else
            <div class="row">
                <div class="col-md-12">
                    <p><strong>Análises Solicitadas</strong></p>
                </div>
                @foreach($tipo_relatorio as $k => $v)
                    @if(in_array($k, explode(',',$projeto->tipo_relatorios_id)))
                    <div class="col-md-3">
                        {!! $v !!}
                    </div>
                    @endif
                @endforeach
                <div class="col-md-12">
                    <hr>
                </div>
            </div>
            @endif
            <div class="row">
                @if(!is_null($projeto->observacao))
                <div class="col-md-12">
                    <p><strong>Observações do Projetista</strong></p>
                    {!! $projeto->observacao !!}
                </div>
                @endif
                @if(!$imagens->isEmpty())
                <div class="col-md-12">
                    <p><strong>Imagens Ilustrativas</strong></p>
                </div>
                @foreach($imagens as $imagem)
                <div class="col-md-6">
                    <img src="{{$imagem->imagem}}" class="img-fluid" />
                </div>
                @endforeach
                @endif
                @if(!is_null($projeto->infra))
                <div class="col-md-12">
                    <p><strong>Informações sobre Infraestrutura da Loja</strong></p>
                </div>
                <div class="col-md-12">
                    {!! $projeto->infra !!}
                </div>
                @endif
                <div class="col-md-12">
                    <hr>
                </div>
                <div class="col-md-12">
                    <p><strong>Arquivos para Análise</strong></p>
                </div>
                @foreach($arquivos as $arquivo)
                    @switch($arquivo->memorial)
                        @case(1)
                        <div class="col-md-2">
                            <p><strong>Memorial</strong></p>
                        </div>
                        <div class="col-md-10">
                            <a href="{{ url('analise/projetos/download/'.$arquivo->id) }}">{{$arquivo->filename}}</a>
                        </div>
                        @break
                        
                        @case(2)
                        <div class="col-md-2">
                            <p><strong>Projeto de Arquitetura</strong></p>
                        </div>
                        <div class="col-md-10">
                            <a href="{{ url('analise/projetos/download/'.$arquivo->id) }}">{{$arquivo->filename}}</a>
                        </div>
                        @break
                        
                        @default
                            @if(strpos($arquivo->filename, '.dwg') !== false)
                                <div class="col-md-2">
                                    <p><strong>Projeto para Análise</strong></p>
                                </div>
                                <div class="col-md-10">
                                    <a href="{{ url('analise/projetos/download/'.$arquivo->id) }}">{{$arquivo->filename}}</a>
                                </div>
                            @endif
                        @break
                    @endswitch
                @endforeach
                @if(!is_null($nivel))
                <div class="col-md-12">
                    <a class="btn btn-primary btn-sm" href="
                       @if($projeto->revisao == 0)
                       {{ url('analise/relatorios/create/'.$projeto->id) }}
                       @else
                       {{ url('analise/relatorios/revisao/'.$projeto->referencia.'/'.$projeto->id) }}
                       @endif
                       " role="button" >
                        Analisar
                    </a>
                </div>
                @endif
            </div>    
        </div>
    </div>
</div>
@endsection