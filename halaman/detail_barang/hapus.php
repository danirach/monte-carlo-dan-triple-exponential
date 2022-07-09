<?php
	if(isset($_POST["id_detail_barang"])) {
		include "../../koneksi.php";
		$id_detail_barang = intval($_POST["id_detail_barang"]);
		
		$q = mysqli_query($conn, "SELECT * FROM `tb_detail_barang` WHERE `id_detail_barang`={$id_detail_barang}");
		if($q) {
			$r = mysqli_fetch_assoc($q);
			
			$q = mysqli_query($conn, "DELETE FROM `tb_detail_barang` WHERE `id_detail_barang`={$id_detail_barang}");
			$q = $q && mysqli_query($conn, "DELETE FROM `tb_prediksi` WHERE `id_barang`='".$r["id_barang"]."' AND periode=".$r["periode"]." AND tahun=".$r["tahun"]);

			if($q) {
				echo "berhasil";
			} else {
				echo "Gagal menghapus data.";
			}
		} else {
			echo "Gagal menghapus data.";
		}
	} else {
		echo "Gagal menghapus data.";
	}
?>