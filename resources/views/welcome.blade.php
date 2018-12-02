@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card text-center text-white bg-primary">
        <div class="card-body">
            <p class="card-text display-4">Bem-vindo(a), {{ Auth::user()->name }} {{ Auth::user()->lname }} e sinta-se a vontade conosco! Adoramos você! Seja muito bem-vindo(a) e conte com a gente!</p>
            <a href="{{ url('/home') }}" class="btn btn-lg btn-light">Iniciar</a>
        </div>
    </div>
</div>
@endsection