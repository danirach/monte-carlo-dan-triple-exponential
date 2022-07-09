<?php
	if(isset($_POST["id_barang"])) {
		include "../../koneksi.php";
		$id_barang_lama = mysqli_real_escape_string($conn, $_POST["id_barang_lama"]);
		$id_barang = mysqli_real_escape_string($conn, $_POST["id_barang"]);
		$nama_barang = mysqli_real_escape_string($conn, $_POST["nama_barang"]);
		$sql = "SELECT * FROM tb_barang WHERE nama_barang = '{$nama_barang}'";
		$query = mysqli_query($conn, $sql);
		$tot = mysqli_num_rows($query);
		if($tot === 0) {
			 $sql = "UPDATE tb_barang SET id_barang='{$id_barang}', nama_barang='{$nama_barang}' WHERE id_barang='{$id_barang}'";
			$q = mysqli_query($conn, $sql);
			if($q) {
				echo "berhasil";
			} else {
				echo "Gagal mengubah data.";
			}
		} else {
			echo "Gagal mengubah data. Nama barang sudah dipakai.";
		}
	} else {
		echo "Gagal mengubah data.";
	}
?>