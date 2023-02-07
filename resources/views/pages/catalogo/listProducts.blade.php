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
                <div class="row">
                    @foreach ($products as $row)
                        @php
                            $row = $row->product;
                        @endphp
                        @if ($row)
                            <div class="col-md-4 col-lg-3 col-sm-6  d-flex justify-content-center">
                                <div class="card mb-4" style="width: 14rem;">
                                    <div class="card-body text-center shadow-sm">
                                        @php
                                            $priceProduct = $row->price;
                                            if ($row->producto_promocion) {
                                                $priceProduct = round($priceProduct - $priceProduct * ($row->descuento / 100), 2);
                                            } else {
                                                $priceProduct = round($priceProduct - $priceProduct * ($row->provider->discount / 100), 2);
                                            }
                                        @endphp
                                        <div class="text-center" style="height: 140px">
                                            <img src="{{ $row->firstImage ? $row->firstImage->image_url : '' }}"
                                                class="card-img-top " alt="{{ $row->name }}"
                                                style="width: 100%; max-width: 100px; max-height: 140px; width: auto">
                                        </div>
                                        <h5 class="card-title" style="text-transform: capitalize">
                                            {{ Str::limit($row->name, 30, '...') }}</h5>
                                        <p class=" m-0 pt-1"><strong>SKU:</strong> {{ $row->sku }}</p>
                                        <div class="">
                                            <p class=" m-0 pt-1">Stock: {{ $row->stock }}</p>
                                            <p class=" m-0 pt-1">$
                                                {{ round($priceProduct, 2) }}</p>
                                        </div>
                                        <br>
                                        <div>
                                            <a href="{{ route('show.product', ['product' => $row->id]) }}"
                                                class="btn btn-primary mb-2 btn-block">
                                                Cotizar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center w-100">
            {{ $products->links() }}
        </div>
    </div>
@endsection()
