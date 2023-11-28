<div class="card-body">
    <p>Filtros de busqueda</p>
    <p>Buscar por</p>
    <input wire:model='nombre' type="text"
        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
        name="search" id="search" placeholder="Nombre">
    <input wire:model='sku' type="text"
        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500"
        name="search" id="search" placeholder="SKU">
    <input wire:model='color' type="text"
        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500"
        name="color" id="color" placeholder="Ingrese el color">
    <input wire:model='category' type="text"
        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500"
        name="category" id="category" placeholder="Ingrese la familia">
    <select wire:model='proveedor' name="proveedores" id="provee"
        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500">
        <option value="">Seleccione Proveedor...</option>
        @foreach ($proveedores as $provider)
            <option value="{{ $provider->id }}">{{ $provider->company }}</option>
        @endforeach
    </select>
    <select wire:model='type' name="type" id="type"
        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500">
        <option value="">Seleccione Tipo...</option>
        @foreach ($types as $type)
            <option value="{{ $type->id }}">{{ $type->type }}</option>
        @endforeach
    </select>
    <p class="mb-0">Precio</p>
    <div class="flex items-center mb-2">
        <input wire:model='precioMin' type="number"
            class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500"
            name="search" id="search" placeholder="Precio Minimo" min="0" value="0">
        -
        <input wire:model='precioMax' type="number"
            class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500"
            name="search" id="search" placeholder="Precio Maximo" value="{{ $price }}"
            max="{{ $price }}">
    </div>
    <p class="mb-0">Stock</p>
    <div class="flex items-center mb-2">
        <input wire:model='stockMin' type="number"
            class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500"
            placeholder="Stock Minimo" min="0" value="0">
        -
        <input wire:model='stockMax' type="number"
            class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500"
            placeholder="Stock Maximo" value="{{ $stock }}" max="{{ $stock }}">
    </div>
    <p class="mb-0">Ordenar por Stock</p>
    <select wire:model='orderStock' name="orderStock" id="provee"
        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500">
        <option value="">Ninguno</option>
        <option value="ASC">De menor a mayor</option>
        <option value="DESC">De mayor a menor</option>
    </select>
    <p class="mb-0">Ordenar por Precio</p>
    <select wire:model='orderPrice' name="orderPrice" id="provee"
        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500">
        <option value="">Ninguno</option>
        <option value="ASC">De menor a mayor</option>
        <option value="DESC">De mayor a menor</option>
    </select>
    <button
        class="w-full hidden md:block text-white bg-green-500 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-xl text-sm px-5 py-2.5 text-center mr-2 mb-2 "
        wire:click="limpiar">Limpiar Filtros</button>
</div>
