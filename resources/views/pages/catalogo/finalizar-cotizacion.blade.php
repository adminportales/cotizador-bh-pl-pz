<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                @if (auth()->user()->currentQuote)
                    <div class="card-body">
                        <h3>Finalizar la cotizacion</h3>
                        <br>
                        <form action="" method="post">
                            <fieldset class="form-group border p-2">
                                <legend class="w-auto px-2">
                                    <h5>Informacion Obligatoria</h5>
                                </legend>
                                <div class="row">
                                    @if (count(auth()->user()->managments) > 1)
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Ejecutivos Disponibles</label>
                                                <select name="tipo" class="form-control" wire:model="selectEjecutivo"
                                                    wire:change="selectManagment">
                                                    <option value="">Seleccionar ejecutivos</option>
                                                    @foreach (auth()->user()->managments as $managment)
                                                        <option value="{{ $managment->id }}">{{ $managment->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Opciones de Cliente</label>
                                            <select name="tipo" class="form-control" wire:model="tipoCliente"
                                                wire:change="cargarDatosCliente">
                                                <option value="">Como vas a registrar el cliente</option>
                                                <option value="buscar">Seleccionar uno de mis clientes</option>
                                                <option value="crear">Crear un nuevo prospecto</option>
                                            </select>
                                        </div>
                                        @if ($errors->has('tipoCliente'))
                                            <span class="text-danger">{{ $errors->first('tipoCliente') }}</span>
                                        @endif
                                    </div>
                                    @if ($tipoCliente == 'buscar')
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Buscar Cliente</label>
                                                <select name="tipo" class="form-control"
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
                                                <span
                                                    class="text-danger">{{ $errors->first('clienteSeleccionado') }}</span>
                                            @endif
                                        </div>
                                    @elseif($tipoCliente == 'crear')
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Nombre de la empresa</label>
                                                <input type="text" class="form-control"
                                                    placeholder="Nombre de la empresa" wire:model="empresa">
                                            </div>
                                            @if ($errors->has('empresa'))
                                                <span class="text-danger">{{ $errors->first('empresa') }}</span>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Nombre del Contacto</label>
                                            <input type="text" class="form-control" placeholder="Nombre"
                                                wire:model="nombre">
                                        </div>
                                        @if ($errors->has('nombre'))
                                            <span class="text-danger">{{ $errors->first('nombre') }}</span>
                                        @endif
                                    </div>
                                    @if ($tipoCliente == '')
                                        <div class="w-100"></div>
                                    @endif
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Email</label>
                                            <input type="email" class="form-control"
                                                placeholder="Correo electronico del contacto" wire:model="email">
                                        </div>
                                        @if ($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Telefono</label>
                                            <input type="tel" class="form-control"
                                                placeholder="Telefono del contacto" wire:model="telefono">
                                        </div>
                                        @if ($errors->has('telefono'))
                                            <span class="text-danger">{{ $errors->first('telefono') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Celular</label>
                                            <input type="tel" class="form-control"
                                                placeholder="Celular del contacto" wire:model="celular">
                                        </div>
                                        @if ($errors->has('celular'))
                                            <span class="text-danger">{{ $errors->first('celular') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Oportunidad</label>
                                            <input type="text" class="form-control" placeholder="Oportunidad"
                                                wire:model="oportunidad">
                                        </div>
                                        @if ($errors->has('oportunidad'))
                                            <span class="text-danger">{{ $errors->first('oportunidad') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Probabilidad de Venta</label>
                                            <select name="tipo" class="form-control" wire:model="rank">
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
                                    <h5>Informacion Opcional</h5>
                                </legend>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Departamento (opcional)</label>
                                            <input type="text" class="form-control" placeholder="Departamento"
                                                wire:model="departamento">
                                        </div>
                                        @if ($errors->has('departamento'))
                                            <span class="text-danger">{{ $errors->first('departamento') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Como mostrar el IVA</label>
                                            <select name="tipo" class="form-control" wire:model="ivaByItem">
                                                <option value="0">Mostrar el IVA en el monto total</option>
                                                <option value="1">Mostrar el IVA por partida</option>
                                            </select>
                                        </div>
                                        @if ($errors->has('ivaByItem'))
                                            <span class="text-danger">{{ $errors->first('departamento') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Vigencia de la cotizacion (Opcional)</label>
                                            <input type="number" class="form-control"
                                                placeholder="Duracion de la vigencia en dias naturales"
                                                wire:model="shelfLife">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Tax Fee (Opcional)</label>
                                            <input type="number" class="form-control"
                                                placeholder="Tax Fee (Valor Maximo 99)" wire:model="taxFee"
                                                max="99">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Informacion Adicional (Opcional)</label>
                                            <textarea name="" id="" cols="30" rows="2" class="form-control"
                                                wire:model="informacion"></textarea>
                                        </div>
                                        @if ($errors->has('informacion'))
                                            <span class="text-danger">{{ $errors->first('informacion') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Logo del Cliente</label>
                                            <div class="form-group" x-data="{ isUploading: false, progress: 5 }"
                                                x-on:livewire-upload-start="isUploading = true"
                                                x-on:livewire-upload-finish="isUploading = false"
                                                x-on:livewire-upload-error="isUploading = false"
                                                x-on:livewire-upload-progress="progress = $event.detail.progress">
                                                <input type="file" class="form-control" wire:model="logo"
                                                    accept="image/*">
                                                <div x-show="isUploading" class="progress">
                                                    <div class="progress-bar" role="progressbar"
                                                        x-bind:style="`width: ${progress}%`" aria-valuenow="25"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            @if ($logo)
                                                <p class="text-warning">Revisa que tenga fondo blanco o que se un
                                                    archivo PNG
                                                </p>
                                                <div class="btn btn-danger btn-sm" wire:click="limpiarLogo()">Eliminar
                                                </div>
                                            @else
                                                <p>No hay un logo de cliente cargado</p>
                                            @endif
                                        </div>
                                        @if ($errors->has('logo'))
                                            <span class="text-danger">{{ $errors->first('departamento') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
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
                                </div>
                            </fieldset>
                        </form>
                        <br>
                        <h3>Tu cotizacion</h3>
                        <div class="d-flex justify-content-between flex-column flex-sm-row">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Subtotal</th>
                                        <th>Piezas</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (auth()->user()->currentQuote->currentQuoteDetails as $quote)
                                        <tr>
                                            <td class="pr-5">
                                                <p>{{ $quote->product->name }}</p>
                                            </td>
                                            <td class="pr-5">
                                                <p>$ {{ $quote->precio_unitario }}</p>
                                            </td>
                                            <td class="pr-5">
                                                <p> {{ $quote->cantidad }} piezas</p>
                                            </td>
                                            <td>
                                                <p>$ {{ $quote->precio_total }}</p>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @php
                                        $subtotal = auth()
                                            ->user()
                                            ->currentQuote->currentQuoteDetails->sum('precio_total');
                                        $discount = 0;
                                        if (auth()->user()->currentQuote->type == 'Fijo') {
                                            $discount = auth()->user()->currentQuote->value;
                                        } else {
                                            $discount = round(($subtotal / 100) * auth()->user()->currentQuote->value, 2);
                                        }
                                    @endphp
                                    <tr>
                                        <th colspan="3">Subtotal</th>
                                        <th>$ {{ $subtotal }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3">Descuento</th>
                                        <th>$ {{ $discount }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3">Total</th>
                                        <th>$ {{ $subtotal - $discount }}</th>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="d-flex flex-column">
                                <div wire:loading wire:target="guardarCotizacion">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                <button class="btn btn-primary btn-sm" onclick="preview()">Previsualizar Cotizacion</button>
                                <br>
                                <button class="btn btn-primary btn-sm" onclick="enviar()">Guardar Cotizacion</button>
                            </div>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div wire:ignore.self class="modal fade" id="modalPreview" tabindex="-1"
                        aria-labelledby="modalPreviewLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalPreviewLabel">Vista Previa de la Cotizacion</h5>
                                    <button type="button" class="close" onclick="cerrarPreview()">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div wire:loading.flex wire:target="previewQuote" class="justify-content-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                    @if ($urlPDFPreview)
                                        <iframe src="{{ $urlPDFPreview }}" style="width:100%; height:700px;"
                                            frameborder="0"></iframe>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        onclick="cerrarPreview()">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-center">No tienes productos en tu cotizacion actual</p>
                @endif
            </div>
        </div>
    </div>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        window.addEventListener('isntCompany', event => {
            Swal.fire('No tienes una empresa asignada')
        })

        function preview() {
            $('#modalPreview').modal('show')
            @this.previewQuote()
        }

        function cerrarPreview() {
            $('#modalPreview').modal('hide')
            @this.urlPDFPreview = null;
        }

        function enviar() {
            Swal.fire({
                title: '¿Desea confirmar la cotización?',
                html: "{{ auth()->user()->companySession->name }}<br><br>Se enviará una copia de la cotización al correo electrónico establecido y se registra como un lead nuevo en Odoo.",
                showCancelButton: true,
                icon: 'warning',
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    @this.guardarCotizacion()
                } else {
                    Swal.fire('No se realizo ningun cambio', '', 'info')
                }
            })
        }
    </script>
</div>
