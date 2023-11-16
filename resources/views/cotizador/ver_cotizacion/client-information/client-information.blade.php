<div class="flex flex-col p-3 gap-y-3 border rounded-md">
    @if ($quote->latestQuotesUpdate)
        <div class="flex justify-between">
            <strong class="w-5/6">Información del Cliente</strong>
            @if ($quote->company_id == auth()->user()->company_session)
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-target="editarInfoCliente" data-modal-toggle="editarInfoCliente">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                        <path fill-rule="evenodd"
                            d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            @endif
        </div>

        <h5 class="text-lg">{{ $quote->latestQuotesUpdate->quotesInformation->name }} |
            {{ $quote->latestQuotesUpdate->quotesInformation->company }}</h5>
        @if (auth()->user()->hasRole('admin'))
            <p><b>Ejecutivo: </b> {{ $quote->user->name }}</p>
            <br>
        @endif
        <p><b>Nombre Comercial: </b> {{ $nombreComercial ? $nombreComercial->name : 'Sin Definir' }}</p>
        <p><b>Numero de lead: </b> {{ $quote->lead }}</p>
        <p><b>Oportunidad: </b> {{ $quote->latestQuotesUpdate->quotesInformation->oportunity }}</p>
        <p><b>Probabilidad de Venta: </b>
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
        </p>
        <br>
        <p><b>Email: </b>{{ $quote->latestQuotesUpdate->quotesInformation->email }}</p>
        <p><b>Telefono: </b>{{ $quote->latestQuotesUpdate->quotesInformation->landline }}</p>
        <p><b>Celular: </b>{{ $quote->latestQuotesUpdate->quotesInformation->cell_phone }}</p>
        <p><b>Departamento: </b>{{ $quote->latestQuotesUpdate->quotesInformation->department }}</p>
        <p><b>El IVA se muestra: </b>{{ $quote->iva_by_item ? 'Por Producto' : 'En el total' }}</p>
        <p>El monto total <b>{{ $quote->show_total ? 'SI' : 'NO' }}</b> se muestra</p>
        <p>Los dias son: <b>{{ $quote->type_days == 0 ? 'Habiles' : 'Naturales' }}</b></p>
        @if ($quote->latestQuotesUpdate->quotesInformation->shelf_life)
            <p><b>Duracion de la cotizacion:
                </b>{{ $quote->latestQuotesUpdate->quotesInformation->shelf_life }}</p>
        @endif
        @if ($quote->latestQuotesUpdate->quotesInformation->tax_fee)
            <p><b>Tax Fee: </b> {{ $quote->latestQuotesUpdate->quotesInformation->tax_fee }} % </p>
        @endif
        <p><b>Informacion adicional:
            </b>{{ $quote->latestQuotesUpdate->quotesInformation->information }}
        </p>
    @else
        <p>Sin informacion</p>
    @endif
    @if ($quote->logo)
        <div class="text-center">
            <p>Logo del cliente</p>
            <img src="{{ asset($quote->logo) }}" alt="" style="max-width: 100%; height: 130px;">
        </div>
    @endif
    @if ($quote->latestQuotesUpdate)
        <div wire:ignore.self id="editarInfoCliente" data-modal-backdrop="static" tabindex="-1"
            aria-hidden="true"
            class="justify-center fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div
                        class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                        <h5 class="text-xl font-semibold text-gray-900 dark:text-white"
                            id="editarInfoCliente">
                            Editar Informacion</h5>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="editarInfoCliente">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-6">
                        @livewire('cotizador.edit-information-client-component', ['quoteInfo' => $quote->latestQuotesUpdate->quotesInformation, 'quote' => $quote])
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
