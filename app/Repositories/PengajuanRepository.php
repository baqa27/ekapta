<?php

namespace App\Repositories;

use App\Models\TA\Pengajuan as TAPengajuan;
use App\Models\KP\Pengajuan as KPPengajuan;

/**
 * Repository untuk Pengajuan (TA/KP)
 */
class PengajuanRepository extends BaseContextRepository
{
    protected function getTAModel(): string
    {
        return TAPengajuan::class;
    }

    protected function getKPModel(): string
    {
        return KPPengajuan::class;
    }

    /**
     * Get pengajuan by mahasiswa
     */
    public function getByMahasiswa(int $mahasiswaId)
    {
        return $this->query()
            ->where('mahasiswa_id', $mahasiswaId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get pengajuan by prodi dengan status
     */
    public function getByProdiWithStatus(int $prodiId, ?string $status = null)
    {
        $query = $this->query()
            ->where('prodi_id', $prodiId)
            ->with(['mahasiswa', 'revisis']);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get pengajuan yang perlu direview
     */
    public function getPendingReview(int $prodiId)
    {
        return $this->query()
            ->where('prodi_id', $prodiId)
            ->where('status', 'review')
            ->with(['mahasiswa'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get latest pengajuan mahasiswa
     */
    public function getLatestByMahasiswa(int $mahasiswaId)
    {
        return $this->query()
            ->where('mahasiswa_id', $mahasiswaId)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Cek apakah mahasiswa punya pengajuan aktif
     */
    public function hasActivePengajuan(int $mahasiswaId): bool
    {
        return $this->query()
            ->where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['review', 'revisi'])
            ->exists();
    }

    /**
     * Cek apakah mahasiswa punya pengajuan yang diterima
     */
    public function hasApprovedPengajuan(int $mahasiswaId): bool
    {
        return $this->query()
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('status', 'diterima')
            ->exists();
    }
}
