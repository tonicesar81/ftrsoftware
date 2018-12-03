@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card text-center">
        <div class="card-body">
            <p class="card-text display-4 text-primary">Bem-vindo(a), {{ Auth::user()->name }} {{ Auth::user()->lname }} e sinta-se a vontade conosco! Seja muito bem-vindo(a) e conte com a gente!</p>
            <a href="{{ url('/home') }}" class="btn btn-lg btn-primary">Iniciar</a>
        </div>
    </div>
</div>
@endsection