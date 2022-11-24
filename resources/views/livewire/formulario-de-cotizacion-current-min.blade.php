<div>
    @if ($product)
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
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="operacion" class="text-dark"><strong>Costo Indirecto de operacion</strong>
                        </label>
                        <input type="number" name="operacion" wire:model="operacion" placeholder="Costo de operacion"
                            class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="margen" class="text-dark">
                            <strong>Margen de Utilidad</strong>
                        </label>
                        <input type="number" name="margen" wire:model="utilidad" placeholder="Margen de Utilidad"
                            class="form-control" max="100">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cantidad" class="text-dark"><strong>Cantidad</strong> </label>
                        <input type="number" name="cantidad" wire:model="cantidad" placeholder="Cantidad de productos"
                            class="form-control" max="{{ $product->stock }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dias" class="text-dark"><strong>Dias de Entrega</strong> </label>
                        <input type="number" name="dias" wire:model="entrega" placeholder="Dias de entrega estimada"
                            class="form-control">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="dias" class="text-dark"><strong>Precio inicial de la tecnica por articulo: $
                                {{ $precioDeTecnica }}</strong><br> Nuevo precio de la tecnica <span
                                class="text-warning">*Solo modificar cuando sea necesario*</span></label>
                        <input type="number" name="dias" wire:model="newPriceTechnique"
                            placeholder="Nuevo precio de la tecnica" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <br>
        @if (session()->has('error'))
            <div wire:poll.4s class="btn btn-sm btn-danger w-100" style="margin-top:0px; margin-bottom:0px;">
                {{ session('error') }} </div>
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
                <button type="submit" class="btn btn-warning py-2 px-4" wire:click="agregarCotizacion">Editar
                    Cotizacion</button>
            </div>
        </div>
    @endif
</div>
