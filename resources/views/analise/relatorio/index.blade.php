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
            @if(isset($shopping_select))
            <h6><a href="{{ url('/analise/relatorios/') }}"><i class="fas fa-home"></i></a> > <a href="{{ url('/analise/relatorios/'.$shopping_select->id) }}"><i class="fas fa-folder-open"></i> {{ $shopping_select->shopping }}</a> > <i class="fas fa-folder-open"></i> {{ $loja }}</h6>
            @endif
            @if($relatorios->isEmpty())
            <div class="alert alert-info" role="alert">
                Não há relatórios disponíveis
            </div>
            @else
            <table class="table table-bordered table-hover table-sm">
                <thead>
                <th>Ações</th>
                <th>Revisão</th>
                <th>Sistema</th>
                <th>Status da análise</th>
                <th>Data de criação</th>
                <th>Última alteração</th>
                </thead>
                <tbody>
                    @foreach($relatorios as $relatorio)
                    <tr>
                        <td>
                            @if(Auth::user()->user_levels_id == 99)
                            <a class="btn btn-sm btn-outline-primary" href="{{ url('/analise/relatorios/pdf/'.$relatorio->id) }}" target="_blank" role="button" title="Gerar PDF"><i class="fas fa-file-pdf"></i></a>
                            @else
                            <div class="btn-group">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Clique para ver as opções">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item text-primary" href="{{ url('/analise/relatorios/pdf/'.$relatorio->id) }}" target="_blank" title="Gerar PDF"><i class="fas fa-file-pdf"></i> Baixar PDF</a>
                                    @if(is_null($nivel))
                                    <a class="dropdown-item text-primary" href="{{ url('/analise/projetos/revisao/'.$relatorio->id) }}" title="Revisão"><i class="fas fa-copy"></i> Solicitar Revisão</a>
                                    @else
                                    <a class="dropdown-item text-primary" href="{{ url('/analise/relatorios/revisao/'.$relatorio->id) }}" title="Revisão"><i class="fas fa-copy"></i> Revisão</a>                                    
                                    <a class="dropdown-item text-primary" href="{{ url('/analise/relatorios/edit/'.$relatorio->id) }}" role="button" title="Editar"><i class="fas fa-edit"></i> Editar</a>
                                    <div class="dropdown-divider"></div>
                                    {!! Form::open(['action' => ['RelatoriosController@destroy', $relatorio->id], 'method' => 'delete']) !!}
                                    {!! Form::button('<i class="fas fa-trash-alt"></i> Apagar', ['class' => 'btn btn-link dropdown-item text-danger', 'onclick' => 'return confirm(\'Deseja apagar esse relatório? Essa ação não poderá ser desfeita.\')', 'type' => 'submit', 'data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'Apagar']) !!}
                                    {!! Form::close() !!}
                                    @endif
                                </div>
                            </div>
                            @endif
                        </td>
                        <td>{{ $relatorio->revisao }}</td>   
                        <td>{{ $relatorio->tipo_relatorio }}</td>
                        <td>
                            @if(($relatorio->analise == null) && ($relatorio->obs->count() == 0))
                            <div class="bg-success text-center text-white">APROVADO</span>
                                @else
                                <button type="button" class="btn btn-sm {!! (is_null($relatorio->ressalva))? 'btn-danger' : 'btn-warning' !!} btn-block" data-toggle="popover" data-trigger="focus" title="Observações" 
                                        data-content="
                                        <ul>
                                        @foreach($relatorio->obs as $obs)
                                        <li>{{ $obs->lista_analise }}</li>
                                        @endforeach
                                        </ul>"
                                        >{!! (is_null($relatorio->ressalva))? 'NÃO APROVADO' : 'APROVADO C/ RESSALVA' !!}</button>
                                @endif
                        </td>
                        @php
                        //$date = new DateTime($relatorio->created_at, new DateTimeZone('America/Sao_Paulo'));
                        @endphp
                        <td>{{ date('d/m/Y - H:i:s', strtotime($relatorio->created_at.' -3 hours')) }}</td>
                        <td>
                            @if($relatorio->updated_at != $relatorio->created_at)
                            {{ date('d/m/Y - H:i:s', strtotime($relatorio->updated_at.' -3 hours')) }}
                            @endif
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