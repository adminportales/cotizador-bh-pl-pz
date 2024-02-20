@section('title', __('Users'))
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <h4><i class="fab fa-laravel text-info"></i>
                                User Listing </h4>
                        </div>
                        @if (session()->has('message'))
                            <div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;">
                                {{ session('message') }} </div>
                        @endif
                        <div>
                            <input wire:model='keyWord' type="text" class="form-control" name="search"
                                id="search" placeholder="Search Users">
                        </div>
                        <div class="btn btn-sm btn-info" data-toggle="modal" data-target="#createDataModal">
                            <i class="fa fa-plus"></i> Add Users
                        </div>
                        <!--<div class="btn btn-sm btn-info" onclick="enviar()">-->
                        <!--    <i class="fa fa-plus"></i> Enviar Acceso-->
                        <!--</div>-->
                        <div class="">
                            <a href="{{ route('exportUsuarios.cotizador') }}" class="btn btn-sm btn-info">Descargar
                                Usuarios</a>

                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @include('admin.users.create')
                    @include('admin.users.update')
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead">
                                <tr>
                                    <td>#</td>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Último Login</th>
                                    <th>InformacIón</th>
                                    <td>ACTIONS</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->email }}</td>
                                        <td>
                                            @if ($row->last_login)
                                                {{ $row->last_login }}
                                            @else
                                                <p>No hay registro</p>
                                            @endif
                                        </td>
                                        <td>
                                            @foreach ($row->info as $infoOdoo)
                                                <p class="m-0 p-0"><strong>Empresa: </strong>
                                                    {{ $infoOdoo->company->name }} <strong>Id Odoo: </strong>
                                                    {{ $infoOdoo->odoo_id }}
                                                </p>
                                            @endforeach
                                        </td>
                                        <td width="90">
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-info btn-sm dropdown-toggle py-1 px-2"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a data-toggle="modal" data-target="#updateModal"
                                                        class="dropdown-item" wire:click="edit({{ $row->id }})"><i
                                                            class="fa fa-edit"></i> Editar </a>
                                                    <a class="dropdown-item"
                                                        onclick="confirm('Confirm Delete User id {{ $row->id }}? \nDeleted Users cannot be recovered!')||event.stopImmediatePropagation()"
                                                        wire:click="destroy({{ $row->id }})"><i
                                                            class="fa fa-trash"></i> Eliminar </a>
                                                    <a class="dropdown-item"
                                                        onclick="confirm('Enviar acceso a {{ $row->name }} por email?')||event.stopImmediatePropagation()"
                                                        wire:click="sendAccess({{ $row->id }})"><i
                                                            class="fa fa-trash"></i> Enviar acceso </a>
                                                </div>
                                            </div>
                                        </td>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function enviar(id) {
            Swal.fire({
                title: 'Esta seguro?',
                text: "Se enviara un email a todos los usuarios!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si!',
                cancelButtonText: 'Cancelar!'
            }).then((result) => {
                if (result.isConfirmed) {


                    let respuesta = @this.sendAccessAll()
                    Swal.fire('Por Favor Espere');
                    respuesta
                        .then((response) => {
                            console.log(response);
                            if (response == 1) {
                                Swal.fire(
                                    'Se han enviado los accesos',
                                    'Cada usuario ya tiene su acceso.',
                                    'success'
                                )
                            }
                            if (response != 1) {
                                Swal.fire(
                                    'Se enviaron los accesos, pero estos no se pudieron enviar',
                                    response, 'success')
                            }
                        }, function() {
                            // one or more failed
                            Swal.fire('¡Error al autorizar!', '', 'error')
                        });
                }
            })
        }
    </script>
</div>
