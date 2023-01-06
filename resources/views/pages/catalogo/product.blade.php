@extends('layouts.cotizador')
@section('title', $product->name)
@section('content')
    <div class="container">
        <div class="row px-3">
            <div class="col-md-6">
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
                <div class="card component-card_1">
                    <div class="card-body">
                        <div class="product text-center">
                            <div class="img-container">
                                <img id="imgBox"
                                    src="{{ $product->firstImage ? $product->firstImage->image_url : asset('img/default.jpg') }}"
                                    class="img-fluid" alt="imagen" style=" width: auto; max-height: 400px; height:auto;">
                            </div>
                            <div class="product-small-img">
                                @foreach ($product->images as $image)
                                    <img src="{{ $image->image_url }}" class="rounded img-fluid"
                                        style=" width: auto; max-height: 100px" alt="{{ $image->image_url }}"
                                        onclick="cambiarImagen(this)">
                                @endforeach
                            </div>
                        </div>
                        <br>
                        <div class="row w-100">
                            <div class="col-md-6">
                                <h5><strong>Informacion Adicional</strong></h5>
                                <p class=""><strong>SKU Padre: </strong> {{ $product->sku_parent }}</p>
                                <p><strong>Color: </strong> {{ $product->color->color }}</p>
                                <p><strong>Proveedor: </strong> {{ $product->provider->company }}</p>
                                <p><strong>Producto Nuevo: </strong> {{ $product->producto_nuevo ? 'SI' : 'NO' }}
                                </p>
                                <p><strong>Producto de Promocion: </strong>
                                    {{ $product->producto_promocion ? 'SI' : 'NO' }}</p>
                                @if (!$product->precio_unico)
                                    <h5><strong>Precios</strong></h5>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Escala</th>
                                                <th>Precio</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($product->precios as $precio)
                                                @php
                                                    $priceProduct = $product->price;
                                                    if ($product->producto_promocion) {
                                                        $priceProduct = round($priceProduct - $priceProduct * ($product->descuento / 100), 2);
                                                    } else {
                                                        $priceProduct = round($priceProduct - $priceProduct * ($product->provider->discount / 100), 2);
                                                    }
                                                @endphp
                                                <tr>
                                                    <td class="p-0">{{ $precio->escala }}</td>
                                                    <td class="p-0">$ {{ $priceProduct }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                                <p><strong>Ultima Actualizacion: </strong> {{ $product->updated_at->diffForHumans() }}
                            </div>

                            <div class="col-md-6">
                                @if (count($product->productCategories) > 0)
                                    <h5><strong>Categorias</strong></h5>
                                    <p><strong>Familia:</strong>
                                        {{ $product->productCategories[0]->category->family }}
                                    </p>
                                    <p><strong>Sub
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
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-component-1">
                    @if ($msg)
                        <div class="alert alert-danger">{{ $msg }}</div>
                    @endif
                    <div class="card-body">
                        <h4 class="card-title">{{ $product->name }}</h4>
                        <p class="my-1"><strong>SKU Interno: </strong> {{ $product->internal_sku }}</p>
                        <p class="my-1"><strong>SKU Proveedor: </strong> {{ $product->sku }}</p>
                        <br>
                        @if ($product->precio_unico)
                            <h5 class="text-primary">
                                $ {{ round($priceProduct + $priceProduct * ($utilidad / 100), 2) }}</p>
                            </h5>
                        @endif

                        <p>{{ $product->description }}</p>
                        <h5 class="text-success">Disponibles:<strong> {{ $product->stock }}</strong> </h5>
                        <br>
                        <h6><strong>Informacion de la cotizacion</strong></h6>
                        @livewire('formulario-de-cotizacion', ['product' => $product])
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="{{ asset('assets/css/components/custom-carousel.css') }}">
    <style>
        .product-small-img img {
            width: 8%;
            border: 1px solid rgba(0, 0, 0, .2);
            padding: 8px;
            margin: 10px 10px 15px;
            cursor: pointer;
        }

        .product-small-img {

            display: flex;
            justify-content: center;
        }

        .img-container img {
            height: 25rem;
        }

        .img-container {
            padding: 10px;
        }
    </style>
    <script>
        function cambiarImagen(smallImg) {
            let fullImg = document.querySelector('#imgBox')
            fullImg.src = smallImg.src
        }
    </script>
@endsection
