<?php
	if(isset($_POST["nama_barang"])) {
		include "../../koneksi.php";
		$id_barang = mysqli_real_escape_string($conn, $_POST["id_barang"]);
		$nama_barang = mysqli_real_escape_string($conn, $_POST["nama_barang"]);	
		
		$q = mysqli_query($conn, "SELECT * FROM `tb_barang` WHERE `nama_barang`='{$nama_barang}'");
		
		if($q && mysqli_num_rows($q) === 0) {
			$q = mysqli_query($conn, "INSERT INTO `tb_barang` VALUES ('{$id_barang}', '{$nama_barang}')");
			if($q) {
				echo "berhasil";
			} else {
				echo "Gagal menyimpan data.";
			}
		} else {
			echo "Gagal menyimpan data. Nama barang sudah ada.";
		}
	} else {
		echo "Gagal menyimpan data.";
	}
?>