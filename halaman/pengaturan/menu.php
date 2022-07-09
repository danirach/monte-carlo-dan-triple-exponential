<button class='btn btn-success pull-right' id='btn-simpan' onclick='update();'><i class='glyphicon glyphicon-floppy-disk'></i>&nbsp;Simpan</button>
<div style='font-size:20px;font-weight:bold;padding-top:6px;'>Pengaturan Akun</div>
<hr/>
<div class='form-group'>
	<label>Username:</label>
	<input type='text' class='form-control' value="<?php echo htmlspecialchars($_SESSION["username"]); ?>" id='input_username' maxlength='32'>
</div>
<div class='form-group'>
	<label>Nama:</label>
	<input type='text' class='form-control' value="<?php echo $_SESSION["nama"]; ?>" id='input_nama' maxlength='64'>
</div>
<div class='form-group'>
	<label>Password Lama:</label>
	<input type='password' class='form-control' placeholder='Masukkan password lama Anda' id='input_password_lama'>
</div>
<div class='form-group'>
	<label>Password Baru:</label>
	<input type='password' class='form-control' placeholder='Masukkan password baru<?php echo isset($_SESSION["stroke-akses"]) ? " (Kosongi jika tidak diganti)" : ""; ?>' id='input_password_baru'>
</div>
<div class='form-group'>	
	<label>Konfirmasi Password Baru:</label>
	<input type='password' class='form-control' placeholder='Konfirmasi password baru<?php echo isset($_SESSION["stroke-akses"]) ? " (Kosongi jika tidak diganti)" : ""; ?>' id='input_konfirmasi'>
</div>
<script type='text/javascript'>
	function update() {
		var nama = $("#input_nama").val();
		var user = $("#input_username").val();
		var lama = $("#input_password_lama").val();
		var pass = $("#input_password_baru").val();
		var konf = $("#input_konfirmasi").val();
		if(lama == "") {
			alert("Masukkan password lama Anda untuk konfirmasi pengubahan.");
			return;
		}
		if(user == "" || nama == "") {
			alert("Kolom nama harus terisi.");
			return;
		}
		if((pass != "" || konf != "") && pass !== konf) {
			alert("Password tidak cocok.");
			return;
		}
		$("#btn-simpan").attr("disabled", "disabled");
		$.post("halaman/<?php echo $halaman; ?>/update.php", {"user": user, "nama": nama, "pass": pass, "lama": lama}, function(data) {
			if(data === "berhasil") {
				alert("Berhasil mengubah data.");
				location.reload();
			} else {
				alert(data);
				$("#input_password_lama").val("");
				$("#input_password_baru").val("");
				$("#input_konfirmasi").val("");
			}
		}).fail(function() {
			alert("Gagal mengubah data. Server sedang bermasalah.");
			$("#input_password_lama").val("");
			$("#input_password_baru").val("");
			$("#input_konfirmasi").val("");
		}).always(function() {
			$("#btn-simpan").removeAttr("disabled");
		});
	}
</script>