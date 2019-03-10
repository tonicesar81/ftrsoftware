<?php
$nivel = App\User_dados::where('users_id',Auth::id())->value('user_levels_id');
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        <!--<script src="{{ asset('js/app.js') }}" defer></script>-->
<!--        <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>-->
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>-->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="http://beneposto.pl/jqueryrotate/js/jQueryRotateCompressed.js"></script>
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>-->
        <script src="http://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js" integrity="sha384-3LK/3kTpDE/Pkp8gTNp2gR/2gOiwQ6QaO7Td0zV76UFJVhqLl4Vl3KL1We6q6wR9" crossorigin="anonymous"></script>
        <script src="{{ asset('js/selectize.min.js') }}"></script>
        <script src="{{ asset('js/summernote-bs4.js') }}"></script>
        <script src="{{ asset('js/summernote-pt-BR.js') }}"></script>
        <script src="{{ asset('js/jquery.mask.js') }}"></script>
    <script>
        $(document).ready(function ($) {
            $('.selectable').selectize();
            $('.select-responsavel').selectize({
                create: true,
                sortField: 'text'
            });
            $('.summernote').summernote({
                lang: 'pt-BR' // default: 'en-US'
            });
            var SPMaskBehavior = function (val) {
                return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
            },
            spOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
              }
            };

            $('.tel').mask(SPMaskBehavior, spOptions);
        });
        $(function () {
            $('[data-toggle="popover"]').popover({
                html: true,
                trigger: 'focus'
            });
        });
    </script>
        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="https://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/selectize.bootstrap3.css') }}" rel="stylesheet">
        <link href="{{ asset('css/summernote-bs4.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-expand-md navbar-dark bg-primary navbar-laravel">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{ asset('img/ftr_logo.png') }}" width="90" alt="...">

                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">
                            
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Análises</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('/analise/projetos')}}">Projetos</a>
                                    <a class="dropdown-item" href="{{ url('/analise/relatorios')}}">Relatórios</a>
                                    <a class="dropdown-item" href="{{ url('arquivos')}}">Arquivos</a>
                                    <a class="dropdown-item" href="{{ url('manuais/ftr_software_guia_analise.pdf')}}" target="_blank">Guia de uso</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ url('/analise/relatorios_antigos')}}">Relatórios da Versão Anterior</a>
                                    @if(!is_null($nivel))
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ url('/analise/sistema')}}">Disciplinas</a>
                                    <a class="dropdown-item" href="{{ url('/analise/item')}}">Itens de Checklist</a>
                                    <a class="dropdown-item" href="{{ url('/analise/obs')}}">Observações de Análise</a>
                                    <a class="dropdown-item" href="{{ url('/analise/grupos')}}">Grupos de disciplinas</a>
                                    <a class="dropdown-item" href="{{ url('/analise/normas')}}">Normas</a>
                                    <a class="dropdown-item" href="{{ url('textos/create')}}">Textos padrão</a>
                                    @endif
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Obras</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('/manutencao/obras')}}">Arquivos e relatórios</a>
                                    @if(!is_null($nivel))
                                    <a class="dropdown-item" href="{{ url('/manutencao/obras/create')}}">Novo relatorio</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ url('/manutencao/itens')}}">Disciplinas</a>
                                    <a class="dropdown-item" href="{{ url('/manutencao/itens/create')}}">Criar disciplina</a>
                                    <a class="dropdown-item" href="{{ url('/manutencao/desc')}}">Recomendações</a>
                                    <a class="dropdown-item" href="{{ url('/manutencao/desc/create')}}">Criar Recomendação</a>
                                    <a class="dropdown-item" href="{{ url('/manutencao/padrao/create')}}">Textos padrão para obras</a>
                                    <a class="dropdown-item" href="{{ url('/manutencao/certificados/create')}}">Textos padrão para certificados</a>
<!--                                    <a class="dropdown-item" href="{{ url('/manutencao/pavimentos')}}">Pavimentos</a>
                                    <a class="dropdown-item" href="{{ url('/manutencao/pavimentos/create')}}">Criar Pavimento</a>
                                    <a class="dropdown-item" href="{{ url('/manutencao/instalacoes')}}">Instalações</a>
                                    <a class="dropdown-item" href="{{ url('/manutencao/instalacoes/create')}}">Criar Instalação</a>
                                    <a class="dropdown-item" href="{{ url('/manutencao/termos')}}">Termos</a>
                                    <a class="dropdown-item" href="{{ url('/manutencao/termos/create')}}">Criar termo</a>
                                    <a class="dropdown-item" href="{{ url('/manutencao/capitulos')}}">Manuais de entrega</a>-->
                                    @endif
                                </div>
                            </li>
                            @if(!is_null($nivel))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Datasheets</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('/datasheets')}}">Datasheets</a>
                                    
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ url('/datasheets/nomes')}}">Equipamentos</a>
                                    <a class="dropdown-item" href="{{ url('/datasheets/tipos')}}">Tipos de equipamentos</a>
                                    <a class="dropdown-item" href="{{ url('/datasheets/localidades')}}">Localidades</a>
                                    
                                </div>
                            </li>
                            
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Configurações</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('/empresas')}}">Empresas</a>
                                    <a class="dropdown-item" href="{{ url('/shoppings')}}">Shoppings</a>
                                    <a class="dropdown-item" href="{{ url('/users')}}">Usuários</a>
                                    <a class="dropdown-item" href="{{ url('/funcionarios')}}">Funcionários</a>
                                    
                                </div>
                            </li>
                            @endif
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            
                            @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ url('cadastro') }}">
                                       Dados cadastrais
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
    document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="py-4">
                @yield('content')
            </main>
        </div>
    </body>
</html>
