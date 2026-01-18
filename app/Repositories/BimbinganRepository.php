<?php

namespace App\Repositories;

use App\Models\TA\Bimbingan as TABimbingan;
use App\Models\KP\Bimbingan as KPBimbingan;
use Illuminate\Support\Collection;

/**
 * Repository untuk Bimbingan (TA/KP)
 */
class BimbinganRepository extends BaseContextRepository
{
    protected function getTAModel(): string
    {
        return TABimbingan::class;
    }

    protected function getKPModel(): string
    {
        return KPBimbingan::class;
    }

    /**
     * Get bimbingan by mahasiswa
     */
    public function getByMahasiswa(int $mahasiswaId): Collection
    {
        return $this->query()
            ->where('mahasiswa_id', $mahasiswaId)
            ->with(['bagian', 'revisis', 'dosens'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get bimbingan by dosen
     */
    public function getByDosen(int $dosenId): Collection
    {
        return $this->query()
            ->whereHas('dosens', function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            })
            ->with(['mahasiswa', 'bagian', 'revisis'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get bimbingan yang perlu direview dosen
     */
    public function getPendingReviewByDosen(int $dosenId): Collection
    {
        return $this->query()
            ->whereHas('dosens', function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            })
            ->where('status', 'review')
            ->with(['mahasiswa', 'bagian'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get progress bimbingan mahasiswa
     */
    public function getProgressByMahasiswa(int $mahasiswaId): array
    {
        $total = $this->query()
            ->where('mahasiswa_id', $mahasiswaId)
            ->count();

        $approved = $this->query()
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('status', 'diterima')
            ->count();

        return [
            'total' => $total,
            'approved' => $approved,
            'pending' => $total - $approved,
            'percentage' => $total > 0 ? round(($approved / $total) * 100, 2) : 0,
        ];
    }

    /**
     * Get bimbingan by bagian
     */
    public function getByBagian(int $mahasiswaId, int $bagianId)
    {
        return $this->query()
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('bagian_id', $bagianId)
            ->with(['revisis', 'dosens'])
            ->first();
    }

    /**
     * Get rekap bimbingan per dosen
     */
    public function getRekapByDosen(int $dosenId): array
    {
        $query = $this->query()
            ->whereHas('dosens', function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            });

        return [
            'total' => $query->count(),
            'pending' => (clone $query)->where('status', 'review')->count(),
            'approved' => (clone $query)->where('status', 'diterima')->count(),
            'revision' => (clone $query)->where('status', 'revisi')->count(),
        ];
    }
}
