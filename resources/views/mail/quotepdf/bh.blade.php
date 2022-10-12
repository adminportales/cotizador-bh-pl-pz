@component('mail::message')
# Buenos Dias {{ $cliente }}.
Se ha realizado una cotizacion adjunta en este email
Pero para BH
<hr>
Gracias,<br>
{{ $vendedor }}
@endcomponent
