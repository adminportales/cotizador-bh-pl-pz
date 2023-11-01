@extends('layouts.cotizador')

@section('content')
    <div class="container">
        @livewire('cotizador.ver-cotizacion-component', ['quote' => $quote])
    </div>
@endsection
