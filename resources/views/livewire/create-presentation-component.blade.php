<div>
    <h5 class="card-title">Personalizacion de Imagenes</h5>
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="portada">Portada</label>
                <input type="file" class="form-control-file" id="portada" wire:model="portada"
                    accept="image/*">
                @error('portada')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="contraportada">Contra Portada</label>
                <input type="file" class="form-control-file" id="portada"
                    wire:model="contraportada" accept="image/*">
                @error('contraportada')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="fondo">Fondo</label>
                <input type="file" class="form-control-file" id="portada" wire:model="fondo"
                    accept="image/*">
                @error('fondo')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="logo">Logo</label>
                <input type="file" class="form-control-file" id="logo" wire:model="logo"
                    accept="image/*">
                @error('logo')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="encabezado">Encabezado</label>
                <input type="file" class="form-control-file" id="encabezado" wire:model="encabezado"
                    accept="image/*">
                @error('encabezado')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="pie_pagina">Pie de Pagina</label>
                <input type="file" class="form-control-file" id="pie_pagina" wire:model="pie_pagina"
                    accept="image/*">
                @error('pie_pagina')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    <h5 class="card-title">Personalizacion del Contenido</h5>
    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label for="color_primario">Color Primario</label>
                <input type="color" class="form-control" id="color_primario" wire:model="color_primario">
                @error('color_primario')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label for="color_secundario">Color Secundario</label>
                <input type="color" class="form-control" id="color_secundario" wire:model="color_secundario">
                @error('color_secundario')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label for="productos_por_pagina">Productos Por Pagina</label>
                <select name="" id="" wire:model="productos_por_pagina" class="form-control">
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>
                @error('productos_por_pagina')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label for="mostrar_formato_de_tabla">Mostrar Informacion en Tablas</label>
                <select name="" id="" wire:model="mostrar_formato_de_tabla" class="form-control">
                    <option value="0">No</option>
                    <option value="1">Si</option>
                </select>
                @error('mostrar_formato_de_tabla')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label for="generar_contraportada">Generar Contra Portada</label>
                <select name="" id="" wire:model="generar_contraportada" class="form-control">
                    <option value="0">No</option>
                    <option value="1">Si</option>
                </select>
                @error('generar_contraportada')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    @php
        $subtotalAdded = 0;
        $subtotalSubstract = 0;
        $sumPrecioTotal = 0;
        $quoteScales = false;
    @endphp
    @if ($quote->latestQuotesUpdate)
        @if (count($quote->latestQuotesUpdate->quoteProducts) > 0)
            <div class="w-100">
                <table class="table table-responsive-sm">
                    <thead class="w-100">
                        <tr class="w-100">
                            <th>Imagen</th>
                            <th>Producto</th>
                            <th>Subtotal</th>
                            <th>Piezas</th>
                            <th>Total</th>
                            <th>...</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quote->latestQuotesUpdate->quoteProducts as $product)
                            @php
                                $producto = (object) json_decode($product->product);
                                $tecnica = (object) json_decode($product->technique);
                                $visible = true;
                                $auxProduct = $product;
                                $sumPrecioTotal += $product->precio_total;
                                if ($product->quote_by_scales) {
                                    $quoteScales = true;
                                }
                            @endphp
                            <tr class="{{ !$visible ? 'd-none' : '' }}">
                                <td class="text-center">
                                    <img src="{{ $producto->image == '' ? asset('img/default.jpg') : $producto->image }}"
                                        style="max-height: 80px;height:auto;max-width: 70px;width:auto;" alt=""
                                        srcset="">
                                </td>
                                <td class="">
                                    <p>{{ Str::limit($producto->name, 25, '...') }}</p>
                                </td>
                                <td class="text-center d-flex">
                                    <button class="btn btn-info btn-sm" wire:click="verDetalles({{ $auxProduct }})">
                                        <div style="width: 1rem">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No hay Productos Cotizados</p>
        @endif
    @else
        <p>No hay Productos Cotizados</p>
    @endif
    <button class="btn btn-primary btn-sm btn-block mb-1" onclick="preview()">Previsualizar Cotizacion</button>
    <div wire:ignore.self class="modal fade" id="modalPreview" tabindex="-1" aria-labelledby="modalPreviewLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPreviewLabel">Vista Previa de la Cotizacion</h5>
                    <button type="button" class="close" onclick="cerrarPreview()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div wire:loading.flex wire:target="previewPresentation" class="justify-content-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    @if ($urlPDFPreview)
                        <iframe src="{{ $urlPDFPreview }}" style="width:100%; height:700px;"
                            frameborder="0"></iframe>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="cerrarPreview()">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function preview() {
            $('#modalPreview').modal('show')
            @this.previewPresentation()
        }

        function cerrarPreview() {
            $('#modalPreview').modal('hide')
            @this.urlPDFPreview = null;
        }
    </script>
</div>
