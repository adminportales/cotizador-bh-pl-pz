<!-- Show Product modal -->
<div wire:ignore.self id="discount-product-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Detalles del Descuento
                </h3>
                <button type="button" onclick="customToggleModal('discount-product-modal', 0)"
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
            <div class="p-4 md:p-5 space-y-4">
                <label for="countries" class=" mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo
                    de
                    Descuento</label>
                <select id="countries"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" wire:model="type">
                    <option value="Seleccione">Seleccione</option>
                    <option value="valorfijo">Valor Fijo</option>
                    <option value="porcentaje">Porcentaje</option>
                </select>
                <label for="">Cantidad</label>
                <input class="w-full py-1 text-center rounded-lg ring-2 ring-stone-950 ring-inset " type="number"
                    name="cantidad" wire:model='value'>
                <button type="button" class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-2 rounded-md"
                    data-dismiss="modal">Salir</button>
                @if ($cotizacion->discount)
                    <button type="button" class="bg-yellow-500 text-white hover:bg-yellow-600 px-4 py-2 rounded-md"
                        wire:click="addDiscount">Editar</button>
                    <button type="button" class="bg-red-500 text-white hover:bg-red-600 px-4 py-2 rounded-md"
                        wire:click="eliminarDescuento">Eliminar</button>
                @else
                    <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-md m-2"
                        wire:click="addDiscount">Guardar</button>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('show-modal-edit', event => {
        customToggleModal('discount-product-modal', 1)
    })
    window.addEventListener('hide-modal-edit', event => {
        customToggleModal('discount-product-modal', 0)
    })
</script>
