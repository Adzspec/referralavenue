<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function index()
    {
        return Inertia::render('roles/index', [
            'roles' => Role::with('permissions')->paginate(10),
            'permissions' => Permission::all(),
            'can' => [
                'create' => auth()->user()->can('create roles'),
                'edit' => auth()->user()->can('edit roles'),
                'delete' => auth()->user()->can('delete roles'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        return redirect()->back();
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        return redirect()->back();
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->back();
    }
}
