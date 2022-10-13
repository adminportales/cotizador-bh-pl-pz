@extends('layouts.cotizador')

@section('content')
    <div class="container">
        @livewire('ver-cotizacion-component', ['quote' => $quote])
    </div>
@endsection
