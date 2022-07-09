<?php
	if(isset($_POST["id_user"])) {
		include "../../koneksi.php";
		$id_user = intval($_POST["id_user"]);
		$username = mysqli_real_escape_string($conn, strtolower($_POST["username"]));
		$nama = mysqli_real_escape_string($conn, $_POST["nama"]);
		$level = $_POST["level"];
		$password = isset($_POST["password"]) && $_POST["password"] != "" ? ", `password`='" . strtolower(md5($_POST["password"])) . "'" : "";
		
		$q = mysqli_query($conn, "SELECT * FROM `tb_user` WHERE `id_user`<>'{$id_user}' AND `username`='{$username}'");
		
		if($q && mysqli_num_rows($q) === 0) {
			$q = mysqli_query($conn, "UPDATE `tb_user` SET `username`='{$username}', `nama`='{$nama}', `level`={$level}{$password} WHERE `id_user`={$id_user}");
			if($q) {
				echo "berhasil";
			} else {
				echo "Gagal mengubah data.";
			}
		} else {
			echo "Gagal mengubah data. Username sudah dipakai.";
		}
	} else {
		echo "Gagal mengubah data.";
	}
?>