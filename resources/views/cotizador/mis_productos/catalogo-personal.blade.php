<div class="my-3">
    <div class="card w-100">
        <div class="card-body">
            <div class="flex justify-between pb-8">
                <h4 class="text-xl">Mis Productos</h4>
                @if (session()->has('message'))
                    <div wire:poll.3s class="alert alert-success p-1 m-0">{{ session('message') }}</div>
                @endif
                <div>
                    <a href="{{ route('addProduct.cotizador') }}"
                        class="bg-green-200 p-3  rounded-md hover:bg-green-300">Agregar Nuevo Producto</a>
                </div>
            </div>
            <div>
                <input wire:model='keyWord' type="text"
                    class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                    name="search" id="search" placeholder="Buscar">
                <br>
            </div>
            <div class="grid grid-cols-12 gap-3">
                @foreach ($products as $row)
                    @php
                        $row = $row->product;
                    @endphp
                    @if ($row)
                        <div class="sm:col-span-6 lg:col-span-3 md:col-span-4 col-span-12">
                            <div class="border border-gray-300 rounded-md">
                                <div class="text-center shadow-sm p-2">
                                    @php
                                        $priceProduct = $row->price;
                                    @endphp
                                    <div class="flex justify-between">
                                        <p class="m-0 mb-1 pt-1" style="font-size: 16px">Stock: <span
                                                style="font-weight: bold">{{ $row->stock }}</span></p>
                                        <div class="flex gap-2">
                                            {{-- <h5><strong>Informacion del Cliente</strong></h5> --}}
                                            <div class="text-green-400" style="width: 25px; cursor: pointer;"
                                                wire:click="editProduct({{ $row->id }})">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                    <path fill-rule="evenodd"
                                                        d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="text-red-400" style="width: 25px; cursor: pointer;"
                                                onclick="eliminar({{ $row->id }})">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-row sm:flex-col">
                                        <div class="text-center self-center" style="height: 150px">
                                            <img src="{{ $row->firstImage ? $row->firstImage->image_url : '' }}"
                                                class="align-middle " alt="{{ $row->name }}"
                                                style="width: auto; max-width: 150px; max-height: 150px; height: auto">
                                        </div>
                                        <div class="info-products">
                                            <h5 class="card-title m-0" style="text-transform: capitalize">
                                                {{ Str::limit($row->name, 22, '...') }}</h5>
                                            <p class=" m-0 pt-1" style="font-size: 16px"><strong>SKU:</strong>
                                                {{ $row->sku }}</p>
                                            <p class="m-0 mb-1 pt-1 sm:hidden" style="font-size: 16px">Stock: <span
                                                    style="font-weight: bold">{{ $row->stock }}</span></p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p class=" m-0 pt-1" style="font-weight: bold">$
                                                    {{ $priceProduct }}</p>
                                                <a
                                                    class="block text-white bg-blue-500 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm px-5 py-2.5 text-center"href="{{ route('show.product', ['product' => $row->id]) }}">
                                                    Cotizar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center w-100">
        {{ $products->links() }}
    </div>
    @include('cotizador.mis_productos.sections.edit-product-modal')
    <script>
        function eliminar(id) {
            Swal.fire({
                title: '¿Está seguro?',
                text: "¿Desea eliminar este producto permanentemente?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si!',
                cancelButtonText: 'Cancelar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let respuesta = @this.deleteProduct(id)
                    Swal.fire('Por Favor Espere');
                    respuesta
                        .then((response) => {
                            console.log(response);
                            if (response == 1) {
                                Swal.fire(
                                    'Se ha elimiado este producto correctamente',
                                    '',
                                    'success'
                                )
                            }
                            /* if (response != 1) {
                                Swal.fire(
                                    'Se enviaron los accesos, pero estos no se pudieron enviar',
                                    response, 'success')
                            } */
                        }, function() {
                            // one or more failed
                            Swal.fire('¡Error al elimiar el producto!', '', 'error')
                        });
                }
            })
        }

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


        /* window.addEventListener('hideModalScales', event => {
            $('#addScaleModal').modal('hide')
        })
        window.addEventListener('showModalScales', event => {
            $('#addScaleModal').modal('show')
        }) */

        function deleteEscala(id) {
            Swal.fire({
                title: '¿Está seguro?',
                text: "¿Desea eliminar esta escala?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    let respuesta = @this.deleteScale(id)
                    Swal.fire('Por Favor Espere');
                    respuesta
                        .then((response) => {
                            console.log(response);
                            if (response == 1) {
                                Swal.fire(
                                    'Se ha elimiado esta escala correctamente',
                                    '',
                                    'success'
                                )
                            }
                        }, function() {
                            // one or more failed
                            Swal.fire('¡Error al elimiar el producto!', '', 'error')
                        });
                }
            })
        }
    </script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</div>
