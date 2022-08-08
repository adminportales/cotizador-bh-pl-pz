@extends('layouts.app')

@section('content')
    <div class="container-fluid ">
        @livewire('ver-cotizacion-component', ['quote' => $quote])
        <div class="row">

            <div class="col-md-12 mt-2">
                <div class="card w-100">
                    <div class="card-body">
                        <h5 class="card-title">Historial de Modificaciones</h5>
                        @if (count($quote->quotesUpdate) > 0)
                            @foreach ($quote->quotesUpdate as $item)
                                {{ $item }}
                            @endforeach
                        @else
                            <p>No hay modificaciones</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
