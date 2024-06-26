@extends('layouts.cotizador')
@section('title', $product->name)
@section('content')
    <div class="grid grid-cols-12 gap-3">
        <div class="md:col-span-5 col-span-12 w-full border-2 border-gray-200 py-4 px-5 rounded-lg">
            @if ($product->precio_unico)
                @php

                    $product_type = $product->productAttributes->where('attribute', 'Tipo Descuento')->first();
                    $priceProduct = $product->price;

                    if ($product_type && $product_type->value == 'Normal') {
                        //$priceProduct = round($priceProduct - $priceProduct * (0 / 100), 2);
                        $priceProduct = round($priceProduct - $priceProduct * (30 / 100), 2);
                    } elseif ($product_type && ($product_type->value == 'Outlet' || $product_type->value == 'Unico')) {
                        $priceProduct = round($priceProduct - $priceProduct * (0 / 100), 2);
                    } else {
                        if ($product->producto_promocion) {
                            $priceProduct = round($priceProduct - $priceProduct * ($product->descuento / 100), 2);
                        } else {
                            $priceProduct = round(
                                $priceProduct - $priceProduct * ($product->provider->discount / 100),
                                2,
                            );
                        }
                        if ($product->provider->company == 'EuroCotton') {
                            $iva = $priceProduct * 0.16;
                            $priceProduct = round($priceProduct - $iva, 2);
                        }
                        if ($product->provider->company == 'For Promotional') {
                            if ($product->descuento >= $product->provider->discount) {
                                $priceProduct = round(
                                    $product->price - $product->price * ($product->descuento / 100),
                                    2,
                                );
                            } else {
                                $priceProduct = round($product->price - $product->price * (25 / 100), 2);
                            }
                        }
                    }

                @endphp
            @endif
            <p class="mb-2 text-xl">Detalles del producto</p>
            <div class="product text-center flex gap-1 justify-center">
                <div class="product-small-img" style="overflow: auto; max-height: 400px">
                    @foreach ($product->images as $image)
                        <img src="{{ $image->image_url }}" class="rounded img-fluid" style=" width: auto; max-height: 60px"
                            alt="{{ $image->image_url }}" onclick="cambiarImagen(this)">
                    @endforeach
                </div>
                <div class="img-container rounded">
                    <img id="imgBox"
                        src="{{ $product->firstImage ? $product->firstImage->image_url : asset('img/default.jpg') }}"
                        class="img-fluid" alt="imagen" style=" width: auto; max-height: 380px; height:auto;">
                </div>
            </div>
            <br>
            <div class="grid grid-cols-12">
                <div class="col-span-12 text-sm">
                    <p class="mb-2 text-xl">Información adicional</p>
                    <div class="grid grid-cols-5">
                        <div class="col-span-2 text-white bg-primary-500 p-2">SKU Padre:</div>
                        <div class="col-span-3 p-2 border border-gray-200"> {{ $product->sku_parent }}</div>
                        <div class="col-span-2 text-white bg-primary-500 p-2">SKU Proveedor:</div>
                        <div class="col-span-3 p-2  border border-gray-200"> {{ $product->sku }}</div>
                        <div class="col-span-2 text-white bg-primary-500 p-2">Color:</div>
                        <div class="col-span-3 p-2  border border-gray-200"> {{ $product->color->color }}</div>
                        <div class="col-span-2 text-white bg-primary-500 p-2">Proveedor:</div>
                        <div class="col-span-3 p-2  border border-gray-200"> {{ $product->provider->company }}</div>
                        <div class="col-span-2 text-white bg-primary-500 p-2">Producto Nuevo:</div>
                        <div class="col-span-3 p-2  border border-gray-200">
                            {{ $product->producto_nuevo ? 'SI' : 'NO' }}</div>
                        <div class="col-span-2 text-white bg-primary-500 p-2">Producto de Promoción:</div>
                        <div class="col-span-3 p-2  border border-gray-200">
                            {{ $product->producto_promocion ? 'SI' : 'NO' }}</div>
                        <div class="col-span-2 text-white bg-primary-500 p-2">Última Actualización:</div>
                        <div class="col-span-3 p-2  border border-gray-200">
                            {{ $product->updated_at->diffForHumans() }}</div>

                    </div>
                    @if (!$product->precio_unico)
                        <p class="mb-2 text-xl mt-4">Precios</p>
                        <div class="grid grid-cols-5">
                            <div class="col-span-2 border-gray-200 border font-bold p-2 bg-gray-200">Escala</div>
                            <div class="col-span-3 p-2 border font-bold border-gray-200  bg-gray-200"> Precio</div>
                            @foreach ($product->precios as $precio)
                                @php

                                    $product_type = $product->productAttributes
                                        ->where('attribute', 'Tipo Descuento')
                                        ->first();
                                    $priceProduct = $product->price;

                                    if ($product_type && $product_type->value == 'Normal') {
                                        $priceProduct = round($priceProduct - $priceProduct * (30 / 100), 2);
                                        //$priceProduct = round($priceProduct - $priceProduct * (0 / 100), 2);
                                    } elseif (
                                        $product_type &&
                                        ($product_type->value == 'Outlet' || $product_type->value == 'Unico')
                                    ) {
                                        $priceProduct = round($priceProduct - $priceProduct * (0 / 100), 2);
                                    } else {
                                        if ($product->producto_promocion) {
                                            $priceProduct = round(
                                                $priceProduct - $priceProduct * ($product->descuento / 100),
                                                2,
                                            );
                                        } else {
                                            $priceProduct = round(
                                                $priceProduct - $priceProduct * ($product->provider->discount / 100),
                                                2,
                                            );
                                        }
                                        if ($product->provider->company == 'EuroCotton') {
                                            $iva = $priceProduct * 0.16;
                                            $priceProduct = round($priceProduct - $iva, 2);
                                        }
                                        if ($product->provider->company == 'For Promotional') {
                                            if ($product->descuento >= $product->provider->discount) {
                                                $priceProduct = round(
                                                    $product->price - $product->price * ($product->descuento / 100),
                                                    2,
                                                );
                                            } else {
                                                $priceProduct = round(
                                                    $product->price - $product->price * (25 / 100),
                                                    2,
                                                );
                                            }
                                        }
                                    }

                                @endphp
                                <div class="col-span-2  border border-gray-200 p-2">{{ $precio->escala_inicial }} -
                                    {{ $precio->escala_final }}</div>
                                <div class="col-span-3 p-2  border border-gray-200"> $ {{ $priceProduct }}
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if (count($product->productCategories) > 0)
                        <p class="mb-2 text-xl mt-4">Categoría</p>
                        <div class="grid grid-cols-5">
                            <div class="col-span-2 text-white bg-primary-500 p-2">Familia:</div>
                            <div class="col-span-3 p-2  border border-gray-200">
                                {{ $product->productCategories[0]->category->family }}</div>
                            <div class="col-span-2 text-white bg-primary-500 p-2">Sub Familia:</div>
                            <div class="col-span-3 p-2  border border-gray-200">
                                {{ $product->productCategories[0]->subcategory->subfamily }}</div>

                        </div>
                    @endif
                    @if (count($product->productAttributes) > 0)
                        <p class="mb-2 text-xl mt-4">Otros atributos</p>
                        <div class="grid grid-cols-5">
                            @foreach ($product->productAttributes as $attr)
                                <div class="col-span-2 text-white bg-primary-500 p-2">{{ $attr->attribute }}:</div>
                                <div class="col-span-3 p-2  border border-gray-200">
                                    {{ $attr->value }}</div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="md:col-span-7 col-span-12 w-full border-2 border-gray-200 py-4 px-5 rounded-lg">
            <p class="mb-2 text-xl">Cotización</p>
            <div class="mb-3">
                @if (!$disponiblidad)
                    <div class="w-full p-4 mb-2 text-sm text-red-800 rounded-lg bg-red-50">No puedes cotizar este producto
                        porque no pertenece a tus proveedores
                    </div>
                @endif
                @if ($msg)
                    <div class="w-full p-4 mb-2 text-sm text-red-800 rounded-lg bg-red-50">{{ $msg }}</div>
                @endif
                <div class="flex items-center justify-between">
                    <p class="font-bold">{{ $product->name }}</p>
                    @if ($product->precio_unico)
                        <div>
                            <p class="text-lg font-bold">
                                $ {{ round($priceProduct + $priceProduct * ($utilidad / 100), 2) }}</p>
                            </p>
                        </div>
                    @endif
                </div>
                <div class="flex items-center justify-between">
                    <div class="grow ">
                        <div class="hidden md:block">
                            <p class="my-1">SKU Interno: {{ $product->internal_sku }}</p>
                            <p class="my-1">SKU Proveedor: {{ $product->sku }}</p>
                        </div>
                        <div class="block md:hidden space-y-1 mb-2">
                            <div>
                                <p>Sku Interno:</p>
                                <p>{{ $product->internal_sku }}</p>
                            </div>
                            <div>
                                <p>Sku Proveedor:</p>
                                <p>{{ $product->sku }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-green-500">Disponibles:<strong> {{ $product->stock }}</strong> </p>
                    </div>
                </div>
                <p>{{ $product->description }}</p>
            </div>
            @if ($disponiblidad)
                @livewire('components.formulario-de-cotizacion', ['product' => $product])
            @endif
        </div>
    </div>
    <style>
        .product-small-img img {
            width: 4%;
            border: 1px solid rgba(0, 0, 0, .2);
            /* padding: 8px; */
            /* margin: 10px 10px 15px; */
            cursor: pointer;
        }

        .product-small-img {
            display: flex;
            /* justify-content: center; */
            flex-direction: column;
            gap: 5px;
        }

        .img-container {
            border: 1px solid rgba(0, 0, 0, .2);
        }

        .img-container img {
            height: 20rem;
        }

        .img-container {
            padding: 10px;
            max-height: 400px;
        }
    </style>
    <script>
        function cambiarImagen(smallImg) {
            let fullImg = document.querySelector('#imgBox')
            console.log(fullImg);
            fullImg.src = smallImg.src
        }
    </script>
@endsection
