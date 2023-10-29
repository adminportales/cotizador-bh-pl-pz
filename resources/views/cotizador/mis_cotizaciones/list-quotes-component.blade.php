<div>

    <div>
        <input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
    </div>
    <br>
    @if (count($quotes) > 0)
        <table class="table table-sm table-responsive">
            <thead>
                <tr>
                    <th scope="col" class="text-center">#</th>
                    <th scope="col">Lead</th>
                    <th scope="col">Cliente</th>
                    <th scope="col" class="">Oportunidad</th>
                    <th scope="col" class="">Probabilidad de Venta</th>
                    <th scope="col" class="text-center">Total</th>
                    <th scope="col" class="text-center">Fecha</th>
                    <th scope="col" class="text-center">...</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quotes as $quote)
                    <tr>
                        <td class="text-center">QS{{ $quote->id }}</td>
                        <th style="vertical-align: middle">{{ $quote->lead }}</th>
                        @if ($quote->latestQuotesUpdate)
                            <td>{{ $quote->latestQuotesUpdate->quotesInformation->name . ' | ' . $quote->latestQuotesUpdate->quotesInformation->company}}
                            </td>
                            <td>{{ $quote->latestQuotesUpdate->quotesInformation->oportunity }}
                            </td>
                            <td>
                                @switch($quote->latestQuotesUpdate->quotesInformation->rank)
                                    @case(1)
                                        {{ 'Medio' }}
                                    @break

                                    @case(2)
                                        {{ 'Alto' }}
                                    @break

                                    @case(3)
                                        {{ 'Muy Alto' }}
                                    @break

                                    @default
                                @endswitch
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
                            <a href="{{ route('verCotizacion', ['quote' => $quote->id]) }}" class="btn btn-primary btn-sm">Ver</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-center">
            {{ $quotes->links() }}
        </div>
    @else
        <div class="d-flex w-100 justify-content-center">
            <p class="text-center m-0 my-5"><strong>No tienes cotizaciones realizadas </strong>
            </p>
        </div>
    @endif
</div>
