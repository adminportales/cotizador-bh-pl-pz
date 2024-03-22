<div class="relative border-2 border-gray-200 py-4 px-5 rounded-lg w-full">
    @php
        $counter = $products->perPage() * $products->currentPage() - $products->perPage() + 1;
    @endphp
    @if (count($products) <= 0)
        <div class="flex flex-wrap justify-center items-center flex-col">
            <p>No hay resultados de busqueda en la pagina actual</p>
            @if (count($products->items()) == 0 && $products->currentPage() > 1)
                <p>Click en la paginacion para ver mas resultados</p>
            @endif
        </div>
    @endif
    <div class="absolute w-full flex justify-center" style="top: 40%;z-index: 100;">
        <div wire:loading.flex>
            @include('components.shared.loading')
        </div>
    </div>
    <div class="grid grid-cols-12 gap-2 mb-3" wire:loading.class="opacity-70">
        @foreach ($products as $row)

            @php

                $product_type = $row->productAttributes->where('attribute', 'Tipo Descuento')->first();

                $priceProduct = $row->price;
                
                if ($product_type && $product_type->value == 'Normal') {
                    $priceProduct = round($priceProduct - $priceProduct * (30 / 100), 2);
                } else if($product_type &&  ($product_type->value == 'Outlet' || $product_type->value == 'Unico')){
                    $priceProduct = round($priceProduct - $priceProduct * (0 / 100), 2);
                }else{
                    if ($row->producto_promocion) {
                    $priceProduct = round($priceProduct - $priceProduct * ($row->descuento / 100), 2);
                    } else {
                        $priceProduct = round($priceProduct - $priceProduct * ($row->provider->discount / 100), 2);
                    }
                        if ($row->provider->company == 'EuroCotton') {
                            $priceProduct = round($priceProduct - $priceProduct * ($row->provider->discount / 100), 2);
                             $iva = $priceProduct * 0.16;
                             $priceProduct = round($priceProduct - $iva, 2);
                        }

                        if ($row->provider->company == 'For Promotional') {
                  
                            if ($row->descuento >= $row->provider->discount ) {
                                $priceProduct = round($row->price- $row->price * ($row->descuento /100),2);
                            } else {
                                $priceProduct = round($row->price - $row->price * (25/100),2);
                            }
                    
                        }
                }                

            @endphp

                @if($row->provider->company == 'EuroCotton')

                    @if(isset($row->firstImage))
                        <div class="sm:col-span-6 lg:col-span-3 md:col-span-4 col-span-12 flex justify-center">

                            <div class="border-2 border-gray-200 py-2 px-3 rounded-xl w-full h-full">

                                <div class="text-center shadow-sm p-2 h-full">
                                    <div class="flex flex-row sm:flex-col sm:justify-between h-full ">
                                        <div class="flex justify-center" style="height: 150px;">
                                            <img src="{{ $row->firstImage ? $row->firstImage->image_url : '' }}"
                                                alt="{{ $row->name }}" class="text-center"
                                                style="width: auto; max-width: 150px; max-height: 150px; height: auto">
                                        </div>
                                        <div class="flex flex-col sm:text-center sm:flex-grow flex-grow-0 text-left">
                                            <h5 class="text-lg font-medium m-0" style="text-transform: capitalize">
                                                {{ Str::limit($row->name, 22, '...') }}</h5>
                                            <p class="m-0 pt-0" style="font-size: 16px">SKU: {{ $row->sku }}</p>
                                            <div class="flex justify-between items-center mb-2 flex-grow">
                                                <div class="text-left">
                                                    <p class=" m-0">$
                                                        {{ round($priceProduct / ((100 - $utilidad) / 100), 2) }}</p>
                                                    <p class="m-0" style="font-size: 16px">Disponible:
                                                        <span class="font-bold">{{ $row->stock }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <a href="#" wire:click="cotizar({{ $row->id }})"
                                                class="block text-white bg-blue-500 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm px-5 py-2.5 text-center">
                                                Cotizar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="sm:col-span-6 lg:col-span-3 md:col-span-4 col-span-12 flex justify-center">

                        <div class="border-2 border-gray-200 py-2 px-3 rounded-xl w-full h-full">
                                
                            <div class="text-center shadow-sm p-2 h-full">
                                <div class="flex flex-row sm:flex-col sm:justify-between h-full ">
                                    <div class="flex justify-center" style="height: 150px;">
                                        <img src="{{ $row->firstImage ? $row->firstImage->image_url : '' }}"
                                            alt="{{ $row->name }}" class="text-center"
                                            style="width: auto; max-width: 150px; max-height: 150px; height: auto">
                                    </div>
                                    <div class="flex flex-col sm:text-center sm:flex-grow flex-grow-0 text-left">
                                        <h5 class="text-lg font-medium m-0" style="text-transform: capitalize">
                                            {{ Str::limit($row->name, 22, '...') }}</h5>
                                        <p class="m-0 pt-0" style="font-size: 16px">SKU: {{ $row->sku }}</p>
                                        <div class="flex justify-between items-center mb-2 flex-grow">
                                            <div class="text-left">
                                                <p class=" m-0">$
                                                    {{ round($priceProduct / ((100 - $utilidad) / 100), 2) }}</p>
                                                <p class="m-0" style="font-size: 16px">Disponible:
                                                    <span class="font-bold">{{ $row->stock }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <a href="#" wire:click="cotizar({{ $row->id }})"
                                            class="block text-white bg-blue-500 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm px-5 py-2.5 text-center">
                                            Cotizar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
        @endforeach
    </div>
    <div class="flex justify-center">
        <div>
            {{ $products->onEachSide(0)->links() }}
        </div>
    </div>
    <div class="hidden justify-center">
        <div>
            {{ $products->onEachSide(3)->links() }}
        </div>
    </div>
</div>
