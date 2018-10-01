<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js" integrity="sha384-3LK/3kTpDE/Pkp8gTNp2gR/2gOiwQ6QaO7Td0zV76UFJVhqLl4Vl3KL1We6q6wR9" crossorigin="anonymous"></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/signin.css') }}" rel="stylesheet">
    </head>
    <body class="text-center">

        {!! Form::open(['route' => 'login', 'class' => 'form-signin']); !!}
        <div class="text-center">
            <img src="{{ asset('img/ftr_logo.jpeg') }}" class="img-fluid" alt="...">
        </div>
        
        {!! Form::label('username', __('Usuário'), ['class' => 'sr-only']); !!}
        @php $control = $errors->has('username') ? ' is-invalid' : ''; @endphp
        {!! Form::text('username', old('username'), ['class' => 'form-control'.$control, 'required' => true, 'autofocus' => true, 'placeholder' => __('Usuário') ]); !!}
        @if ($errors->has('email'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
        @endif
        {!! Form::label('password', __('Senha'), ['class' => 'sr-only']); !!}
        @php $control = $errors->has('password') ? ' is-invalid' : ''; @endphp
        {!! Form::password('password', ['class' => 'form-control'.$control, 'required' => true, 'placeholder' => __('Senha')]); !!}
        @if ($errors->has('password'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('password') }}</strong>
        </span>
        @endif
        <div class="checkbox mb-3">
            {!! Form::checkbox('remember', '', old('remember') ? true : false, ['class' => 'form-check-input']); !!} {{ __('Manter-se conectado') }}
        </div>
        {!! Form::submit(__('Login'), ['class' => 'btn btn-lg btn-primary btn-block']) !!}
        <a class="btn btn-link" href="{{ route('password.request') }}">
            {{ __('Esqueci minha senha.') }}
        </a>
        <p class="mt-5 mb-3 text-muted">&copy; FTR Engenharia 2018</p>
        {!! Form::close() !!}
        
    </body>
</html>
