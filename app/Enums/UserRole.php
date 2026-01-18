<?php

namespace App\Enums;

/**
 * User Roles untuk E-KAPTA
 */
enum UserRole: string
{
    case ADMIN = 'admin';
    case PRODI = 'prodi';
    case DOSEN = 'dosen';
    case DEKAN = 'dekan';
    case FAKULTAS = 'fakultas';
    case ADMIN_FOTOCOPY = 'admin_fotocopy';
    case MAHASISWA = 'mahasiswa';
    case HIMPUNAN = 'himpunan'; // Khusus KP

    /**
     * Get display name
     */
    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Admin',
            self::PRODI => 'Prodi',
            self::DOSEN => 'Dosen',
            self::DEKAN => 'Dekan',
            self::FAKULTAS => 'Fakultas',
            self::ADMIN_FOTOCOPY => 'Admin Fotocopy',
            self::MAHASISWA => 'Mahasiswa',
            self::HIMPUNAN => 'Himpunan',
        };
    }

    /**
     * Get guard name untuk authentication
     */
    public function guard(): string
    {
        return $this->value;
    }

    /**
     * Cek apakah role tersedia di context tertentu
     */
    public function isAvailableIn(EkaptaContext $context): bool
    {
        // Himpunan hanya tersedia di KP
        if ($this === self::HIMPUNAN) {
            return $context === EkaptaContext::KERJA_PRAKTIK;
        }

        return true;
    }

    /**
     * Get dashboard route name
     */
    public function dashboardRoute(): string
    {
        return 'dashboard.' . $this->value;
    }

    /**
     * Get roles yang bisa akses TA
     */
    public static function taRoles(): array
    {
        return [
            self::ADMIN,
            self::PRODI,
            self::DOSEN,
            self::DEKAN,
            self::FAKULTAS,
            self::ADMIN_FOTOCOPY,
            self::MAHASISWA,
        ];
    }

    /**
     * Get roles yang bisa akses KP
     */
    public static function kpRoles(): array
    {
        return [
            self::ADMIN,
            self::PRODI,
            self::DOSEN,
            self::DEKAN,
            self::FAKULTAS,
            self::MAHASISWA,
            self::HIMPUNAN,
        ];
    }

    /**
     * Get all roles
     */
    public static function all(): array
    {
        return self::cases();
    }
}
