<div>
    <p class="w-100"><strong>Personalizacion de la tecnica</strong></p>
    <div class="border border-primary rounded p-2">
        <div class="row">
            <div class="form-group col-md-6">
                <label for="tecnica" class="text-dark"><strong>Material</strong> </label>
                <select name="" id="" class="form-control" wire:model="materialSeleccionado"
                    wire:change="resetSizes">
                    <option value="">Seleccione el material</option>
                    @foreach ($materiales as $material)
                        <option value="{{ $material->id }}">{{ $material->nombre }}</option>
                    @endforeach
                </select>
            </div>
            @if ($techniquesAvailables)
                <div class="form-group col-md-6">
                    <label for="tecnica" class="text-dark"><strong>Tecnica</strong> </label>
                    <select name="" id="" class="form-control" wire:model="tecnicaSeleccionada">
                        <option value="">Seleccione la tecnica</option>
                        @foreach ($techniquesAvailables as $technique)
                            <option value="{{ $technique->id }}">{{ $technique->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
            @if ($sizesAvailables)
                <div class="form-group col-md-6">
                    <label for="tecnica" class="text-dark"><strong>Tamaño</strong> </label>
                    <select name="" id="" class="form-control" wire:model="sizeSeleccionado">
                        <option value="">Seleccione el tamaño</option>
                        @foreach ($sizesAvailables as $size)
                            <option value="{{ $size->id }}">{{ $size->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="form-group col-md-6">
                <label for="colores" class="text-dark"><strong>Cantidad de Colores/Logos</strong> </label>
                <input type="number" name="colores" wire:model="colores" placeholder="Cantidad de Colores"
                    class="form-control" min="0">
            </div>
            @if ($preciosDisponibles)
                <div class="col-md-12">
                    <p class="m-1"><strong> Precios Por Cantidad de Articulos, de acuerdo al material,
                            tecnica y tamaño seleccionados</strong></p>
                    <table class="table ">
                        <thead>
                            <tr>
                                <td><strong>Escala</strong></td>
                                <td><strong>Precio</strong></td>
                                <td><strong>Tipo de precio</strong></td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($preciosDisponibles as $item)
                                <tr>
                                    <td>{{ $item->escala_inicial }} - {{ $item->escala_final }}</td>
                                    <td>{{ $item->precio }}</td>
                                    <td>{{ $item->tipo_precio == 'F' ? 'Precio por Articulo' : 'Precio Fijo' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    <p class="w-100"><strong>Costos y Utilidad</strong></p>
    <div class="border border-primary rounded p-2">
        <div class="form-group d-flex align-items-center">
            <label for="operacion" class="w-50 text-dark"><strong>Costo Indirecto de operacion</strong>
            </label>
            <input type="number" name="operacion" wire:model="operacion" placeholder="Costo de operacion"
                class="form-control">
        </div>
        <div class="form-group d-flex align-items-center">
            <label for="margen" class="w-50 text-dark"><strong>Margen de Utilidad</strong> </label>
            <input type="number" name="margen" wire:model="utilidad" placeholder="Margen de Utilidad. Max: 99"
                max="99" maxlength="2" class="form-control" max="100">
        </div>

        <div class="form-group d-flex align-items-center">
            <label for="cantidad" class="w-50 text-dark"><strong>Cantidad</strong> </label>
            <input type="number" name="cantidad" wire:model="cantidad" placeholder="Cantidad de productos"
                class="form-control" max="{{ $product->stock }}">
        </div>
        <div class="form-group d-flex align-items-center">
            <label for="dias" class="w-50 text-dark"><strong>Dias de Entrega</strong> </label>
            <input type="number" name="dias" wire:model="entrega" placeholder="Dias de entrega estimada"
                class="form-control">
        </div>
        <div class="form-group">
            <label for="dias" class="text-dark"><strong>Precio actual de la tecnica por articulo: $
                    {{ $precioDeTecnica }}</strong><br> <span class="text-warning">*Solo modificar cuando
                    sea
                    necesario*</span> </label>
            <input type="number" name="dias" wire:model="newPriceTechnique" placeholder="Nuevo precio de la tecnica"
                class="form-control">
        </div>
        <div class="form-group">
            <label for="newDescription" class="text-dark"><strong>Coloca la descripcion que se mostrara en
                    la
                    cotizacion: </strong><br> <span class="text-warning">*Solo modificar cuando sea
                    necesario*</span> </label>
            <input type="text" name="newDescription" wire:model="newDescription"
                placeholder="Descripcion del producto" class="form-control">
        </div>
        <div class="form-group">
            <label for="" class="text-dark"><strong>Imagen que sera visualizada en la
                    cotizacion</strong></label>
            @if (!$imageSelected)
                <p>Imagen No Seleccionada. Solo Puedes Seleccionar una Imagen</p>
                <div class="d-flex flex-wrap">
                    @foreach ($product->images as $image)
                        <div class="img-select {{ $imageSelected == $image->image_url ? 'selected' : '' }}"
                            wire:click="seleccionarImagen('{{ $image->image_url }}')">
                            <img src="{{ $image->image_url }}" class="rounded img-fluid"
                                style=" width: auto; max-width: 150px; max-height: 145px"
                                alt="{{ $image->image_url }}">
                        </div>
                    @endforeach
                </div>
            @else
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-center">
                        <img src="{{ $imageSelected }}" class="rounded img-fluid"
                            style=" width: auto; max-width: 180px; max-height: 100px" alt="{{ $imageSelected }}">
                    </div>
                    <div class="">
                        <button type="button" class="btn btn-danger btn-sm btn-block" wire:click="eliminarImagen">
                            Eliminar
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <br>
    @if ($errors)
        <div wire:poll.12s>
            @if ($errors->has('colores'))
                <div class="btn btn-sm btn-danger w-100" style="margin-top:0px; margin-bottom:0px;">
                    No se han especificado la cantidad de colores</div>
            @endif
            @if ($errors->has('operacion'))
                <div class="btn btn-sm btn-danger w-100" style="margin-top:0px; margin-bottom:0px;">
                    Es necesario el costo de operacion</div>
            @endif
            @if ($errors->has('utilidad'))
                <div class="btn btn-sm btn-danger w-100" style="margin-top:0px; margin-bottom:0px;">
                    No se ha colocado el margen de utilidad</div>
            @endif
            @if ($errors->has('cantidad'))
                <div class="btn btn-sm btn-danger w-100" style="margin-top:0px; margin-bottom:0px;">
                    No se ha colocado la cantidad de productos</div>
            @endif
            @if ($errors->has('entrega'))
                <div class="btn btn-sm btn-danger w-100" style="margin-top:0px; margin-bottom:0px;">
                    No se han colocado los dias de entrega </div>
            @endif
            @if ($errors->has('priceTechnique'))
                <div class="btn btn-sm btn-danger w-100" style="margin-top:0px; margin-bottom:0px;">
                    No se ha seleccionado una tecnica de personalizacion </div>
            @endif
        </div>
        @php
            $errors = null;
        @endphp
    @endif
    @if (session()->has('message'))
        <div wire:poll.4s class="btn btn-sm btn-success w-100" style="margin-top:0px; margin-bottom:0px;">
            {{ session('message') }} </div>
    @endif
    <div class="d-flex justify-content-between">
        <div>
            <h6 class="text-success">Precio Final por Articulo: $ {{ $precioCalculado }}</h6>
            <h6 class="text-success">Precio Total: $ {{ $precioTotal }}</h6>
        </div>
        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary py-2 px-4" wire:click="agregarCotizacion">Añadir a la
                cotizacion</button>
        </div>
    </div>
    <script>
        window.addEventListener('closeModal', event => {
            $('#modalCotizador').modal('hide');
        })
    </script>
</div>
