@if (count($allQuotes) > 0)
    <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200">
        <ul class="flex flex-wrap">
            @foreach ($allQuotes as $item)
                <li class="hover:border-b-2 rounded-t-lg cursor-pointer text-center flex items-center {{ $item->active ? 'bg-gray-50 active border-gray-300 border-b-2 ' : '' }}"
                    wire:click='selectQuoteActive({{ $item->id }})'>
                    <a class="inline-block py-2 px-4 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300"
                        aria-current="page">{{ $item->name ?: 'Cotizacion Actual' }}</a>
                    @if ($item->active)
                        <div class="flex">
                            <button wire:click="editQuote({{ $item->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>

                            <button onclick="eliminarCurrentQuote({{ $item->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    @endif
                </li>
            @endforeach
            <li class="hover:border-b-2 rounded-t-lg cursor-pointer text-center flex items-center"
                onclick="customToggleModal('modal-add-quote',1)">
                <a class="inline-block p-4 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300"
                    aria-current="page">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </a>
            </li>
        </ul>
    </div>
@endif

<div wire:ignore.self id="modal-add-quote" data-modal-backdrop="static" tabindex="-1" aria-hidden="true"
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900 ">
                    {{ $quoteEdit ? 'Editar Nombre de la Cotizacion' : 'Agregar nueva cotizacion' }}
                </h3>
            </div>
            <!-- Modal body -->
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-2 gap-2 text-left">
                    @if (!$quoteEdit)
                        <p class="col-span-2">Esta funcion es util cuando quieres crear una nueva cotizacion para un
                            cliente nuevo o
                            para el mismo cliente, pero no quieres perder el progreso de tu cotizacion actual</p>
                    @endif
                    <div class="col-span-2">
                        <label for="" class="text-sm font-semibold">Nombre de la cotizacion</label>
                        <input
                            class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                            type="text" placeholder="Ej. Bolsas Coca Cola" wire:model="nameQuote">
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b ">
                @if ($quoteEdit)
                    <button type="button" class="btn btn-primary btn-sm" wire:click="updateQuote">Editar</button>
                @else
                    <button type="button" class="btn btn-primary btn-sm" wire:click="addQuote">Agregar</button>
                @endif
                <button onclick="customToggleModal('modal-add-quote',0)" type="button"
                    class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 ">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('hideModalAddQuote', event => {
        customToggleModal('modal-add-quote', 0)
    })
    window.addEventListener('showModalEditQuote', event => {
        customToggleModal('modal-add-quote', 1)
    })

    function eliminarCurrentQuote(quoteId) {
        Swal.fire({
            title: 'Esta seguro?',
            text: "Esta accion ya no se puede revertir!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar mi cotizacion!',
            cancelButtonText: 'Cancelar!'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.deleteCurrentQuote(quoteId)
                Swal.fire(
                    'Eliminado!',
                    'El producto se ha eliminado.',
                    'success'
                )
            }
        })
    }
</script>
