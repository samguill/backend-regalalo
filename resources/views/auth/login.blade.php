@extends('layouts.app')

@section('content')
    <form class="login-form" method="POST" action="{{ route('login') }}">
        <h3 class="login-head">Iniciar sesión</h3>
        @csrf
        <div class="form-group">
            <label class="control-label">E-mail</label>
            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="E-mail" value="{{ old('email') }}" required autofocus>
            @if ($errors->has('email'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            <label class="control-label">Contraseña</label>
            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Contraseña" required>
            @if ($errors->has('password'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <div class="utility">
                <div class="animated-checkbox">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="label-text">Recordarme</span>
                    </label>
                </div>
                <p class="semibold-text mb-2">
                    <a href="#" data-toggle="flip">Olvidaste tu contraseña?</a>
                </p>
            </div>
            <div class="form-group btn-container">
                <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>Ingresar</button>
            </div>
        </div>
    </form>

    <form method="POST" class="forget-form" action="{{ route('password.email') }}">
        <h3 class="login-head">Recuperar contraseña</h3>
        @csrf
        <div class="form-group">
            <label class="control-label">E-mail</label>
            <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="E-mail" name="email" value="{{ old('email') }}" required>
            @if ($errors->has('email'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group btn-container">
            <button class="btn btn-primary btn-block"><i class="fa fa-unlock fa-lg fa-fw"></i>Aceptar</button>
        </div>
        <div class="form-group mt-3">
            <p class="semibold-text mb-0"><a href="#" data-toggle="flip"><i class="fa fa-angle-left fa-fw"></i> Regresara al Login</a></p>
        </div>
    </form>
@endsection
