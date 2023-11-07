<div>
    <div class="row">
        @if (session()->has('messageError'))
            <div class="col-12">
                <div class="alert alert-warning w-100 alert-dismissible fade show" role="alert"
                    style="margin-top:0px; margin-bottom:0px;">
                    {{ session('messageError') }}

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="alert alert-danger w-100 alert-dismissible fade show" role="alert"
                    style="margin-top:0px; margin-bottom:0px;">
                    {{ session('messageMail') }}

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif

        @if (session()->has('message'))
            <div class="col-12" wire:poll.4s>
                <div class="alert alert-success w-100" style="margin-top:0px; margin-bottom:0px;">
                    {{ session('message') }} </div>
            </div>
        @endif
        @if ($quote->company_id != auth()->user()->company_session)
            <div class="alert alert-warning w-100 alert-dismissible fade show" role="alert"
                style="margin-top:0px; margin-bottom:0px;">
                Esta cotizacion fue realizada con {{ $quote->company->name }}. Algunas opciones, estan
                deshabilitadas
            </div>
        @endif
        <div class="col-md-4 flex">

            <div
                class="flex flex-col w-1/2 h-fit bg-gray-100 p-1 border border-black rounded-md drop-shadow-xl lg:w-1/4 ">
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


                                <div class="modal-body text-center">

                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div>

                        </div>
                    </div>
                @endif


            </div>
            <div class="col-md-8 w-1/2">
                <div class="card h-100">
                    @livewire('cotizador.editar-cotizacion-component', ['quote' => $quote], key($quote->id))
                    @if ($quote->latestQuotesUpdate)
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <a class="btn btn-success w-100" target="_blank"
                                        href="{{ route('previsualizar.cotizacion', ['quote' => $quote]) }}">Ver PDF</a>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-info w-100" data-toggle="modal"
                                            data-target="#createPPTModal">Crear Presentacion</button>
                                    </div>

                                    <!-- Modal -->
                                    <div wire:ignore.self class="modal fade" id="createPPTModal" tabindex="-1"
                                        data-backdrop="static" aria-labelledby="createPPTModalLabel" aria-hidden="true">

                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="createPPTModalLabel">Crea tu
                                                        presentacion
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    @livewire('cotizador.create-presentation-component', ['quote' => $quote])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if ($quote->company_id == auth()->user()->company_session)
                                    <div class="col-md-4 mb-2">
                                        <div class="d-flex justify-content-center">
                                            <div wire:loading wire:target="enviar">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="sr-only">loading...</span>
                                                </div>
                                            </div>
                                            <button class="btn btn-primary w-100" wire:click="enviar">Enviar al
                                                Cliente</button>

                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="d-flex justify-content-center">
                                            <div wire:loading wire:target="enviarOdoo">
                                                <div class="spinner-border text-danger" role="status">
                                                    <span class="sr-only">loading...</span>
                                                </div>
                                            </div>
                                            <button class="btn btn-danger w-100" wire:click="enviarOdoo">Enviar a
                                                ODOO</button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-2">
            <div class="card w-100">
                <div class="card-body">
                    <h5 class="card-title">Historial de Modificaciones</h5>
                    <div class="accordion w-100" id="accordionExample">
                        <div class="card w-100">
                            @if (count($quote->quotesUpdate) > 0)
                                @foreach ($quote->quotesUpdate()->orderBy('created_at', 'DESC')->get() as $item)
                                    <div class="card-header w-100" id="modificacion{{ $item->id }}">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left collapsed" type="button"
                                                data-toggle="collapse" data-target="#collapseMod{{ $item->id }}"
                                                aria-expanded="false" aria-controls="collapseMod{{ $item->id }}">
                                                {{ $item->created_at }}
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseMod{{ $item->id }}" class="collapse"
                                        aria-labelledby="modificacion{{ $item->id }} "
                                        data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div>
                                                <p class="m-0"><strong>Informacion de Descuento Actual</strong>
                                            </div>
                                            </p>

                                            <p class="m-0"><strong>Cantidad:
                                                </strong>{{ $item->quoteDiscount->value }}</p>

                                            <p class="m-0"> <strong>Tipo de descuento:
                                                </strong>{{ $item->quoteDiscount->type }}</p>
                                            <br>
                                            <div>
                                                <p class="m-0"><strong>Cliente</strong>
                                            </div>
                                            </p>

                                            <div>
                                                <p class="m-0"><strong>Nombre: </strong>
                                                    {{ $item->quotesInformation->name }}</p>
                                                <p class="m-0"><strong>Compañia: </strong>
                                                    {{ $item->quotesInformation->company }}</p>
                                                <p class="m-0"><strong>Correo: </strong>
                                                    {{ $item->quotesInformation->email }}</p>
                                                <p class="m-0"><strong>Telefono: </strong>
                                                    {{ $item->quotesInformation->landline }}</p>
                                                <p class="m-0"><strong>Celular: </strong>
                                                    {{ $item->quotesInformation->cell_phone }}</p>
                                                <p class="m-0"> <strong>Oportunidad: </strong>
                                                    {{ $item->quotesInformation->oportunity }}</p>
                                                <p class="m-0"><strong>Probabilidad de Venta: </strong>
                                                    {{ $item->quotesInformation->rank }}</p>
                                                <p class="m-0"><strong>Departamento: </strong>
                                                    {{ $item->quotesInformation->department }}</p>
                                                <p class="m-0"><strong> Informacion adicional: </strong>
                                                    {{ $item->quotesInformation->information }}
                                            </div>
                                            </p>
                                            <br>
                                            <div>
                                                <p class="m-0"><strong>Productos Cotizados</strong></p>
                                                @foreach ($item->quoteProducts as $product)
                                                    <div>
                                                        <p class="m-0"><strong>Num: </strong>{{ $product->id }}
                                                        </p>
                                                        <br>
                                                        @php
                                                            $producto = (object) json_decode($product->product);
                                                            $tecnica = (object) json_decode($product->technique);
                                                        @endphp
                                                        <p class="m-0"><strong>Producto:
                                                            </strong>{{ $producto->name }}</p>
                                                        <p class="m-0"><strong>Tecnica:
                                                            </strong>{{ $tecnica->size }} {{ $tecnica->tecnica }}</p>

                                                        <p class="m-0"><strong>Precios de tecnica:
                                                            </strong>{{ $product->prices_techniques }}</p>

                                                        <p class="m-0"><strong>Color/logos:
                                                            </strong>{{ $product->color_logos }}</p>

                                                        <p class="m-0"><strong>Costo indirecto:
                                                            </strong>{{ $product->costo_indirecto }}</p>

                                                        <p class="m-0"><strong>Utilidad:
                                                            </strong>{{ $product->utilidad }}</p>

                                                        <p class="m-0"><strong>Dias de entrega:
                                                            </strong>{{ $product->dias_entrega }}</p>

                                                        <p class="m-0"><strong>Cantidad:
                                                            </strong>{{ $product->cantidad }}</p>

                                                        <p class="m-0"><strong>Precio unitario:
                                                            </strong>{{ $product->precio_unitario }}</p>

                                                        <p class="m-0"><strong>Precio total:
                                                            </strong>{{ $product->precio_total }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <br>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="card-header" id="sinModificacionesCollapse">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left collapsed" type="button"
                                            data-toggle="collapse" data-target="#collapsesinModificacionesCollapse"
                                            aria-expanded="false" aria-controls="collapsesinModificacionesCollapse">
                                            Sin Modificaciones
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapsesinModificacionesCollapse" class="collapse"
                                    aria-labelledby="sinModificacionesCollapse" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <p>No hay modificaciones</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('showModalInfoClient', event => {
            $('#editarInfoCliente').modal('hide')
        })
        window.addEventListener('Enviar cliente y oddo', event => {
            Swal.fire('Enviado correctamente')
        })
        window.addEventListener('Editarcliente', event => {
            Swal.fire('Informacion Actualizada')
        })
        window.addEventListener('errorSendMail', event => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: event.detail.message,
            })
        })
    </script>
</div>
