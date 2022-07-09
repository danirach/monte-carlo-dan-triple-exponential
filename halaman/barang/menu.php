<?php if($_SESSION["level"] == 1) { ?>
<button class='btn btn-primary pull-right' onclick='tambah();'><i class='glyphicon glyphicon-plus'></i>&nbsp;Tambah</button>
<?php } ?>
<div style='font-size:20px;font-weight:bold;padding-top:6px;'>Data barang</div>
<hr/>
<div class='table-responsive'>
	<table class='table table-striped table-bordered' style='width:100%;' id='tabel'>
		<thead>
			<tr>
				<th style='width:120px;'>ID barang</th>
				<th>Nama barang</th>
				<?php if($_SESSION["level"] == 1) { ?>
				<th style='width:100px;'>Tool</th>
				<?php } ?>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
</div>
<input type='hidden' id='input_id_barang_lama' style='display:none;'>
<div class='modal fade' id='dlg' role='dialog' aria-hidden='true' data-backdrop='static' data-keyboard='false'>
	<div class='modal-dialog modal-lg'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></button>
				<div class='modal-title' id='judul_input'></div>
			</div>
			<div class='modal-body'>
				<div class='row'>
					<div class='col-md-12'>
						<div class='form-group'>
							<label>ID barang:</label>
							<input type='text' class='form-control'id='input_id_barang' maxlength='16'>
						</div>
						<div class='form-group'>
							<label>Nama barang:</label>
							<input type='text' class='form-control' id='input_nama_barang' maxlength='50'>
						</div>
					</div>
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
				<div class='modal-title'><b><i class='glyphicon glyphicon-trash'></i>&nbsp;Hapus Data barang</b></div>
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
				{"targets": 1, "className": "text-center"}<?php if($_SESSION["level"] == 1) { ?>,
				{"targets": 2, "orderable": false}<?php } ?>
			],
		});
	});
	
	function edit(baris) {
		var kolom = TabelData.row(baris).data();
		$("#input_id_barang_lama").val(kolom[0]);
		$("#input_id_barang").val(kolom[0]);
		$("#input_nama_barang").val($("<div/>").html(kolom[1]).text());
		$("#input_simpan").hide();
		$("#input_update").show();
		$("#judul_input").html("<b><i class='glyphicon glyphicon-pencil'></i>&nbsp;Edit Data barang</b>");
		$("#dlg").modal("show");
	}
	function update() {
		var id_barang_lama = $("#input_id_barang_lama").val();
		var id_barang = $("#input_id_barang").val();
		var nama_barang = $("#input_nama_barang").val();
		if(nama_barang == "") {
			alert("Semua kolom harus terisi.");
			return;
		}
		$(".close").hide();
		$(".btn-tunggu").attr("disabled", "disabled");
		$.post("halaman/<?php echo $halaman; ?>/update.php", {"id_barang_lama": id_barang_lama, "id_barang": id_barang, "nama_barang": nama_barang}, function(data) {
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
		$("#input_id_barang_lama").val(kolom[0]);
		$("#konfirmasi_hapus").html(kolom[1]);
		$("#dlg_hapus").modal("show");
	}
	function hapus() {
		$(".close").hide();
		$(".btn-tunggu").attr("disabled", "disabled");
		$.post("halaman/<?php echo $halaman; ?>/hapus.php", {"id_barang": $("#input_id_barang_lama").val()}, function(data) {
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
		$("#input_id_barang").val("");
		$("#input_nama_barang").val("");
		$("#input_simpan").show();
		$("#input_update").hide();
		$("#judul_input").html("<b><i class='glyphicon glyphicon-plus'></i>&nbsp;Tambah Data barang</b>");
		$("#dlg").modal("show");
	}
	function simpan() {
		var id_barang = $("#input_id_barang").val();
		var nama_barang = $("#input_nama_barang").val();
		if(id_barang == "" || nama_barang == "") {
			alert("Semua kolom harus terisi.");
			return;
		}
		$(".close").hide();
		$(".btn-tunggu").attr("disabled", "disabled");
		$.post("halaman/<?php echo $halaman; ?>/simpan.php", {"id_barang": id_barang, "nama_barang": nama_barang}, function(data) {
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
