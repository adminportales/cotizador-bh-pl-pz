<div>
    @php
        $subtotal = $quote->latestQuotesUpdate->quoteProducts->sum('precio_total');
        $discount = 0;
        if ($quote->latestQuotesUpdate->quoteDiscount->type == 'Fijo') {
            $discount = $quote->latestQuotesUpdate->quoteDiscount->value;
        } else {
            $discount = round(($subtotal / 100) * $quote->latestQuotesUpdate->quoteDiscount->value, 2);
        }
    @endphp
    <div class="d-flex justify-content-between">
        <h5 class="card-title">Informacion del descuento actual</h5>
        <div style="width: 20px; cursor: pointer;" data-toggle="modal" data-target="#discountModalEdit">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                <path fill-rule="evenodd"
                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                    clip-rule="evenodd" />
            </svg>
        </div>
    </div>
    <div class="d-flex flex-column">
        <p><b>Subtotal: </b>$ {{ $subtotal + $subtotalAdded }}</p>
        <p><b>Descuento: </b>$ {{ $discount }}
        </p>
        <p><b>Total: </b>$ {{ $subtotal + $subtotalAdded - $discount }}</p>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="discountModalEdit" tabindex="-1" aria-labelledby="discountModalEditLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="discountModalEditLabel">Editar Descuento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Tipo de Descuento</label>
                        {{ auth()->user()->currentQuote->type }}
                        <select class="form-control" wire:model.lazy="type">
                            <option value="" {{ auth()->user()->currentQuote->type == '' ? 'selected' : '' }}>
                                Seleccione...
                            </option>
                            <option value="Fijo" {{ auth()->user()->currentQuote->type == 'Fijo' ? 'selected' : '' }}>
                                Valor Fijo
                            </option>
                            <option value="Porcentaje"
                                {{ auth()->user()->currentQuote->type == 'Porcentaje' ? 'selected' : '' }}>
                                Porcentaje</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Cantidad</label>
                        <input type="number" class="form-control" placeholder="ej: 50, 2000" wire:model.lazy="value">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click="updateDiscount">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('hideModalDiscount', event => {
            $('#discountModalEdit').modal('hide')
        })
    </script>
</div>
