<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Verificar y redirigir según el rol del usuario
        if ($user->roles->contains('name', 'admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->roles->contains('name', 'barra')) {
            return redirect()->route('barra.index');
        } elseif ($user->roles->contains('name', 'cocina')) {
            return redirect()->route('cocinero.index');
        } elseif ($user->roles->contains('name', 'camarero')) {
            return redirect()->route('camarero.index');
        }

        // Redirigir a una ruta por defecto si el usuario no tiene ningún rol específico
        return redirect()->route('dashboard');
    }
}
