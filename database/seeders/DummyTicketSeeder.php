<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Seeder;

class DummyTicketSeeder extends Seeder
{
    public function run(): void
    {
        $date = '20260709';

        $tickets = [
            [
                'ticket_number' => 'HM'.$date.'0001',
                'type' => 'Pengaduan',
                'category_id' => 1,
                'room_id' => 5,
                'is_anonymous' => false,
                'reporter_name' => 'Siti Rahmawati',
                'reporter_phone' => '081234567890',
                'title' => 'Dokter datang terlambat',
                'description' => 'Dokter spesialis dalam tidak datang sesuai jadwal praktek, pasien menunggu lebih dari 2 jam tanpa informasi.',
                'status' => 'NEW',
                'sla_id' => 2,
                'sla_breached' => false,
            ],
            [
                'ticket_number' => 'HM'.$date.'0002',
                'type' => 'Pengaduan',
                'category_id' => 4,
                'room_id' => 2,
                'is_anonymous' => true,
                'reporter_name' => null,
                'reporter_phone' => null,
                'title' => 'AC rusak di ruang tunggu IGD',
                'description' => 'Pendingin udara di ruang tunggu IGD tidak berfungsi sejak kemarin, ruangan terasa panas dan pengap. Pasien dan keluarga mengeluh.',
                'status' => 'TERVERIFIKASI',
                'sla_id' => 3,
                'sla_breached' => false,
            ],
            [
                'ticket_number' => 'HM'.$date.'0003',
                'type' => 'Survei',
                'category_id' => 7,
                'room_id' => 8,
                'is_anonymous' => false,
                'reporter_name' => 'Ahmad Fauzi',
                'reporter_phone' => '082345678901',
                'title' => 'Kepuasan pelayanan pendaftaran',
                'description' => 'Proses pendaftaran cepat dan petugas ramah. Sistem antrian digital sangat membantu mempercepat alur pendaftaran.',
                'status' => 'DONE',
                'sla_id' => 1,
                'sla_breached' => false,
                'rating' => 5,
                'review' => 'Pelayanan sangat memuaskan, semoga dipertahankan dan ditingkatkan.',
            ],
            [
                'ticket_number' => 'HM'.$date.'0004',
                'type' => 'Apresiasi',
                'category_id' => 2,
                'room_id' => 14,
                'is_anonymous' => false,
                'reporter_name' => 'Dewi Sartika',
                'reporter_phone' => '083456789012',
                'title' => 'Terima kasih perawat Ruang Melati',
                'description' => 'Perawat di Ruang Melati sangat perhatian dan sigap menangani pasien rawat inap. Pelayanan ramah dan profesional. Terima kasih banyak.',
                'status' => 'DONE',
                'sla_id' => 1,
                'sla_breached' => false,
            ],
            [
                'ticket_number' => 'HM'.$date.'0005',
                'type' => 'Informasi',
                'category_id' => 7,
                'room_id' => 1,
                'is_anonymous' => false,
                'reporter_name' => 'Bambang Subianto',
                'reporter_phone' => '084567890123',
                'title' => 'Jam besuk pasien IGD',
                'description' => 'Mohon informasi jam besuk untuk pasien yang sedang diobservasi di IGD. Apakah ada batasan jumlah pengunjung yang diizinkan?',
                'status' => 'NEW',
                'sla_id' => null,
                'sla_breached' => false,
            ],
            [
                'ticket_number' => 'HM'.$date.'0006',
                'type' => 'Pengaduan',
                'category_id' => 5,
                'room_id' => 25,
                'is_anonymous' => false,
                'reporter_name' => 'Rina Marlina',
                'reporter_phone' => '085678901234',
                'title' => 'Kebersihan toilet apotek kurang',
                'description' => 'Toilet pasien di dekat apotek rawat jalan kurang terawat, bau kurang sedap, dan lantai licin. Mohon segera dibersihkan.',
                'status' => 'IN_PROGRESS',
                'sla_id' => 2,
                'sla_breached' => false,
            ],
            [
                'ticket_number' => 'HM'.$date.'0007',
                'type' => 'Pengaduan',
                'category_id' => 6,
                'room_id' => 36,
                'is_anonymous' => true,
                'reporter_name' => null,
                'reporter_phone' => null,
                'title' => 'Motor hilang di area parkir',
                'description' => 'Sepeda motor milik pengunjung hilang saat diparkir di area parkir RS. Keamanan parkir perlu ditingkatkan dan ditambah CCTV.',
                'status' => 'NEW',
                'sla_id' => 4,
                'sla_breached' => true,
            ],
            [
                'ticket_number' => 'HM'.$date.'0008',
                'type' => 'Apresiasi',
                'category_id' => 2,
                'room_id' => 30,
                'is_anonymous' => false,
                'reporter_name' => 'Hendra Gunawan',
                'reporter_phone' => '086789012345',
                'title' => 'Perawat ICU sangat profesional',
                'description' => 'Keluarga kami sangat berterima kasih kepada tim perawat ICU yang sigap, telaten, dan profesional merawat ayah saya selama dirawat.',
                'status' => 'TERVERIFIKASI',
                'sla_id' => 1,
                'sla_breached' => false,
            ],
            [
                'ticket_number' => 'HM'.$date.'0009',
                'type' => 'Pengaduan',
                'category_id' => 3,
                'room_id' => 9,
                'is_anonymous' => false,
                'reporter_name' => 'Maria Ulfah',
                'reporter_phone' => '087890123456',
                'title' => 'Lama proses administrasi rawat inap',
                'description' => 'Proses administrasi masuk rawat inap memakan waktu lebih dari 1 jam karena sistem aplikasi lemot dan antrian panjang di loket.',
                'status' => 'IN_PROGRESS',
                'sla_id' => 2,
                'sla_breached' => false,
            ],
            [
                'ticket_number' => 'HM'.$date.'0010',
                'type' => 'Survei',
                'category_id' => 7,
                'room_id' => 1,
                'is_anonymous' => false,
                'reporter_name' => 'Agus Wijaya',
                'reporter_phone' => '088901234567',
                'title' => 'Tanggapan pelayanan RS secara umum',
                'description' => 'Pelayanan RS sudah baik, ramah, dan cepat. Hanya perlu penambahan kursi tunggu di beberapa poli karena sering kehabisan tempat duduk.',
                'status' => 'DONE',
                'sla_id' => 1,
                'sla_breached' => false,
                'rating' => 4,
                'review' => 'Sudah bagus, tingkatkan terus kualitas pelayanannya.',
            ],
        ];

        foreach ($tickets as $data) {
            Ticket::create($data);
        }

        $this->command->info('10 dummy tickets berhasil dibuat.');
    }
}
