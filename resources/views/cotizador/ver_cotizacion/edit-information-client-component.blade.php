<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="col-md-1">
            <div class="form-group">
                <label for="">Nombre de contacto</label>
                <input type="text" class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 " placeholder="Nombre" wire:model="nombre">
            </div>
            @if ($errors->has('nombre'))
                <span class="text-danger">{{ $errors->first('nombre') }}</span>
            @endif
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 " placeholder="Correo electrónico del contacto"
                    wire:model="email">
            </div>
            @if ($errors->has('email'))
                <span class="text-danger">{{ $errors->first('email') }}</span>
            @endif
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="">Telefono</label>
                <input type="tel" class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 " placeholder="Teléfono del contacto" wire:model="telefono">
            </div>
            @if ($errors->has('telefono'))
                <span class="text-danger">{{ $errors->first('telefono') }}</span>
            @endif
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="">Celular</label>
                <input type="tel" class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 " placeholder="Celular del contacto" wire:model="celular">
            </div>
            @if ($errors->has('celular'))
                <span class="text-danger">{{ $errors->first('celular') }}</span>
            @endif
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="">Oportunidad</label>
                <input type="text" class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 " placeholder="Oportunidad" wire:model="oportunidad">
            </div>
            @if ($errors->has('oportunidad'))
                <span class="text-danger">{{ $errors->first('oportunidad') }}</span>
            @endif
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="">Probabilidad de Venta</label>
                <select name="tipo" class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 " wire:model="rank">
                    <option value="">Seleccione la Probabilidad de Venta</option>
                    <option value="1">Medio</option>
                    <option value="2">Alto</option>
                    <option value="3">Muy Alto</option>
                </select>
            </div>
            @if ($errors->has('rank'))
                <span class="text-danger">{{ $errors->first('rank') }}</span>
            @endif
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="">Departamento (opcional)</label>
                <input type="text" class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 " placeholder="Departamento" wire:model="departamento">
            </div>
            @if ($errors->has('departamento'))
                <span class="text-danger">{{ $errors->first('departamento') }}</span>
            @endif
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="">Como mostrar el IVA</label>
                <select name="tipo" class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 " wire:model="ivaByItem">
                    <option value="0">Mostrar el IVA en el monto total</option>
                    <option value="1">Mostrar el IVA por partida</option>
                </select>
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="">El monto total es</label>
                <select name="tipo" class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 " wire:model="showTotal">
                    <option value="1">Visible</option>
                    <option value="0">No visible</option>
                </select>
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="">Los dias seran</label>
                <select name="tipo" class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 " wire:model="typeDays">
                    <option value="0">Habiles</option>
                    <option value="1">Naturales</option>
                </select>
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="">Tax Fee (Opcional)</label>
                <input type="number" class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 " placeholder="Tax Fee (Valor Máximo 99)" wire:model="taxFee"
                    max="99">
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="">Vigencia de la cotizacion (Opcional)</label>
                <input type="number" class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 " placeholder="Duración de la vigencia"
                    wire:model="shelfLife" max="99">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="">Informacion Adicional (Opcional)</label>
                <textarea name="" id="" cols="30" rows="2" class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 " wire:model="informacion"></textarea>
            </div>
            @if ($errors->has('informacion'))
                <span class="text-danger">{{ $errors->first('informacion') }}</span>
            @endif
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="">Logo del Cliente</label>
                <div class="form-group" x-data="{ isUploading: false, progress: 5 }" x-on:livewire-upload-start="isUploading = true"
                    x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress">
                    <input type="file" class="block w-full mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 " wire:model="logo" accept="image/*">
                    <div x-show="isUploading" class="progress">
                        <div class="progress-bar" role="progressbar" x-bind:style="`width: ${progress}%`"
                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                @if ($logo)
                    <p class="text-warning">Revisa que tenga fondo blanco o que se un archivo PNG
                    </p>
                    <div class="btn btn-danger btn-sm" wire:click="limpiarLogo()">Eliminar</div>
                @else
                    <p>No hay un logo de cliente cargado</p>
                @endif
            </div>
            @if ($errors->has('logo'))
                <span class="text-danger">{{ $errors->first('departamento') }}</span>
            @endif
        </div>
        <div class="col-md-1">
            <div class="form-group">
                @if ($logo)
                    <div class="text-center">
                        <p>Logo del cliente</p>
                        <img src="{{ $logo->temporaryUrl() }}" alt=""
                            style="max-width: 100%; height: auto; max-height: 150px; width: auto">
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-12">
            <div class="flex justify-center">
                <div wire:loading wire:target="guardarCotizacion">
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
                <button class="bg-yellow-200 p-3 w-full rounded-md hover:bg-yellow-300 text-center" wire:click="guardarCotizacion">Guardar Cambios</button>
            </div>
        </div>
    </div>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</div>
