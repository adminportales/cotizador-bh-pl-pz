@extends('layouts.app')
@section('content')
    <div class="col-md-6">
        @livewire('materials')
    </div>
    <div class="col-md-6">
        @livewire('techniques')
    </div>
    <div class="col-md-6">
        @livewire('material-techniques')
    </div>
    <div class="col-md-6">
        @livewire('sizes')
    </div>
    <div class="col-md-6">
        @livewire('size-material-techniques')
    </div>
    <div class="col-md-6">
        @livewire('prices-techniques')
    </div>
@endsection
