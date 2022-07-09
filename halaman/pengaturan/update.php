<?php
	session_start();
	include "../../koneksi.php";
	$user = mysqli_real_escape_string($conn, $_POST["user"]);
	$nama = mysqli_real_escape_string($conn, $_POST["nama"]);
	$pass = isset($_POST["pass"]) && $_POST["pass"] != "" ? strtolower(md5($_POST["pass"])) : $_SESSION["password"];
	$lama = strtolower(md5($_POST["lama"]));
	
	$q = mysqli_query($conn, "SELECT * FROM (SELECT `id_user`, `username` FROM `tb_master_user` UNION ALL SELECT `id_pelanggan` AS `id_user`, `username` FROM `tb_data_pelanggan`) AS `semua` WHERE `id_user`<>'{$_SESSION["id_user"]}' AND `username`='{$user}'");
	
	if($q) {
		$n = mysqli_num_rows($q);
		if($n === 0) {
			if($lama !== $_SESSION["password"]) {
				echo "Gagal mengubah data. Password lama salah!";
				exit;
			}
			if($_SESSION["level"] === 3) {
				$q = mysqli_query($conn, "UPDATE `tb_data_pelanggan` SET `username`='{$user}', `password`='{$pass}', `nama_pelanggan`='{$nama}' WHERE `id_pelanggan`='{$_SESSION["id_user"]}'");
			} else {
				$q = mysqli_query($conn, "UPDATE `tb_master_user` SET `username`='{$user}', `password`='{$pass}', `nama`='{$nama}' WHERE `id_user`={$_SESSION["id_user"]}");
			}
			if($q) {
				echo "berhasil";
				$_SESSION["username"] = $_POST["user"];
				$_SESSION["password"] = $pass;
				$_SESSION["nama"] = htmlspecialchars($nama);
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