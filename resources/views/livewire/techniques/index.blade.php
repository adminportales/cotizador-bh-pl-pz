@extends('layouts.app')
@section('content')
    <div class="col-md-6">
        @livewire('materials')
    </div>
    <div class="col-md-6">
        @livewire('techniques')
    </div>
@endsection
