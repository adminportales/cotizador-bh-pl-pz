@extends('layouts.cotizador')
@section('title', $product->name)
@section('content')
    <div class="grid grid-cols-12 gap-3">
        <div class="col-span-6 w-full border-2 border-gray-200 py-4 px-5 rounded-lg">
            @if ($product->precio_unico)
                @php
                    $priceProduct = $product->price;
                    if ($product->producto_promocion) {
                        $priceProduct = round($priceProduct - $priceProduct * ($product->descuento / 100), 2);
                    } else {
                        $priceProduct = round($priceProduct - $priceProduct * ($product->provider->discount / 100), 2);
                    }
                @endphp
            @endif
            <p class="font-bold mb-2">Detalles del Producto</p>
            <div class="product text-center flex gap-1">
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
                <div class="md:col-span-12">
                    <p class="font-bold mb-2">Informacion Adicional</p>

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
                        <div class="col-span-2 text-white bg-primary-500 p-2">Producto de Promocion:</div>
                        <div class="col-span-3 p-2  border border-gray-200">
                            {{ $product->producto_promocion ? 'SI' : 'NO' }}</div>
                        <div class="col-span-2 text-white bg-primary-500 p-2">Ultima Actualizacion:</div>
                        <div class="col-span-3 p-2  border border-gray-200">
                            {{ $product->updated_at->diffForHumans() }}</div>

                    </div>

                    @if (!$product->precio_unico)
                        <p class="font-bold mb-2 mt-4">Precios</p>
                        <div class="grid grid-cols-5">
                            <div class="col-span-2 border-gray-200 border font-bold p-2 bg-gray-200">Escala</div>
                            <div class="col-span-3 p-2 border font-bold border-gray-200  bg-gray-200"> Precio</div>
                            @foreach ($product->precios as $precio)
                                @php
                                    $priceProduct = $precio->price;
                                    if ($product->producto_promocion) {
                                        $priceProduct = round($priceProduct - $priceProduct * ($product->descuento / 100), 2);
                                    } else {
                                        $priceProduct = round($priceProduct - $priceProduct * ($product->provider->discount / 100), 2);
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
                        <h5><strong>Categorias</strong></h5>
                        <p class="m-0 m-1"><strong>Familia:</strong>
                            {{ $product->productCategories[0]->category->family }}
                        </p>
                        <p class="m-0 m-1"><strong>Sub
                                Familia:</strong>
                            {{ $product->productCategories[0]->subcategory->subfamily }}
                        </p>
                    @endif
                    @if (count($product->productAttributes) > 0)
                        <h5><strong>Otros Atributos</strong></h5>


                        @foreach ($product->productAttributes as $attr)
                            <p class="my-1">
                                <strong>{{ $attr->attribute }}:</strong> {{ $attr->value }}
                            </p>
                        @endforeach

                    @endif
                </div>
            </div>
        </div>
        <div class="col-span-6 w-full border-2 border-gray-200 py-4 px-5 rounded-lg">
            <p class="font-bold mb-2">Cotización</p>
            <div class="card card-component-1">
                @if (!$disponiblidad)
                    <div class="alert alert-danger">No puedes cotizar este producto porque no pertenece a tus proveedores
                    </div>
                @endif
                @if ($msg)
                    <div class="alert alert-danger">{{ $msg }}</div>
                @endif
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $product->name }}</h4>
                        </div>
                        @if ($product->precio_unico)
                            <div>
                                <h5 class="text-primary">
                                    $ {{ round($priceProduct + $priceProduct * ($utilidad / 100), 2) }}</p>
                                </h5>
                            </div>
                        @endif
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="my-1"><strong>SKU Interno: </strong> {{ $product->internal_sku }}</p>
                            <p class="my-1"><strong>SKU Proveedor: </strong> {{ $product->sku }}</p>
                        </div>
                        <div>
                            <h5 class="text-success">Disponibles:<strong> {{ $product->stock }}</strong> </h5>
                        </div>
                    </div>
                    <p>{{ $product->description }}</p>
                    @if ($disponiblidad)
                        <h6><strong>Informacion de la cotizacion</strong></h6>
                        @livewire('formulario-de-cotizacion', ['product' => $product])
                    @endif
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="{{ asset('assets/css/components/custom-carousel.css') }}">
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
