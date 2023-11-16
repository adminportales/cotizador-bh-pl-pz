<div class="col-span-4 lg:col-span-1">
    <div class="bg-white shadow-md rounded-md px-6 py-4">
        <p class="text-black text-center text-lg mb-3">Total de la cotizacion</p>
        @if (false)
            <button
                class="bg-gray-200 hover:bg-gray-200 text-black font-bold py-2 px-4 rounded w-full  {{ $cotizacion->discount }}"
                onclick="customToggleModal('discount-product-modal', 1)"
                >
                Agregar Descuento
            </button>
            @include('cotizador.cotizacion_actual.sections.discount-modal')
        @endif
        <div class="content-between">
            <div class="flex justify-between">
                <span class="text-black"> Subtotal:</span>
                <span class="text-black"> $ {{ $totalQuote }}</span>

            </div>
            @if (false)
                <div class="flex justify-between">
                    <span class="text-black"> Descuento: </span>
                    <span class="text-black text-right"> $
                        {{ $discount }}</span>
                </div>
            @endif
            <hr>
            <div class="flex justify-between">
                <span class="text-black">Total:</span>
                <span class="text-black text-right">$ {{ $totalQuote - $discount }}</span>
            </div>
        </div>
        <div class="justify-items-center bg-gray-200 text-center p-2 font-bold">
            <a href="{{ route('finalizar') }}" class="">Finalizar
                Cotizacion</a>
        </div>
    </div>
</div>
