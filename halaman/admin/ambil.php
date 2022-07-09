<?php
	session_start();
	include "../../koneksi.php";
	$s = isset($_POST["search"]) ? mysqli_real_escape_string($conn, $_POST["search"]["value"]) : "";
	$p = isset($_POST["start"]) && $_POST["start"] != "" && is_numeric($_POST["start"]) ? intval($_POST["start"]) : 0;
	$e = isset($_POST["length"]) && $_POST["length"] != "" && is_numeric($_POST["length"]) ? intval($_POST["length"]) : 10;
	$c = array("`id_user`", "`nama`", "`username`", "`level`");
	$o = isset($_POST["order"]) ? array($c[intval($_POST["order"][0]["column"])], strtoupper($_POST["order"][0]["dir"])) : array($c[0], "DESC");
	
	$result["draw"] = isset($_POST["draw"]) ? intval($_POST["draw"]) : 1;
	
	$query = mysqli_query($conn, "SELECT COUNT(*) AS `total` FROM `tb_user` WHERE `id_user`<>{$_SESSION["id_user"]}");
	$row = mysqli_fetch_assoc($query);
	$result["recordsTotal"] = intval($row["total"]);
	
	$query = mysqli_query($conn, "SELECT COUNT(*) AS `filtered` FROM `tb_user` WHERE `id_user`<>{$_SESSION["id_user"]} AND (`username` LIKE '%{$s}%' OR `nama` LIKE '%{$s}%')");
	$row = mysqli_fetch_assoc($query);
	$result["recordsFiltered"] = intval($row["filtered"]);
	
	$query = mysqli_query($conn, "SELECT * FROM `tb_user` WHERE `id_user`<>{$_SESSION["id_user"]} AND (`username` LIKE '%{$s}%' OR `nama` LIKE '%{$s}%') ORDER BY {$o[0]} {$o[1]} LIMIT {$p},{$e}");
	
	$result["data"] = array();
	$baris = 0;
	
	while($row = mysqli_fetch_assoc($query)) {
		$tombol = array(
			"<button class='btn btn-success btn-xs' onclick='edit({$baris});'>Edit</button>",
			"<button class='btn btn-danger btn-xs' onclick='konfirmasi({$baris});'>Delete</button>"
		);
		$result["data"][] = array(
			$row["id_user"],
			htmlspecialchars($row["nama"]),
			htmlspecialchars($row["username"]),
			intval($row["level"]) === 1 ? "Admin" : "Pimpinan",
			"<center><div style='width:90px;'>{$tombol[0]}&nbsp;{$tombol[1]}</div></center>",
			$row["level"]
		);
		$baris++;
	}
	echo json_encode($result);
?>