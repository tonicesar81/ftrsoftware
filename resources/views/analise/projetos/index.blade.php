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
                        <td>{{$projeto->id}}</td>
                        <td>{{$projeto->shopping}}</td>
                        <td>{{$projeto->loja}}</td>
                        <td>
                            @if($projeto->tipo_relatorios_id == 3)
                            SPK - EXTINTORES
                            @else
                            {{$projeto->ref}}
                            @endif
                        </td>
                        <td>{{$projeto->revisao}}</td>
                        <td>{{ date('d/m/Y - H:i:s', strtotime($projeto->created_at.' -3 hours')) }}</td>
                        <td>
                            <div class="dropdown">
                                @if(!is_null($nivel))
                                <a class="btn btn-primary" href="
                                   @if($projeto->revisao == 0)
                                   {{ url('analise/relatorios/create/'.$projeto->id) }}
                                   @else
                                   {{ url('analise/relatorios/revisao/'.$projeto->referencia.'/'.$projeto->id) }}
                                   @endif
                                   " role="button" id="dropdownMenuLink"  >
                                    Analisar
                                </a>
                                @endif
                                <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Arquivos
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    @foreach($projeto->arquivos as $arquivo)
                                    <a class="dropdown-item" href="{{ url('analise/projetos/download/'.$arquivo->id) }}">{{$arquivo->filename}}</a>
                                    @endforeach
                                </div>
                            </div>
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