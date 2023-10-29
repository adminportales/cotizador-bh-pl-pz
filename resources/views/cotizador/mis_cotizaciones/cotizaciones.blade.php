@extends('layouts.cotizador')

@section('title', 'Mis Cotizaciones')
@section('content')
    <div class="container">
        <style>
            .table td {
                vertical-align: middle;
            }
        </style>
        <div class="row px-3">
            <div class="card w-100">
                <div class="card-body">
                    @livewire('cotizador.list-quotes-component')
                </div>
            </div>
        </div>
    </div>
@endsection
