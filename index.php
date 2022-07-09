<?php

session_start();
$halaman = isset($_GET["halaman"]) ? strtolower($_GET["halaman"]) : "";
$masuk = isset($_SESSION["id_user"]) && isset($_SESSION["username"]) && isset($_SESSION["password"]) && isset($_SESSION["nama"]) && isset($_SESSION["level"]);
$salah = false;

include "koneksi.php";

if($halaman == "logout") {
	session_destroy();
	$masuk = false;
	header("location:./");
}

if(isset($_POST["username"]) || isset($_POST["password"])) {
	$user = mysqli_real_escape_string($conn, $_POST["username"]);
	$pass = md5($_POST["password"]);
	$query = mysqli_query($conn, "SELECT * FROM tb_user WHERE `username`='{$user}' AND `password`='{$pass}'");
	if($query && mysqli_num_rows($query) > 0) {
		$masuk = true;
		$row = mysqli_fetch_assoc($query);
		$_SESSION["id_user"] = $row["id_user"];
		$_SESSION["username"] = $row["username"];
		$_SESSION["password"] = $row["password"];
		$_SESSION["nama"] = htmlspecialchars($row["nama"]);
		$_SESSION["level"] = intval($row["level"]);
	} else {
		$masuk = false;
		$salah = true;
	}
}

if($masuk) {
	include("header.php");
	if($halaman != "") {
		if(file_exists("./halaman/{$halaman}/menu.php")) {
			include("./halaman/{$halaman}/menu.php");
		} else {
			echo "<h1>Halaman tidak ditemukan!</h1>";
		}
	} else {
		?>
		<center><div style='font-size:18px;color:#058cd9;'><div style='font-size:20px; font-weight:bold;'>.: SELAMAT DATANG, <?php echo $_SESSION["nama"]; ?> :.</div></br>Sistem Prediksi Jumlah Stok Barang Lampu Di BERKAH GROUP TULUNGAGUNG</div><img src='gambar/vapor.jpg' class='img-responsive' style='width:200px;'><br/></center>";
		<?php
		}
	include("footer.php");
} else {
	include("login.php");
}
