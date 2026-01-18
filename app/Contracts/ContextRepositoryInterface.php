<?php

namespace App\Contracts;

use App\Enums\EkaptaContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Contract untuk Repository dengan dukungan Context (TA/KP)
 */
interface ContextRepositoryInterface
{
    /**
     * Set context untuk repository
     */
    public function setContext(EkaptaContext $context): self;

    /**
     * Get context aktif
     */
    public function getContext(): EkaptaContext;

    /**
     * Get semua data
     */
    public function all(): Collection;

    /**
     * Get data dengan pagination
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find by ID
     */
    public function find(int $id): ?Model;

    /**
     * Find or fail
     */
    public function findOrFail(int $id): Model;

    /**
     * Create data baru
     */
    public function create(array $data): Model;

    /**
     * Update data
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete data
     */
    public function delete(int $id): bool;

    /**
     * Get query builder dengan context
     */
    public function query(): Builder;

    /**
     * Find by specific column
     */
    public function findBy(string $column, mixed $value): ?Model;

    /**
     * Find all by specific column
     */
    public function findAllBy(string $column, mixed $value): Collection;
}
