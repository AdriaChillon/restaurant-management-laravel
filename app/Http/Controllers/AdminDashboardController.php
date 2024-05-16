<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $users = User::all();
        $roles = Role::all();

        return view('admin.dashboard', compact('users', 'roles'));
    }

    public function assignRole(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $role = $request->role;

        $user->assignRole($role);

        return redirect()->back()->with('success', 'Rol asignado correctamente.');
    }
}
