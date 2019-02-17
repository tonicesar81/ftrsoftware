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
    <table class="table table-bordered table-sm">
        <thead>
        <th>Shopping</th>
        <th>Pavimentos</th>
        </thead>
        <tbody>
            @foreach($shoppings as $shopping)
            <tr>
                <td><a href="{{ url('/manutencao/pavimentos/'.$shopping->id) }}"><i class="fas fa-building"></i> {{ $shopping->shopping }}</a></td>
                <td>{{ $shopping->pavs }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection