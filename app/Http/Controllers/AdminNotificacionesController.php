<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\NotificacionDestinatario;
use Illuminate\Http\Request;

class AdminNotificacionesController extends Controller
{
    /** ===================== Destinatarios ===================== */

    public function destinatariosIndex()
    {
        $destinatarios = NotificacionDestinatario::orderBy('email')->get();
        return view('admin.notificaciones.destinatarios', compact('destinatarios'));
    }

    public function destinatariosStore(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|max:255',
            'nombre' => 'nullable|string|max:255',
            'eventos' => 'nullable|array',
            'eventos.*' => 'string',
            'activo' => 'boolean',
        ]);

        NotificacionDestinatario::create([
            'email' => $data['email'],
            'nombre' => $data['nombre'] ?? null,
            'eventos' => $data['eventos'] ?? [],
            'activo' => $request->boolean('activo', true),
        ]);

        return redirect()->route('admin.notificaciones.destinatarios.index')
            ->with('success', 'Destinatario agregado.');
    }

    public function destinatariosUpdate(Request $request, NotificacionDestinatario $destinatario)
    {
        $data = $request->validate([
            'email' => 'required|email|max:255',
            'nombre' => 'nullable|string|max:255',
            'eventos' => 'nullable|array',
            'eventos.*' => 'string',
            'activo' => 'boolean',
        ]);

        $destinatario->update([
            'email' => $data['email'],
            'nombre' => $data['nombre'] ?? null,
            'eventos' => $data['eventos'] ?? [],
            'activo' => $request->boolean('activo', true),
        ]);

        return redirect()->route('admin.notificaciones.destinatarios.index')
            ->with('success', 'Destinatario actualizado.');
    }

    public function destinatariosDestroy(NotificacionDestinatario $destinatario)
    {
        $destinatario->delete();
        return redirect()->route('admin.notificaciones.destinatarios.index')
            ->with('success', 'Destinatario eliminado.');
    }

    /** ===================== Plantillas ===================== */

    public function plantillasIndex()
    {
        $plantillas = EmailTemplate::orderBy('name')->get();
        return view('admin.notificaciones.plantillas-index', compact('plantillas'));
    }

    public function plantillasEdit(EmailTemplate $plantilla)
    {
        return view('admin.notificaciones.plantillas-edit', compact('plantilla'));
    }

    public function plantillasUpdate(Request $request, EmailTemplate $plantilla)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $plantilla->update([
            'subject' => $data['subject'],
            'body' => $data['body'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.notificaciones.plantillas.edit', $plantilla)
            ->with('success', 'Plantilla actualizada.');
    }
}
