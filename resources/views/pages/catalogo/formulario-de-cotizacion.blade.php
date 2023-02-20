<div>
    <form wire:submit.prevent="agregarCotizacion" novalidate>
        <p class="w-100"><strong>Personalizacion de la tecnica</strong></p>
        <div class="border border-primary rounded p-2">
            <div class="row">
                <div class="form-group m-0 mb-1 col-md-6">
                    <label for="tecnica" class="text-dark m-0"><strong>Material</strong> </label>
                    <select name="" id="" class="form-control form-control-sm"
                        wire:model="materialSeleccionado" wire:change="resetSizes">
                        <option value="">Seleccione el material</option>
                        @foreach ($materiales as $material)
                            <option value="{{ $material->id }}">{{ $material->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                @if ($techniquesAvailables)
                    <div class="form-group m-0 mb-1 col-md-6">
                        <label for="tecnica" class="text-dark m-0"><strong>Tecnica</strong> </label>
                        <select name="" id="" class="form-control form-control-sm"
                            wire:model="tecnicaSeleccionada">
                            <option value="">Seleccione la tecnica</option>
                            @foreach ($techniquesAvailables as $technique)
                                <option value="{{ $technique->id }}">{{ $technique->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                @if ($sizesAvailables)
                    <div class="form-group m-0 mb-1 col-md-6">
                        <label for="tecnica" class="text-dark m-0"><strong>Tamaño</strong> </label>
                        <select name="" id="" class="form-control form-control-sm"
                            wire:model="sizeSeleccionado">
                            <option value="">Seleccione el tamaño</option>
                            @foreach ($sizesAvailables as $size)
                                <option value="{{ $size->id }}">{{ $size->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="form-group m-0 mb-1 col-md-6">
                    <label for="colores" class="text-dark m-0"><strong>Cantidad de Colores/Logos</strong> </label>
                    <input type="number" name="colores" wire:model="colores" placeholder="Cantidad de Colores"
                        class="form-control form-control-sm" min="0">
                </div>
                @if ($preciosDisponibles)
                    <div class="col-md-12">
                        <p class="m-1"><strong> Precios Por Cantidad de Articulos, de acuerdo al material,
                                tecnica y tamaño seleccionados</strong></p>
                        <table class="table table-sm mb-0">
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
        <p class="w-100 mt-2 mb-1"><strong>Costos y Utilidad</strong></p>
        <div class="border border-primary rounded p-2">
            <div class="row">
                <div class="form-group m-0 mb-1 col-md-6">
                    <label for="operacion" class="text-dark m-0"><strong>Costo Indirecto de operacion</strong>
                    </label>
                    <input type="number" name="operacion" wire:model="operacion"
                        placeholder="Costo indirecto de operacion" class="form-control form-control-sm">
                </div>
                <div class="form-group m-0 mb-1 col-md-6">
                    <label for="margen" class="text-dark m-0"><strong>Margen de Utilidad</strong> </label>
                    <input type="number" name="margen" wire:model="utilidad" placeholder="Margen de Utilidad. Max: 99"
                        max="99" maxlength="2" class="form-control form-control-sm" max="100">
                </div>
                <div class="form-group m-0 mb-1 col-md-6">
                    <label for="cantidad" class="text-dark m-0"><strong>Cantidad</strong> </label>
                    <input type="number" name="cantidad" wire:model="cantidad"
                        placeholder="Cantidad de productos a cotizar" class="form-control form-control-sm"
                        max="{{ $product->stock }}">
                </div>
                <div class="form-group m-0 mb-1 col-md-6 ">
                    <label for="dias" class="text-dark m-0"><strong>Dias de Entrega</strong> </label>
                    <input type="number" name="dias" wire:model="entrega" placeholder="Dias de entrega estimada"
                        class="form-control form-control-sm">
                </div>
            </div>
        </div>
        <p class="w-100 mt-2 mb-1"><strong>Personalizacion</strong></p>
        <div class="border border-primary rounded p-2">
            <div class="form-group m-0 mb-1 ">
                <label for="newTechnique" class="text-dark m-0"><strong>Precio actual de la tecnica por articulo:
                    </strong>
                    $ {{ $precioDeTecnica }}</label>
                <input type="number" name="newTechnique" wire:model="newPriceTechnique"
                    placeholder="Nuevo precio de la tecnica (Opcional)" class="form-control form-control-sm">
            </div>
            <div class="form-group m-0 mb-1">
                <label for="newDescription" class="text-dark m-0"><strong>Coloca la descripcion que se mostrara en la
                        cotizacion:</strong> </label>
                <textarea rows="3" name="newDescription" wire:model="newDescription"
                     class="form-control form-control-sm" placeholder="Descripcion del producto (Opcional)"> </textarea>
            </div>
            <div class="form-group m-0 mb-1">
                <label for="" class="text-dark m-0"><strong>Imagen que sera visualizada en la
                        cotizacion</strong></label>
                @if (!$imageSelected)
                    <button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="modal"
                        data-target="#exampleModal1">
                        Selecciona la imagen que se vera en la cotizacion
                    </button>
                @else
                    <div class="d-flex justify-content-between">
                        <div class="text-center">
                            <img src="{{ $imageSelected }}" class="rounded img-fluid"
                                style=" width: auto; max-width: 180px; max-height: 100px" alt="{{ $imageSelected }}">
                        </div>
                        <div class="">
                            <button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="modal"
                                data-target="#exampleModal1">
                                Actualiza la imagen que se vera en la cotizacion
                            </button>
                            <button type="button" class="btn btn-danger btn-sm btn-block"
                                wire:click="eliminarImagen">
                                Eliminar
                            </button>
                        </div>
                    </div>
                @endif

                <div wire:ignore.self class="modal fade" id="exampleModal1" tabindex="-1"
                    aria-labelledby="exampleModal1Label" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModal1Label">Seleccionar la imagen que se
                                    visualizara en la cotizacion</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="d-flex justify-content-between">
                                    <p>Solo en caso de que no quieras que se muestre la primera imagen</p>
                                    <div wire:loading wire:target='seleccionarImagen'>
                                        <p>Espere...</p>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap">
                                    @foreach ($product->images as $image)
                                        <div class="img-select {{ $imageSelected == $image->image_url ? 'selected' : '' }}"
                                            wire:click="seleccionarImagen('{{ $image->image_url }}')">
                                            <img src="{{ $image->image_url }}" class="rounded img-fluid"
                                                style=" width: auto; max-width: 180px; max-height: 200px"
                                                alt="{{ $image->image_url }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
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
            <div class="d-flex">
                <a href="{{ url('/') }}" class="btn btn-sm btn-info w-50 px-1"
                    style="margin-top:0px; margin-bottom:0px;">
                    Ir al cotizador </a>
                <a href="{{ url('/cotizacion-actual') }}" class="btn btn-sm btn-secondary w-50 px-1"
                    style="margin-top:0px; margin-bottom:0px;">
                    Ver mi cotizacion </a>
            </div>
        @endif
        <div class="d-flex justify-content-between">
            <div>
                <h6 class="text-success">Precio Final por Articulo: $ {{ $precioCalculado }}</h6>
                <h6 class="text-success">Precio Total: $ {{ $precioTotal }}</h6>
            </div>
            <div class="form-group m-0 mb-1 text-center">
                <button type="submit" class="btn btn-primary py-2 px-4">Añadir a la cotizacion</button>
            </div>
        </div>
    </form>
    <style>
        .img-select:hover {
            background-color: rgb(177, 191, 250);
        }

        .img-select.selected {
            background-color: rgb(177, 191, 250);
        }

        .img-select {
            padding: 10px;
            margin: 0 10px;
            border-radius: 10px;
            background-color: rgb(251, 251, 254);
        }
    </style>
</div>
