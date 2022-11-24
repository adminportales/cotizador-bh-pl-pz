@extends('layouts.cotizador')
@section('content')
    <div class="container">
        <div class="card w-100">
            <div class="card-body">
                <h5>Agregar Nuevo Producto</h5>
                <p><span class="text-danger">Importante:</span> Este producto solo sera registrado para esta cotizacion y no
                    sera posible volver a cotizarlo.</p>
                <form action="{{ route('storeproduct.cotizador') }}" enctype="multipart/form-data" method="POST">
                    @method('POST')
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" name="nombre" placeholder="Nombre del producto"
                                    value="{{ old('nombre') }}">
                                @if ($errors->has('nombre'))
                                    <span class="text-danger">{{ $errors->first('nombre') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Descripcion</label>
                                <input type="text" class="form-control" name="descripcion"
                                    placeholder="Descripcion del producto" value="{{ old('descripcion') }}">
                                @if ($errors->has('descripcion'))
                                    <span class="text-danger">{{ $errors->first('descripcion') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Precio</label>
                                <input type="number" class="form-control" name="precio" placeholder="Precio del producto"
                                    value="{{ old('precio') }}">
                                @if ($errors->has('precio'))
                                    <span class="text-danger">{{ $errors->first('precio') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Stock</label>
                                <input type="number" class="form-control" name="stock"
                                    placeholder="Cantidad Disponible o Cantidad que desea cotizar"
                                    value="{{ old('stock') }}">
                                @if ($errors->has('stock'))
                                    <span class="text-danger">{{ $errors->first('stock') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Color</label>
                                <input type="text" class="form-control" name="color" placeholder="Color del producto"
                                    value="{{ old('color') }}">
                                @if ($errors->has('color'))
                                    <span class="text-danger">{{ $errors->first('color') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Proveedor</label>
                                <input type="text" class="form-control" name="proveedor"
                                    placeholder="Proverdor del Producto" value="{{ old('proveedor') }}">
                                @if ($errors->has('proveedor'))
                                    <span class="text-danger">{{ $errors->first('proveedor') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Imagen</label>
                                <input type="file" class="form-control" name="imagen">
                                @if ($errors->has('imagen'))
                                    <span class="text-danger">{{ $errors->first('imagen') }}</span>
                                @endif
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
