<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
    
        $request->session()->regenerate();
    
        $user = Auth::user();
        
        // Verificar y redirigir según el rol del usuario
        if ($user->roles->contains('name', 'admin')) {
            return redirect()->route('productos.index');
        } elseif ($user->roles->contains('name', 'barra')) {
            return redirect()->route('barra.index');
        } elseif ($user->roles->contains('name', 'cocina')) {
            return redirect()->route('cocinero.index');
        } elseif ($user->roles->contains('name', 'camarero')) {
            return redirect()->route('camarero.index');
        }

        // Redirigir a una ruta por defecto si el usuario no tiene ningún rol específico
        return redirect()->route('/dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
