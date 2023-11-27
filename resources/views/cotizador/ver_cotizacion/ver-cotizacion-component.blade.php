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
                    <div class="grid md:grid-cols-2 grid-cols-1 gap-3">
                        <div class="col-span-1">
                            {{-- Lista de presentaciones --}}
                            <div class="grid grid-cols-1 gap-3">
                                <div class="col-span-1">
                                    <p>Presentaciones realizadas</p>
                                    <div
                                        class="w-full text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        @foreach ($quote->presentations()->orderBy('created_at', 'DESC')->get() as $presentation)
                                            <a href="{{ route('previsualizar_ppt.cotizacion', ['presentacion' => $presentation->id]) }}"
                                                target="_blank"
                                                class="relative flex justify-between  items-center w-full px-4 py-4 text-sm font-medium border-b border-gray-200 {{ $loop->first ? 'rounded-t-lg' : '' }} {{ $loop->last ? 'rounded-b-lg' : '' }} hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                                                {{ $presentation->created_at->format('d-m-Y H:m:s') }}
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-5 h-5 ml-2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </a>
                                        @endforeach
                                        @if ($quote->presentations->count() == 0)
                                            <div class="p-4 text-center">
                                                <p>No hay presentaciones realizadas</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-3">
                                <div class="col-span-1 mb-2">
                                    <div class="flex justify-center">
                                        <a class="bg-green-200 p-3 w-full rounded-md hover:bg-green-300 text-center"
                                            target="_blank"
                                            href="{{ route('previsualizar.cotizacion', ['quote' => $quote]) }}">Ver
                                            PDF</a>
                                    </div>
                                </div>
                                <div class="col-span-1 mb-2">
                                    <div class="flex justify-center">
                                        <button class="bg-green-200 p-3 w-full rounded-md hover:bg-green-300"
                                            onclick="customToggleModal('createPPTModal', 1)">Crear Presentacion</button>
                                    </div>
                                    <div wire:ignore.self id="createPPTModal" tabindex="-1" aria-hidden="true"
                                        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                        <div class="relative p-4 w-full max-w-7xl max-h-full">
                                            <!-- Modal content -->
                                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                <!-- Modal header -->
                                                <div
                                                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                        Crea tu
                                                        presentacion
                                                    </h3>
                                                    <button type="button"
                                                        onclick="customToggleModal('createPPTModal', 0)"
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
                                </div>
                                @if ($quote->company_id == auth()->user()->company_session)
                                    <div class="col-span-1 mb-2">
                                        <div class="flex justify-center">
                                            <div wire:loading wire:target="enviar">
                                                <div role="status">
                                                    <svg aria-hidden="true"
                                                        class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                                                        viewBox="0 0 100 101" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                                            fill="currentColor" />
                                                        <path
                                                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                                            fill="currentFill" />
                                                    </svg>
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>
                                            <button class="bg-yellow-200 p-3 w-full rounded-md hover:bg-yellow-300"
                                                wire:click="enviar">Enviar al
                                                Cliente</button>

                                        </div>
                                    </div>
                                    <div class="col-span-1 mb-2">
                                        <div class="flex justify-center">
                                            <div wire:loading wire:target="enviarOdoo">
                                                <div role="status">
                                                    <svg aria-hidden="true"
                                                        class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                                                        viewBox="0 0 100 101" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                                            fill="currentColor" />
                                                        <path
                                                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                                            fill="currentFill" />
                                                    </svg>
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>
                                            <button class="bg-yellow-200 p-3 w-full rounded-md hover:bg-yellow-300"
                                                wire:click="enviarOdoo">Enviar a
                                                ODOO</button>
                                        </div>
                                    </div>
                                @endif
                            </div>
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
