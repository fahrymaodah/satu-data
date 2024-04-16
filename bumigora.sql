-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Apr 2024 pada 04.14
-- Versi server: 10.4.24-MariaDB
-- Versi PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `satu-data`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `administrator`
--

CREATE TABLE `administrator` (
  `id_administrator` int(11) NOT NULL,
  `email_administrator` varchar(50) NOT NULL,
  `password_administrator` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `administrator`
--

INSERT INTO `administrator` (`id_administrator`, `email_administrator`, `password_administrator`) VALUES
(1, 'admin@universitasbumigora.ac.id', '601cc1a84168b396221bbe5cb9c813296a054afa');

-- --------------------------------------------------------

--
-- Struktur dari tabel `feeder`
--

CREATE TABLE `feeder` (
  `id_feeder` int(11) NOT NULL,
  `id_tahun` int(11) NOT NULL,
  `id_prodi` int(11) NOT NULL,
  `semester_feeder` enum('ganjil','genap') NOT NULL,
  `file_feeder` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `generate`
--

CREATE TABLE `generate` (
  `id_generate` int(11) NOT NULL,
  `id_prodi` int(11) NOT NULL,
  `jenis_generate` enum('siska','feeder','perbandingan') NOT NULL,
  `angkatan_generate` varchar(10) NOT NULL,
  `file_generate` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `prodi`
--

CREATE TABLE `prodi` (
  `id_prodi` int(11) NOT NULL,
  `nama_prodi` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `siska`
--

CREATE TABLE `siska` (
  `id_siska` int(11) NOT NULL,
  `id_tahun` int(11) NOT NULL,
  `id_prodi` int(11) NOT NULL,
  `semester_siska` enum('ganjil','genap') NOT NULL,
  `angkatan_siska` varchar(10) NOT NULL,
  `file_siska` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tahun`
--

CREATE TABLE `tahun` (
  `id_tahun` int(11) NOT NULL,
  `nama_tahun` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tahun`
--

INSERT INTO `tahun` (`id_tahun`, `nama_tahun`) VALUES
(1, '2018-2019'),
(2, '2019-2020'),
(3, '2020-2021'),
(4, '2021-2022'),
(5, '2022-2023');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`id_administrator`);

--
-- Indeks untuk tabel `feeder`
--
ALTER TABLE `feeder`
  ADD PRIMARY KEY (`id_feeder`),
  ADD KEY `id_tahun` (`id_tahun`),
  ADD KEY `id_prodi` (`id_prodi`);

--
-- Indeks untuk tabel `generate`
--
ALTER TABLE `generate`
  ADD PRIMARY KEY (`id_generate`),
  ADD KEY `id_prodi` (`id_prodi`);

--
-- Indeks untuk tabel `prodi`
--
ALTER TABLE `prodi`
  ADD PRIMARY KEY (`id_prodi`);

--
-- Indeks untuk tabel `siska`
--
ALTER TABLE `siska`
  ADD PRIMARY KEY (`id_siska`),
  ADD KEY `id_prodi` (`id_prodi`),
  ADD KEY `id_tahun` (`id_tahun`);

--
-- Indeks untuk tabel `tahun`
--
ALTER TABLE `tahun`
  ADD PRIMARY KEY (`id_tahun`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `administrator`
--
ALTER TABLE `administrator`
  MODIFY `id_administrator` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `feeder`
--
ALTER TABLE `feeder`
  MODIFY `id_feeder` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `generate`
--
ALTER TABLE `generate`
  MODIFY `id_generate` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT untuk tabel `prodi`
--
ALTER TABLE `prodi`
  MODIFY `id_prodi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `siska`
--
ALTER TABLE `siska`
  MODIFY `id_siska` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `tahun`
--
ALTER TABLE `tahun`
  MODIFY `id_tahun` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `feeder`
--
ALTER TABLE `feeder`
  ADD CONSTRAINT `feeder_ibfk_1` FOREIGN KEY (`id_tahun`) REFERENCES `tahun` (`id_tahun`) ON UPDATE CASCADE,
  ADD CONSTRAINT `feeder_ibfk_2` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id_prodi`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `generate`
--
ALTER TABLE `generate`
  ADD CONSTRAINT `generate_ibfk_1` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id_prodi`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `siska`
--
ALTER TABLE `siska`
  ADD CONSTRAINT `siska_ibfk_1` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id_prodi`) ON UPDATE CASCADE,
  ADD CONSTRAINT `siska_ibfk_2` FOREIGN KEY (`id_tahun`) REFERENCES `tahun` (`id_tahun`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
