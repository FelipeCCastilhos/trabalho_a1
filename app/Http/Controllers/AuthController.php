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

    public function showRegister(): View
    {
        // Esta tela so e aberta por admin, pois a rota usa auth + profile:admin.
        return view('register');
    }

    public function register(Request $request): RedirectResponse
    {
        // O middleware profile:admin impede cadastro publico ou por atendente.
        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'email' => ['required', 'email', 'max:120', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', 'min:6'],
            'profile' => ['required', Rule::in(array_keys(User::PROFILE_LABELS))],
            'telefone' => ['nullable', 'string', 'max:20'],
        ]);

        // Hash::make garante que a senha nunca seja gravada em texto puro.
        User::create([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'password' => Hash::make($data['password']),
            'profile' => $data['profile'],
            'telefone' => $data['telefone'] ?? null,
            'ativo' => true,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario cadastrado com sucesso.');
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
