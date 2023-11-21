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
                                        onclick="customToggleModal('createPPTModal', 1)">Crear Presentacion</button>
                                </div>
                                {{--  --}}
                                <div wire:ignore.self id="createPPTModal" tabindex="-1" aria-hidden="true"
                                    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    <div class="relative p-4 w-full max-w-4xl max-h-full">
                                        <!-- Modal content -->
                                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                            <!-- Modal header -->
                                            <div
                                                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                    Crea tu
                                                    presentacion
                                                </h3>
                                                <button type="button" onclick="customToggleModal('createPPTModal', 0)"
                                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                                    <svg class="w-3 h-3" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 14 14">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2"
                                                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                    </svg>
                                                    <span class="sr-only">Close modal</span>
                                                </button>
                                            </div>
                                            <!-- Modal body -->

                                            <div class="p-10">
                                                @livewire('cotizador.create-presentation-component', ['quote' => $quote])
                                            </div>

                                        </div>
                                    </div>
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
