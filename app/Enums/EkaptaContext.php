<?php

namespace App\Enums;

/**
 * Context untuk E-KAPTA
 * Menentukan apakah user sedang dalam context Tugas Akhir atau Kerja Praktik
 */
enum EkaptaContext: string
{
    case TUGAS_AKHIR = 'ta';
    case KERJA_PRAKTIK = 'kp';

    /**
     * Get display name untuk context
     */
    public function label(): string
    {
        return match($this) {
            self::TUGAS_AKHIR => 'Tugas Akhir',
            self::KERJA_PRAKTIK => 'Kerja Praktik',
        };
    }

    /**
     * Get short label
     */
    public function shortLabel(): string
    {
        return match($this) {
            self::TUGAS_AKHIR => 'TA',
            self::KERJA_PRAKTIK => 'KP',
        };
    }

    /**
     * Get route prefix
     */
    public function routePrefix(): string
    {
        return $this->value;
    }

    /**
     * Get view folder
     */
    public function viewFolder(): string
    {
        return $this->value;
    }

    /**
     * Get table suffix (kosong untuk TA, _kp untuk KP)
     */
    public function tableSuffix(): string
    {
        return match($this) {
            self::TUGAS_AKHIR => '',
            self::KERJA_PRAKTIK => '_kp',
        };
    }

    /**
     * Cek apakah role tertentu tersedia di context ini
     */
    public function isRoleAvailable(string $role): bool
    {
        // Role Himpunan hanya tersedia di KP
        if ($role === 'himpunan') {
            return $this === self::KERJA_PRAKTIK;
        }

        // Role lainnya tersedia di semua context
        return true;
    }

    /**
     * Get default context
     */
    public static function default(): self
    {
        return self::TUGAS_AKHIR;
    }

    /**
     * Create from string value
     */
    public static function fromString(?string $value): self
    {
        return match(strtolower($value ?? '')) {
            'kp', 'kerja_praktik' => self::KERJA_PRAKTIK,
            default => self::TUGAS_AKHIR,
        };
    }
}
