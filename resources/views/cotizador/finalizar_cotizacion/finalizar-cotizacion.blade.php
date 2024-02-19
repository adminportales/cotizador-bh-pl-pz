<div class="">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                @if (auth()->user()->currentQuoteActive)
                    @if (count(auth()->user()->currentQuoteActive->currentQuoteDetails) > 0)
                        <div class="card-body">
                            <p class="mb-2 text-xl">Finalizar la cotizacion</p>
                            @include('cotizador.finalizar_cotizacion.sections.form-finalizar')
                            <br>
                            <p class="mb-2 text-xl">Resumen </p>
                            <div class="flex gap-3 md:flex-row flex-col justify-between">
                                <div class="relative overflow-x-auto my-2">
                                    <table class="w-full text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        <thead
                                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr class="text-center">
                                                <th scope="col" class="px-5 py-3">
                                                    Producto
                                                </th>
                                                <th scope="col" class="px-5 py-3">
                                                    Subtotal
                                                </th>
                                                <th scope="col" class="px-5 py-3">
                                                    Piezas
                                                </th>
                                                <th scope="col" class="px-5 py-3">
                                                    Total
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $scales = false;
                                            @endphp
                                            @foreach (auth()->user()->currentQuoteActive->currentQuoteDetails as $quote)
                                                <tr
                                                    class="text-center bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                    <td class="px-5 py-3">
                                                        <p class="m-0">{{ $quote->product->name }}</p>
                                                    </td>
                                                    @if ($quote->quote_by_scales)
                                                        <td colspan="3">
                                                            <table
                                                                class="w-full text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                                                <tbody>
                                                                    @php
                                                                        $scales = true;
                                                                        $priceScales = json_decode($quote->scales_info);
                                                                    @endphp
                                                                    @foreach ($priceScales as $it)
                                                                        <tr
                                                                            class="text-center bg-white border-b dark:bg-gray-800 dark:border-gray-700">

                                                                            <td class="px-5 py-3">
                                                                                <p class="m-0">$
                                                                                    {{ $it->unit_price }}
                                                                                </p>
                                                                            </td>
                                                                            <td class="px-5 py-3">
                                                                                <p class="inline">
                                                                                    {{ $it->quantity }} pz
                                                                                </p>
                                                                            </td>
                                                                            <td class="px-5 py-3">
                                                                                $ {{ $it->total_price }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    @else
                                                        <td class="px-5 py-3">
                                                            <p class="inline">$ {{ $quote->precio_unitario }}</p>
                                                        </td>
                                                        <td class="px-5 py-3">
                                                            <p class="inline"> {{ $quote->cantidad }} pz</p>
                                                        <td class="px-5 py-3">
                                                            <p class="inline">$ {{ $quote->precio_total }}</p>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            @if (!$scales)
                                                @php
                                                    $subtotal = auth()
                                                        ->user()
                                                        ->currentQuoteActive->currentQuoteDetails->sum('precio_total');
                                                    $discount = 0;
                                                    if (auth()->user()->currentQuoteActive->type == 'Fijo') {
                                                        $discount = auth()->user()->currentQuoteActive->value;
                                                    } else {
                                                        $discount = round(($subtotal / 100) * auth()->user()->currentQuoteActive->value, 2);
                                                    }
                                                @endphp
                                                <tr>
                                                    <th colspan="3">Subtotal</th>
                                                    <th>$ {{ $subtotal }}</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="3">Descuento</th>
                                                    <th>$ {{ $discount }}</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="3">Total</th>
                                                    <th>$ {{ $subtotal - $discount }}</th>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <div class="">
                                    <div wire:loading wire:target="guardarCotizacion">
                                        <div role="status">
                                            <svg aria-hidden="true"
                                                class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                                                viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
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


                                    <div class="flex flex-col gap-2">
                                        <input type="checkbox" wire:model="enviarCorreo" id="enviarCorreo"> Enviar cotización por correo electrónico
                                        <button class="bg-gray-200 p-3 rounded-md hover:bg-gray-300"
                                            data-modal-target="preview" data-modal-toggle="preview"
                                            onclick="preview()">Previsualizar Cotizacion</button>
                                        <button class="bg-gray-200 p-3  rounded-md hover:bg-gray-300"
                                            onclick="enviar()">Guardar
                                            Cotizacion</button>
                                    </div>
                                </div>
                                {{-- <div class="d-flex flex-column">
                                <div wire:loading wire:target="guardarCotizacion">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                <button class="btn btn-primary btn-sm btn-block mb-1"
                                    onclick="preview()">Previsualizar
                                    Cotizacion</button>
                                <button class="btn btn-primary btn-sm btn-block" onclick="enviar()">Guardar
                                    Cotizacion</button>
                            </div> --}}

                            </div>

                            {{-- <div wire:ignore.self class="modal fade" id="preview" tabindex="-1"
                            aria-labelledby="modalPreviewLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalPreviewLabel">Vista Previa de la Cotización
                                        </h5>
                                        <button type="button" class="close" onclick="cerrarPreview()">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div wire:loading.flex wire:target="previewQuote"
                                            class="justify-content-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                        @if ($urlPDFPreview)
                                            <iframe src="{{ $urlPDFPreview }}" style="width:100%; height:700px;"
                                                frameborder="0"></iframe>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            onclick="cerrarPreview()">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                            <div wire:ignore.self id="preview" tabindex="-1" aria-hidden="true"
                                class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                <div class="relative w-full max-w-5xl h-[95%]">
                                    <!-- Modal content -->
                                    <div
                                        class="relative bg-white rounded-lg shadow dark:bg-gray-700 h-full flex flex-col pb-5">
                                        <!-- Modal header -->
                                        <div
                                            class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                Vista Previa de la Cotización
                                            </h3>
                                            <button type="button"
                                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                                data-modal-hide="preview">
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
                                        <div class="flex-grow px-5 flex flex-col justify-center">
                                            <div wire:loading.flex wire:target="previewQuote" class="justify-center">
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

                                            @if ($urlPDFPreview)
                                                <iframe src="{{ $urlPDFPreview }}" class="w-full h-full"
                                                    wire:loading.class="hidden" frameborder="0"></iframe>
                                            @endif
                                        </div>

                                        <div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-center mb-4">No tienes productos en tu cotizacion actual</p>
                        {{-- Boton ir al carrito  --}}
                        <div class="flex justify-center">
                            <a href="{{ route('cotizacion') }}"
                                class="bg-green-200 p-3 rounded-md hover:bg-green-300">Ir al
                                carrito</a>
                        </div>
                    @endif
                @else
                    <p class="text-center mb-4">No tienes una cotizacion activa</p>
                    <div class="flex justify-center">
                        <a href="{{ route('cotizacion') }}" class="bg-green-200 p-3 rounded-md hover:bg-green-300">Ir
                            al
                            carrito</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        window.addEventListener('isntCompany', event => {
            Swal.fire('No tienes una empresa asignada')
        })

        function preview() {
            //$('#modalPreview').modal('show')
            @this.previewQuote()


        }

        function cerrarPreview() {
            //$('#modalPreview').modal('hide')
            @this.urlPDFPreview = null;
        }

        function enviar() {
            const enviarCorreo = document.getElementById('enviar_correo').checked;
            Swal.fire({
                title: '¿Desea confirmar la cotización?',
                html: "{{ auth()->user()->companySession->name }}<br><br>Se enviará una copia de la cotización al correo electrónico establecido y se registra como un lead nuevo en Odoo.",
                showCancelButton: true,
                icon: 'warning',
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    @this.guardarCotizacion(enviarCorreo)
                } else {
                    Swal.fire('No se realizo ningun cambio', '', 'info')
                }
            })
        }
    </script>


</div>
