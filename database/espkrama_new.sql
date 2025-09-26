-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Sep 2025 pada 14.30
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
-- Database: `espkrama`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_alternatif`
--

CREATE TABLE `data_alternatif` (
  `ID_Alternatif` int(20) NOT NULL,
  `Nama_Menu` varchar(50) NOT NULL,
  `Jenis_Menu` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data_alternatif`
--

INSERT INTO `data_alternatif` (`ID_Alternatif`, `Nama_Menu`, `Jenis_Menu`) VALUES
(1, 'Rama', 'Beasiswa Genbi'),
(2, 'Azira', 'Beasiswa Genbi'),
(3, 'Putra', 'Beasiswa Genbi'),
(4, 'David', 'Beasiswa Genbi'),
(5, 'Manda', 'Beasiswa Genbi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_kriteria`
--

CREATE TABLE `data_kriteria` (
  `ID_Kriteria` int(20) NOT NULL,
  `Harga` int(20) NOT NULL,
  `Penjualan` int(20) NOT NULL,
  `Rasa` int(20) NOT NULL,
  `Feedback` int(20) NOT NULL,
  `Semester` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data_kriteria`
--

INSERT INTO `data_kriteria` (`ID_Kriteria`, `Harga`, `Penjualan`, `Rasa`, `Feedback`, `Semester`) VALUES
(0, 4, 3, 2, 5, 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_penilaian`
--

CREATE TABLE `data_penilaian` (
  `ID_Penilaian` int(20) NOT NULL,
  `Alternatif` varchar(50) NOT NULL,
  `Harga` int(20) NOT NULL,
  `Penjualan` int(20) NOT NULL,
  `Rasa` int(20) NOT NULL,
  `Feedback` int(20) NOT NULL,
  `Semester` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data_penilaian`
--

INSERT INTO `data_penilaian` (`ID_Penilaian`, `Alternatif`, `Harga`, `Penjualan`, `Rasa`, `Feedback`, `Semester`) VALUES
(1, 'Rama', 2, 4, 7, 5, 2),
(2, 'Manda', 2, 2, 4, 3, 1),
(3, 'Putra', 3, 4, 5, 7, 8),
(4, 'David', 1, 2, 3, 4, 0),
(5, 'Azira', 4, 3, 2, 1, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_user`
--

CREATE TABLE `data_user` (
  `id` int(20) NOT NULL,
  `npm` varchar(255) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data_user`
--

INSERT INTO `data_user` (`id`, `npm`, `username`, `password`, `role`) VALUES
(6, '1', 'admin', '$2y$10$ee19Ya9l4iBB1SukBKnH4e63Od07sxNMWo0YtAMXoqpWslSlSHJze', 'admin'),
(10, '2', 'admin2', '$2y$10$cChNgHRSep.qrQytAAWQuewQLRys1fJVNF5ofJEXme03x/ayUHIWO', 'admin'),
(11, '21562020019', 'rama', '$2y$10$BgO0gXlo2IvNII27N/35VOVptfhWWPloQ6trFM4NQzQJr3Fr5ze7m', 'user');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_preferensi`
--

CREATE TABLE `hasil_preferensi` (
  `ID_Pref` int(20) NOT NULL,
  `C1` float NOT NULL,
  `C2` float NOT NULL,
  `C3` float NOT NULL,
  `C4` float NOT NULL,
  `C5` float NOT NULL,
  `Total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
COMMIT;
-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_normalisasi`
--

CREATE TABLE `hasil_normalisasi` (
  `ID_Norm` int(20) NOT NULL,
  `C1` float NOT NULL,
  `C2` float NOT NULL,
  `C3` float NOT NULL,
  `C4` float NOT NULL,
  `C5` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_berkas`
--

CREATE TABLE `tbl_berkas` (
  `id` int(11) NOT NULL,
  `nama_mahasiswa` varchar(100) NOT NULL,
  `npm` varchar(20) NOT NULL,
  `program_studi` varchar(50) NOT NULL,
  `file_aktif_organisasi` varchar(255) DEFAULT NULL,
  `file_tidak_beasiswa` varchar(255) DEFAULT NULL,
  `file_keluarga_tidak_mampu` varchar(255) DEFAULT NULL,
  `file_ipk` varchar(255) DEFAULT NULL,
  `jumlah_sks` int(11) DEFAULT 0,
  `status` enum('pending','diterima','ditolak') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_berkas`
--

INSERT INTO `tbl_berkas` (`id`, `nama_mahasiswa`, `npm`, `program_studi`, `file_aktif_organisasi`, `file_tidak_beasiswa`, `file_keluarga_tidak_mampu`, `file_ipk`, `jumlah_sks`, `status`, `created_at`) VALUES
(13, 'rama', '21562020019', 'tekkom', 'uploads/berkas/1756899246_bitcoin_id.pdf', 'uploads/berkas/1756899246_bitcoin_id.pdf', 'uploads/berkas/1756899246_bitcoin_id.pdf', 'uploads/berkas/1756899246_bitcoin_id.pdf', 40, 'diterima', '2025-09-03 11:34:06');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `data_user`
--
ALTER TABLE `data_user`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_berkas`
--
ALTER TABLE `tbl_berkas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `data_user`
--
ALTER TABLE `data_user`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `tbl_berkas`
--
ALTER TABLE `tbl_berkas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
