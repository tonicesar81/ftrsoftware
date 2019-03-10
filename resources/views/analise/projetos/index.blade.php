@extends('layouts.app')
@section('content')
<?php
$tipo_relatorio = [
    1 => 'DET E ALARME',
    3 => 'SPK',
//    4 => 'EXTINTORES',
    5 => 'CO2 SAP',
    6 => 'EXAUST',
    7 => 'GAS',
    8 => 'HIDRANTES',
    9 => 'HVAC'
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
            Projetos
        </div>
        <div class="card-body">

            <div class="row justify-content-between">
                @if(is_null($nivel))
                <div class="col-md-4">
                    <a class="btn btn-primary" href="{{url('analise/projetos/create')}}" role="button">+ Novo projeto</a>
                </div>
                @else
                <div class="col-md-12">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="{{ url('analise/projetos') }}" class="btn btn-outline-primary {{ (is_null($active))? 'active' : '' }}" role="button">TODOS</a>
                        @foreach($tipo_relatorios as $tipo)
                        <a href="{{ url('analise/projetos/'.$tipo->id) }}" class="btn btn-outline-primary {{ ($tipo->id == $active)? 'active' : '' }}" role="button">
                        @if($tipo->id == 3)
                        SPK - EXTINTORES
                        @else
                        {{$tipo->ref}}
                        @endif
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            <hr>
            @if($projetos->isEmpty())
            <div class="alert alert-primary" role="alert">
                Nenhum resultado encontrado
            </div>
            @else
            <table class="table table-bordered table-hover table-sm"  >
                <thead >
                    <tr class="bg-primary text-white">
                        <th scope="col" >Cód.</th>
                        <th scope="col" >Shopping/Cliente</th>
                        <th scope="col" >Loja</th>
                        <th scope="col">Disciplina</th>
                        <th scope="col">Revisão</th>
                        <th scope="col">Data de envio</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projetos as $projeto)
                    <tr>
                        <td>
                            @if(!is_null($nivel) && $nivel == 1)
                            {!! Form::open(['action' => ['ProjetosController@destroy', $projeto->id], 'method' => 'delete']) !!}
                            {!! Form::button('<i class="fas fa-trash-alt"></i>', ['class' => 'btn btn-outline-danger btn-sm', 'onclick' => 'return confirm(\'Ao apagar esse elemento, todas as dependências serão removidas. \n Deseja consinuar?\')', 'type' => 'submit', 'data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'Apagar']) !!}
                            {!! Form::close() !!}
                            @endif
                            {{$projeto->id}}
                        </td>
                        <td>{{$projeto->shopping}}</td>
                        <td>{{$projeto->loja}}</td>
                        <td>
                            @foreach(explode(',',$projeto->tipo_relatorios_id) as $tipo)
                            @if($tipo == 3)
                            <span class="badge badge-primary">SPK - EXTINTORES</span>
                            @elseif($tipo != 4)
                            <span class="badge badge-primary">{{$tipo_relatorio[$tipo]}}</span>
                            @endif
                            @endforeach
                        </td>
                        <td>{{$projeto->revisao}}</td>
                        <td>{{ date('d/m/Y - H:i:s', strtotime($projeto->created_at.' -3 hours')) }}</td>
                        <td>
                            <a class="btn btn-primary" href="{{ url('analise/projetos/detalhes/'.$projeto->id) }}" role="button" >
                                Detalhes
                            </a>                            
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif

        </div>
    </div>
</div>
@endsection