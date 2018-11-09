@component('mail::message')
Olá, {{ $shopping }}
<br>
<br>
A análise {{ $sistema }} REV_{{ $rev }} da loja {{ $loja }} solicitado, já está disponível no sistema
<br>
<br>

@component('mail::button', ['url' => url('/analise/relatorios')])
Ir para o sistema
@endcomponent

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent