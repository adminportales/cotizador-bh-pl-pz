@extends('layouts.cotizador')

@section('content')
    @livewire('cotizador.ver-cotizacion-component', ['quote' => $quote])
@endsection
