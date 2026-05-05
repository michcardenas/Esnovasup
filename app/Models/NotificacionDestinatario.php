<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificacionDestinatario extends Model
{
    protected $table = 'notificaciones_destinatarios';

    protected $fillable = [
        'email', 'nombre', 'eventos', 'activo',
    ];

    protected $casts = [
        'eventos' => 'array',
        'activo' => 'boolean',
    ];

    /**
     * Devuelve los emails activos suscritos a un evento dado.
     * Si `eventos` es null o array vacío, se considera "todos los eventos".
     */
    public static function emailsParaEvento(string $evento): array
    {
        return static::query()
            ->where('activo', true)
            ->get()
            ->filter(function ($d) use ($evento) {
                $eventos = $d->eventos ?? [];
                return empty($eventos) || in_array($evento, $eventos, true);
            })
            ->pluck('email')
            ->unique()
            ->values()
            ->all();
    }
}
