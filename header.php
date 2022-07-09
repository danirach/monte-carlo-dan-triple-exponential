<?php
if($halaman != "") {
	$title = ucfirst(str_replace("-", " ", $halaman)) . " - ";
} else {
	$title = "";
}
?><!DOCTYPE html>
<html>

<head>

<meta charset='UTF-8'>
<meta http-equiv='X-UA-Compatible' content='IE=edge'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<title><?php echo $title; ?>Sistem Prediksi Jumlah Stok Barang Lampu Di BERKAH GROUP TULUNGAGUNG </title>
<link rel='shortcut icon' href='gambar/vapor.jpg'>
<link rel='stylesheet' type='text/css' href='css/bootstrap.min.css'>
<link rel='stylesheet' type='text/css' href='css/bootstrap-theme.min.css'>
<link rel='stylesheet' type='text/css' href='css/highcharts.css'>
<link rel='stylesheet' type='text/css' href='css/datatables.min.css'>
<link rel='stylesheet' type='text/css' href='css/custom.css'>
<script type='text/javascript' src='js/jquery-1.12.4.min.js'></script>
<script type='text/javascript' src='js/jquery.print.js'></script>
<script type='text/javascript' src='js/bootstrap.min.js'></script>
<script type='text/javascript' src='js/datatables.min.js'></script>
<script type='text/javascript' src='js/highcharts.js'></script>
<script type='text/javascript' src='js/exporting.js'></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<nav class='navbar navbar-default navbar-fixed-top'>
	<div class='container-fluid'>
		<div class='navbar-header'>
			<a href='#'>
				<img src='gambar/vapor.jpg' class='gambar-navbar'>
				
			</a>
			<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>
				<span class='sr-only'>Toggle navigation</span>
				<span class='icon-bar'></span>
				<span class='icon-bar'></span>
				<span class='icon-bar'></span>
			</button>
		</div>
		<div id='navbar' class='navbar-collapse collapse' aria-expanded='false'>
			<center style="text-shadow:0px 0px 1px;color: #2a1770;font-weight: bold; font-size: 20px"><br>
			Sistem Prediksi Jumlah Stok Barang Lampu Di BERKAH GROUP TULUNGAGUNG
			</center>
			<ul class='nav navbar-nav side-nav'>
				<li class='<?php echo $halaman === "pengaturan" ? "active" : ""; ?>'>
					<a href='pengaturan'>
						<table cellspacing='0' cellpadding='0' border='0'>
							<tr>
								<td style='width:60px;'><img src='gambar/user.png' style='width:50px;'></td>
								<td><b><?php echo $_SESSION["nama"]; ?></b><br/><i style='font-size:11px'><img src="gambar/circle.png" width="10px"> Sebagai <?php echo $_SESSION["level"] === 1 ? "Admin" : "Pimpinan" ; ?></i></td>
							</tr>
						</table>
					</a>
				</li>
				<li class='<?php echo $halaman === "" ? "active" : ""; ?>'><a href='./'><i class='glyphicon glyphicon-home'></i>  Home</a></li>
				<?php if($_SESSION["level"] === 1 || $_SESSION["level"] === 2) { ?>
				<li class='<?php echo $halaman === "barang" ? "active" : ""; ?>'><a href='barang'><i class='glyphicon glyphicon-th-list'></i> Barang</a></li>
				<?php } ?>
				<li class='<?php echo $halaman === "detail_barang" ? "active" : ""; ?>'><a href='detail_barang'><i class='glyphicon glyphicon-th-list'></i> Detail Barang</a></li>
				<li class='<?php echo $halaman === "forecast" ? "active" : ""; ?>'><a href='forecast'><i class='glyphicon glyphicon-list-alt'></i> Forecast</a></li>
				<li class='<?php echo $halaman === "monte" ? "active" : ""; ?>'><a href='monte'><i class='glyphicon glyphicon-list-alt'></i> Monte</a></li>
				<li class='side-nav-padding'></li>
			</ul>
			<ul class='nav navbar-nav navbar-right' style="margin-top: -80px;">
				<li>
					<a href='logout' style='padding:7px 25px;'class='btn btn-default tombol-logout'><i class='glyphicon glyphicon-log-out'></i> Logout</a>
				</li>
				<li class='right-nav-padding'></li>
			</ul>
		</div>
	</div>
</nav>

<div class='wrapper'>