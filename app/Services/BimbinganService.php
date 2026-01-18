<?php

namespace App\Services;

use App\Repositories\BimbinganRepository;
use App\Enums\EkaptaContext;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Service untuk Bimbingan (TA/KP)
 */
class BimbinganService extends BaseContextService
{
    protected BimbinganRepository $repository;

    public function __construct(BimbinganRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * Get repository instance
     */
    public function getRepository(): BimbinganRepository
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
     * Get bimbingan mahasiswa
     */
    public function getBimbinganMahasiswa(int $mahasiswaId)
    {
        return $this->repository->getByMahasiswa($mahasiswaId);
    }

    /**
     * Get bimbingan untuk dosen
     */
    public function getBimbinganDosen(int $dosenId)
    {
        return $this->repository->getByDosen($dosenId);
    }

    /**
     * Get bimbingan pending review untuk dosen
     */
    public function getPendingReviewDosen(int $dosenId)
    {
        return $this->repository->getPendingReviewByDosen($dosenId);
    }

    /**
     * Get progress bimbingan mahasiswa
     */
    public function getProgress(int $mahasiswaId): array
    {
        return $this->repository->getProgressByMahasiswa($mahasiswaId);
    }

    /**
     * Create bimbingan baru
     */
    public function create(array $data, ?UploadedFile $lampiran = null)
    {
        return DB::transaction(function () use ($data, $lampiran) {
            // Handle file upload
            if ($lampiran) {
                $folder = $this->isKP() ? 'bimbingan_kp' : 'bimbingan_ta';
                $data['lampiran'] = $lampiran->store($folder, 'public');
            }

            $data['status'] = 'review';
            $data['tanggal_bimbingan'] = $data['tanggal_bimbingan'] ?? now();

            $bimbingan = $this->repository->create($data);

            // Attach dosen pembimbing
            if (isset($data['dosen_ids'])) {
                $bimbingan->dosens()->attach($data['dosen_ids']);
            }

            return $bimbingan;
        });
    }

    /**
     * Update bimbingan
     */
    public function update(int $id, array $data, ?UploadedFile $lampiran = null)
    {
        return DB::transaction(function () use ($id, $data, $lampiran) {
            $bimbingan = $this->repository->findOrFail($id);

            // Handle file upload
            if ($lampiran) {
                if ($bimbingan->lampiran) {
                    Storage::disk('public')->delete($bimbingan->lampiran);
                }

                $folder = $this->isKP() ? 'bimbingan_kp' : 'bimbingan_ta';
                $data['lampiran'] = $lampiran->store($folder, 'public');
            }

            $this->repository->update($id, $data);

            return $this->repository->find($id);
        });
    }

    /**
     * ACC bimbingan (oleh Dosen)
     */
    public function accBimbingan(int $id)
    {
        return DB::transaction(function () use ($id) {
            $this->repository->update($id, [
                'status' => 'diterima',
                'tanggal_acc' => now(),
            ]);

            return $this->repository->find($id);
        });
    }

    /**
     * Revisi bimbingan
     */
    public function revisiBimbingan(int $id, string $catatan)
    {
        return DB::transaction(function () use ($id, $catatan) {
            $bimbingan = $this->repository->findOrFail($id);

            $this->repository->update($id, ['status' => 'revisi']);

            $bimbingan->revisis()->create([
                'catatan' => $catatan,
            ]);

            return $this->repository->find($id);
        });
    }

    /**
     * Cancel ACC bimbingan
     */
    public function cancelAcc(int $id)
    {
        return DB::transaction(function () use ($id) {
            $this->repository->update($id, [
                'status' => 'review',
                'tanggal_acc' => null,
            ]);

            return $this->repository->find($id);
        });
    }

    /**
     * Delete bimbingan
     */
    public function delete(int $id): bool
    {
        $bimbingan = $this->repository->findOrFail($id);

        if ($bimbingan->lampiran) {
            Storage::disk('public')->delete($bimbingan->lampiran);
        }

        return $this->repository->delete($id);
    }

    /**
     * Get rekap bimbingan dosen
     */
    public function getRekapDosen(int $dosenId): array
    {
        return $this->repository->getRekapByDosen($dosenId);
    }

    /**
     * Store manual bimbingan (khusus KP - offline)
     */
    public function storeManual(array $data, ?UploadedFile $buktiOffline = null)
    {
        if (!$this->isKP()) {
            throw new \Exception('Manual bimbingan hanya tersedia untuk Kerja Praktik');
        }

        return DB::transaction(function () use ($data, $buktiOffline) {
            $data['tipe'] = 'offline';
            $data['status'] = 'review';
            $data['status_offline'] = 'pending';

            if ($buktiOffline) {
                $data['bukti_bimbingan_offline'] = $buktiOffline->store('bimbingan_kp/offline', 'public');
            }

            return $this->repository->create($data);
        });
    }
}
