<div>
    <div id="tabProducts">
        <div class="flex justify-between ">
            <strong class="w-5/6">Productos Cotizados</strong>
            @if ($quote->company_id == auth()->user()->company_session)
                <div class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
                    style="width: 25px; cursor: pointer;" wire:click="editar" data-toggle="tooltip" data-placement="bottom"
                    title="Editar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                        <path fill-rule="evenodd"
                            d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            @endif
        </div>
        <br>
        @php
            $subtotalAdded = 0;
            $subtotalSubstract = 0;
            $sumPrecioTotal = 0;
            $quoteScales = false;
        @endphp
        @if ($quote->latestQuotesUpdate)
            @if (count($quote->latestQuotesUpdate->quoteProducts) > 0)
                @include('cotizador.ver_cotizacion.products-information.sections.list-products')
            @else
                <p>No hay Productos Cotizados</p>
            @endif
        @else
            <p>No hay Productos Cotizados</p>
        @endif
    </div>
</div>
