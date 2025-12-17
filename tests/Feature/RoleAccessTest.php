<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\Prodi;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Himpunan;
use Illuminate\Support\Facades\Auth;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    /**
     * Test Admin Access
     *
     * @return void
     */
    public function test_admin_pages_access()
    {
        $admin = Admin::where('kode', 'admin')->first();
        if (!$admin) { $this->markTestSkipped('Admin user not found'); }

        $response = $this->actingAs($admin, 'admin')->get(route('dashboard.admin'));
        $response->assertStatus(200);

        $response = $this->actingAs($admin, 'admin')->get(route('pengajuan.admin'));
        $response->assertStatus(200);

        $response = $this->actingAs($admin, 'admin')->get(route('pendaftaran.admin'));
        $response->assertStatus(200);

        $response = $this->actingAs($admin, 'admin')->get(route('dosens'));
        $response->assertStatus(200);
    }

    /**
     * Test Prodi Access
     *
     * @return void
     */
    public function test_prodi_pages_access()
    {
        $prodi = Prodi::where('kode', 'TI')->first();
        if (!$prodi) { $this->markTestSkipped('Prodi user not found'); }

        $response = $this->actingAs($prodi, 'prodi')->get(route('dashboard.prodi'));
        $response->assertStatus(200);

        $response = $this->actingAs($prodi, 'prodi')->get(route('pengajuan.prodi'));
        $response->assertStatus(200);

        $response = $this->actingAs($prodi, 'prodi')->get(route('bimbingan.prodi'));
        $response->assertStatus(200);

        $response = $this->actingAs($prodi, 'prodi')->get(route('seminar.prodi'));
        $response->assertStatus(200);
    }

    /**
     * Test Dosen Access
     *
     * @return void
     */
    public function test_dosen_pages_access()
    {
        $dosen = Dosen::where('nidn', '0601018501')->first();
        if (!$dosen) { $this->markTestSkipped('Dosen user not found'); }

        $response = $this->actingAs($dosen, 'dosen')->get(route('dashboard.dosen'));
        $response->assertStatus(200);

        $response = $this->actingAs($dosen, 'dosen')->get(route('bimbingan.dosen'));
        $response->assertStatus(200);

        $response = $this->actingAs($dosen, 'dosen')->get(route('seminar.dosen'));
        $response->assertStatus(200);
    }

    /**
     * Test Mahasiswa Access
     *
     * @return void
     */
    public function test_mahasiswa_pages_access()
    {
        // Use 'Siap Seminar' mahasiswa
        $mahasiswa = Mahasiswa::where('nim', '20210001')->first();
        if (!$mahasiswa) { $this->markTestSkipped('Mahasiswa user not found'); }

        $response = $this->actingAs($mahasiswa, 'mahasiswa')->get(route('dashboard.mahasiswa'));
        $response->assertStatus(200);

        $response = $this->actingAs($mahasiswa, 'mahasiswa')->get(route('pengajuan.mahasiswa'));
        $response->assertStatus(200);

        $response = $this->actingAs($mahasiswa, 'mahasiswa')->get(route('pendaftaran.mahasiswa'));
        $response->assertStatus(200);

        $response = $this->actingAs($mahasiswa, 'mahasiswa')->get(route('bimbingan.mahasiswa'));
        $response->assertStatus(200);
    }

    /**
     * Test Himpunan Access
     *
     * @return void
     */
    public function test_himpunan_pages_access()
    {
        $himpunan = Himpunan::where('username', 'himatif')->first();
        if (!$himpunan) { $this->markTestSkipped('Himpunan user not found'); }

        $response = $this->actingAs($himpunan, 'himpunan')->get(route('dashboard.himpunan'));
        $response->assertStatus(200);

        $response = $this->actingAs($himpunan, 'himpunan')->get(route('seminar.himpunan'));
        $response->assertStatus(200);
    }
}
