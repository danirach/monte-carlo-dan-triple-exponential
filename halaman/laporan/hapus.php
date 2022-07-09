<?php
	if(isset($_POST["id_forecasting"])) {
		include "../../koneksi.php";
		$id_forecasting = intval($_POST["id_forecasting"]);
		
		$q = mysqli_query($conn, "DELETE FROM `tb_prediksi` WHERE `id_forecasting`={$id_forecasting}");
		
		if($q) {
			echo "berhasil";
		} else {
			echo "Gagal menghapus data.";
		}
	} else {
		echo "Gagal menghapus data.";
	}
?>