@component('mail::message')
Olá, {{ $name }}
<br>
<br>
Seu acesso ao sistema FTR está liberado através dessas credenciais de acesso:
<br>
<br>
Login: {{ $username }}
<br>
Senha: {{ $password }}

@component('mail::button', ['url' => url('/login')])
Ir para o sistema
@endcomponent

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent