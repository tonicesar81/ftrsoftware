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
            Datasheets
        </div>
        <div class="card-body">
            @if(!is_null($nivel))
            <div class="row justify-content-between">
                <div class="col-md-4">
                    <a class="btn btn-primary" href="{{url('/datasheets/create')}}" role="button">+ Criar Novo</a>
                </div>
            </div>
            <hr>
            @endif
            @if($datasheets->isEmpty())
            <div class="alert alert-primary" role="alert">
                Nenhum resultado encontrado
            </div>
            @else
            <table class="table table-bordered table-hover table-sm"  >
                <thead >
                    <tr class="bg-primary text-white">
                        <th scope="col" >Shopping</th>
                        <th scope="col" >Loja</th>
                        <th scope="col" >Número</th>
                        <th scope="col">Dt. Emissão</th>
                        @if(!is_null($nivel))
                        <th scope="col"></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($datasheets as $datasheet)
                    <tr>
                        <td><a href='{{ url('datasheets/'.$datasheet->id) }}' target='_blank'>{{$datasheet->shopping}}</a></td>
                        <td><a href='{{ url('datasheets/'.$datasheet->id) }}' target='_blank'>{{$datasheet->loja}}</a></td>
                        <td>{{$datasheet->numero}}</td>
                        <td>{!! date('d/m/Y', strtotime($datasheet->created_at)) !!}</td>
                        @if(!is_null($nivel))
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a class="btn btn-outline-primary btn-sm" href="{{url('/datasheets/edit/'.$datasheet->id)}}" role="button" data-toggle="tooltip" data-placement="left" title="Modificar"><i class="fas fa-edit"></i></a>
                                {!! Form::open(['url' => 'datasheets/'.$datasheet->id, 'method' => 'delete']) !!}
                                {!! Form::button('<i class="fas fa-trash-alt"></i>', ['class' => 'btn btn-outline-danger btn-sm', 'onclick' => 'return confirm(\'Você tem certeza?\')', 'type' => 'submit', 'data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'Apagar']) !!}
                                <!--<button class="btn btn-outline-danger btn-sm" type="submit" onClick="return confirm('Você tem certeza?')" name="name"></button>-->
                                {!! Form::close() !!}
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @php
            try{
            echo $datasheets->links();
            } catch (\Exception $e) {

            }
            @endphp
            @endif
        </div>
    </div>
</div>
@endsection