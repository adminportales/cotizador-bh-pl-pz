@extends('layouts.app')
@section('title', 'Mis Cotizaciones')
@section('content')
    <div class="container-fluid">
        <div class="row px-3">
            <div class="card w-100">
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
                                <th scope="col" class="text-center">Fecha</th>
                                <th scope="col" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($quotes as $quote)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <th style="vertical-align: middle">{{ $quote->lead }}</th>
                                    @if ($quote->latestQuotesUpdate)
                                        <td>{{ $quote->latestQuotesUpdate->quotesInformation->name . ' | ' . $quote->latestQuotesUpdate->quotesInformation->company }}
                                        </td>
                                        <td>{{ $quote->latestQuotesUpdate->quotesInformation->oportunity }}
                                        </td>
                                        <td>{{ $quote->latestQuotesUpdate->quotesInformation->rank }}
                                        </td>
                                        @if ($quote->latestQuotesUpdate->quoteProducts)
                                            @if (count($quote->latestQuotesUpdate->quoteProducts) > 0)
                                                <td class="text-center">$
                                                    {{ $quote->latestQuotesUpdate->quoteProducts->sum('precio_total') }}
                                                </td>
                                            @else
                                                <td class="text-center">Sin Dato</td>
                                            @endif
                                        @else
                                            <td class="text-center">Sin Dato</td>
                                        @endif
                                        <td class="text-center">
                                            {{ $quote->latestQuotesUpdate->created_at->format('d-m-Y') }}</td>
                                    @else
                                        <td>Sin Dato</td>
                                        <td>Sin Dato</td>
                                        <td>Sin Dato</td>
                                        <td class="text-center">Sin Dato</td>
                                        <td class="text-center">{{ $quote->created_at->format('d-m-Y') }}</td>
                                    @endif

                                    <td class="text-center">
                                        <a href="{{ route('verCotizacion', ['quote' => $quote->id]) }}"
                                            class="btn btn-primary">Ver
                                            Cotizacion</a>
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
