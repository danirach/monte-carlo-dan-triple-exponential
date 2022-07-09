<!DOCTYPE html>
<html>

<head>

<meta charset='UTF-8'>
<meta http-equiv='X-UA-Compatible' content='IE=edge'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<title>Login - Sistem Prediksi Jumlah Stok Barang Lampu Di BERKAH GROUP TULUNGAGUNG</title>
<link rel='shortcut icon' href='gambar/favicon.jpg'>
<link rel='stylesheet' type='text/css' href='css/bootstrap.min.css'>

</head>

<body>

<div class='container' style='padding-top:20px;'>

<div class='row'>
	<div class='col-sm-12' style='line-height:60px;'>
		<center>
			<div style='text-shadow: 0px 0px 1px;color: #2a1770;font-weight: bold;font-size:20px;line-height: 1.5em'>Sistem Prediksi Jumlah Stok Barang Lampu Di </br>BERKAH GROUP TULUNGAGUNG
	</div>
</div>
<hr/>
<br/>
<div class='row'>
	<div class='col-md-4 col-sm-offset-4'>
		<center>
			<img src='gambar/vapor.jpg' style='width:160px;'>
		</center><br>
		<form method='post' class="" action=''>
			<div class='form-group' style='text-align:center;'>
				<label class="">Username :</label>
					<input type='username' class='form-control' name='username'>
			</div>
			<div class='form-group' style='text-align:center;'>
				<label class="">Password :</label>
					<input type='password' class='form-control' name='password'>
			</div>
			<div class='form-group'>
					<button type='submit' class='btn btn-primary btn-block'><i class='glyphicon glyphicon-log-in'></i> Login</button>
			</div>
			<?php if(isset($salah) && $salah) { ?>
			<div class='form-group' style='text-align:center;font-weight:bold;color:#ff0000;'>
				Username/Password Salah!
			</div>
			<?php } ?>
		</form>
	</div>
</div>

</div>

</body>

</html>