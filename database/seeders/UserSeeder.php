<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Jabatan;
use App\Models\Unit;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /** Password default semua akun non-superadmin. */
    private const DEFAULT_PASSWORD = 'password';

    public function run(): void
    {
        $rows = [];

        $rows[] = $this->upsertSuperAdmin();
        $rows = [...$rows, ...$this->upsertCoreUsers()];

        $this->command->info('Akun pengguna berhasil disiapkan (semua nomor WA: '.UserFactory::DEFAULT_WA_NUMBER.').');
        $this->command->newLine();
        $this->command->table(['Peran', 'NIP (Login)', 'Password', 'Unit'], $rows);
        $this->command->newLine();
    }

    /**
     * Super Admin — akses penuh, password = NIP.
     *
     * @return list<string>
     */
    private function upsertSuperAdmin(): array
    {
        $nip = '198706072020121003';

        $user = User::updateOrCreate(
            ['nip' => $nip],
            [
                'nama' => 'Super Admin',
                'email' => 'superadmin@halomanap.com',
                'phone_number' => UserFactory::DEFAULT_WA_NUMBER,
                'password' => Hash::make($nip),
                'status' => 'active',
            ]
        );

        $user->syncRoles(['Super Admin']);

        return ['Super Admin', $nip, $nip, '-'];
    }

    /**
     * User inti aplikasi. Semua akun memakai NIP sebagai login
     * (form login: nip + password) dan nomor WA 6282280514945 agar
     * notifikasi WhatsApp uji terpusat di satu nomor.
     *
     * @return list<list<string>>
     */
    private function upsertCoreUsers(): array
    {
        $unit = fn (string $kode) => Unit::where('kode', $kode)->value('id');
        $jabatan = fn (string $kode) => Jabatan::where('kode', $kode)->value('id');

        $users = [
            [
                'nip' => '100000000000000001',
                'nama' => 'Admin Pengaduan',
                'email' => 'admin@halomanap.com',
                'unit_id' => null,
                'jabatan_id' => null,
                'role' => 'Admin Pengaduan',
            ],
            [
                'nip' => '100000000000000002',
                'nama' => 'Hendra Kusuma',
                'email' => 'hendra.kusuma@halomanap.com',
                'unit_id' => $unit('UNIT_004'),
                'jabatan_id' => $jabatan('JAB_KEPALA_UNIT'),
                'role' => 'Pegawai',
            ],
            [
                'nip' => '100000000000000003',
                'nama' => 'dr. Siti Rahayu',
                'email' => 'siti.rahayu@halomanap.com',
                'unit_id' => $unit('UNIT_004'),
                'jabatan_id' => $jabatan('JAB_KASI_PENUNJANG'),
                'role' => 'Pegawai',
            ],
            [
                'nip' => '100000000000000004',
                'nama' => 'dr. Ahmad Fauzi',
                'email' => 'ahmad.fauzi@halomanap.com',
                'unit_id' => $unit('UNIT_009'),
                'jabatan_id' => $jabatan('JAB_DIREKTUR'),
                'role' => 'Direktur',
            ],
            [
                'nip' => '100000000000000005',
                'nama' => 'Budi Santoso',
                'email' => 'budi.santoso@halomanap.com',
                'unit_id' => $unit('UNIT_001'),
                'jabatan_id' => $jabatan('JAB_PETUGAS'),
                'role' => 'Kepala Ruangan',
            ],
            [
                'nip' => '100000000000000006',
                'nama' => 'Dewi Lestari',
                'email' => 'dewi.lestari@halomanap.com',
                'unit_id' => $unit('UNIT_001'),
                'jabatan_id' => $jabatan('JAB_PETUGAS'),
                'role' => 'Pegawai',
            ],
        ];

        $rows = [];

        foreach ($users as $data) {
            $role = $data['role'];
            unset($data['role']);

            $user = User::updateOrCreate(
                ['nip' => $data['nip']],
                [
                    ...$data,
                    'phone_number' => UserFactory::DEFAULT_WA_NUMBER,
                    'password' => Hash::make(self::DEFAULT_PASSWORD),
                    'status' => 'active',
                ]
            );
            $user->syncRoles([$role]);

            $rows[] = [
                $role,
                $data['nip'],
                self::DEFAULT_PASSWORD,
                Unit::find($data['unit_id'])?->nama ?? '-',
            ];
        }

        return $rows;
    }
}
