<div class="">
    <div class="grid grid-cols-12 gap-3">
        <div class="col-span-12 md:hidden block mb-2">
            <div class="flex justify-between shadow-sm border border-gray-200 rounded-lg items-center p-1">
                <p class="m-0 grow">+ {{ $products->total() }} resultados</p>
                <div class="">
                    <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                        class="rounded-md text-gray-900 bg-white focus:outline-none focus:ring-4 focus:ring-gray-200 text-sm px-3 py-2.5  flex items-center"
                        type="button">
                        <p class="block">Mis Productos</p><svg class="w-2.5 h-2.5 ml-1" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 ">
                        <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownDefaultButton">
                            <li>
                                <a href="{{ route('addProduct.cotizador') }}"
                                    class="block px-4 py-2 hover:bg-gray-100">Nuevo Producto</a>
                            </li>
                            <li>
                                <a href="{{ route('listProducts.cotizador') }}"
                                    class="block px-4 py-2 hover:bg-gray-100">Ver Mis
                                    Productos</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <button data-modal-target="staticModal" data-modal-toggle="staticModal"
                    class="rounded-md text-gray-900 bg-white focus:outline-none focus:ring-4 focus:ring-gray-200 text-sm px-3 py-2.5 "
                    type="button">
                    Busqueda
                </button>
            </div>
        </div>
        <div class="col-span-3 hidden md:block">
            <div class="w-full border-2 border-gray-200 py-4 px-5 rounded-lg">
                @include('cotizador.catalogo.sections.filter-section')
            </div>
        </div>
        <div class="col-span-12 md:col-span-9">
            <div class="w-full border-2 border-gray-200 py-4 px-5 rounded-lg md:block hidden mb-3">
                <div class="flex justify-between items-center">
                    <p class="m-0 text-lg font-semibold grow" style="font-size: 20px; font-weight: bold; color: red;">¿Vas a cotizar un producto que no está en el catálogo?
                    </p>
                    <div class="text-center flex lg:flex-row md:flex-col">
                        <a href="{{ route('addProduct.cotizador') }}"
                            class="inline-block text-white bg-green-500 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-xl text-sm px-5 py-2.5 text-center mr-2 mb-2 ">Agregar
                            Nuevo
                            Producto</a>
                        <a href="{{ route('listProducts.cotizador') }}"
                            class="inline-block text-white bg-blue-600 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm px-5 py-2.5 text-center mr-2 mb-2 ">Ver
                            Mis
                            Productos</a>
                    </div>
                </div>
            </div>
            @include('cotizador.catalogo.sections.products')
        </div>
    </div>

    <!-- Modal De Filtros Responsive-->
    @include('cotizador.catalogo.sections.modal-filters-responsive')

    <script>
        // event scroll-to-top
        window.addEventListener('scroll-to-top', event => {
            $('html, body').animate({
                scrollTop: 0
            }, 300);
        });
    </script>
</div>
