<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProfile
{
    public function handle(Request $request, Closure $next, string ...$profiles): Response
    {
        // O middleware recebe perfis pela rota, por exemplo: profile:admin.
        if (! $request->user() || ! in_array($request->user()->profile, $profiles, true)) {
            // DELETE recebe mensagem especifica porque exclusao e restrita ao admin.
            if ($request->isMethod('delete')) {
                return back()->with('error', 'Apenas administradores podem excluir registros.');
            }

            // Nas demais rotas administrativas, volta ao dashboard com erro amigavel.
            return redirect()->route('dashboard')->with('error', 'Acesso restrito a administradores.');
        }

        return $next($request);
    }
}
