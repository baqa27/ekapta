<?php

namespace App\Helpers;

use App\Enums\EkaptaContext;
use Illuminate\Support\Facades\Session;

class ContextHelper
{
    const TA = 'ta';
    const KP = 'kp';

    const SESSION_KEY = 'app_context';

    /**
     * Set context (TA atau KP)
     */
    public static function set(string $context): void
    {
        if (in_array($context, [self::TA, self::KP])) {
            Session::put(self::SESSION_KEY, $context);
        }
    }

    /**
     * Get current context as string
     */
    public static function get(): ?string
    {
        return Session::get(self::SESSION_KEY);
    }

    /**
     * Get current context as EkaptaContext enum
     * Returns TA as default if no context set
     */
    public static function getEnum(): EkaptaContext
    {
        $context = Session::get(self::SESSION_KEY);
        return match($context) {
            self::KP => EkaptaContext::KERJA_PRAKTIK,
            default => EkaptaContext::TUGAS_AKHIR,
        };
    }

    /**
     * Check if context is TA
     */
    public static function isTA(): bool
    {
        return self::get() === self::TA;
    }

    /**
     * Check if context is KP
     */
    public static function isKP(): bool
    {
        return self::get() === self::KP;
    }

    /**
     * Get label for current context
     */
    public static function getLabel(): string
    {
        return match(self::get()) {
            self::TA => 'Tugas Akhir',
            self::KP => 'Kerja Praktik',
            default => ''
        };
    }

    /**
     * Check if context has been set
     */
    public static function hasContext(): bool
    {
        return Session::has(self::SESSION_KEY);
    }

    /**
     * Clear context
     */
    public static function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    /**
     * Get route prefix based on context
     */
    public static function getRoutePrefix(): string
    {
        return self::get() ?? '';
    }

    /**
     * Get view path prefix based on context
     */
    public static function getViewPrefix(): string
    {
        return match(self::get()) {
            self::TA => 'ta.',
            self::KP => 'kp.',
            default => ''
        };
    }
}
