<?php

namespace App\Contracts;

use App\Enums\EkaptaContext;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Contract untuk Service dengan dukungan Context (TA/KP)
 */
interface ContextServiceInterface
{
    /**
     * Set context untuk service
     */
    public function setContext(EkaptaContext $context): self;

    /**
     * Get context aktif
     */
    public function getContext(): EkaptaContext;

    /**
     * Check apakah dalam context TA
     */
    public function isTA(): bool;

    /**
     * Check apakah dalam context KP
     */
    public function isKP(): bool;
}
