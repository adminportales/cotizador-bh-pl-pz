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
            <button class="bg-green-200 p-3 rounded-md hover:bg-green-300" wire:loading.class='cursor-not-allowed' wire:loading.attr="disabled" onclick="preview()">Previsualizar
                Presentacion</button>
            <button class="bg-green-200 p-3 rounded-md hover:bg-green-300" wire:loading.class='cursor-not-allowed' wire:loading.attr="disabled" onclick="savePPT()">Guardar
                Presentacion</button>
        </div>
    </div>
    <div class="w-1/2 ">
        {{-- Spinnner loading --}}
        <div wire:loading.flex wire:target="previewPresentation" class="justify-content-center">
            <div role="status">
                <svg aria-hidden="true"
                    class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                    viewBox="0 0 100 101" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor" />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentFill" />
                </svg>
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

        function savePPT() {
            Swal.fire({
                title: '¿Está seguro?',
                text: "¿Desea guardar esta presentación?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    let respuesta = @this.savePPT()
                    Swal.fire('Por Favor Espere');
                    respuesta
                        .then((response) => {
                            console.log(response);
                            if (response == 1) {
                                Swal.fire(
                                    'Se ha guardado la presentacion!',
                                    '',
                                    'success'
                                )
                                customToggleModal('createPPTModal', 0)
                            } else {
                                Swal.fire(
                                    'Primero debe previsualizar la presentacion para revisar si es correcta!',
                                    '',
                                    'error'
                                )
                            }
                        }, function() {
                            Swal.fire('¡Error al guardar la presentacion!', '', 'error')
                        });
                }
            })
        }
    </script>
</div>
