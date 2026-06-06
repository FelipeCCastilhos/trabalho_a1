<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        // Usuario autenticado nao deve voltar para a tela de login.
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('login');
    }

    public function login(Request $request): RedirectResponse
    {
        // Valida somente os campos necessarios para autenticar.
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        // Auth::attempt usa o provider de config/auth.php e compara a senha com o hash salvo.
        // O filtro ativo=true impede login de usuarios desativados.
        if (! Auth::attempt($credentials + ['ativo' => true], $remember)) {
            return back()
                ->withErrors(['email' => 'Credenciais invalidas ou usuario inativo.'])
                ->onlyInput('email');
        }

        // regenerate evita fixacao de sessao: depois do login, o id da sessao muda.
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function showRegister(Request $request): View|RedirectResponse
    {
        // Atendente logado nao pode abrir cadastro de usuario para criar outro perfil.
        if ($request->user() && ! $request->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Acesso restrito a administradores.');
        }

        return view('register');
    }

    public function register(Request $request): RedirectResponse
    {
        // A mesma protecao vale para o POST, caso alguem tente enviar direto pela URL.
        if ($request->user() && ! $request->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Acesso restrito a administradores.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'email' => ['required', 'email', 'max:120', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', 'min:6'],
            'profile' => ['required', Rule::in(array_keys(User::PROFILE_LABELS))],
            'telefone' => ['nullable', 'string', 'max:20'],
        ]);

        // Hash::make garante que a senha nunca seja gravada em texto puro.
        $user = User::create([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'password' => Hash::make($data['password']),
            'profile' => $data['profile'],
            'telefone' => $data['telefone'] ?? null,
            'ativo' => true,
        ]);

        // Se o admin ja esta logado, ele esta apenas cadastrando outro usuario.
        if ($request->user()) {
            return redirect()->route('usuarios.index')->with('success', 'Usuario cadastrado com sucesso.');
        }

        // Primeiro cadastro publico entra automaticamente no sistema.
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Cadastro realizado com sucesso.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        // Invalida a sessao atual para nao reaproveitar dados apos sair.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout realizado com sucesso.');
    }
}
