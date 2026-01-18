<?php

namespace App\Services;

use App\Contracts\ContextServiceInterface;
use App\Enums\EkaptaContext;
use App\Helpers\ContextHelper;

/**
 * Base Service dengan dukungan Context (TA/KP)
 */
abstract class BaseContextService implements ContextServiceInterface
{
    protected EkaptaContext $context;

    public function __construct()
    {
        $this->context = ContextHelper::getEnum();
    }

    /**
     * Set context
     */
    public function setContext(EkaptaContext $context): self
    {
        $this->context = $context;
        return $this;
    }

    /**
     * Get context
     */
    public function getContext(): EkaptaContext
    {
        return $this->context;
    }

    /**
     * Check apakah dalam context TA
     */
    public function isTA(): bool
    {
        return $this->context === EkaptaContext::TUGAS_AKHIR;
    }

    /**
     * Check apakah dalam context KP
     */
    public function isKP(): bool
    {
        return $this->context === EkaptaContext::KERJA_PRAKTIK;
    }

    /**
     * Get label context
     */
    public function getContextLabel(): string
    {
        return $this->context->label();
    }
}
