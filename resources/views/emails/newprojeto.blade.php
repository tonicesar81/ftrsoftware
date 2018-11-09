@component('mail::message')
Olá,
<br>
<br>
O cliente {{ $shopping }} Solicitou uma análise para a loja {{ $loja }}
<br>
<br>

@component('mail::button', ['url' => url('/analise/projetos')])
Ir para o sistema
@endcomponent

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent