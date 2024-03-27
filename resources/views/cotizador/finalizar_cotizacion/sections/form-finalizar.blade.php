<form action="" method="post">
    <fieldset class="border p-2">
        <legend class="w-auto px-2">
            <h5>Información obligatoria</h5>
        </legend>
        <div class="grid grid-cols-3 gap-3">
            @if (count(auth()->user()->managments) > 1)
                <div class="col-span-3 md:col-span-1">
                    <div class="form-group">
                        <label for="">Ejecutivos Disponibles</label>
                        <select name="tipo"
                            class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                            wire:model="selectEjecutivo" wire:change="selectManagment">
                            <option value="">Seleccionar ejecutivos</option>
                            @foreach (auth()->user()->managments as $managment)
                                <option value="{{ $managment->id }}">{{ $managment->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
            <div class="col-span-3 md:col-span-1">
                <div class="form-group">
                    <label for="">Opciones de Cliente</label>
                    <select name="tipo"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                        wire:model="tipoCliente" wire:change="cargarDatosCliente">
                        <option value="">¿Cómo vas a registrar al cliente?</option>
                        <option value="buscar">Seleccionar uno de mis clientes</option>
                        <option value="crear">Crear un nuevo prospecto</option>
                    </select>
                </div>
                @if ($errors->has('tipoCliente'))
                    <span class="text-danger">{{ $errors->first('tipoCliente') }}</span>
                @endif
            </div>
            @if ($tipoCliente == 'buscar')
                <div class="col-span-3 md:col-span-1">
                    <div class="form-group">
                        <label for="">Buscar Cliente</label>
                        <select name="tipo"
                            class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                            wire:model="clienteSeleccionado" wire:change="cargarDatosCliente">
                            <option value="">Seleccionar Cliente</option>
                            @foreach ($userClients as $client)
                                <option value="{{ $client->id }}">
                                    {{ $client->name }}</option>
                            @endforeach
                            @if (count($userClients) < 1)
                                <option value="">No tienes clientes asignados, si es un
                                    error,
                                    reportalo
                                    con el area de desarrollo</option>
                            @endif
                        </select>
                    </div>
                    @if ($errors->has('clienteSeleccionado'))
                        <span class="text-danger">{{ $errors->first('clienteSeleccionado') }}</span>
                    @endif
                </div>
            @elseif($tipoCliente == 'crear')
                <div class="col-span-3 md:col-span-1">
                    <div class="form-group">
                        <label for="">Nombre de la empresa</label>
                        <input type="text"
                            class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                            placeholder="Nombre de la empresa" wire:model="empresa">
                    </div>
                    @if ($errors->has('empresa'))
                        <span class="text-danger">{{ $errors->first('empresa') }}</span>
                    @endif
                </div>
            @endif
            <div class="col-span-3 md:col-span-1">
                <div class="form-group">
                    <label for="">Nombre del Contacto</label>
                    <input type="text"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                        placeholder="Nombre" wire:model="nombre">
                </div>
                @if ($errors->has('nombre'))
                    <span class="text-danger">{{ $errors->first('nombre') }}</span>
                @endif
            </div>
            @if ($tipoCliente == '')
                <div class="w-100"></div>
            @endif
            <div class="col-span-3 md:col-span-1">
                <div class="form-group">
                    <label for="">Email</label>
                    <input type="email"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                        placeholder="Correo electrónico del contacto" wire:model="email">
                </div>
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
            </div>
            <div class="col-span-3 md:col-span-1">
                <div class="form-group">
                    <label for="">Teléfono</label>
                    <input type="number"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                        placeholder="Teléfono del contacto" wire:model="telefono">
                </div>
                @if ($errors->has('telefono'))
                    <span class="text-danger">{{ $errors->first('telefono') }}</span>
                @endif
            </div>
            <div class="col-span-3 md:col-span-1">
                <div class="form-group">
                    <label for="">Celular</label>
                    <input type="number"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                        placeholder="Celular del contacto" wire:model="celular">
                </div>
                @if ($errors->has('celular'))
                    <span class="text-danger">{{ $errors->first('celular') }}</span>
                @endif
            </div>
            <div class="col-span-3 md:col-span-1">
                <div class="form-group">
                    <label for="">Oportunidad</label>
                    <input type="text"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                        placeholder="Oportunidad" wire:model="oportunidad">
                </div>
                @if ($errors->has('oportunidad'))
                    <span class="text-danger">{{ $errors->first('oportunidad') }}</span>
                @endif
            </div>
            <div class="col-span-3 md:col-span-1">
                <div class="form-group">
                    <label for="">Probabilidad de venta</label>
                    <select name="tipo"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                        wire:model="rank">
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
        </div>
    </fieldset>
    <fieldset class="form-group border p-2">
        <legend class="w-auto px-2">
            <h5>Información opcional</h5>
        </legend>
        <div class="grid grid-cols-3 gap-3">
            <div class="col-span-3 md:col-span-1">
                <div class="form-group">
                    <label for="">Departamento (opcional)</label>
                    <input type="text"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                        placeholder="Departamento" wire:model="departamento">
                </div>
                @if ($errors->has('departamento'))
                    <span class="text-danger">{{ $errors->first('departamento') }}</span>
                @endif
            </div>
            <div class="col-span-3 md:col-span-1">
                <div class="form-group">
                    <label for="">Como mostrar el IVA</label>
                    <select name="tipo"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                        wire:model="ivaByItem">
                        <option value="0">Mostrar el IVA en el monto total</option>
                        <option value="1">Mostrar el IVA por partida</option>
                    </select>
                </div>
            </div>
            <div class="col-span-3 md:col-span-1">
                <div class="form-group">
                    <label for="">El monto total es</label>
                    <select name="tipo"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                        wire:model="showTotal">
                        <option value="1">Visible</option>
                        <option value="0">No visible</option>
                    </select>
                </div>
            </div>
            <div class="col-span-3 md:col-span-1">
                <div class="form-group">
                    <label for="">Los días serán (Configuración Global)</label>
                    <select name="tipo"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                        wire:model="typeDays">
                        <option value="0">Habiles</option>
                        <option value="1">Naturales</option>
                    </select>
                </div>
            </div>
            <div class="col-span-3 md:col-span-1">
                <div class="form-group">
                    <label for="">Vigencia de la cotización (Opcional)</label>
                    <input type="number"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                        placeholder="Duración de la vigencia" wire:model="shelfLife">
                </div>
            </div>
            <div class="col-span-3 md:col-span-1">
                <div class="form-group">
                    <label for="">Tax Fee (Opcional)</label>
                    <input type="number"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                        placeholder="Tax Fee (Valor Máximo 99)" wire:model="taxFee" max="99">
                </div>
            </div>
            <div class="col-span-3 md:col-span-1">
                <div class="form-group">
                    <label for="">En la cotización se mostrará:</label>
                    <select name="tipo"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                        wire:model="show_tax">
                        <option value="1">Etiqueta del cliente (Si cuenta con una)
                        </option>
                        <option value="0">Razon Social del cliente</option>
                    </select>
                </div>
            </div>
            {{-- <div class="col-span-3 md:col-span-1">
                <div class="form-group">
                    <label for="">Tipo de Moneda:</label>
                    <select name="tipo" class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 " wire:model="currency_type">
                        <option value="MXN">MXN</option>
                        <option value="USD">USD</option>
                    </select>
                </div>
            </div>
            <div class="col-span-3 md:col-span-1">
                <div class="form-group">
                    <label for="">Tipo de Cambio Actual</label>
                    <p>$1 USD => ${{ $currency }} MXN</p>
                </div>
            </div> --}}
            <div class="col-span-3">
                <div class="form-group">
                    <label for="">Información adicional (Opcional)</label>
                    <textarea name="" id="" cols="30" rows="2"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                        wire:model="informacion"></textarea>
                </div>
                @if ($errors->has('informacion'))
                    <span class="text-danger">{{ $errors->first('informacion') }}</span>
                @endif
            </div>
            <div class="col-span-3 md:col-span-1">
                <div class="form-group">
                    <label for="">Logo del Cliente</label>
                    <div class="form-group" x-data="{ isUploading: false, progress: 5 }" x-on:livewire-upload-start="isUploading = true"
                        x-on:livewire-upload-finish="isUploading = false"
                        x-on:livewire-upload-error="isUploading = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress">
                        <input type="file"
                            class="block w-full mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                            wire:model="logo" accept="image/*">

                        <div x-show="isUploading" class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                            <div class="bg-blue-600 h-2.5 rounded-full" x-bind:style="`width: ${progress}%`">
                            </div>
                        </div>
                    </div>
                    @if ($logo)
                        <p class="text-yellow-500 py-3">Revisa que tenga fondo blanco o que se un
                            archivo PNG
                        </p>
                        <div class="bg-red-200 p-2 rounded-md hover:bg-red-300 text-center"
                            wire:click="limpiarLogo()">Eliminar
                        </div>
                    @else
                        <p>No hay un logo de cliente cargado</p>
                    @endif
                </div>
                @if ($errors->has('logo'))
                    <span class="text-danger">{{ $errors->first('departamento') }}</span>
                @endif
            </div>
            <div class="col-span-3 md:col-span-1">
                <div class="flex justify-center">
                    @if ($logo)
                        <div class="text-center">
                            <p>Logo del cliente</p>
                            <img src="{{ $logo->temporaryUrl() }}" alt=""
                                style="max-width: 100%; height: auto; max-height: 150px; width: auto">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </fieldset>
</form>
