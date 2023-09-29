@extends('layouts.cotizador')
@section('title', 'Cotizacion Actual')
@section('content')
    <div class="container">
        <h3>Mis Cotizaciones Actuales</h3>
        @if (count(auth()->user()->currentQuotes) > 0)
        <div class="form-group">
            <select class="form-control">
                    @foreach (auth()->user()->currentQuotes as $cotizacion)
                        <option value="{{ $cotizacion->id }}">
                            {{ $cotizacion->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>
    @if (count(auth()->user()->currentQuotes) > 0)
        @livewire('current-quote-component', ['cotizacionActual'=> $cotizacionActual])
    @else
        <div class="d-flex w-100 justify-content-center">
            <p class="text-center m-0 my-5"><strong>No tienes productos en tu cotizacion actual </strong></p>
        </div>
    @endif
@endsection
