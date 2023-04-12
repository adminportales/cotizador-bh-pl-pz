@extends('layouts.guest')

@section('content')
    <div class="form-container sign-in-container">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <h1>Iniciar sesión</h1>
            <br>
            <input type="email" placeholder="Correo electrónico" class=" @error('email') is-invalid @enderror" name="email"
                value="{{ old('email') }}" required autocomplete="email" autofocus />
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <input placeholder="Contraseña" id="password" type="password"
                class="form-control @error('password') is-invalid @enderror" name="password" required
                autocomplete="current-password" />
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <br>
            <button style="width:100% ;" type="submit">Ingresar</button>
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-right">
                <h1>¡Bienvenido!</h1>
                <br>
                <img style="width: 200px;" src="{{ asset('img/logo-pilgrims.png') }}" alt="logo">
                <p>Para conectarse, inicie sesión con su información personal</p>
            </div>
        </div>
    </div>
@endsection
