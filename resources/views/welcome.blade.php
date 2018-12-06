@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card text-center">
        <div class="card-body">
            <p class="card-text display-4 text-primary">Seja muito bem vindo(a), {{ Auth::user()->name }}{{ Auth::user()->lname }}. Estamos muito felizes em poder ajudar!</p>
            <a href="{{ url('/home') }}" class="btn btn-lg btn-primary">Iniciar</a>
        </div>
    </div>
</div>
@endsection