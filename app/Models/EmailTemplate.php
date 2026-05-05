<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'key', 'name', 'description', 'subject', 'body',
        'available_variables', 'is_active',
    ];

    protected $casts = [
        'available_variables' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Render una plantilla reemplazando {variable} con los valores provistos.
     * Devuelve ['subject' => ..., 'body' => ...] o null si no existe / está inactiva.
     */
    public static function render(string $key, array $vars = []): ?array
    {
        $tpl = static::where('key', $key)->where('is_active', true)->first();
        if (!$tpl) {
            return null;
        }

        return [
            'subject' => self::interpolate($tpl->subject, $vars),
            'body' => self::interpolate($tpl->body, $vars),
        ];
    }

    private static function interpolate(string $text, array $vars): string
    {
        foreach ($vars as $k => $v) {
            $text = str_replace('{' . $k . '}', (string) $v, $text);
        }
        return $text;
    }
}
