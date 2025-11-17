<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class CorreoController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:root');
    }

    /**
     * Mostrar el formulario de prueba de correo
     */
    public function index()
    {
        return view('correo.prueba');
    }

    /**
     * Enviar correo de prueba
     */
    public function enviar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'destinatario' => 'required|email',
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string',
        ], [
            'destinatario.required' => 'El campo destinatario es obligatorio.',
            'destinatario.email' => 'El destinatario debe ser un correo electrónico válido.',
            'asunto.required' => 'El campo asunto es obligatorio.',
            'mensaje.required' => 'El campo mensaje es obligatorio.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $destinatario = $request->destinatario;
            $asunto = $request->asunto;
            $mensaje = $request->mensaje;

            Mail::raw($mensaje, function ($message) use ($destinatario, $asunto) {
                $message->to($destinatario)
                        ->subject($asunto);
            });

            return redirect()->back()
                ->with('success', 'Correo enviado exitosamente a ' . $destinatario);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al enviar el correo: ' . $e->getMessage())
                ->withInput();
        }
    }
}

