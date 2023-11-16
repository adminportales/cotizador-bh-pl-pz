@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-9">
                @livewire('admin.materials')
            </div>
            <div class="col-md-3">
                @livewire('admin.techniques')
            </div>
        </div>
    </div>
@endsection
