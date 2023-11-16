{{-- <div class="modal fade" id="discountModalEdit" tabindex="-1" aria-labelledby="discountModalEditLabel"
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
                    <select class="form-control" wire:model.lazy="type">
                        <option value=""
                            {{ $quote->latestQuotesUpdate->quoteDiscount->type == '' ? 'selected' : '' }}>
                            Seleccione...
                        </option>
                        <option value="Fijo"
                            {{ $quote->latestQuotesUpdate->quoteDiscount->type == 'Fijo' ? 'selected' : '' }}>
                            Valor Fijo
                        </option>
                        <option value="Porcentaje"
                            {{ $quote->latestQuotesUpdate->quoteDiscount->type == 'Porcentaje' ? 'selected' : '' }}>
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

                <div class="d-flex justify-content-center">
                    <div wire:loading wire:target="updateDiscount">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">loading...</span>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" wire:click="updateDiscount">Actualizar</button>
                </div>
            </div>

        </div>
    </div>
</div> --}}
<script>
    window.addEventListener('hideModalDiscount', event => {
        $('#discountModalEdit').modal('hide')
    })
</script>
