@section('title', __('Clients'))
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <h4><i class="fab fa-laravel text-info"></i>
                                Client Listing </h4>
                        </div>
                        @if (session()->has('message'))
                            <div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;">
                                {{ session('message') }} </div>
                        @endif
                        <div>
                            <input wire:model='keyWord' type="text" class="form-control" name="search"
                                id="search" placeholder="Search Clients">
                        </div>
                        <div class="btn btn-sm btn-info" data-toggle="modal" data-target="#createDataModal">
                            <i class="fa fa-plus"></i> Add Clients
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @include('livewire.clients.create')
                    @include('livewire.clients.update')
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead">
                                <tr>
                                    <td>#</td>
                                    <th>Odoo Id</th>
                                    <th>Usuario Ejecutivo</th>
                                    <th>Nombre de la empresa</th>
                                    <th>Contacto</th>
                                    <th>Email</th>
                                    <th>Telefono</th>
                                    <td>ACTIONS</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clients as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row->client_odoo_id }}</td>
                                        <td>
                                            @if ($row->user)
                                                {{ $row->user->name }}
                                            @endif
                                        </td>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->contact }}</td>
                                        <td>{{ $row->email }}</td>
                                        <td>{{ $row->phone }}</td>
                                        <td width="90">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info btn-sm dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a data-toggle="modal" data-target="#updateModal"
                                                        class="dropdown-item" wire:click="edit({{ $row->id }})"><i
                                                            class="fa fa-edit"></i> Edit </a>
                                                    <a class="dropdown-item"
                                                        onclick="confirm('Confirm Delete Client id {{ $row->id }}? \nDeleted Clients cannot be recovered!')||event.stopImmediatePropagation()"
                                                        wire:click="destroy({{ $row->id }})"><i
                                                            class="fa fa-trash"></i> Delete </a>
                                                </div>
                                            </div>
                                        </td>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $clients->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
