<div>
    <style>
        .table td {
            vertical-align: middle;
        }

        .tr-link {
            cursor: pointer;
        }
    </style>
    <div class="row px-3">
        <div class="card w-100">
            <div class="card-body">
                @if (count($quotes) > 0)
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col">Lead</th>
                                <th scope="col">Empresa</th>
                                <th scope="col">Vendedor</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Oportunidad</th>
                                <th scope="col">Probabilidad</th>
                                <th scope="col" class="text-center">Total</th>
                                <th scope="col" class="text-center">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($quotes as $quote)
                                <tr onclick="window.location='{{ route('verCotizacion', ['quote' => $quote->id]) }}'"
                                    class="tr-link {{ $quote->lead == 'No Definido' ? 'bg-warning' : '' }} ">
                                    <td class="text-center">
                                        {{ $loop->iteration }}
                                    </td>
                                    <th style="vertical-align: middle">
                                        {{ $quote->lead }}
                                    </th>
                                    <td style="vertical-align: middle">
                                        {{ $quote->company->name }}
                                    </td>
                                    <td style="vertical-align: middle">
                                        {{ $quote->user->name }}
                                    </td>
                                    @if ($quote->latestQuotesUpdate)
                                        <td>
                                            {{ Str::limit($quote->latestQuotesUpdate->quotesInformation->name . ' | ' . $quote->latestQuotesUpdate->quotesInformation->company, 35, '...') }}
                                        </td>
                                        <td>
                                            {{ Str::limit($quote->latestQuotesUpdate->quotesInformation->oportunity, 35, '...') }}
                                        </td>
                                        <td>

                                            @php
                                                $probabilidad = '';
                                                $color = '';
                                            @endphp
                                            @switch($quote->latestQuotesUpdate->quotesInformation->rank)
                                                @case(1)
                                                    @php
                                                        $probabilidad = 'Medio';
                                                        $color = 'alert-secondary';
                                                    @endphp
                                                @break

                                                @case(2)
                                                    @php
                                                        $probabilidad = 'Alto';
                                                        $color = 'alert-warning';
                                                    @endphp
                                                @break

                                                @case(3)
                                                    @php
                                                        $probabilidad = 'Muy Alto';
                                                        $color = 'alert-success';
                                                    @endphp
                                                @break

                                                @default
                                            @endswitch
                                            <p class="alert {{ $color }} p-0 m-0 text-center">{{ $probabilidad }}
                                            </p>
                                        </td>
                                        @if ($quote->latestQuotesUpdate->quoteProducts)
                                            @if (count($quote->latestQuotesUpdate->quoteProducts) > 0)
                                                <td class="d-flex justify-content-between">
                                                    <p>$</p>
                                                    <p>{{ number_format($quote->latestQuotesUpdate->quoteProducts->sum('precio_total'), 2, '.', ',') }}
                                                    </p>
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="d-flex w-100 justify-content-center">
                        <p class="text-center m-0 my-5"><strong>No tienes cotizaciones realizadas </strong>
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
