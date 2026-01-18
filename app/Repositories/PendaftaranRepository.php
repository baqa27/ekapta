<?php

namespace App\Repositories;

use App\Models\TA\Pendaftaran as TAPendaftaran;
use App\Models\KP\Pendaftaran as KPPendaftaran;

/**
 * Repository untuk Pendaftaran (TA/KP)
 */
class PendaftaranRepository extends BaseContextRepository
{
    protected function getTAModel(): string
    {
        return TAPendaftaran::class;
    }

    protected function getKPModel(): string
    {
        return KPPendaftaran::class;
    }

    /**
     * Get pendaftaran by mahasiswa
     */
    public function getByMahasiswa(int $mahasiswaId)
    {
        return $this->query()
            ->where('mahasiswa_id', $mahasiswaId)
            ->with(['pengajuan', 'revisis'])
            ->first();
    }

    /**
     * Get pendaftaran yang perlu direview admin
     */
    public function getPendingReview()
    {
        return $this->query()
            ->where('status', 'review')
            ->with(['mahasiswa', 'pengajuan'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get semua pendaftaran dengan status
     */
    public function getWithStatus(?string $status = null)
    {
        $query = $this->query()->with(['mahasiswa', 'pengajuan']);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get pendaftaran yang sudah diterima
     */
    public function getApproved()
    {
        return $this->query()
            ->where('status', 'diterima')
            ->with(['mahasiswa', 'pengajuan'])
            ->orderBy('tanggal_acc', 'desc')
            ->get();
    }

    /**
     * Cek apakah mahasiswa punya pendaftaran aktif
     */
    public function hasActivePendaftaran(int $mahasiswaId): bool
    {
        return $this->query()
            ->where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['review', 'revisi', 'diterima'])
            ->exists();
    }
}
