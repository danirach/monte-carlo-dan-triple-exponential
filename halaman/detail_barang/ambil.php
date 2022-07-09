<?php
	session_start();
	include "../../koneksi.php";
	$s = isset($_POST["search"]) ? mysqli_real_escape_string($conn, $_POST["search"]["value"]) : "";
	$p = isset($_POST["start"]) && $_POST["start"] != "" && is_numeric($_POST["start"]) ? intval($_POST["start"]) : 0;
	$e = isset($_POST["length"]) && $_POST["length"] != "" && is_numeric($_POST["length"]) ? intval($_POST["length"]) : 10;
	$c = array("`tb_detail_barang`.`id_detail_barang`", "`tb_barang`.`id_barang`", "`tb_barang`.`nama_barang`", "`tb_detail_barang`.`periode`", "`tb_detail_barang`.`tahun`", "`tb_detail_barang`.`jumlah_barang`");
	$o = isset($_POST["order"]) ? array($c[intval($_POST["order"][0]["column"])], strtoupper($_POST["order"][0]["dir"])) : array($c[0], "DESC");
	
	$result["draw"] = isset($_POST["draw"]) ? intval($_POST["draw"]) : 1;
	
	$query = mysqli_query($conn, "SELECT COUNT(*) AS `total` FROM `tb_detail_barang`");
	$row = mysqli_fetch_assoc($query);
	$result["recordsTotal"] = intval($row["total"]);
	
	$query = mysqli_query($conn, "SELECT COUNT(*) AS `filtered` FROM `tb_detail_barang`, `tb_barang` WHERE `tb_barang`.`id_barang`=`tb_detail_barang`.`id_barang` AND (`tb_barang`.`nama_barang` LIKE '%{$s}%' OR `tb_detail_barang`.`jumlah_barang` LIKE '%{$s}%')");
	$row = mysqli_fetch_assoc($query);
	$result["recordsFiltered"] = intval($row["filtered"]);
	
	$query = mysqli_query($conn, "SELECT * FROM `tb_detail_barang`, `tb_barang` WHERE `tb_barang`.`id_barang`=`tb_detail_barang`.`id_barang` AND (`tb_barang`.`nama_barang` LIKE '%{$s}%' OR `tb_detail_barang`.`jumlah_barang` LIKE '%{$s}%') ORDER BY {$o[0]} {$o[1]} LIMIT {$p},{$e}");
	
	$result["data"] = array();
	$baris = 0;
	$bulan = array(null, "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
	
	while($row = mysqli_fetch_assoc($query)) {
		if($_SESSION["level"] == 1) {
			$tombol = array(
				"<button class='btn btn-success btn-xs' onclick='edit({$baris});'>Edit</button>",
				"<button class='btn btn-danger btn-xs' onclick='konfirmasi({$baris});'>Delete</button>"
			);
			$result["data"][] = array(
				$row["id_detail_barang"],
				$row["id_barang"],
				htmlspecialchars($row["nama_barang"]),
				$row["minggu"],
				$bulan[intval($row["periode"])],
				$row["tahun"],
				number_format($row["jumlah_barang"]),
				"<center><div style='width:90px;'>{$tombol[0]}&nbsp;{$tombol[1]}</div></center>",
				array(
					$row["periode"],
					$row["jumlah_barang"]
				)
			);
			$baris++;
		} else {
			$result["data"][] = array(
				$row["id_detail_barang"],
				$row["id_barang"],
				htmlspecialchars($row["nama_barang"]),
				$row["minggu"],
				$bulan[intval($row["periode"])],
				$row["tahun"],
				number_format($row["jumlah_barang"])
			);
		}
	}
	echo json_encode($result);
?>