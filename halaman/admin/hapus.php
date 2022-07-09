<?php
	if(isset($_POST["id_user"])) {
		include "../../koneksi.php";
		$id_user = intval($_POST["id_user"]);
		
		$q = mysqli_query($conn, "DELETE FROM `tb_user` WHERE `id_user`={$id_user}");
		
		if($q) {
			echo "berhasil";
		} else {
			echo "Gagal menghapus data.";
		}
	} else {
		echo "Gagal menghapus data.";
	}
?>