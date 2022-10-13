@component('mail::message')
# Buenos Dias {{ $cliente }}.
Se ha realizado una cotizacion adjunta en este email para Promo Zale
<hr>
Gracias,<br>
{{ $vendedor }}
@endcomponent
