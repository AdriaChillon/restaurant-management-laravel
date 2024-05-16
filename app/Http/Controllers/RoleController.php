<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $users = User::all();
        $roles = Role::all();

        return view('admin.roles.index', compact('users', 'roles'));
    }

    public function updateRoles(Request $request, User $user)
    {
        try {
            $user->syncRoles($request->roles);
            return redirect()->back()->with('success', 'Roles actualizados correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Se produjo un error al actualizar los roles. Por favor, inténtalo de nuevo.');
        }
    }    
}
