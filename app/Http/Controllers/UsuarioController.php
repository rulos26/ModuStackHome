<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $usuarios = User::with('roles')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', User::class);

        $roles = Role::all();
        return view('usuarios.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $usuario = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Asignar rol cliente por defecto
            $rolCliente = Role::where('name', 'cliente')->first();
            if ($rolCliente) {
                $usuario->assignRole($rolCliente);
            }

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario creado exitosamente con rol cliente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear el usuario: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $usuario)
    {
        $this->authorize('view', $usuario);

        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $usuario)
    {
        $this->authorize('update', $usuario);

        $roles = Role::all();
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        $this->authorize('update', $usuario);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $usuario->id,
        ];

        $messages = [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
        ];

        // Si se proporciona una contraseña, validarla
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
            $messages['password.required'] = 'La contraseña es obligatoria.';
            $messages['password.min'] = 'La contraseña debe tener al menos 8 caracteres.';
            $messages['password.confirmed'] = 'Las contraseñas no coinciden.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $usuario->name = $request->name;
            $usuario->email = $request->email;

            if ($request->filled('password')) {
                $usuario->password = Hash::make($request->password);
            }

            $usuario->save();

            // Solo root y admin pueden cambiar roles
            if (auth()->user()->hasAnyRole(['root', 'admin']) && $request->filled('role')) {
                $nuevoRol = Role::find($request->role);
                if ($nuevoRol) {
                    $usuario->syncRoles([$nuevoRol->name]);
                }
            }

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el usuario: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        $this->authorize('delete', $usuario);

        try {
            // No permitir eliminar a sí mismo
            if (auth()->id() === $usuario->id) {
                return redirect()->route('usuarios.index')
                    ->with('error', 'No puedes eliminar tu propia cuenta.');
            }

            $usuario->delete();

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }
}
