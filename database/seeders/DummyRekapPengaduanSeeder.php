<?php

use App\Models\RekapPengaduan;

$data = [
    ['tanggal'=>'2026-08-01','nama'=>'Andi Saputra','nomor_pelapor'=>'081234567890','via_pengaduan'=>'WhatsApp','kategori'=>'Tenaga Kesehatan','keluhan'=>'Dokter jadwal praktek tidak sesuai dengan jadwal yang tertera','tindak_lanjut'=>'Dikoordinasikan dengan bagian jadwal','status'=>'Selesai','tanggal_selesai'=>'2026-08-03'],
    ['tanggal'=>'2026-08-03','nama'=>'Siti Rahma','nomor_pelapor'=>'085678901234','via_pengaduan'=>'Telepon','kategori'=>'Sarana & Prasarana','keluhan'=>'AC ruang tunggu tidak berfungsi','tindak_lanjut'=>'Diperbaiki oleh teknisi','status'=>'Selesai','tanggal_selesai'=>'2026-08-04'],
    ['tanggal'=>'2026-08-05','nama'=>null,'nomor_pelapor'=>null,'via_pengaduan'=>'Walk-in','kategori'=>'Ketersediaan Obat','keluhan'=>'Obat paracetamol kosong di apotek','tindak_lanjut'=>'Pengadaan obat mendesak','status'=>'Diproses','tanggal_selesai'=>null],
    ['tanggal'=>'2026-08-07','nama'=>'Budi Santoso','nomor_pelapor'=>'087890123456','via_pengaduan'=>'Email','kategori'=>'Keluhan Lainnya','keluhan'=>'Asuransi BPJS tidak bisa digunakan untuk rawat inap','tindak_lanjut'=>'Verifikasi dengan pihak asuransi','status'=>'Selesai','tanggal_selesai'=>'2026-08-10'],
    ['tanggal'=>'2026-08-10','nama'=>'Rina Wati','nomor_pelapor'=>'081234567891','via_pengaduan'=>'WhatsApp','kategori'=>'Tenaga Kesehatan','keluhan'=>'Perawat kurang ramah dalam melayani pasien','tindak_lanjut'=>'Teguran dan pelatihan pelayanan','status'=>'Selesai','tanggal_selesai'=>'2026-08-12'],
    ['tanggal'=>'2026-08-12','nama'=>null,'nomor_pelapor'=>'085678901235','via_pengaduan'=>'SMS','kategori'=>'Sarana & Prasarana','keluhan'=>'Lift gedung utama sering macet','tindak_lanjut'=>'Maintenance rutin lift','status'=>'Diproses','tanggal_selesai'=>null],
    ['tanggal'=>'2026-08-14','nama'=>'Dewi Lestari','nomor_pelapor'=>'087890123457','via_pengaduan'=>'Website','kategori'=>'Ketersediaan Obat','keluhan'=>'Obat antibiotik stok menipis','tindak_lanjut'=>'Pesan ke supplier','status'=>'Selesai','tanggal_selesai'=>'2026-08-16'],
    ['tanggal'=>'2026-08-17','nama'=>'Ahmad Fauzi','nomor_pelapor'=>'081234567892','via_pengaduan'=>'Walk-in','kategori'=>'Keluhan Lainnya','keluhan'=>'Jadwal operasi yang sudah ditentukan berubah tanpa pemberitahuan','tindak_lanjut'=>'Eskalasi ke manajemen','status'=>'Baru','tanggal_selesai'=>null],
    ['tanggal'=>'2026-08-19','nama'=>'Maya Putri','nomor_pelapor'=>null,'via_pengaduan'=>'Social Media','kategori'=>'Tenaga Kesehatan','keluhan'=>'Dokter spesialis tidak hadir di jadwal praktek','tindak_lanjut'=>null,'status'=>'Baru','tanggal_selesai'=>null],
    ['tanggal'=>'2026-08-20','nama'=>'Hendra Kurniawan','nomor_pelapor'=>'085678901236','via_pengaduan'=>'Telepon','kategori'=>'Sarana & Prasarana','keluhan'=>'Parkir kendaraan penuh dan tidak tertata rapi','tindak_lanjut'=>'Penataan ulang area parkir','status'=>'Diproses','tanggal_selesai'=>null],
];

foreach ($data as $d) {
    $d['user_id'] = 1;
    RekapPengaduan::create($d);
}

echo 'Berhasil insert ' . count($data) . ' data dummy untuk Agustus 2026.';
