@extends('layouts.cotizador')
@section('content')
    <div class="container">
        <div class="card w-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                <h4>Mis Productos</h4>
                    {{-- <p>Si agregaste productos con anterioridad, lamentablemente no podran ser visualizados.</p> --}}
                    <div>
                        <a href="{{ route('addProduct.cotizador') }}" class="btn btn-sm btn-info">Agregar Nuevo
                            Producto</a>
                    </div>
                </div>

                {{ $products }}
                @foreach ($products as $product)
                   
                    <div class="col-md-4 col-lg-3 col-sm-6  d-flex justify-content-center">
                        <div class="card mb-4" style="width: 14rem;">
                            <div class="card-body text-center shadow-sm">
                                @php
                                   
                                    $priceProduct = $product->price;
                                    if ($product->producto_promocion) {
                                        $priceProduct = round($priceProduct - $priceProduct * ($product->descuento / 100), 2);
                                    } else {
                                        $priceProduct = round($priceProduct - $priceProduct * ($product->provider->discount / 100), 2);
                                    }
                                @endphp
                                <div class="text-center" style="height: 140px">
                                    <img src="{{ $product->firstImage ? $product->firstImage->image_url : '' }}"
                                        class="card-img-top " alt="{{ $product->name }}"
                                        style="width: 100%; max-width: 100px; max-height: 140px; width: auto">
                                </div>
                                <h5 class="card-title" style="text-transform: capitalize">
                                    {{ Str::limit($product->name, 30, '...') }}</h5>
                                <p class=" m-0 pt-1"><strong>SKU:</strong> {{ $product->sku }}</p>
                                <div class="">
                                    <p class=" m-0 pt-1">Stock: {{ $product->stock }}</p>
                                    <p class=" m-0 pt-1">$
                                        {{ round($priceProduct, 2) }}</p>
                                </div>
                                <br>
                                <div>
                                    <a href="{{ route('show.product', ['product' => $product->id]) }}"
                                        class="btn btn-primary mb-2 btn-block">
                                        Cotizar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        {{ $products->links() }}
    </div>
    </div>
@endsection()
