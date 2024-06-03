<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RoleCheckController extends Controller
{
    public function checkRole()
    {
        $user = Auth::user();
        $role = null;

        if ($user->roles->contains('name', 'admin')) {
            $role = 'admin';
        } elseif ($user->roles->contains('name', 'barra')) {
            $role = 'barra';
        } elseif ($user->roles->contains('name', 'cocina')) {
            $role = 'cocina';
        } elseif ($user->roles->contains('name', 'camarero')) {
            $role = 'camarero';
        }

        return response()->json(['role' => $role]);
    }
}
