@extends('layouts.cotizador')
@section('content')
    <div class="container">
        <div class="card w-100">
            <div class="card-body">
                <p>Agregar Nuevo Producto</p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" name="nombre" placeholder="Nombre del producto">
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="nombre">Descripcion</label>
                            <input type="text" class="form-control" name="nombre"
                                placeholder="Descripcion del producto">
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="nombre">Precio</label>
                            <input type="number" class="form-control" name="nombre" placeholder="Precio del producto">
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="nombre">Stock</label>
                            <input type="number" class="form-control" name="nombre"
                                placeholder="Cantidad Disponible o Cantidad que desea cotizar">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">Imagen</label>
                            <input type="file" class="form-control" name="nombre" placeholder="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection()
