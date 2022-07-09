<?php
	if(isset($_POST["id_barang"])) {
		include "../../koneksi.php";
		$id_barang = mysqli_real_escape_string($conn, $_POST["id_barang"]);
		
		$q = mysqli_query($conn, "DELETE FROM `tb_barang` WHERE `id_barang`='{$id_barang}'");
		
		if($q) {
			echo "berhasil";
		} else {
			echo "Gagal menghapus data.";
		}
	} else {
		echo "Gagal menghapus data.";
	}
?>