<div class="flex h-full gap-3">
    <div class="w-1/2">
        <div class="mb-3">
            <div class="custom-file">
                <label for="portadaFile">Seleccionar Portada</label>
                <input type="file"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    id="portadaFile" wire:model="portada" accept="image/*">
            </div>
            @error('portada')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        {{-- Check para mostrar o no la contraportada --}}
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" {{ $tieneContraportada ? 'checked' : '' }}
                    wire:model="tieneContraportada">
                <label class="form-check-label" style="font-weight: bold;" for="defaultCheck1">
                    ¿Deseas una contraportada?
                </label>
            </div>
            @if ($tieneContraportada)
                <div class="custom-file">
                    <label for="contraportadaFile">Seleccionar Contraportada</label>
                    <input type="file"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        id="contraportadaFile" wire:model="contraportada" accept="image/*">
                </div>
            @endif
            @error('contraportada')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <div class="custom-file">
                <label for="fondoFile">Seleccionar Fondo</label>
                <input type="file"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    id="fondoFile" wire:model="fondo" accept="image/*">
            </div>
            @error('fondo')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <div class="custom-file">
                <label for="logoFile">Seleccionar Logo</label>
                <input type="file"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    id="logoFile" wire:model="logo" accept="image/*">
            </div>
            @error('logo')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="flex justify-between mt-5">
            <button class="bg-green-200 p-3 rounded-md hover:bg-green-300" onclick="preview()">Previsualizar
                Presentacion</button>
            <button class="bg-green-200 p-3 rounded-md hover:bg-green-300">Guardar Presentacion</button>
        </div>
    </div>
    <div class="w-1/2 ">
        {{-- Spinnner loading --}}
        <div wire:loading.flex wire:target="previewPresentation" class="justify-content-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        @if ($urlPDFPreview)
            <iframe src="{{ $urlPDFPreview }}" style="width:100%; height:700px" frameborder="0"></iframe>
        @endif
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
