<div>
    <h3 class="font-semibold text-xl py-4">Listado de cotizaciones</h3>
    <div class="mb-3">
        <input wire:model='keyWord' type="text"
            class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
            name="search" id="search" placeholder="Buscar">
    </div>
    @if (count($quotes) > 0)
        <div class="relative overflow-x-auto my-2 shadow-md rounded-md">
            <table class="w-full text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-3 py-2 text-center">#</th>
                        <th scope="col" class="px-3 py-2 ">Lead</th>
                        <th scope="col" class="px-3 py-2 ">Cliente</th>
                        <th scope="col" class="px-3 py-2 ">Oportunidad</th>
                        <th scope="col" class="px-3 py-2 ">Probabilidad de Venta</th>
                        <th scope="col" class="px-3 py-2 text-center">Total</th>
                        <th scope="col" class="px-3 py-2 text-center">Fecha</th>
                        <th scope="col" class="px-3 py-2 text-center">...</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($quotes as $quote)
                        <tr class="border-b">
                            <td class="px-3 py-2 text-center">QS{{ $quote->id }}</td>
                            <th style="vertical-align: middle" class="px-3 py-2">{{ $quote->lead }}</th>
                            @if ($quote->latestQuotesUpdate)
                                <td class="px-3 py-2">
                                    {{ $quote->latestQuotesUpdate->quotesInformation->name . ' | ' . $quote->latestQuotesUpdate->quotesInformation->company }}
                                </td>
                                <td class="px-3 py-2">{{ $quote->latestQuotesUpdate->quotesInformation->oportunity }}
                                </td>
                                <td class="px-3 py-2">
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
                                <td class="px-3 py-2">Sin Dato</td>
                                <td class="px-3 py-2">Sin Dato</td>
                                <td class="px-3 py-2">Sin Dato</td>
                                <td class="text-center px-3 py-2">Sin Dato</td>
                                <td class="text-center px-3 py-2">{{ $quote->created_at->format('d-m-Y') }}</td>
                            @endif

                            <td class="text-center px-3 py-2">
                                <a href="{{ route('verCotizacion', ['quote' => $quote->id]) }}"
                                    class="bg-green-200 p-3  rounded-md hover:bg-green-300">Ver</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex justify-center">
            <div class="">{{ $quotes->links() }}</div>
        </div>
    @else
        <div class="flex w-100 justify-center">
            <p class="text-center m-0 my-5"><strong>No tienes cotizaciones realizadas </strong>
            </p>
        </div>
    @endif
</div>
