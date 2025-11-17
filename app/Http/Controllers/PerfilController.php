<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PerfilController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar el formulario de perfil
     */
    public function index()
    {
        $usuario = auth()->user();
        return view('perfil.index', compact('usuario'));
    }

    /**
     * Actualizar el perfil del usuario
     */
    public function update(Request $request)
    {
        $usuario = auth()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $usuario->id,
            'password' => 'nullable|string|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $messages = [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser jpeg, png, jpg o gif.',
            'image.max' => 'La imagen no debe ser mayor a 2MB.',
        ];

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

            // Manejar subida de imagen
            if ($request->hasFile('image')) {
                // Eliminar imagen anterior si existe
                if ($usuario->image) {
                    // Remover 'public/' de la ruta para obtener la ruta física
                    $imagePath = str_replace('public/', '', $usuario->image);
                    if (file_exists(public_path($imagePath))) {
                        unlink(public_path($imagePath));
                    }
                }

                // Crear directorio si no existe
                $userDir = public_path('img/user');
                if (!file_exists($userDir)) {
                    mkdir($userDir, 0755, true);
                }
                
                // Obtener extensión del archivo
                $extension = $request->file('image')->getClientOriginalExtension();
                // Nombre del archivo: nombre del usuario + extensión
                $fileName = str_replace(' ', '_', $usuario->name) . '_' . time() . '.' . $extension;
                $imagePath = 'public/img/user/' . $fileName;
                
                // Mover archivo a public/img/user/
                $request->file('image')->move($userDir, $fileName);
                $usuario->image = $imagePath;
            }

            $usuario->save();

            return redirect()->route('perfil.index')
                ->with('success', 'Perfil actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el perfil: ' . $e->getMessage())
                ->withInput();
        }
    }
}
