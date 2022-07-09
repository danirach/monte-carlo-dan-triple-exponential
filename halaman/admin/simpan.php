<?php
	if(isset($_POST["username"])) {
		include "../../koneksi.php";
		$username = mysqli_real_escape_string($conn, strtolower($_POST["username"]));
		$nama = mysqli_real_escape_string($conn, $_POST["nama"]);
		$level = $_POST["level"];
		$password = strtolower(md5($_POST["password"]));
		
		$q = mysqli_query($conn, "SELECT * FROM `tb_user` WHERE `username`='{$username}'");
		
		if($q && mysqli_num_rows($q) === 0) {
			$q = mysqli_query($conn, "INSERT INTO `tb_user` VALUES (NULL, '{$username}', '{$password}', '{$nama}', {$level})");
			if($q) {
				echo "berhasil";
			} else {
				echo "Gagal menyimpan data.";
			}
		} else {
			echo "Gagal menyimpan data. Username sudah dipakai.";
		}
	} else {
		echo "Gagal menyimpan data.";
	}
?>