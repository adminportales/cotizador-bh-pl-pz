@extends('layouts.app')
@section('title', 'Catalogo de Productos')
@section('content')
    @livewire('catalogo')
@endsection

@section('styles')
    <link href="{{ asset('assets/css/components/cards/card.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/elements/custom-pagination.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .component-card_2 a.btn-primary {
            background: #1b55e2 !important;
            border-color: #1b55e2 !important;
        }

    </style>
@endsection
