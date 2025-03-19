<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $currentUser = Auth::user();

        // Si es root, puede ver a todos los usuarios
        if ($currentUser->hasRole('root')) {
            $users = User::all();
        }
        // Si es admin, no puede ver usuarios root
        else {
            $users = User::where('role', '!=', 'root')->get();
        }

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $currentUser = Auth::user();
        $roles = ['admin', 'user'];

        // Solo el root puede crear otros usuarios root
        if ($currentUser->hasRole('root')) {
            $roles = ['admin', 'user', 'root'];
        }

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();

        // Validación base
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        // Validación de rol
        if ($currentUser->hasRole('root')) {
            $rules['role'] = ['required', 'string', Rule::in(['admin', 'user', 'root'])];
        } else {
            $rules['role'] = ['required', 'string', Rule::in(['admin', 'user'])];
        }

        $validatedData = $request->validate($rules);

        // Verificar si ya existe un usuario root y están intentando crear otro
        if ($validatedData['role'] === 'root' && User::where('role', 'root')->exists() && $currentUser->role !== 'root') {
            return back()->withErrors(['role' => 'Ya existe un usuario con rol root.'])->withInput();
        }

        // Crear usuario
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'],
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado con éxito.');
    }

    /**
     * Display the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(User $user)
    {
        $currentUser = Auth::user();

        // Un admin no puede ver detalles de usuarios root
        if (!$currentUser->hasRole('root') && $user->hasRole('root')) {
            abort(403, 'No tienes permiso para ver este usuario.');
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(User $user)
    {
        $currentUser = Auth::user();

        // Un admin no puede editar usuarios root
        if (!$currentUser->hasRole('root') && $user->hasRole('root')) {
            abort(403, 'No tienes permiso para editar este usuario.');
        }

        // El usuario root no se puede editar a sí mismo para cambiar su rol
        if ($currentUser->id === $user->id && $currentUser->hasRole('root')) {
            $roles = ['root']; // Solo mantiene su rol actual
        } elseif ($currentUser->hasRole('root')) {
            $roles = ['admin', 'user', 'root'];
        } else {
            $roles = ['admin', 'user'];
        }

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $currentUser = Auth::user();

        // Un admin no puede actualizar usuarios root
        if (!$currentUser->hasRole('root') && $user->hasRole('root')) {
            abort(403, 'No tienes permiso para actualizar este usuario.');
        }

        // Validación base
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ];

        // Si se está cambiando la contraseña
        if ($request->filled('password')) {
            $rules['password'] = ['string', 'min:8', 'confirmed'];
        }

        // Validación de rol
        if ($currentUser->hasRole('root') && $currentUser->id !== $user->id) {
            // El root puede cambiar cualquier rol excepto el suyo propio
            $rules['role'] = ['required', 'string', Rule::in(['admin', 'user', 'root'])];
        } elseif (!$currentUser->hasRole('root')) {
            // Los admin solo pueden cambiar a admin y user
            $rules['role'] = ['required', 'string', Rule::in(['admin', 'user'])];
        }

        $validatedData = $request->validate($rules);

        // Si el usuario que se está editando es root y no es el actual usuario root,
        // no se puede cambiar su rol
        if ($user->hasRole('root') && $currentUser->id !== $user->id) {
            unset($validatedData['role']);
        }

        // Actualizar usuario
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        // Actualizar rol si está presente
        if (isset($validatedData['role'])) {
            $user->role = $validatedData['role'];
        }

        // Actualizar contraseña si está presente
        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuario actualizado con éxito.');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $currentUser = Auth::user();

        // Nadie puede eliminar a un usuario root
        if ($user->hasRole('root')) {
            return back()->withErrors(['delete' => 'No se puede eliminar un usuario con rol root.']);
        }

        // Un usuario no puede eliminarse a sí mismo
        if ($currentUser->id === $user->id) {
            return back()->withErrors(['delete' => 'No puedes eliminarte a ti mismo.']);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado con éxito.');
    }
}
