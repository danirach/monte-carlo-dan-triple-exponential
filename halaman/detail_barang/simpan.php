<?php
	if(isset($_POST["id_barang"])) {
		include "../../koneksi.php";
		$id_barang = mysqli_real_escape_string($conn, $_POST["id_barang"]);
		$id_barang = mysqli_real_escape_string($conn, $_POST["id_barang"]);
		$minggu = intval($_POST["minggu"]);
		$periode = intval($_POST["periode"]);
		$tahun = intval($_POST["tahun"]);
		$jumlah_barang = intval($_POST["jumlah_barang"]);
		
		$bulan = array(null, "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

		$query = mysqli_query($conn, "SELECT * FROM tb_detail_barang WHERE id_barang='$id_barang' AND periode=".$periode." AND tahun=".$tahun);
		// if(mysqli_num_rows($query) > 0) {
		// 	echo "Gagal menyimpan data. Data " . $bulan[$periode] . " " . $tahun . " sudah ada.";
		// 	exit;
		// }
		
		$q = mysqli_query($conn, "INSERT INTO `tb_detail_barang` VALUES (NULL, '{$id_barang}', {$minggu}, {$periode}, {$tahun}, {$jumlah_barang})");
		if($q) {
			echo "berhasil";
		} else {
			echo "Gagal menyimpan data.";
		}
	} else {
		echo "Gagal menyimpan data.";
	}
?>