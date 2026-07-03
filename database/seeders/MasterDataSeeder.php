<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // 1. SLA (Service Level Agreement)
        // ============================================================
        DB::table('slas')->insert([
            ['name' => 'Rendah',  'response_time_hours' => 48, 'resolution_time_hours' => 120, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sedang',  'response_time_hours' => 24, 'resolution_time_hours' => 72,  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tinggi',  'response_time_hours' => 6,  'resolution_time_hours' => 24,  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kritis',  'response_time_hours' => 2,  'resolution_time_hours' => 8,   'created_at' => now(), 'updated_at' => now()],
        ]);

        // ============================================================
        // 2. KATEGORI PENGADUAN
        // ============================================================
        DB::table('report_categories')->insert([
            ['name' => 'Pelayanan Dokter',          'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pelayanan Perawat',         'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pelayanan Administrasi',    'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fasilitas',                 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kebersihan',                'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Keamanan',                  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Informasi & Komunikasi',    'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lainnya',                   'created_at' => now(), 'updated_at' => now()],
        ]);

        // ============================================================
        // 3. UNIT (Instalasi / Departemen)
        // ============================================================
        $units = [
            ['id' => 1,  'kode' => 'UNIT_001', 'nama' => 'IGD (Instalasi Gawat Darurat)', 'jenis' => 'Instalasi'],
            ['id' => 2,  'kode' => 'UNIT_002', 'nama' => 'Rawat Jalan', 'jenis' => 'Pelayanan'],
            ['id' => 3,  'kode' => 'UNIT_003', 'nama' => 'Rawat Inap', 'jenis' => 'Pelayanan'],
            ['id' => 4,  'kode' => 'UNIT_004', 'nama' => 'Radiologi', 'jenis' => 'Penunjang'],
            ['id' => 5,  'kode' => 'UNIT_005', 'nama' => 'Laboratorium', 'jenis' => 'Penunjang'],
            ['id' => 6,  'kode' => 'UNIT_006', 'nama' => 'Farmasi', 'jenis' => 'Penunjang'],
            ['id' => 7,  'kode' => 'UNIT_007', 'nama' => 'Kasir', 'jenis' => 'Pelayanan'],
            ['id' => 8,  'kode' => 'UNIT_008', 'nama' => 'Rekam Medis', 'jenis' => 'Penunjang'],
            ['id' => 9,  'kode' => 'UNIT_009', 'nama' => 'Administrasi & Umum', 'jenis' => 'Bagian'],
            ['id' => 10, 'kode' => 'UNIT_010', 'nama' => 'Instalasi Gizi', 'jenis' => 'Instalasi'],
            ['id' => 11, 'kode' => 'UNIT_011', 'nama' => 'Bedah Sentral', 'jenis' => 'Instalasi'],
            ['id' => 12, 'kode' => 'UNIT_012', 'nama' => 'ICU / ICCU', 'jenis' => 'Instalasi'],
            ['id' => 13, 'kode' => 'UNIT_013', 'nama' => 'Lainnya', 'jenis' => 'Lainnya'],
        ];

        foreach ($units as $unit) {
            DB::table('units')->insert([
                'id'          => $unit['id'],
                'uuid'        => \Illuminate\Support\Str::uuid(),
                'kode'        => $unit['kode'],
                'nama'        => $unit['nama'],
                'jenis'       => $unit['jenis'],
                'is_public'   => true, // all these are public by default in seeder
                'status'      => 'active',
                'head_user_id'=> null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        // ============================================================
        // 4. RUANGAN (per Unit)
        // ============================================================
        $rooms = [
            // IGD (unit_id=1)
            ['unit_id' => 1,  'name' => 'Ruang Triage'],
            ['unit_id' => 1,  'name' => 'Ruang Resusitasi'],
            ['unit_id' => 1,  'name' => 'Ruang Observasi IGD'],

            // Rawat Jalan (unit_id=2)
            ['unit_id' => 2,  'name' => 'Poli Umum'],
            ['unit_id' => 2,  'name' => 'Poli Spesialis Dalam'],
            ['unit_id' => 2,  'name' => 'Poli Spesialis Anak'],
            ['unit_id' => 2,  'name' => 'Poli Spesialis Bedah'],
            ['unit_id' => 2,  'name' => 'Poli Spesialis Kandungan'],
            ['unit_id' => 2,  'name' => 'Poli Spesialis Mata'],
            ['unit_id' => 2,  'name' => 'Poli Gigi'],

            // Rawat Inap (unit_id=3)
            ['unit_id' => 3,  'name' => 'Ruang Melati 1 (VIP)'],
            ['unit_id' => 3,  'name' => 'Ruang Melati 2 (Kelas I)'],
            ['unit_id' => 3,  'name' => 'Ruang Anggrek (Kelas II)'],
            ['unit_id' => 3,  'name' => 'Ruang Mawar (Kelas III)'],
            ['unit_id' => 3,  'name' => 'Ruang Perinatal'],
            ['unit_id' => 3,  'name' => 'Ruang Kebidanan'],

            // Radiologi (unit_id=4)
            ['unit_id' => 4,  'name' => 'Ruang Rontgen'],
            ['unit_id' => 4,  'name' => 'Ruang USG'],
            ['unit_id' => 4,  'name' => 'Ruang CT Scan'],

            // Laboratorium (unit_id=5)
            ['unit_id' => 5,  'name' => 'Ruang Sampling'],
            ['unit_id' => 5,  'name' => 'Ruang Analisa'],

            // Farmasi (unit_id=6)
            ['unit_id' => 6,  'name' => 'Apotek Rawat Jalan'],
            ['unit_id' => 6,  'name' => 'Apotek Rawat Inap'],
            ['unit_id' => 6,  'name' => 'Apotek IGD'],

            // Kasir (unit_id=7)
            ['unit_id' => 7,  'name' => 'Loket Kasir Rawat Jalan'],
            ['unit_id' => 7,  'name' => 'Loket Kasir Rawat Inap'],

            // Rekam Medis (unit_id=8)
            ['unit_id' => 8,  'name' => 'Loket Pendaftaran'],
            ['unit_id' => 8,  'name' => 'Ruang Arsip Rekam Medis'],

            // Administrasi (unit_id=9)
            ['unit_id' => 9,  'name' => 'Ruang Direktur'],
            ['unit_id' => 9,  'name' => 'Ruang Tata Usaha'],
            ['unit_id' => 9,  'name' => 'Ruang Kepegawaian'],

            // Gizi (unit_id=10)
            ['unit_id' => 10, 'name' => 'Dapur Gizi'],
            ['unit_id' => 10, 'name' => 'Ruang Konsultasi Gizi'],

            // Bedah Sentral (unit_id=11)
            ['unit_id' => 11, 'name' => 'Ruang Operasi 1'],
            ['unit_id' => 11, 'name' => 'Ruang Operasi 2'],
            ['unit_id' => 11, 'name' => 'Ruang Pemulihan (Recovery)'],

            // ICU (unit_id=12)
            ['unit_id' => 12, 'name' => 'Ruang ICU'],
            ['unit_id' => 12, 'name' => 'Ruang ICCU'],

            // Lainnya (unit_id=13)
            ['unit_id' => 13, 'name' => 'Area Parkir'],
            ['unit_id' => 13, 'name' => 'Lobby / Lobi Utama'],
            ['unit_id' => 13, 'name' => 'Koridor / Halaman RS'],
        ];

        foreach ($rooms as $room) {
            DB::table('rooms')->insert([
                'unit_id'      => $room['unit_id'],
                'name'         => $room['name'],
                'qr_code_path' => null,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}
