<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    public function index(Request $request): View
    {
        $busca = trim((string) $request->query('busca', ''));

        // Listagem administrativa para o menu "Usuarios" pedido no requisito.
        $usuarios = User::query()
            ->when($busca !== '', function ($query) use ($busca) {
                $query->where('name', 'like', "%{$busca}%")
                    ->orWhere('email', 'like', "%{$busca}%")
                    ->orWhere('profile', 'like', "%{$busca}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('usuarios.index', compact('usuarios', 'busca'));
    }
}
