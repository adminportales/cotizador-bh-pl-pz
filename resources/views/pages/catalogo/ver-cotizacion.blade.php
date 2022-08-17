@extends('layouts.app')

@section('content')
    <div class="container-fluid ">
        @livewire('ver-cotizacion-component', ['quote' => $quote])
    </div>
@endsection
