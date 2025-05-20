-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 19 Bulan Mei 2025 pada 15.00
-- Versi server: 8.0.30
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ica-cobain`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `alternatif`
--

CREATE TABLE `alternatif` (
  `id_alternatif` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `siswa_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `nama`, `siswa_id`, `status`) VALUES
(77, 'a1', 5, 1),
(78, 'a2', 5, 1),
(79, 'a3', 5, 1),
(80, 'a4', 5, 1),
(81, 'a5', 5, 1),
(82, 'a6', 5, 1),
(83, 'a7', 5, 1),
(84, 'a8', 5, 1),
(85, 'a9', 5, 1),
(86, 'a10', 5, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil`
--

CREATE TABLE `hasil` (
  `id_hasil` int NOT NULL,
  `id_alternatif` int NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `hasil`
--

INSERT INTO `hasil` (`id_hasil`, `id_alternatif`, `nilai`) VALUES
(1, 77, 0.0419711),
(2, 78, -0.148907),
(3, 79, 0.0419711),
(4, 80, -0.0268717),
(5, 81, -0.0174029),
(6, 82, -0.0156552),
(7, 83, -0.0692282),
(8, 84, -0.148907),
(9, 85, -0.0814261),
(10, 86, -0.122801);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `kode_kriteria` varchar(100) NOT NULL,
  `jenis` varchar(100) NOT NULL,
  `bobot` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `keterangan`, `kode_kriteria`, `jenis`, `bobot`) VALUES
(43, 'Kepemilikan Kartu', 'C1', 'Benefit', 0.3),
(44, 'Penghasilan Orang Tua', 'C2', 'Cost', 0.25),
(45, 'Jumlah Tanggungan Orang Tua', 'C3', 'Benefit', 0.2),
(46, 'Kepemilikan Rumah', 'C4', 'Cost', 0.15),
(47, 'Nilai Rapor', 'C5', 'Benefit', 0.1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penilaian`
--

CREATE TABLE `penilaian` (
  `id_penilaian` int NOT NULL,
  `id_alternatif` int NOT NULL,
  `id_kriteria` int NOT NULL,
  `id_sub_kriteria` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `penilaian`
--

INSERT INTO `penilaian` (`id_penilaian`, `id_alternatif`, `id_kriteria`, `id_sub_kriteria`) VALUES
(388, 77, 43, 170),
(389, 77, 44, 173),
(390, 77, 45, 176),
(391, 77, 46, 178),
(392, 77, 47, 185),
(393, 78, 43, 170),
(394, 78, 44, 172),
(395, 78, 45, 175),
(396, 78, 46, 181),
(397, 78, 47, 185),
(398, 79, 43, 170),
(399, 79, 44, 173),
(400, 79, 45, 176),
(401, 79, 46, 178),
(402, 79, 47, 185),
(403, 80, 43, 170),
(404, 80, 44, 174),
(405, 80, 45, 175),
(406, 80, 46, 189),
(407, 80, 47, 187),
(408, 81, 43, 170),
(409, 81, 44, 172),
(410, 81, 45, 177),
(411, 81, 46, 181),
(412, 81, 47, 187),
(413, 82, 43, 170),
(414, 82, 44, 173),
(415, 82, 45, 175),
(416, 82, 46, 178),
(417, 82, 47, 185),
(418, 83, 43, 170),
(419, 83, 44, 173),
(420, 83, 45, 175),
(421, 83, 46, 189),
(422, 83, 47, 185),
(423, 84, 43, 170),
(424, 84, 44, 172),
(425, 84, 45, 175),
(426, 84, 46, 181),
(427, 84, 47, 185),
(428, 85, 43, 170),
(429, 85, 44, 173),
(430, 85, 45, 176),
(431, 85, 46, 181),
(432, 85, 47, 184),
(433, 86, 43, 170),
(434, 86, 44, 173),
(435, 86, 45, 175),
(436, 86, 46, 181),
(437, 86, 47, 185);

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `id` int NOT NULL,
  `nisn` int NOT NULL,
  `nama` text COLLATE utf8mb4_general_ci NOT NULL,
  `penghasilan_ortu` enum('> Rp. 5.000.000','Rp. 3.000.000 - Rp. 5.000.000','Rp. 1.000.000 - Rp. 3.000.000','< Rp. 1.000.000') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah_tanggungan` int NOT NULL,
  `kepemilikan_rumah` enum('Sewa/Kontrak','Milik Bersama','Hak Milik','') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_rapor` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `file_kip` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `file_penghasilan_orang_tua` text COLLATE utf8mb4_general_ci NOT NULL,
  `file_tanggungan_orang_tua` text COLLATE utf8mb4_general_ci NOT NULL,
  `file_rumah` text COLLATE utf8mb4_general_ci NOT NULL,
  `file_rapor` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int NOT NULL,
  `verifikasi_file` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '1',
  `created_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id`, `nisn`, `nama`, `penghasilan_ortu`, `jumlah_tanggungan`, `kepemilikan_rumah`, `nilai_rapor`, `file_kip`, `file_penghasilan_orang_tua`, `file_tanggungan_orang_tua`, `file_rumah`, `file_rapor`, `user_id`, `verifikasi_file`, `status`, `created_at`) VALUES
(5, 12345678, 'dsad', '> Rp. 5.000.000', 1, 'Sewa/Kontrak', '88', '1747577576_Peralatan_yang_digunakan_untuk_fiber_optik.pdf', '1747577576_Peralatan_yang_digunakan_untuk_fiber_optik1.pdf', '1747577576_Peralatan_yang_digunakan_untuk_fiber_optik2.pdf', '1747577576_Peralatan_yang_digunakan_untuk_fiber_optik3.pdf', '1747577576_Peralatan_yang_digunakan_untuk_fiber_optik4.pdf', 9, 0, 1, '2025-05-18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sub_kriteria`
--

CREATE TABLE `sub_kriteria` (
  `id_sub_kriteria` int NOT NULL,
  `id_kriteria` int NOT NULL,
  `deskripsi` varchar(200) NOT NULL,
  `nilai` float NOT NULL,
  `bobot` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `sub_kriteria`
--

INSERT INTO `sub_kriteria` (`id_sub_kriteria`, `id_kriteria`, `deskripsi`, `nilai`, `bobot`) VALUES
(170, 43, 'Tidak', 2, 0),
(171, 43, 'Ada', 1, 0),
(172, 44, 'Rp. 3.000.000 - Rp. 5.000.000', 3, 0),
(173, 44, 'Rp. 1.000.000 - Rp. 3.000.000', 2, 0),
(174, 44, '< Rp. 1.000.000', 1, 0),
(175, 45, '1 - 3 Anak ', 1, 0),
(176, 45, '4 - 6 Anak', 2, 0),
(177, 45, '> 6 Anak', 3, 0),
(178, 46, 'Sewa/Kontrak', 1, 0),
(181, 46, 'Hak Milik', 3, 0),
(184, 47, '75 - 79', 1, 0),
(185, 47, '80 - 89', 2, 0),
(187, 47, '90 - 99', 3, 0),
(188, 44, '> Rp. 5.000.000', 4, 0),
(189, 46, 'Milik Bersama', 2, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `id_user_level` int NOT NULL,
  `nama` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `verifikasi` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `id_user_level`, `nama`, `email`, `username`, `password`, `verifikasi`) VALUES
(1, 1, 'Operator', 'Operator@gmail.com', 'operator', '21232f297a57a5a743894a0e4a801fc3', 1),
(7, 2, 'User', 'user@gmail.com', 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 1),
(9, 2, 'icaaa', 'icaaa@gmail.com', 'icaa', '25d55ad283aa400af464c76d713c07ad', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_level`
--

CREATE TABLE `user_level` (
  `id_user_level` int NOT NULL,
  `user_level` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_level`
--

INSERT INTO `user_level` (`id_user_level`, `user_level`) VALUES
(1, 'Administrator'),
(2, 'User');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indeks untuk tabel `hasil`
--
ALTER TABLE `hasil`
  ADD PRIMARY KEY (`id_hasil`),
  ADD KEY `id_alternatif` (`id_alternatif`);

--
-- Indeks untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indeks untuk tabel `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`id_penilaian`),
  ADD KEY `id_alternatif` (`id_alternatif`),
  ADD KEY `id_kriteria` (`id_kriteria`),
  ADD KEY `id_sub_kriteria` (`id_sub_kriteria`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indeks untuk tabel `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  ADD PRIMARY KEY (`id_sub_kriteria`),
  ADD KEY `id_kriteria` (`id_kriteria`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `id_user_level` (`id_user_level`);

--
-- Indeks untuk tabel `user_level`
--
ALTER TABLE `user_level`
  ADD PRIMARY KEY (`id_user_level`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id_alternatif` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT untuk tabel `hasil`
--
ALTER TABLE `hasil`
  MODIFY `id_hasil` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT untuk tabel `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id_penilaian` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=438;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  MODIFY `id_sub_kriteria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `user_level`
--
ALTER TABLE `user_level`
  MODIFY `id_user_level` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `hasil`
--
ALTER TABLE `hasil`
  ADD CONSTRAINT `hasil_ibfk_1` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id_alternatif`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penilaian`
--
ALTER TABLE `penilaian`
  ADD CONSTRAINT `penilaian_ibfk_1` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id_alternatif`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penilaian_ibfk_2` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penilaian_ibfk_3` FOREIGN KEY (`id_sub_kriteria`) REFERENCES `sub_kriteria` (`id_sub_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  ADD CONSTRAINT `sub_kriteria_ibfk_1` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_user_level`) REFERENCES `user_level` (`id_user_level`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
