@extends('layouts.app')

@section('content')
    <div class="container-fluid ">
        <div class="row">
            <div class="col-md-6">
                <div class="card w-100 h-100">
                    <div class="card-body">
                        @if ($quote->latestQuotesInformation)
                            <h5 class="card-title">{{ $quote->latestQuotesInformation->name }} |
                                {{ $quote->latestQuotesInformation->company }}</h5>
                            <h6><b>Numero de lead: </b> {{ $quote->lead }}</h6>
                            <h6><b>Oportunidad: </b> {{ $quote->latestQuotesInformation->oportunity }}</h6>
                            <br>
                            <p><b>Email: </b>{{ $quote->latestQuotesInformation->email }}</p>
                            <p><b>Telefono: </b>{{ $quote->latestQuotesInformation->landline }}</p>
                            <p><b>Celular: </b>{{ $quote->latestQuotesInformation->cell_phone }}</p>
                            <p><b>Rank: </b>{{ $quote->latestQuotesInformation->rank }}</p>
                            <p><b>Departamento: </b>{{ $quote->latestQuotesInformation->department }}</p>
                            <p><b>Informacion adicional: </b>{{ $quote->latestQuotesInformation->information }}</p>
                        @else
                            <p>Sin informacion</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card w-100 h-100">
                    @livewire('editar-cotizacion-component', ['quote' => $quote], key($quote->id))
                </div>
            </div>
            {{-- <div class="col-md-12 mt-2">
                <div class="card w-100">
                    <div class="card-body">
                        <h5 class="card-title">Historial de Modificaciones</h5>
                        @if (count($quote->quotesInformation) > 1)
                            @foreach ($quote->quotesInformation as $item)
                                {{ $item }}
                            @endforeach
                        @else
                            <p>No hay modificaciones</p>
                        @endif
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
@endsection
