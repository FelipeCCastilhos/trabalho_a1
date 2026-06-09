@extends('layouts.app')

@section('title', 'Login - Locadora Prendatta')

@section('content')
    {{-- Tela publica de login: autentica com email e senha via Auth::attempt no controller. --}}
    <div class="page-header">
        <div>
            <h1>Login</h1>
            <p class="muted">Acesse o sistema da Locadora Prendatta.</p>
        </div>
    </div>

    <section class="form-panel">
        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email') <div class="error-text">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label for="password">Senha</label>
                <input id="password" type="password" name="password" required>
                @error('password') <div class="error-text">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label class="checkbox-row">
                    <input type="checkbox" name="remember" value="1">
                    Manter conectado
                </label>
            </div>

            <div class="actions">
                <button class="btn primary" type="submit">Entrar</button>
            </div>
        </form>
    </section>
@endsection
