@extends('layouts.cotizador')
@section('content')
    <div class="container">
        <div class="card w-100">
            <div class="card-body">
                @livewire('components.add-new-product')
            </div>
        </div>
    </div>
@endsection()
