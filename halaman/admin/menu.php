<button class='btn btn-primary pull-right' onclick='tambah();'><i class='glyphicon glyphicon-plus'></i>&nbsp;Tambah</button>
<div style='font-size:20px;font-weight:bold;padding-top:6px;'>Data Pengguna</div>
<hr/>
<div class='table-responsive'>
	<table class='table table-striped table-bordered' style='width:100%;' id='tabel'>
		<thead>
			<tr>
				<th style='width:80px;'>ID User</th>
				<th>Nama</th>
				<th>Username</th>
				<th style='width:100px;'>Level</th>
				<th style='width:100px;'>Tool</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
</div>
<input type='hidden' id='input_id_user' style='display:none;'>
<div class='modal fade' id='dlg' role='dialog' aria-hidden='true' data-backdrop='static' data-keyboard='false'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></button>
				<div class='modal-title' id='judul_input'></div>
			</div>
			<div class='modal-body'>
				<div class='form-group'>
					<label>Username:</label>
					<input type='text' class='form-control' id='input_username' maxlength='50'>
				</div>
				<div class='form-group'>
					<label>Nama:</label>
					<input type='text' class='form-control' id='input_nama' maxlength='50'>
				</div>
				<div class='form-group'>
					<label>Password:</label>
					<input type='password' class='form-control' id='input_password'>
				</div>
				<div class='form-group'>
					<label>Konfirmasi Password:</label>
					<input type='password' class='form-control' id='input_konfirmasi'>
				</div>
				<div class='form-group'>
					<label>Level:</label>
					<select class='form-control' id='input_level'>
						<option value='' selected='selected'>-- Pilih Level --</option>
						<option value='1'>Admin</option>
						<option value='2'>Pimpinan</option>
					</select>
				</div>
			</div>
			<div class='modal-footer'>
				<button type='button' class='btn btn-default btn-tunggu' onclick='simpan();' id='input_simpan'><i class='glyphicon glyphicon-ok'></i>&nbsp;Simpan</button>
				<button type='button' class='btn btn-default btn-tunggu' onclick='update();' id='input_update'><i class='glyphicon glyphicon-ok'></i>&nbsp;Simpan</button>
				<button type='button' class='btn btn-default btn-tunggu' data-dismiss='modal'><i class='glyphicon glyphicon-remove'></i>&nbsp;Tutup</button>
			</div>
		</div>
	</div>
</div>
<div class='modal fade' id='dlg_hapus' role='dialog' aria-hidden='true' data-backdrop='static' data-keyboard='false'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></button>
				<div class='modal-title'><b><i class='glyphicon glyphicon-trash'></i>&nbsp;Hapus Data Pengguna</b></div>
			</div>
			<div class='modal-body'>
				Anda yakin ingin menghapus "<span id='konfirmasi_hapus'></span>"?
			</div>
			<div class='modal-footer'>
				<button type='button' class='btn btn-default btn-tunggu' onclick='hapus();'><i class='glyphicon glyphicon-ok'></i>&nbsp;Hapus</button>
				<button type='button' class='btn btn-default btn-tunggu' data-dismiss='modal'><i class='glyphicon glyphicon-remove'></i>&nbsp;Tutup</button>
			</div>
		</div>
	</div>
</div>
<script type='text/javascript'>
	var TabelData;
	$(document).ready(function() {
		TabelData = $("#tabel").DataTable({
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "halaman/<?php echo $halaman; ?>/ambil.php",
				"type": "POST"
			},
			"order": [[0, "desc"]],
			"columnDefs": [
				{"targets": 0, "className": "text-center"},
				{"targets": 3, "className": "text-center"},
				{"targets": 4, "orderable": false},
				{"targets": 5, "visible": false}
			],
		});
	});
	function edit(baris) {
		var kolom = TabelData.row(baris).data();
		$("#input_id_user").val(kolom[0]);
		$("#input_username").val($("<div/>").html(kolom[2]).text());
		$("#input_nama").val($("<div/>").html(kolom[1]).text());
		$("#input_level").val(kolom[5]);
		$("#input_password").val("");
		$("#input_konfirmasi").val("");
		$("#input_password").attr("placeholder", "Kosongi jika tidak diganti");
		$("#input_konfirmasi").attr("placeholder", "Kosongi jika tidak diganti");
		$("#input_simpan").hide();
		$("#input_update").show();
		$("#judul_input").html("<b><i class='glyphicon glyphicon-pencil'></i>&nbsp;Edit Data Pengguna</b>");
		$("#dlg").modal("show");
	}
	function update() {
		var id_user = $("#input_id_user").val();
		var username = $("#input_username").val();
		var nama = $("#input_nama").val();
		var level = $("#input_level").val();
		var password = $("#input_password").val();
		var konfirmasi = $("#input_konfirmasi").val();
		if(username == "" || nama == "" || level == "") {
			alert("Semua kolom harus terisi.");
			return;
		}
		if(password != "" || konfirmasi != "") {
			if(password !== konfirmasi) {
				alert("Password tidak cocok.");
				return;
			}
		}
		$(".close").hide();
		$(".btn-tunggu").attr("disabled", "disabled");
		$.post("halaman/<?php echo $halaman; ?>/update.php", {"id_user": id_user, "username": username, "nama": nama, "level": level, "password": password}, function(data) {
			if(data === "berhasil") {
				$("#dlg").modal("hide");
				TabelData.draw(false);
				alert("Berhasil mengubah data.");
			} else {
				alert(data);
			}
		}).fail(function() {
			alert("Gagal mengubah data. Server sedang bermasalah.");
		}).always(function() {
			$(".btn-tunggu").removeAttr("disabled");
			$(".close").show();
		});
	}
	function konfirmasi(baris) {
		var kolom = TabelData.row(baris).data();
		$("#input_id_user").val(kolom[0]);
		$("#konfirmasi_hapus").html(kolom[1]);
		$("#dlg_hapus").modal("show");
	}
	function hapus() {
		$(".close").hide();
		$(".btn-tunggu").attr("disabled", "disabled");
		$.post("halaman/<?php echo $halaman; ?>/hapus.php", {"id_user": $("#input_id_user").val()}, function(data) {
			if(data === "berhasil") {
				$("#dlg_hapus").modal("hide");
				TabelData.draw(false);
				alert("Berhasil menghapus data.");
			} else {
				alert(data);
			}
		}).fail(function() {
			alert("Gagal menghapus data. Server sedang bermasalah.");
		}).always(function() {
			$(".btn-tunggu").removeAttr("disabled");
			$(".close").show();
		});
	}
	function tambah() {
		$("#input_username").val("");
		$("#input_nama").val("");
		$("#input_password").val("");
		$("#input_konfirmasi").val("");
		$("#input_level").val("");
		$("#input_password").attr("placeholder", "");
		$("#input_konfirmasi").attr("placeholder", "");
		$("#input_simpan").show();
		$("#input_update").hide();
		$("#judul_input").html("<b><i class='glyphicon glyphicon-plus'></i>&nbsp;Tambah Data Pengguna</b>");
		$("#dlg").modal("show");
	}
	function simpan() {
		var username = $("#input_username").val();
		var nama = $("#input_nama").val();
		var level = $("#input_level").val();
		var password = $("#input_password").val();
		var konfirmasi = $("#input_konfirmasi").val();
		if(username == "" || nama == "" || level == "" || password == "" || konfirmasi == "") {
			alert("Semua kolom harus terisi.");
			return;
		}
		if(password !== konfirmasi) {
			alert("Password tidak cocok.");
			return;
		}
		$(".close").hide();
		$(".btn-tunggu").attr("disabled", "disabled");
		$.post("halaman/<?php echo $halaman; ?>/simpan.php", {"username": username, "nama": nama, "level": level, "password": password}, function(data) {
			if(data === "berhasil") {
				$("#dlg").modal("hide");
				TabelData.draw();
				alert("Berhasil menyimpan data.");
			} else {
				alert(data);
			}
		}).fail(function() {
			alert("Gagal menyimpan data. Server sedang bermasalah.");
		}).always(function() {
			$(".btn-tunggu").removeAttr("disabled");
			$(".close").show();
		});
	}
</script>