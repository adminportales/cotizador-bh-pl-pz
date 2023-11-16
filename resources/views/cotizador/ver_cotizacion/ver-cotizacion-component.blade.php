<div>
    @if (session()->has('messageError'))
        <div class="w-full">
            <div class="p-4 mb-2 text-sm text-red-800 rounded-lg bg-red-50">
                {{ session('messageError') }}
            </div>
            <div class="p-4 mb-2 text-sm text-yellow-800 rounded-lg bg-yellow-50">
                {{ session('messageMail') }}
            </div>
        </div>
    @endif
    @if (session()->has('message'))
        <div class="w-full" wire:poll.4s>
            <div class="p-4 mb-2 text-sm text-green-800 rounded-lg bg-green-50">
                {{ session('message') }}
            </div>
        </div>
    @endif
    @if ($quote->company_id != auth()->user()->company_session)
        <div class="w-full p-4 mb-2 text-sm text-red-800 rounded-lg bg-red-50">
            Esta cotizacion fue realizada con {{ $quote->company->name }}. Algunas opciones, estan
            deshabilitadas
        </div>
    @endif
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <div class="col-span-1 md:col-span-1">
            @include('cotizador.ver_cotizacion.client-information.client-information')
        </div>
        <div class="col-span-1 md:col-span-3 p-3 border rounded-md">
            <div class="">
                @livewire('cotizador.editar-cotizacion-component', ['quote' => $quote], key($quote->id))
                @if ($quote->latestQuotesUpdate)
                    <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-x-3">
                            <div class="col-span-1 mb-2">
                                <div class="flex justify-center">
                                    <a class="bg-green-200 p-3 w-full rounded-md hover:bg-green-300 text-center"
                                        target="_blank"
                                        href="{{ route('previsualizar.cotizacion', ['quote' => $quote]) }}">Ver PDF</a>
                                </div>
                            </div>
                            <div class="col-span-1 mb-2">
                                <div class="flex justify-center">
                                    <button class="bg-green-200 p-3 w-full rounded-md hover:bg-green-300"
                                        data-toggle="modal" data-target="#createPPTModal">Crear Presentacion</button>
                                </div>

                                {{-- <!-- Modal -->
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
                                </div> --}}
                            </div>
                            @if ($quote->company_id == auth()->user()->company_session)
                                <div class="col-span-1 mb-2">
                                    <div class="flex justify-center">
                                        <div wire:loading wire:target="enviar">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="sr-only">loading...</span>
                                            </div>
                                        </div>
                                        <button class="bg-green-200 p-3 w-full rounded-md hover:bg-green-300"
                                            wire:click="enviar">Enviar al
                                            Cliente</button>

                                    </div>
                                </div>
                                <div class="col-span-1 mb-2">
                                    <div class="flex justify-center">
                                        <div wire:loading wire:target="enviarOdoo">
                                            <div class="spinner-border text-danger" role="status">
                                                <span class="sr-only">loading...</span>
                                            </div>
                                        </div>
                                        <button class="bg-green-200 p-3 w-full rounded-md hover:bg-green-300"
                                            wire:click="enviarOdoo">Enviar a
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

        function customToggleModal(id, estatus) {
            const modalGeneral = new Modal(document.getElementById(id), {
                backdrop: 'static'
            });
            if (estatus == 1) {
                modalGeneral.show();
            } else if (estatus == 0) {
                modalGeneral.hide();
                if (document.querySelector("body > div[modal-backdrop]")) {
                    document.querySelector("body > div[modal-backdrop]")?.remove()
                }
            }
        }
    </script>
</div>
