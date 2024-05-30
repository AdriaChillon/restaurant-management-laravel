<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Búsqueda por nombre
        if ($request->has('search') && $request->input('search') != '') {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        // Búsqueda por rol
        if ($request->has('role') && $request->input('role') != '') {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->input('role'));
            });
        }

        $users = $query->get();
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
