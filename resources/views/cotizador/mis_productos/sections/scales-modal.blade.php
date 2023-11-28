<div>
    <!-- Show Product modal -->
    <div wire:ignore.self id="scales-new-product-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $editScale ? 'Editar la cantidad de Productos' : 'Agregar una nueva cantidad' }}
                    </h3>
                    <button type="button" onclick="customToggleModal('scales-new-product-modal', 0)"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4 grid grid-cols-2 gap-3">
                    <div class="col-span-2">
                        <p class="m-0">Importante</p>
                        <p class="m-0">Colocar las escalas de precio en orden, de menor a mayor y sin repetir
                            datos en las escalas</p>
                        <p class="m-0">Ejemplo:</p>
                        <div class="relative overflow-x-auto my-2">
                            <table class="w-full text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th>Escala Inicial</th>
                                        <th>Escala Final</th>
                                        <th>Escala Costo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>500</td>
                                        <td>$ 2.50</td>
                                    </tr>
                                    <tr>
                                        <td>501</td>
                                        <td>999</td>
                                        <td>$ 2.40</td>
                                    </tr>
                                    <tr>
                                        <td>1000</td>
                                        <td>9999</td>
                                        <td>$ 2.40</td>
                                    </tr>
                                    <tr>
                                        <td>10000</td>
                                        <td>-</td>
                                        <td>$ 2.40</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="m-0 mb-1 col-span-2 md:col-span-1">
                        <label for="cantidad" class="text-dark m-0"><strong>Escala Inicial</strong>
                        </label>
                        <input type="number" name="cantidad" wire:model="inicial" placeholder="Escala Inicial"
                            class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 ">
                    </div>
                    <div class="m-0 mb-1 col-span-2 md:col-span-1">
                        <label for="cantidad" class="text-dark m-0"><strong>Escala Final</strong>
                        </label>
                        <input type="number" name="cantidad" wire:model="final" placeholder="Escala Final"
                            class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 ">
                    </div>
                    <div class="m-0 mb-1 col-span-2">
                        <label for="margen" class="text-dark m-0"><strong>Costo</strong> </label>
                        <input type="number" name="margen" wire:model="costo" placeholder="Costo"
                            class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 ">
                    </div>
                    <div class="d-flex col-12 flex-column">
                        {{-- Errores de validacion --}}
                        @if ($errors->has('inicial'))
                            <span class="text-danger">{{ $errors->first('inicial') }}</span>
                        @endif
                        @if ($errors->has('costo'))
                            <span class="text-danger">{{ $errors->first('costo') }}</span>
                        @endif
                    </div>
                </div>
                <div class="border-t p-4 md:p-5 space-y-4">
                    <button type="button" class="bg-gray-200 p-3  rounded-md hover:bg-gray-300" wire:click='closeScale'>Cerrar</button>
                    @if ($editScale)
                        <button type="button" class="bg-yellow-200 p-3  rounded-md hover:bg-yellow-300" wire:click='updateScale'>Editar</button>
                    @else
                        <button type="button" class="bg-green-200 p-3  rounded-md hover:bg-green-300" wire:click='addScale'>Agregar</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('showModalScales', event => {
            customToggleModal('scales-new-product-modal', 1)
        })
        window.addEventListener('hideModalScales', event => {
            customToggleModal('scales-new-product-modal', 0)
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
