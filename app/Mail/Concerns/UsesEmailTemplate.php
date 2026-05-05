<?php

namespace App\Mail\Concerns;

use App\Models\EmailTemplate;

/**
 * Permite que un Mailable use una plantilla administrable desde DB
 * (tabla `email_templates`). Si la plantilla está activa, su asunto y
 * cuerpo HTML reemplazan al envelope/content por defecto.
 *
 * El Mailable que use este trait debe:
 *   - Definir `protected string $templateKey;`
 *   - Implementar `protected function templateVars(): array;`
 *   - Definir un asunto y un blade de fallback (envelope() y content() normales).
 */
trait UsesEmailTemplate
{
    /**
     * Devuelve el render activo desde DB o null si no hay plantilla.
     * Cachea el resultado por instancia.
     */
    protected function dbTemplate(): ?array
    {
        if (!isset($this->templateKey)) {
            return null;
        }

        if (!property_exists($this, '_dbTemplateCache') || $this->_dbTemplateCache === false) {
            $this->_dbTemplateCache = EmailTemplate::render($this->templateKey, $this->templateVars()) ?: null;
        }

        return $this->_dbTemplateCache;
    }

    /** @var array|null|false */
    public $_dbTemplateCache = false;
}
