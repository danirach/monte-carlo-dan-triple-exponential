<?php
	session_start();
	include "../../koneksi.php";
	$s = isset($_POST["search"]) ? mysqli_real_escape_string($conn, $_POST["search"]["value"]) : "";
	$p = isset($_POST["start"]) && $_POST["start"] != "" && is_numeric($_POST["start"]) ? intval($_POST["start"]) : 0;
	$e = isset($_POST["length"]) && $_POST["length"] != "" && is_numeric($_POST["length"]) ? intval($_POST["length"]) : 10;
	$c = array("`tb_prediksi`.`id_forecasting`", "`tb_prediksi`.`id_barang`", "`tb_barang`.`nama_barang`", "`tb_prediksi`.`periode`", "`tb_prediksi`.`tahun`", "`tb_prediksi`.`acuan`", "`tb_prediksi`.`alpha`", "`tb_prediksi`.`hasil_forecasting`");
	$o = isset($_POST["order"]) ? array($c[intval($_POST["order"][0]["column"])], strtoupper($_POST["order"][0]["dir"])) : array($c[0], "DESC");
	
	$result["draw"] = isset($_POST["draw"]) ? intval($_POST["draw"]) : 1;
	
	$query = mysqli_query($conn, "SELECT COUNT(*) AS `total` FROM `tb_prediksi`");
	$row = mysqli_fetch_assoc($query);
	$result["recordsTotal"] = intval($row["total"]);
	
	$query = mysqli_query($conn, "SELECT COUNT(*) AS `filtered` FROM `tb_prediksi`, `tb_barang` WHERE `tb_barang`.`id_barang`=`tb_prediksi`.`id_barang` AND (`tb_barang`.`nama_barang` LIKE '%{$s}%' OR `tb_prediksi`.`hasil_forecasting` LIKE '%{$s}%')");
	$row = mysqli_fetch_assoc($query);
	$result["recordsFiltered"] = intval($row["filtered"]);
	
	$query = mysqli_query($conn, "SELECT * FROM `tb_prediksi`, `tb_barang` WHERE `tb_barang`.`id_barang`=`tb_prediksi`.`id_barang` AND (`tb_barang`.`nama_barang` LIKE '%{$s}%' OR `tb_prediksi`.`hasil_forecasting` LIKE '%{$s}%') ORDER BY {$o[0]} {$o[1]} LIMIT {$p},{$e}");
	
	$result["data"] = array();
	$baris = 0;
	$bulan = array(null, "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
	
	while($row = mysqli_fetch_assoc($query)) {
		$result["data"][] = array(
			$row["id_forecasting"],
			$row["id_barang"],
			htmlspecialchars($row["nama_barang"]),
			// $bulan[intval($row["periode"])],
			$row["periode"],
			$row["tahun"],
			intval($row["acuan"]) > 0 ? $row["acuan"]." bulan" : "Semua",
			number_format($row["alpha"], 1, ",", "."),
			number_format($row["hasil_forecasting"], 3, ",", "."),
			"<center><button class='btn btn-success btn-xs' onclick='cetak({$baris});'>Cetak</button></center>"
		);
		$baris++;
	}
	echo json_encode($result);
?>