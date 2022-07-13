@extends('layouts.app')

@section('content')
    <div class="container-fluid ">
        <div class="row px-3">
            <div class="card w-100">
                <div class="card-header">
                    <h5 class="card-title">Mis Cotizaciones</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col">Lead</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Oportunidad</th>
                                <th scope="col">Rank</th>
                                <th scope="col" class="text-center">Total</th>
                                <th scope="col" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($quotes as $quote)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <th style="vertical-align: middle">{{ $quote->lead }}</th>
                                    @if ($quote->latestQuotesInformation)
                                        <td>{{ $quote->latestQuotesInformation->name . ' | ' . $quote->latestQuotesInformation->company }}
                                        </td>
                                        <td>{{ $quote->latestQuotesInformation->oportunity }}
                                        </td>
                                        <td>{{ $quote->latestQuotesInformation->rank }}
                                        </td>
                                        @if (count($quote->latestQuotesInformation->quotesProducts) > 0)
                                            <td class="text-center">$
                                                {{ $quote->latestQuotesInformation->quotesProducts->sum('precio_total') }}
                                            </td>
                                        @else
                                            <td class="text-center">Sin Dato</td>
                                        @endif
                                    @else
                                        <td>Sin Dato</td>
                                        <td>Sin Dato</td>
                                        <td>Sin Dato</td>
                                        <td class="text-center">Sin Dato</td>
                                    @endif

                                    <td class="text-center">
                                        <a href="{{ route('verCotizacion', ['quote' => $quote->id]) }}"
                                            class="btn btn-primary">Ver Cotizacion</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
