-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Okt 2022 pada 03.12
-- Versi server: 10.4.24-MariaDB
-- Versi PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotel`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `book_detail`
--

CREATE TABLE `book_detail` (
  `id_bodet` int(11) NOT NULL,
  `nama_pelanggan` varchar(30) NOT NULL,
  `alamat` text DEFAULT NULL,
  `tanggal_masuk` datetime DEFAULT current_timestamp(),
  `id_jenkam` int(11) NOT NULL,
  `no_kamar` varchar(3) NOT NULL,
  `lama_inap` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `book_detail`
--

INSERT INTO `book_detail` (`id_bodet`, `nama_pelanggan`, `alamat`, `tanggal_masuk`, `id_jenkam`, `no_kamar`, `lama_inap`) VALUES
(1, 'Yayoga', 'gotroya', '2022-10-09 05:22:56', 2, '31', 24),
(2, 'Jundi', 'Duren Sawit', '2022-10-09 05:39:23', 2, '124', 4),
(7, 'hgkj', 'ghfgh', '2022-10-09 08:07:36', 2, '12', 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis_kamar`
--

CREATE TABLE `jenis_kamar` (
  `id_jenkam` int(11) NOT NULL,
  `jenkam` varchar(10) NOT NULL,
  `biaya` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `jenis_kamar`
--

INSERT INTO `jenis_kamar` (`id_jenkam`, `jenkam`, `biaya`) VALUES
(1, 'deluxe', 650000),
(2, 'standar', 450000),
(3, 'ekonomi', 300000);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `book_detail`
--
ALTER TABLE `book_detail`
  ADD PRIMARY KEY (`id_bodet`),
  ADD KEY `id_jenkam` (`id_jenkam`);

--
-- Indeks untuk tabel `jenis_kamar`
--
ALTER TABLE `jenis_kamar`
  ADD PRIMARY KEY (`id_jenkam`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `book_detail`
--
ALTER TABLE `book_detail`
  MODIFY `id_bodet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `jenis_kamar`
--
ALTER TABLE `jenis_kamar`
  MODIFY `id_jenkam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `book_detail`
--
ALTER TABLE `book_detail`
  ADD CONSTRAINT `book_detail_ibfk_1` FOREIGN KEY (`id_jenkam`) REFERENCES `jenis_kamar` (`id_jenkam`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
