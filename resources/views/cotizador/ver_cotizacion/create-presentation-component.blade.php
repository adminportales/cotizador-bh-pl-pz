<div class="flex">
    <div class="w-1/2">
        <div class="row ">
            <div class="col-md-4 ">
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="portadaFile" wire:model="portada"
                            accept="image/*">
                        <label class="custom-file-label" for="portadaFile">Seleccionar Portada</label>
                    </div>
                    @error('portada')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                {{-- Check para mostrar o no la contraportada --}}
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" {{ $tieneContraportada ? 'checked' : '' }}
                            wire:model="tieneContraportada">
                        <label class="form-check-label" style="font-weight: bold;" for="defaultCheck1">
                            ¿Deseas una contraportada?
                        </label>
                    </div>
                    @if ($tieneContraportada)
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="contraportadaFile"
                                wire:model="contraportada" accept="image/*">
                            <label class="custom-file-label" for="contraportadaFile">Seleccionar Contraportada</label>
                        </div>
                    @endif
                    @error('contraportada')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="fondoFile" wire:model="fondo"
                            accept="image/*">
                        <label class="custom-file-label" for="fondoFile">Seleccionar Fondo</label>
                    </div>
                    @error('fondo')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="logoFile" wire:model="logo"
                            accept="image/*">
                        <label class="custom-file-label" for="logoFile">Seleccionar Logo</label>
                    </div>
                    @error('logo')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <button class="btn btn-info btn-sm btn-block mb-1" onclick="preview()">Previsualizar
                    Presentacion</button>
                {{-- <button class="btn btn-success btn-sm btn-block mb-1">Guardar Presentacion</button> --}}
            </div>
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
            <iframe src="{{ $urlPDFPreview }}" style="width:100%; height:600px;" frameborder="0"></iframe>
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
