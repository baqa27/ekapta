<?php

namespace App\Repositories;

use App\Models\TA\Seminar as TASeminar;
use App\Models\KP\Seminar as KPSeminar;
use Illuminate\Support\Collection;

/**
 * Repository untuk Seminar (TA/KP)
 */
class SeminarRepository extends BaseContextRepository
{
    protected function getTAModel(): string
    {
        return TASeminar::class;
    }

    protected function getKPModel(): string
    {
        return KPSeminar::class;
    }

    /**
     * Get seminar by mahasiswa
     */
    public function getByMahasiswa(int $mahasiswaId)
    {
        return $this->query()
            ->where('mahasiswa_id', $mahasiswaId)
            ->with(['pengajuan', 'revisis'])
            ->first();
    }

    /**
     * Get seminar yang perlu direview
     */
    public function getPendingReview(): Collection
    {
        return $this->query()
            ->where('status', 'review')
            ->with(['mahasiswa', 'pengajuan'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get seminar yang sudah dijadwalkan
     */
    public function getScheduled(): Collection
    {
        return $this->query()
            ->whereNotNull('tanggal_seminar')
            ->where('status', 'diterima')
            ->with(['mahasiswa', 'pengajuan'])
            ->orderBy('tanggal_seminar', 'asc')
            ->get();
    }

    /**
     * Get seminar by tanggal
     */
    public function getByDate(string $date): Collection
    {
        return $this->query()
            ->whereDate('tanggal_seminar', $date)
            ->with(['mahasiswa', 'pengajuan'])
            ->orderBy('jam_seminar', 'asc')
            ->get();
    }

    /**
     * Get rekap seminar
     */
    public function getRekap(): array
    {
        return [
            'total' => $this->query()->count(),
            'pending' => $this->query()->where('status', 'review')->count(),
            'scheduled' => $this->query()->whereNotNull('tanggal_seminar')->where('status', 'diterima')->count(),
            'completed' => $this->query()->where('status', 'selesai')->count(),
        ];
    }
}
