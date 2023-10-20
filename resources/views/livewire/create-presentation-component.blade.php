<div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="portadaFile" wire:model="portada" accept="image/*">
                    <label class="custom-file-label" for="portadaFile">Seleccionar Portada</label>
                </div>
                @error('portada')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            {{-- Check para mostrar o no la contraportada --}}
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" {{ $tieneContraportada ? 'checked' : '' }}
                        wire:model="tieneContraportada">
                    <label class="form-check-label" style="font-weight: bold;" for="defaultCheck1">
                        ¿Deseas una contraportada?
                    </label>
                </div>
                @if ($tieneContraportada)
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="contraportadaFile"
                            wire:model="contraportada" accept="image/*">
                        <label class="custom-file-label" for="contraportadaFile">Seleccionar Contraportada</label>
                    </div>
                @endif
                @error('contraportada')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="fondoFile" wire:model="fondo" accept="image/*">
                    <label class="custom-file-label" for="fondoFile">Seleccionar Fondo</label>
                </div>
                @error('fondo')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="logoFile" wire:model="logo" accept="image/*">
                    <label class="custom-file-label" for="logoFile">Seleccionar Logo</label>
                </div>
                @error('logo')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="color_primario">Color Primario</label>
                <input type="color" class="form-control form-control-sm" id="color_primario"
                    wire:model="color_primario">
                @error('color_primario')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            {{-- <div class="form-group">
                        <label for="color_secundario">Color Secundario</label>
                        <input type="color" class="form-control" id="color_secundario" wire:model="color_secundario">
                        @error('color_secundario')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div> --}}
            {{-- <div class="form-group">
                <label for="productos_por_pagina">Productos Por Pagina</label>
                <select name="" id="" wire:model="productos_por_pagina" class="form-control">
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>
                @error('productos_por_pagina')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div> --}}

            {{-- @php
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
                                                style="max-height: 80px;height:auto;max-width: 70px;width:auto;"
                                                alt="" srcset="">
                                        </td>
                                        <td class="">
                                            <p>{{ Str::limit($producto->name, 25, '...') }}</p>
                                        </td>
                                        <td class="text-center d-flex">
                                            <button class="btn btn-info btn-sm"
                                                wire:click="verDetalles({{ $auxProduct }})">
                                                <div style="width: 1rem">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-6 h-6">
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
            @endif --}}
            <button class="btn btn-info btn-sm btn-block mb-1" onclick="preview()">Previsualizar Presentacion</button>
            <button class="btn btn-success btn-sm btn-block mb-1">Cuardar Presentacion</button>
        </div>
        <div class="col-md-8">
            {{-- Spinnner loading --}}
            {{-- <div wire:loading wire:target="previewPresentation" class="w-100 d-flex justify-content-center">
                <div class="spinner-border text-info" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div> --}}
            @if ($urlPDFPreview)
                <iframe src="{{ $urlPDFPreview }}" style="width:100%; height:600px;" frameborder="0"></iframe>
            @endif
        </div>
    </div>
    <script>
        function preview() {
            @this.previewPresentation()
        }

        function cerrarPreview() {
            @this.urlPDFPreview = null;
        }
    </script>
</div>
