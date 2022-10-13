@extends('layouts.cotizador')
@section('title', 'Cotizacion Actual')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                @if (count($cotizacionActual) > 0)
                    @livewire('current-quote-component', ['cotizacion'])
                @else
                    <div class="d-flex w-100 justify-content-center">
                        <p class="text-center m-0 my-5"><strong>No tienes productos en tu cotizacion actual </strong></p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
