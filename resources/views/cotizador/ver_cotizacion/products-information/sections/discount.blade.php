@if (!$quoteScales)

    @php
        $subtotal = $sumPrecioTotal - $subtotalSubstract;
        $discountValue = 0;
        if ($type == 'Fijo') {
            $discountValue = $value;
        } else {
            $discountValue = round(($subtotal / 100) * $value, 2);
        }
    @endphp
    <div class="flex justify-between mt-3">
        <h5 class="font-semibold text-lg">Informacion del descuento actual</h5>
        @if ($quote->company_id == auth()->user()->company_session)
            <div class="text-success" style="width: 25px; cursor: pointer;" data-toggle="modal"
                data-target="#discountModalEdit" data-toggle="tooltip" data-placement="bottom" title="Editar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                    <path fill-rule="evenodd"
                        d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                        clip-rule="evenodd" />
                </svg>
            </div>
        @endif
    </div>
    <div class="flex flex-col gap-2">
        <p><b>Subtotal: </b>$ {{ number_format($subtotal + $subtotalAdded, 2, '.', ',') }}</p>
        <p><b>Descuento: </b>$ {{ number_format($discountValue, 2, '.', ',') }}
        </p>
        <p><b>Total: </b>$
            {{ number_format($subtotal + $subtotalAdded - $discountValue, 2, '.', ',') }}
        </p>
    </div>
@endif
