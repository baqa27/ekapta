<?php

namespace App\Services;

use App\Repositories\PengajuanRepository;
use App\Enums\EkaptaContext;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Service untuk Pengajuan (TA/KP)
 *
 * Service ini menangani business logic pengajuan
 * tanpa mencampur logika TA dan KP dalam satu query
 */
class PengajuanService extends BaseContextService
{
    protected PengajuanRepository $repository;

    public function __construct(PengajuanRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * Get repository instance
     */
    public function getRepository(): PengajuanRepository
    {
        return $this->repository;
    }

    /**
     * Override setContext untuk sync dengan repository
     */
    public function setContext(EkaptaContext $context): self
    {
        parent::setContext($context);
        $this->repository->setContext($context);
        return $this;
    }

    /**
     * Get pengajuan mahasiswa
     */
    public function getPengajuanMahasiswa(int $mahasiswaId)
    {
        return $this->repository->getByMahasiswa($mahasiswaId);
    }

    /**
     * Get pengajuan untuk prodi
     */
    public function getPengajuanProdi(int $prodiId, ?string $status = null)
    {
        return $this->repository->getByProdiWithStatus($prodiId, $status);
    }

    /**
     * Get pengajuan pending review
     */
    public function getPendingReview(int $prodiId)
    {
        return $this->repository->getPendingReview($prodiId);
    }

    /**
     * Create pengajuan baru
     */
    public function create(array $data, ?UploadedFile $lampiran = null)
    {
        return DB::transaction(function () use ($data, $lampiran) {
            // Handle file upload
            if ($lampiran) {
                $folder = $this->isKP() ? 'pengajuan_kp' : 'pengajuan_ta';
                $data['lampiran'] = $lampiran->store($folder, 'public');
            }

            $data['status'] = 'review';

            return $this->repository->create($data);
        });
    }

    /**
     * Update pengajuan
     */
    public function update(int $id, array $data, ?UploadedFile $lampiran = null)
    {
        return DB::transaction(function () use ($id, $data, $lampiran) {
            $pengajuan = $this->repository->findOrFail($id);

            // Handle file upload
            if ($lampiran) {
                // Delete old file
                if ($pengajuan->lampiran) {
                    Storage::disk('public')->delete($pengajuan->lampiran);
                }

                $folder = $this->isKP() ? 'pengajuan_kp' : 'pengajuan_ta';
                $data['lampiran'] = $lampiran->store($folder, 'public');
            }

            $this->repository->update($id, $data);

            return $this->repository->find($id);
        });
    }

    /**
     * ACC pengajuan (oleh Prodi)
     */
    public function accPengajuan(int $id, ?int $dosenPembimbingId = null)
    {
        return DB::transaction(function () use ($id, $dosenPembimbingId) {
            $data = [
                'status' => 'diterima',
                'tanggal_acc' => now(),
            ];

            // Untuk KP, bisa set calon pembimbing
            if ($this->isKP() && $dosenPembimbingId) {
                $data['calon_pembimbing_id'] = $dosenPembimbingId;
            }

            $this->repository->update($id, $data);

            return $this->repository->find($id);
        });
    }

    /**
     * Revisi pengajuan
     */
    public function revisiPengajuan(int $id, string $catatan)
    {
        return DB::transaction(function () use ($id, $catatan) {
            $pengajuan = $this->repository->findOrFail($id);

            // Update status
            $this->repository->update($id, ['status' => 'revisi']);

            // Create revisi record
            $pengajuan->revisis()->create([
                'catatan' => $catatan,
            ]);

            return $this->repository->find($id);
        });
    }

    /**
     * Tolak pengajuan
     */
    public function tolakPengajuan(int $id, string $alasan)
    {
        return DB::transaction(function () use ($id, $alasan) {
            $pengajuan = $this->repository->findOrFail($id);

            $data = [
                'status' => 'ditolak',
            ];

            // Untuk KP, track jumlah penolakan
            if ($this->isKP()) {
                $data['jumlah_tolak'] = ($pengajuan->jumlah_tolak ?? 0) + 1;
            }

            $this->repository->update($id, $data);

            // Create revisi/alasan tolak
            $pengajuan->revisis()->create([
                'catatan' => $alasan,
                'is_tolak' => true,
            ]);

            return $this->repository->find($id);
        });
    }

    /**
     * Delete pengajuan
     */
    public function delete(int $id): bool
    {
        $pengajuan = $this->repository->findOrFail($id);

        // Delete file
        if ($pengajuan->lampiran) {
            Storage::disk('public')->delete($pengajuan->lampiran);
        }

        return $this->repository->delete($id);
    }

    /**
     * Cek apakah mahasiswa bisa mengajukan
     */
    public function canSubmit(int $mahasiswaId): array
    {
        $hasActive = $this->repository->hasActivePengajuan($mahasiswaId);
        $hasApproved = $this->repository->hasApprovedPengajuan($mahasiswaId);

        return [
            'can_submit' => !$hasActive && !$hasApproved,
            'has_active' => $hasActive,
            'has_approved' => $hasApproved,
            'message' => $this->getCanSubmitMessage($hasActive, $hasApproved),
        ];
    }

    /**
     * Get pesan untuk status submit
     */
    protected function getCanSubmitMessage(bool $hasActive, bool $hasApproved): string
    {
        $type = $this->isKP() ? 'Kerja Praktik' : 'Tugas Akhir';

        if ($hasApproved) {
            return "Anda sudah memiliki pengajuan {$type} yang disetujui.";
        }

        if ($hasActive) {
            return "Anda memiliki pengajuan {$type} yang masih dalam proses.";
        }

        return "Anda dapat mengajukan {$type} baru.";
    }
}
