@extends('layouts.cotizador')
@section('content')
    <div class="container">
        <div class="card w-100">
            <div class="card-body">
                <h5>Agregar Nuevo Producto</h5>
                <p><span class="text-danger">Importante:</span> Este producto solo sera registrado para esta cotizacion y no
                    sera posible volver a cotizarlo.</p>
                <form action="{{ route('storeproduct.cotizador') }}">
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
                                <input type="text" class="form-control" name="descripcion"
                                    placeholder="Descripcion del producto">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Precio</label>
                                <input type="number" class="form-control" name="precio" placeholder="Precio del producto">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Stock</label>
                                <input type="number" class="form-control" name="stock"
                                    placeholder="Cantidad Disponible o Cantidad que desea cotizar">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Color</label>
                                <input type="text" class="form-control" name="color" placeholder="Color del producto">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Proveedor</label>
                                <input type="text" class="form-control" name="proveedor"
                                    placeholder="Proverdor del Producto">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Informacion Adicional </label>
                                <textarea name="informacion" placeholder="Medidas, peso, etc" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Imagen</label>
                                <input type="file" class="form-control" name="imagen">
                            </div>
                            <p>Si tu imagen pesa mas de 500 KB, puedes comprimirla <a
                                    href="https://www.iloveimg.com/es/comprimir-imagen" target="_blank">aqui</a></p>
                        </div>
                        <div class="col-md-12">
                            <input type="submit" class="btn btn-primary" value="Utilizar Producto">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection()
