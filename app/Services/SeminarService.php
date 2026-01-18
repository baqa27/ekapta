<?php

namespace App\Services;

use App\Repositories\SeminarRepository;
use App\Enums\EkaptaContext;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Service untuk Seminar (TA/KP)
 */
class SeminarService extends BaseContextService
{
    protected SeminarRepository $repository;

    public function __construct(SeminarRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
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
     * Get seminar mahasiswa
     */
    public function getSeminarMahasiswa(int $mahasiswaId)
    {
        return $this->repository->getByMahasiswa($mahasiswaId);
    }

    /**
     * Get seminar pending review
     */
    public function getPendingReview()
    {
        return $this->repository->getPendingReview();
    }

    /**
     * Get seminar yang sudah dijadwalkan
     */
    public function getScheduled()
    {
        return $this->repository->getScheduled();
    }

    /**
     * Create seminar baru
     */
    public function create(array $data, array $files = [])
    {
        return DB::transaction(function () use ($data, $files) {
            $folder = $this->isKP() ? 'seminar_kp' : 'seminar_ta';

            // Handle multiple file uploads
            foreach ($files as $key => $file) {
                if ($file instanceof UploadedFile) {
                    $data[$key] = $file->store($folder, 'public');
                }
            }

            $data['status'] = 'review';

            return $this->repository->create($data);
        });
    }

    /**
     * ACC seminar
     */
    public function accSeminar(int $id)
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
     * Set jadwal seminar
     */
    public function setJadwal(int $id, array $jadwalData)
    {
        return DB::transaction(function () use ($id, $jadwalData) {
            $this->repository->update($id, [
                'tanggal_seminar' => $jadwalData['tanggal'],
                'jam_seminar' => $jadwalData['jam'],
                'ruang_seminar' => $jadwalData['ruang'] ?? null,
            ]);

            return $this->repository->find($id);
        });
    }

    /**
     * Revisi seminar
     */
    public function revisiSeminar(int $id, string $catatan)
    {
        return DB::transaction(function () use ($id, $catatan) {
            $seminar = $this->repository->findOrFail($id);

            $this->repository->update($id, ['status' => 'revisi']);

            $seminar->revisis()->create([
                'catatan' => $catatan,
            ]);

            return $this->repository->find($id);
        });
    }

    /**
     * Get rekap seminar
     */
    public function getRekap(): array
    {
        return $this->repository->getRekap();
    }

    /**
     * Get seminar by tanggal
     */
    public function getByDate(string $date)
    {
        return $this->repository->getByDate($date);
    }

    /**
     * Selesaikan seminar
     */
    public function selesaikanSeminar(int $id, array $nilaiData = [])
    {
        return DB::transaction(function () use ($id, $nilaiData) {
            $data = [
                'status' => 'selesai',
                'tanggal_selesai' => now(),
            ];

            // Merge nilai data jika ada
            if (!empty($nilaiData)) {
                $data = array_merge($data, $nilaiData);
            }

            $this->repository->update($id, $data);

            return $this->repository->find($id);
        });
    }
}
