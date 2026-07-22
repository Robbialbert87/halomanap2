<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TestingWorkflowSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('=== TESTING WORKFLOW SEEDER ===');

        $roleAdmin = Role::firstOrCreate(['name' => 'Admin Pengaduan',  'guard_name' => 'web'], ['kode' => 'ADMIN_PENGADUAN', 'status' => 'active']);
        $rolePegawai = Role::firstOrCreate(['name' => 'Pegawai',          'guard_name' => 'web'], ['kode' => 'PEGAWAI',         'status' => 'active']);
        $roleDirektur = Role::firstOrCreate(['name' => 'Direktur',         'guard_name' => 'web'], ['kode' => 'DIREKTUR',        'status' => 'active']);
        $this->command->info('✓ Roles siap.');

        $jabKepala = Jabatan::firstOrCreate(
            ['nama' => 'Kepala Instalasi'],
            [
                'kode' => 'JAB_KA_INSTALASI',
                'kategori_jabatan' => 'Kepala Unit',
                'status' => 'active',
            ]
        );

        $jabKasi = Jabatan::firstOrCreate(
            ['nama' => 'Kepala Seksi Penunjang Medik'],
            [
                'kode' => 'JAB_KASI_PENUNJANG',
                'kategori_jabatan' => 'Kasi',
                'status' => 'active',
            ]
        );

        $jabDirektur = Jabatan::firstOrCreate(
            ['nama' => 'Direktur'],
            [
                'kode' => 'JAB_DIREKTUR',
                'kategori_jabatan' => 'Direktur',
                'status' => 'active',
            ]
        );

        $this->command->info("✓ Jabatan: [{$jabKepala->nama}] → [{$jabKasi->nama}] → [{$jabDirektur->nama}]");

        $unit = Unit::where('kode', 'UN_RADIOLOGI')->first();
        if (! $unit) {
            $unit = Unit::create([
                'kode' => 'UN_RADIOLOGI',
                'nama' => 'Instalasi Radiologi',
                'jenis' => 'Instalasi',
                'status' => 'active',
            ]);
        }
        $this->command->info("✓ Unit terpilih: {$unit->nama} (ID: {$unit->id})");

        $users = [
            [
                'nip' => '100000000000000001',
                'nama' => 'Admin Pengaduan',
                'phone_number' => '081111111111',
                'unit_id' => null,
                'jabatan_id' => null,
                'status' => 'active',
                'role' => $roleAdmin->name,
                'info' => 'NIP: 100000000000000001 | Password: password123',
            ],
            [
                'nip' => '100000000000000002',
                'nama' => 'Hendra Kusuma',
                'phone_number' => '082222222222',
                'unit_id' => $unit->id,
                'jabatan_id' => $jabKepala->id,
                'status' => 'active',
                'role' => $rolePegawai->name,
                'info' => 'NIP: 100000000000000002 | Password: password123 | Role: Kepala Instalasi',
            ],
            [
                'nip' => '100000000000000003',
                'nama' => 'dr. Siti Rahayu',
                'phone_number' => '083333333333',
                'unit_id' => $unit->id,
                'jabatan_id' => $jabKasi->id,
                'status' => 'active',
                'role' => $rolePegawai->name,
                'info' => 'NIP: 100000000000000003 | Password: password123 | Role: Kasi Penunjang Medik',
            ],
            [
                'nip' => '100000000000000004',
                'nama' => 'dr. Ahmad Fauzi',
                'phone_number' => '084444444444',
                'unit_id' => $unit->id,
                'jabatan_id' => $jabDirektur->id,
                'status' => 'active',
                'role' => $roleDirektur->name,
                'info' => 'NIP: 100000000000000004 | Password: password123 | Role: Direktur',
            ],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['nip' => $data['nip']],
                [
                    'nama' => $data['nama'],
                    'phone_number' => $data['phone_number'],
                    'password' => Hash::make('password123'),
                    'unit_id' => $data['unit_id'],
                    'jabatan_id' => $data['jabatan_id'],
                    'status' => $data['status'],
                ]
            );
            $user->syncRoles([$data['role']]);
            $this->command->info("  ✓ User: {$data['info']}");
        }

        $this->command->newLine();
        $this->command->line('========================================');
        $this->command->info('SEEDING SELESAI! Data siap untuk testing.');
        $this->command->line('========================================');
        $this->command->newLine();
        $this->command->line('AKUN TESTING:');
        $this->command->table(
            ['Peran', 'NIP (Login)', 'Password', 'Unit'],
            [
                ['Admin Pengaduan',      '100000000000000001', 'password123', '-'],
                ['Kepala Instalasi',     '100000000000000002', 'password123', $unit->nama],
                ['Kasi Penunjang Medik', '100000000000000003', 'password123', $unit->nama],
                ['Direktur',             '100000000000000004', 'password123', $unit->nama],
            ]
        );
        $this->command->newLine();
        $this->command->line('ALUR TESTING:');
        $this->command->line('1. Login sebagai Admin → Buat Pengaduan (atau gunakan form publik)');
        $this->command->line('2. Login sebagai Admin → Buka tiket → Klik Disposisi → Pilih unit');
        $this->command->line('3. Login sebagai Kepala Instalasi → Pilih: Tangani Sendiri atau Eskalasi');
        $this->command->line('4. Login sebagai Kasi → Lanjutkan (jika dieskalasi)');
        $this->command->line('5. Login sebagai Direktur → Buka /admin/monitoring untuk monitor');
        $this->command->newLine();
    }
}
