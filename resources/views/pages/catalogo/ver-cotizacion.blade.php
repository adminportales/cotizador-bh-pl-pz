@extends('layouts.app')

@section('content')
    <div class="container-fluid ">
        <div class="row">
            <div class="col-md-6">
                <div class="card w-100 h-100">
                    <div class="card-body">
                        @if ($quote->latestQuotesUpdate)
                            <h5 class="card-title">{{ $quote->latestQuotesUpdate->quotesInformation->name }} |
                                {{ $quote->latestQuotesUpdate->quotesInformation->company }}</h5>
                            <h6><b>Numero de lead: </b> {{ $quote->lead }}</h6>
                            <h6><b>Oportunidad: </b> {{ $quote->latestQuotesUpdate->quotesInformation->oportunity }}</h6>
                            <br>
                            <p><b>Email: </b>{{ $quote->latestQuotesUpdate->quotesInformation->email }}</p>
                            <p><b>Telefono: </b>{{ $quote->latestQuotesUpdate->quotesInformation->landline }}</p>
                            <p><b>Celular: </b>{{ $quote->latestQuotesUpdate->quotesInformation->cell_phone }}</p>
                            <p><b>Rank: </b>{{ $quote->latestQuotesUpdate->quotesInformation->rank }}</p>
                            <p><b>Departamento: </b>{{ $quote->latestQuotesUpdate->quotesInformation->department }}</p>
                            <p><b>Informacion adicional: </b>{{ $quote->latestQuotesUpdate->quotesInformation->information }}</p>
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
