-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Jul 2022 pada 03.44
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
-- Database: `rsmg`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_barang`
--

CREATE TABLE `tb_barang` (
  `id_barang` char(16) NOT NULL,
  `nama_barang` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_barang`
--

INSERT INTO `tb_barang` (`id_barang`, `nama_barang`) VALUES
('001', 'Philips');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_detail_barang`
--

CREATE TABLE `tb_detail_barang` (
  `id_detail_barang` int(11) NOT NULL,
  `id_barang` char(16) NOT NULL,
  `minggu` varchar(50) DEFAULT NULL,
  `periode` int(11) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `jumlah_barang` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_detail_barang`
--

INSERT INTO `tb_detail_barang` (`id_detail_barang`, `id_barang`, `minggu`, `periode`, `tahun`, `jumlah_barang`) VALUES
(357, '001', '1', 1, 2017, 363),
(358, '001', '1', 2, 2017, 271),
(359, '001', '1', 3, 2017, 200),
(360, '001', '1', 4, 2017, 130),
(361, '001', '1', 5, 2017, 175),
(362, '001', '1', 6, 2017, 233),
(363, '001', '1', 7, 2017, 178),
(364, '001', '1', 8, 2017, 100),
(365, '001', '1', 9, 2017, 256),
(366, '001', '1', 10, 2017, 134),
(367, '001', '1', 11, 2017, 120),
(368, '001', '1', 12, 2017, 76),
(369, '001', '1', 1, 2018, 300),
(370, '001', '1', 2, 2018, 250),
(371, '001', '1', 3, 2018, 150),
(372, '001', '1', 4, 2018, 200),
(373, '001', '1', 5, 2018, 150),
(374, '001', '1', 6, 2018, 100),
(375, '001', '1', 7, 2018, 190),
(376, '001', '1', 8, 2018, 100),
(377, '001', '1', 9, 2018, 200),
(378, '001', '1', 10, 2018, 150),
(379, '001', '1', 11, 2018, 120),
(380, '001', '1', 12, 2018, 80),
(381, '001', '1', 1, 2019, 230),
(382, '001', '1', 2, 2019, 100),
(383, '001', '1', 3, 2019, 150),
(384, '001', '1', 4, 2019, 175),
(385, '001', '1', 5, 2019, 90),
(386, '001', '1', 6, 2019, 200),
(387, '001', '1', 7, 2019, 160),
(388, '001', '1', 8, 2019, 180),
(389, '001', '1', 9, 2019, 150),
(390, '001', '1', 10, 2019, 100),
(391, '001', '1', 11, 2019, 190),
(392, '001', '1', 12, 2019, 145),
(393, '001', '1', 1, 2020, 200),
(394, '001', '1', 2, 2020, 250),
(395, '001', '1', 3, 2020, 150),
(396, '001', '1', 4, 2020, 100),
(397, '001', '1', 5, 2020, 100),
(398, '001', '1', 6, 2020, 100),
(399, '001', '1', 7, 2020, 190),
(400, '001', '1', 8, 2020, 300),
(401, '001', '1', 9, 2020, 200),
(402, '001', '1', 10, 2020, 250),
(403, '001', '1', 11, 2020, 100),
(404, '001', '1', 12, 2020, 170);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_prediksi`
--

CREATE TABLE `tb_prediksi` (
  `id_forecasting` int(11) NOT NULL,
  `id_barang` char(16) NOT NULL,
  `periode` varchar(50) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `acuan` int(2) DEFAULT NULL,
  `alpha` double DEFAULT NULL,
  `hasil_forecasting` double DEFAULT NULL,
  `minggu` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` char(50) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `level` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `username`, `password`, `nama`, `level`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Admin', 1),
(2, 'pimpinan', '90973652b88fe07d05a4304f0a945de8', 'Pimpinan Vapor', 2);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_barang`
--
ALTER TABLE `tb_barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indeks untuk tabel `tb_detail_barang`
--
ALTER TABLE `tb_detail_barang`
  ADD PRIMARY KEY (`id_detail_barang`),
  ADD KEY `FK_tb_detail_poli` (`id_barang`);

--
-- Indeks untuk tabel `tb_prediksi`
--
ALTER TABLE `tb_prediksi`
  ADD PRIMARY KEY (`id_forecasting`),
  ADD KEY `fk_id_pelanggan_2` (`id_barang`);

--
-- Indeks untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_detail_barang`
--
ALTER TABLE `tb_detail_barang`
  MODIFY `id_detail_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=410;

--
-- AUTO_INCREMENT untuk tabel `tb_prediksi`
--
ALTER TABLE `tb_prediksi`
  MODIFY `id_forecasting` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tb_detail_barang`
--
ALTER TABLE `tb_detail_barang`
  ADD CONSTRAINT `FK_tb_detail_poli` FOREIGN KEY (`id_barang`) REFERENCES `tb_barang` (`id_barang`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_prediksi`
--
ALTER TABLE `tb_prediksi`
  ADD CONSTRAINT `FK_tb_prediksi` FOREIGN KEY (`id_barang`) REFERENCES `tb_barang` (`id_barang`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
