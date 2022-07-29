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
                            <input wire:model='keyWord' type="text" class="form-control" name="search"
                                id="search" placeholder="Search Prices Techniques">
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
                                    <th>Tamaños de las tecnicas</th>
                                    <th>Escala Inicial</th>
                                    <th>Escala Final</th>
                                    <th>Precio</th>
                                    <th>Tipo Precio</th>
                                    <td>Acciones</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sizeMaterialTechniques as $row)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td> <b>{{ $row->materialTechnique->material->nombre }}</b>
                                            {{ $row->materialTechnique->technique->nombre . ' ' . $row->size->nombre }}
                                        </td>
                                        <td>{{ $row->escala_inicial }}</td>
                                        <td>{{ $row->escala_final }}</td>
                                        <td>{{ $row->precio }}</td>
                                        <td>{{ $row->tipo_precio }}</td>
                                        <td width="90">
                                            <button type="button" class="btn btn-info btn-sm" wire:click="editar({{  }})">
                                                Editar
                                            </button>
                                        </td>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $sizeMaterialTechniques->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
