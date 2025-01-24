-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 24 Jan 2025 pada 14.04
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `umri_database`
--
CREATE DATABASE IF NOT EXISTS `umri_database` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `umri_database`;

-- --------------------------------------------------------

--
-- Struktur dari tabel `dosen`
--

CREATE TABLE `dosen` (
  `id_dosen` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `departemen` varchar(50) NOT NULL,
  `kontak` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dosen`
--

INSERT INTO `dosen` (`id_dosen`, `nama`, `nip`, `departemen`, `kontak`) VALUES
(1, 'Rahmad Firdaus, S.Kom.,M.Kom', '12345671', 'teknik informatika', '08956343728'),
(2, 'Winda Fahira, M.Pd', '12345672', 'teknik informatika', '089546872635'),
(3, 'Bayu Anugerah Putra, S.Kom.,M.Cs', '12345673', 'teknik informatika', '089563437243'),
(4, 'Januar AL Amien, S.Kom.,M.Kom', '12345731', 'teknik informatika', '089563437244'),
(5, 'Evan Fuad, S.Kom.,M.Eng.,CITA', '23232442', 'teknik informatika', '3123123131'),
(6, 'Dr. Baidarus, M.M., M.Ag', '12345734', 'teknik informatika', '089546872639'),
(7, 'Assoc. Prof. Harun Mukhtar, S.Kom., M.Kom', '12345722', 'teknik informatika', '0895634372333'),
(8, 'Fauzan Azim, S.Kom., M.Kom', '12345987', 'teknik informatika', '089563437289'),
(9, 'Ir. Nurul Huda, MH., M.I.Kom', '219872', 'teknik informatika', '0895634372832'),
(10, 'Atiqah, S.IP., M.Si', '6987654754', 'teknik informatika', '089546872630'),
(11, 'Dr. Tuti Andriani, S.Ag,.M.Pd', '876643453', 'teknik informatika', '089563437249'),
(12, 'Hasanatul Fu\'adah Amran, S.Pd., M.Pd', '076422345', 'teknik informatika', '089563437286'),
(13, 'Adrian Ali, MH', '345324343', 'teknikinformatika', '081223327667'),
(14, 'Edi Ismanto, S.T.,M.Kom', '56457699', 'teknikinformatika', '089745653245');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_kosong`
--

CREATE TABLE `jadwal_kosong` (
  `id_jadwal_kosong` int(11) NOT NULL,
  `id_ruangan` int(11) DEFAULT NULL,
  `waktu_kosong` datetime DEFAULT NULL,
  `durasi` int(11) DEFAULT NULL,
  `status_sementara` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL,
  `id_mata_kuliah` int(11) NOT NULL,
  `nomor_kelas` varchar(11) NOT NULL,
  `tipe_kelas` enum('Teori','Praktik') NOT NULL,
  `hari` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `jadwal_mulai` time(6) NOT NULL,
  `jadwal_selesai` time(6) NOT NULL,
  `id_ruangan` int(11) NOT NULL,
  `id_dosen` int(11) NOT NULL,
  `kapasitas_kelas` int(11) NOT NULL,
  `peserta` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `id_mata_kuliah`, `nomor_kelas`, `tipe_kelas`, `hari`, `jadwal_mulai`, `jadwal_selesai`, `id_ruangan`, `id_dosen`, `kapasitas_kelas`, `peserta`) VALUES
(15, 4, 'A10', 'Teori', 'Thursday', '15:00:00.000000', '15:07:00.000000', 1, 8, 40, 1),
(16, 3, 'A10', 'Praktik', 'Tuesday', '14:45:00.000000', '15:45:00.000000', 2, 8, 40, 1),
(17, 6, 'A8', 'Teori', 'Friday', '13:30:00.000000', '16:00:00.000000', 7, 1, 40, 0),
(18, 5, 'A8', 'Praktik', 'Wednesday', '14:25:00.000000', '15:15:00.000000', 9, 1, 40, 0),
(19, 1, 'A5', 'Teori', 'Tuesday', '13:00:00.000000', '14:40:00.000000', 3, 10, 40, 0),
(20, 10, 'A8', 'Teori', 'Tuesday', '15:31:00.000000', '18:01:00.000000', 10, 11, 40, 0),
(21, 8, 'A3', 'Teori', 'Wednesday', '10:52:00.000000', '12:32:00.000000', 8, 6, 40, 0),
(22, 9, 'A2', 'Teori', 'Thursday', '08:41:00.000000', '10:21:00.000000', 5, 7, 40, 0),
(23, 7, 'A10', 'Teori', 'Thursday', '13:00:00.000000', '15:30:00.000000', 11, 12, 40, 0),
(24, 2, 'A7', 'Teori', 'Friday', '16:01:00.000000', '17:41:00.000000', 7, 1, 40, 1),
(25, 6, 'A1', 'Teori', 'Monday', '07:00:00.000000', '09:30:00.000000', 5, 4, 40, 2),
(26, 5, 'A10', 'Praktik', 'Monday', '07:30:00.000000', '08:20:00.000000', 9, 4, 40, 0),
(27, 3, 'A2', 'Praktik', 'Monday', '07:30:00.000000', '08:20:00.000000', 2, 3, 40, 0),
(28, 3, 'A3', 'Praktik', 'Monday', '08:21:00.000000', '09:11:00.000000', 2, 3, 40, 0),
(29, 3, 'A4', 'Praktik', 'Monday', '09:12:00.000000', '10:02:00.000000', 2, 3, 40, 0),
(30, 6, 'A6', 'Teori', 'Monday', '09:31:00.000000', '12:01:00.000000', 5, 4, 40, 0),
(31, 8, 'A9', 'Teori', 'Tuesday', '07:00:00.000000', '08:40:00.000000', 10, 13, 40, 0),
(32, 5, 'A1', 'Praktik', 'Tuesday', '07:30:00.000000', '08:20:00.000000', 9, 4, 40, 2),
(33, 5, 'A2', 'Praktik', 'Tuesday', '08:21:00.000000', '09:10:00.000000', 9, 4, 40, 0),
(34, 8, 'A10', 'Teori', 'Tuesday', '08:41:00.000000', '10:21:00.000000', 10, 13, 40, 1),
(35, 5, 'A3', 'Praktik', 'Tuesday', '09:11:00.000000', '10:01:00.000000', 9, 4, 40, 0),
(36, 1, 'A2', 'Teori', 'Tuesday', '10:22:00.000000', '12:02:00.000000', 10, 10, 40, 0),
(37, 9, 'A7', 'Teori', 'Tuesday', '08:41:00.000000', '10:21:00.000000', 3, 14, 40, 1),
(38, 9, 'A8', 'Teori', 'Tuesday', '10:22:00.000000', '12:02:00.000000', 3, 14, 40, 0),
(39, 6, 'A3', 'Teori', 'Tuesday', '13:00:00.000000', '15:30:00.000000', 1, 4, 40, 0),
(40, 10, 'A10', 'Teori', 'Tuesday', '13:00:00.000000', '15:30:00.000000', 5, 11, 40, 0),
(41, 7, 'A8', 'Teori', 'Tuesday', '13:00:00.000000', '15:30:00.000000', 12, 3, 40, 0),
(42, 7, 'A4', 'Teori', 'Tuesday', '13:00:00.000000', '15:30:00.000000', 6, 5, 40, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `krs`
--

CREATE TABLE `krs` (
  `id_krs` int(11) NOT NULL,
  `id_mahasiswa` int(11) DEFAULT NULL,
  `id_kelas` int(11) DEFAULT NULL,
  `status_krs` enum('Belum_disetujui','disetujui') DEFAULT NULL,
  `waktu_ditambahkan` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `krs`
--

INSERT INTO `krs` (`id_krs`, `id_mahasiswa`, `id_kelas`, `status_krs`, `waktu_ditambahkan`) VALUES
(146, 2, 37, 'Belum_disetujui', '2025-01-14 07:59:43'),
(149, 2, 25, 'Belum_disetujui', '2025-01-14 08:01:10'),
(150, 2, 32, 'Belum_disetujui', '2025-01-14 08:01:10'),
(151, 1, 15, 'Belum_disetujui', '2025-01-14 09:48:18'),
(152, 1, 16, 'Belum_disetujui', '2025-01-14 09:48:18'),
(153, 1, 25, 'Belum_disetujui', '2025-01-14 09:48:23'),
(154, 1, 32, 'Belum_disetujui', '2025-01-14 09:48:23'),
(155, 1, 34, 'Belum_disetujui', '2025-01-14 09:48:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_pindah_ruangan`
--

CREATE TABLE `log_pindah_ruangan` (
  `id_log` int(11) NOT NULL,
  `id_kelas` int(11) DEFAULT NULL,
  `id_ruangan_awal` int(11) DEFAULT NULL,
  `id_ruangan_baru` int(11) DEFAULT NULL,
  `waktu_pindah` timestamp NOT NULL DEFAULT current_timestamp(),
  `hari_baru` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `jadwal_mulai_baru` time NOT NULL,
  `jadwal_selesai_baru` time NOT NULL,
  `alasan` text DEFAULT NULL,
  `status` enum('valid','expired') DEFAULT 'valid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log_pindah_ruangan`
--

INSERT INTO `log_pindah_ruangan` (`id_log`, `id_kelas`, `id_ruangan_awal`, `id_ruangan_baru`, `waktu_pindah`, `hari_baru`, `jadwal_mulai_baru`, `jadwal_selesai_baru`, `alasan`, `status`) VALUES
(7, 15, 1, 4, '2025-01-14 14:00:00', 'Tuesday', '15:00:00', '21:01:00', 'Pemindahan ruangan dan hari', 'expired');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id_mahasiswa` int(11) NOT NULL,
  `nama_mahasiswa` varchar(100) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `jurusan` varchar(50) NOT NULL,
  `semester` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `nama_mahasiswa`, `nim`, `jurusan`, `semester`) VALUES
(1, 'vijjay novaldi', '230401116', 'teknik informatika', 3),
(2, 'Andre Putra Melky.p', '230401124', 'teknik informatika', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa_kelas`
--

CREATE TABLE `mahasiswa_kelas` (
  `id_mahasiswa` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Trigger `mahasiswa_kelas`
--
DELIMITER $$
CREATE TRIGGER `update_peserta` AFTER INSERT ON `mahasiswa_kelas` FOR EACH ROW BEGIN
    UPDATE kelas
    SET peserta = (
        SELECT COUNT(*)
        FROM mahasiswa_kelas
        WHERE id_kelas = NEW.id_kelas
    )
    WHERE id_kelas = NEW.id_kelas;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_peserta_after_delete` AFTER DELETE ON `mahasiswa_kelas` FOR EACH ROW BEGIN
    UPDATE kelas
    SET peserta = (
        SELECT COUNT(*)
        FROM mahasiswa_kelas
        WHERE id_kelas = OLD.id_kelas
    )
    WHERE id_kelas = OLD.id_kelas;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mata_kuliah`
--

CREATE TABLE `mata_kuliah` (
  `id_mata_kuliah` int(11) NOT NULL,
  `nama_mata_kuliah` varchar(255) NOT NULL,
  `kode_mata_kuliah` varchar(50) NOT NULL,
  `sks` int(11) NOT NULL,
  `semester` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mata_kuliah`
--

INSERT INTO `mata_kuliah` (`id_mata_kuliah`, `nama_mata_kuliah`, `kode_mata_kuliah`, `sks`, `semester`) VALUES
(1, 'Pancasila', 'UMRIZ02', 2, '3'),
(2, 'Kewirausahaan', 'UMRIZ04', 2, '3'),
(3, 'Praktikum Pemrograman Berorientasi Objek', '0401302', 1, '3'),
(4, 'Pemrograman Berorientasi Objek', '0401303', 3, '3'),
(5, 'Praktikum Jaringan Komputer', '0401305', 3, '3'),
(6, 'Jaringan Komputer', '0401304', 3, '3'),
(7, 'Interaksi Manusia dan Komputer', '0401301', 3, '3'),
(8, 'Al Islam 3', 'UMRI303', 2, '3'),
(9, 'Struktur Data', '0401306', 2, '3'),
(10, 'Komunikasi Interpersonal', '0401307', 3, '3');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaturan_krs`
--

CREATE TABLE `pengaturan_krs` (
  `id` int(11) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaturan_krs`
--

INSERT INTO `pengaturan_krs` (`id`, `tanggal_mulai`, `tanggal_selesai`) VALUES
(1, '2025-01-01', '2025-01-31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `relasi_mata_kuliah`
--

CREATE TABLE `relasi_mata_kuliah` (
  `id_relasi` int(11) NOT NULL,
  `id_teori` int(11) NOT NULL,
  `id_praktik` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `relasi_mata_kuliah`
--

INSERT INTO `relasi_mata_kuliah` (`id_relasi`, `id_teori`, `id_praktik`) VALUES
(1, 6, 5),
(2, 4, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ruangan`
--

CREATE TABLE `ruangan` (
  `id_ruangan` int(11) NOT NULL,
  `nama_ruangan` varchar(100) DEFAULT NULL,
  `kapasitas` int(11) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ruangan`
--

INSERT INTO `ruangan` (`id_ruangan`, `nama_ruangan`, `kapasitas`, `lokasi`) VALUES
(1, 'GTC 02', 40, 'GTC'),
(2, 'RA 46 LAB KOMPUTER 2', 35, 'RA'),
(3, 'RA 43', 40, 'RA'),
(4, 'RA 40', 35, 'RA'),
(5, 'GTC 01', 40, 'GTC'),
(6, 'RA 55', 40, 'RA'),
(7, 'RA 48 Lab3', 40, 'RA'),
(8, 'GR 702', 40, 'GR'),
(9, 'RA 52 LKTIF', 40, 'RA'),
(10, 'GR 603', 40, 'GR'),
(11, 'GTC 19', 40, 'GTC'),
(12, 'GTC 28', 40, 'GTC'),
(13, 'RA 41', 40, 'RA'),
(14, 'RA 42', 40, 'RA'),
(15, 'RA 44', 40, 'RA'),
(16, 'RA 45', 40, 'RA');

-- --------------------------------------------------------

--
-- Struktur dari tabel `semester`
--

CREATE TABLE `semester` (
  `id` int(11) NOT NULL,
  `nama_semester` varchar(20) NOT NULL,
  `tahun_akademik` varchar(10) NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'nonaktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT NULL CHECK (`role` in ('Mahasiswa','Dosen')),
  `id_mahasiswa` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_dosen` int(11) DEFAULT NULL,
  `nomor_hp` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `email`, `role`, `id_mahasiswa`, `created_at`, `last_login`, `id_dosen`, `nomor_hp`) VALUES
(1, 'vijjay', '$2y$10$Xp.eDDWwx2gNqTvhNLOJeepq89RwzlMza4K1t6rQl3chhyAQ2Bh9S', '', 'mahasiswa', 1, '2024-11-02 10:18:45', '2025-01-13 15:56:08', NULL, '+62895602591914'),
(4, 'andre', '$2y$10$4fToldiEZEYfRHmTmt27YOh8wKJ6GcbJnrAX8vCkxBXrOUPhY.0Le', '', 'mahasiswa', 2, '2024-11-02 10:20:03', '2025-01-13 15:55:02', NULL, ''),
(5, 'dosen', '$2y$10$QMdmiDmHmctm9p0V6EfsZOd27Dzt.PnKIAdjXWbgirhdooBPnVxc2', '', 'dosen', NULL, '2024-12-28 14:43:01', '2024-12-28 14:52:43', 8, '');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id_dosen`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- Indeks untuk tabel `jadwal_kosong`
--
ALTER TABLE `jadwal_kosong`
  ADD PRIMARY KEY (`id_jadwal_kosong`),
  ADD KEY `id_ruangan` (`id_ruangan`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD KEY `id_mata_kuliah` (`id_mata_kuliah`),
  ADD KEY `id_ruangan` (`id_ruangan`),
  ADD KEY `id_dosen` (`id_dosen`);

--
-- Indeks untuk tabel `krs`
--
ALTER TABLE `krs`
  ADD PRIMARY KEY (`id_krs`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- Indeks untuk tabel `log_pindah_ruangan`
--
ALTER TABLE `log_pindah_ruangan`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_ruangan_awal` (`id_ruangan_awal`),
  ADD KEY `id_ruangan_baru` (`id_ruangan_baru`);

--
-- Indeks untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD UNIQUE KEY `nim` (`nim`);

--
-- Indeks untuk tabel `mahasiswa_kelas`
--
ALTER TABLE `mahasiswa_kelas`
  ADD PRIMARY KEY (`id_mahasiswa`,`id_kelas`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- Indeks untuk tabel `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD PRIMARY KEY (`id_mata_kuliah`),
  ADD UNIQUE KEY `kode_mata_kuliah` (`kode_mata_kuliah`);

--
-- Indeks untuk tabel `pengaturan_krs`
--
ALTER TABLE `pengaturan_krs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `relasi_mata_kuliah`
--
ALTER TABLE `relasi_mata_kuliah`
  ADD PRIMARY KEY (`id_relasi`),
  ADD KEY `id_teori` (`id_teori`),
  ADD KEY `id_praktik` (`id_praktik`);

--
-- Indeks untuk tabel `ruangan`
--
ALTER TABLE `ruangan`
  ADD PRIMARY KEY (`id_ruangan`);

--
-- Indeks untuk tabel `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_entity` (`id_mahasiswa`),
  ADD KEY `fk_user_dosen` (`id_dosen`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id_dosen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT untuk tabel `krs`
--
ALTER TABLE `krs`
  MODIFY `id_krs` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT untuk tabel `log_pindah_ruangan`
--
ALTER TABLE `log_pindah_ruangan`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id_mahasiswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id_mata_kuliah` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `pengaturan_krs`
--
ALTER TABLE `pengaturan_krs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `relasi_mata_kuliah`
--
ALTER TABLE `relasi_mata_kuliah`
  MODIFY `id_relasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id_ruangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `semester`
--
ALTER TABLE `semester`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `jadwal_kosong`
--
ALTER TABLE `jadwal_kosong`
  ADD CONSTRAINT `jadwal_kosong_ibfk_1` FOREIGN KEY (`id_ruangan`) REFERENCES `ruangan` (`id_ruangan`);

--
-- Ketidakleluasaan untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`id_mata_kuliah`) REFERENCES `mata_kuliah` (`id_mata_kuliah`),
  ADD CONSTRAINT `kelas_ibfk_2` FOREIGN KEY (`id_ruangan`) REFERENCES `ruangan` (`id_ruangan`),
  ADD CONSTRAINT `kelas_ibfk_3` FOREIGN KEY (`id_dosen`) REFERENCES `dosen` (`id_dosen`);

--
-- Ketidakleluasaan untuk tabel `krs`
--
ALTER TABLE `krs`
  ADD CONSTRAINT `krs_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`),
  ADD CONSTRAINT `krs_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`);

--
-- Ketidakleluasaan untuk tabel `log_pindah_ruangan`
--
ALTER TABLE `log_pindah_ruangan`
  ADD CONSTRAINT `log_pindah_ruangan_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`),
  ADD CONSTRAINT `log_pindah_ruangan_ibfk_2` FOREIGN KEY (`id_ruangan_awal`) REFERENCES `ruangan` (`id_ruangan`),
  ADD CONSTRAINT `log_pindah_ruangan_ibfk_3` FOREIGN KEY (`id_ruangan_baru`) REFERENCES `ruangan` (`id_ruangan`);

--
-- Ketidakleluasaan untuk tabel `mahasiswa_kelas`
--
ALTER TABLE `mahasiswa_kelas`
  ADD CONSTRAINT `mahasiswa_kelas_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`);

--
-- Ketidakleluasaan untuk tabel `relasi_mata_kuliah`
--
ALTER TABLE `relasi_mata_kuliah`
  ADD CONSTRAINT `relasi_mata_kuliah_ibfk_1` FOREIGN KEY (`id_teori`) REFERENCES `mata_kuliah` (`id_mata_kuliah`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `relasi_mata_kuliah_ibfk_2` FOREIGN KEY (`id_praktik`) REFERENCES `mata_kuliah` (`id_mata_kuliah`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_dosen` FOREIGN KEY (`id_dosen`) REFERENCES `dosen` (`id_dosen`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
