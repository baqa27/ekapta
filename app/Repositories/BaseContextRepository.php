<?php

namespace App\Repositories;

use App\Contracts\ContextRepositoryInterface;
use App\Enums\EkaptaContext;
use App\Helpers\ContextHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Base Repository dengan dukungan Context (TA/KP)
 *
 * Repository ini secara otomatis memilih model yang tepat
 * berdasarkan context aktif (TA atau KP)
 */
abstract class BaseContextRepository implements ContextRepositoryInterface
{
    protected EkaptaContext $context;
    protected Model $model;

    /**
     * Get model class untuk TA
     */
    abstract protected function getTAModel(): string;

    /**
     * Get model class untuk KP
     */
    abstract protected function getKPModel(): string;

    public function __construct()
    {
        $this->context = ContextHelper::getEnum();
        $this->resolveModel();
    }

    /**
     * Resolve model berdasarkan context
     */
    protected function resolveModel(): void
    {
        $modelClass = $this->context === EkaptaContext::KERJA_PRAKTIK
            ? $this->getKPModel()
            : $this->getTAModel();

        $this->model = new $modelClass;
    }

    /**
     * Set context dan resolve model baru
     */
    public function setContext(EkaptaContext $context): self
    {
        $this->context = $context;
        $this->resolveModel();
        return $this;
    }

    /**
     * Get context aktif
     */
    public function getContext(): EkaptaContext
    {
        return $this->context;
    }

    /**
     * Get query builder
     */
    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * Get semua data
     */
    public function all(): Collection
    {
        return $this->query()->get();
    }

    /**
     * Get data dengan pagination
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()->paginate($perPage);
    }

    /**
     * Find by ID
     */
    public function find(int $id): ?Model
    {
        return $this->query()->find($id);
    }

    /**
     * Find or fail
     */
    public function findOrFail(int $id): Model
    {
        return $this->query()->findOrFail($id);
    }

    /**
     * Create data baru
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update data
     */
    public function update(int $id, array $data): bool
    {
        return $this->query()->where('id', $id)->update($data) > 0;
    }

    /**
     * Delete data
     */
    public function delete(int $id): bool
    {
        return $this->query()->where('id', $id)->delete() > 0;
    }

    /**
     * Find by specific column
     */
    public function findBy(string $column, mixed $value): ?Model
    {
        return $this->query()->where($column, $value)->first();
    }

    /**
     * Find all by specific column
     */
    public function findAllBy(string $column, mixed $value): Collection
    {
        return $this->query()->where($column, $value)->get();
    }

    /**
     * Get model instance
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Get dengan relasi
     */
    public function with(array $relations): Builder
    {
        return $this->query()->with($relations);
    }
}
