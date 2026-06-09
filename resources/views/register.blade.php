@extends('layouts.app')

@section('title', 'Cadastro - Locadora Prendatta')

@section('content')
    {{-- Cadastro manual de usuario: somente admin acessa esta tela via middleware profile:admin. --}}
    <div class="page-header">
        <div>
            <h1>Novo usuario</h1>
            <p class="muted">Crie um acesso com perfil de administrador ou atendente.</p>
        </div>
    </div>

    <section class="form-panel">
        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <div class="field-row">
                <div class="field">
                    <label for="name">Nome</label>
                    <input id="name" name="name" value="{{ old('name') }}" required autofocus>
                    @error('name') <div class="error-text">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                    @error('email') <div class="error-text">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="telefone">Telefone</label>
                    <input id="telefone" name="telefone" value="{{ old('telefone') }}">
                    @error('telefone') <div class="error-text">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label for="profile">Perfil</label>
                    <select id="profile" name="profile" required>
                        @foreach (\App\Models\User::PROFILE_LABELS as $valor => $label)
                            <option value="{{ $valor }}" @selected(old('profile', \App\Models\User::PROFILE_ATENDENTE) === $valor)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('profile') <div class="error-text">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="password">Senha</label>
                    <input id="password" type="password" name="password" required>
                    @error('password') <div class="error-text">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label for="password_confirmation">Confirmar senha</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>
            </div>

            <div class="actions">
                <button class="btn primary" type="submit">Salvar usuario</button>
                <a class="btn secondary" href="{{ route('usuarios.index') }}">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
