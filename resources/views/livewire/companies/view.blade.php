@section('title', __('Companys'))
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <h4><i class="fab fa-laravel text-info"></i>
                                Company Listing </h4>
                        </div>
                        <div wire:poll.60s>
                            <code>
                                <h5>{{ now()->format('H:i:s') }} UTC</h5>
                            </code>
                        </div>
                        @if (session()->has('message'))
                            <div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;">
                                {{ session('message') }} </div>
                        @endif
                        <div>
                            <input wire:model='keyWord' type="text" class="form-control" name="search"
                                id="search" placeholder="Search Companys">
                        </div>
                        <div class="btn btn-sm btn-info" data-toggle="modal" data-target="#createDataModal">
                            <i class="fa fa-plus"></i> Add Companys
                        </div>


                    </div>
                </div>

                <div class="card-body">
                    @include('livewire.companies.create')
                    @include('livewire.companies.prooveder')
                    @include('livewire.companies.update')

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead">
                                <tr>
                                    <td>#</td>
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Manager</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Proveder</th>
                                    <td>ACTIONS</td>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($companies as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->image }}</td>
                                        <td>{{ $row->manager }}</td>
                                        <td>{{ $row->email }}</td>
                                        <td>{{ $row->phone }}</td>

                                        <td width="80">
                                            <button type="button" class="btn btn-info btn-sm dropdown-toggle"
                                                data-toggle="modal" data-target="#exampleModal{{ $row->id }}">
                                                Add proveders
                                            </button>
                                        </td>
                                        <!-- Modal -->
                                        <div wire:ignore.self class="modal fade" id="exampleModal{{ $row->id }}"
                                            tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Proveedores
                                                        </h5>
                                                        <label>{{ $row->name }}</label>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>


                                                    <div class="modal-body">
                                                        <div class="form-check">
                                                            @foreach ($proveders as $proveder)
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="{{ $proveder->id }}"
                                                                    name="selectedProveedores[]"
                                                                    wire:model="selectedProveedores"
                                                                    id="proveder_{{ $proveder->id }}">
                                                                <label class="form-check-label"

                                                                    for="proveder_{{ $proveder->id }}">
                                                                    {{ $proveder->company }}
                                                                </label>
                                                                <br>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary"
                                                            wire:click="saveSelectedProveedores({{ $row->id }})">Guardar
                                                            Respuesta</button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>



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
                                                        onclick="confirm('Confirm Delete Company id {{ $row->id }}? \nDeleted Companys cannot be recovered!')||event.stopImmediatePropagation()"
                                                        wire:click="destroy({{ $row->id }})"><i
                                                            class="fa fa-trash"></i> Delete </a>
                                                </div>
                                            </div>
                                        </td>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $companies->links() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
