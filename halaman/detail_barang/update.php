<?php
	if(isset($_POST["id_detail_barang"])) {
		include "../../koneksi.php";
		$id_detail_barang = intval($_POST["id_detail_barang"]);
		$id_barang = mysqli_real_escape_string($conn, $_POST["id_barang"]);
		$periode = intval($_POST["periode"]);
		$tahun = intval($_POST["tahun"]);
		$jumlah_barang = intval($_POST["jumlah_barang"]);
		
		$q = mysqli_query($conn, "UPDATE `tb_detail_barang` SET `id_barang`='{$id_barang}', `periode`={$periode}, `tahun`={$tahun}, `jumlah_barang`={$jumlah_barang} WHERE `id_detail_barang`={$id_detail_barang}");
		
		if($q) {
			echo "berhasil";
		} else {
			echo "Gagal mengubah data.";
		}
	} else {
		echo "Gagal mengubah data.";
	}
?>