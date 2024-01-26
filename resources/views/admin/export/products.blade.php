@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('exportProducts.download.cotizador') }}">
            @csrf
            <button type="submit">Enviar</button>
        </form>
    </div>
@endsection
