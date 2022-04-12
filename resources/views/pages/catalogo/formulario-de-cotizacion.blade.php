<div>
    <form wire:submit.prevent="agregarCotizacion">
        <div class="form-group d-flex align-items-center">
            <label for="tecnica" class="w-50 text-dark"><strong>Tecnica</strong> </label>
            <input type="text" name="tecnica" wire:model="tecnica" placeholder="Tecnica de personalizacion"
                class="form-control">
        </div>
        <div class="form-group d-flex align-items-center">
            <label for="colores" class="w-50 text-dark"><strong>Cantidad de Colores/Logos</strong> </label>
            <input type="number" name="colores" wire:model="colores" placeholder="Cantidad de Colores"
                class="form-control">
        </div>
        <div class="form-group d-flex align-items-center">
            <label for="operacion" class="w-50 text-dark"><strong>Costo Indirecto de operacion</strong>
            </label>
            <input type="number" name="operacion" wire:model="operacion" placeholder="Costo de operacion"
                class="form-control">
        </div>
        <div class="form-group d-flex align-items-center">
            <label for="margen" class="w-50 text-dark"><strong>Margen de Utilidad</strong> </label>
            <input type="number" name="margen" wire:model="utilidad" placeholder="Margen de Utilidad"
                class="form-control" max="100">
        </div>
        <div class="form-group d-flex align-items-center">
            <label for="dias" class="w-50 text-dark"><strong>Dias de Entrega</strong> </label>
            <input type="number" name="dias" wire:model="entrega" placeholder="Dias de entrega estimada"
                class="form-control">
        </div>
        <div class="form-group d-flex align-items-center">
            <label for="cantidad" class="w-50 text-dark"><strong>Cantidad</strong> </label>
            <input type="number" name="cantidad" wire:model="cantidad" placeholder="Cantidad de productos"
                class="form-control" max="{{ $product->stock }}">
        </div>
        <h5 class="text-success">Precio : $ {{ $precio }}</h5>
        <h5 class="text-success">Precio Final: $ {{ $precioCalculado }}</h5>
        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary py-2 px-4">Añadir a la cotizacion</button>
        </div>
    </form>
</div>
