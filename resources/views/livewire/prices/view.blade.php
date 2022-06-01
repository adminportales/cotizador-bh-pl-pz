<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <h4><i class="fab fa-laravel text-info"></i>
                                Listado de Precios</h4>
                        </div>
                        @if (session()->has('message'))
                            <div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;">
                                {{ session('message') }} </div>
                        @endif
                        <div>
                            <input wire:model='keyWord' type="text" class="form-control" name="search" id="search"
                                placeholder="Search Prices Techniques">
                        </div>
                        <div class="btn btn-sm btn-info" data-toggle="modal" data-target="#createDataPTModal">
                            <i class="fa fa-plus"></i> Add Prices Techniques
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @include('livewire.prices.create')
                    @include('livewire.prices.update')
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead">
                                <tr>
                                    <td>#</td>
                                    <th>Size Material Technique Id</th>
                                    <th>Escala Inicial</th>
                                    <th>Escala Final</th>
                                    <th>Precio</th>
                                    <th>Tipo Precio</th>
                                    <td>ACTIONS</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pricesTechniques as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row->size_material_technique_id }}</td>
                                        <td>{{ $row->escala_inicial }}</td>
                                        <td>{{ $row->escala_final }}</td>
                                        <td>{{ $row->precio }}</td>
                                        <td>{{ $row->tipo_precio }}</td>
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
                                                        onclick="confirm('Confirm Delete Prices Technique id {{ $row->id }}? \nDeleted Prices Techniques cannot be recovered!')||event.stopImmediatePropagation()"
                                                        wire:click="destroy({{ $row->id }})"><i
                                                            class="fa fa-trash"></i> Delete </a>
                                                </div>
                                            </div>
                                        </td>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $pricesTechniques->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
