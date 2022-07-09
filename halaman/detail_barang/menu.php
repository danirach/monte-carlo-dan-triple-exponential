<?php if($_SESSION["level"] == 1) { ?>
<div class='pull-right'>
	<button class='btn btn-warning' onclick='tambah();'><i class='glyphicon glyphicon-plus'></i>&nbsp;Tambah Jumlah barang</button>&nbsp;
</div>
<?php } ?>
<div style='font-size:20px;font-weight:bold;padding-top:6px;'>Detail Barang</div>
<hr/>
<div class='table-responsive'>
	<table class='table table-striped table-bordered' style='width:100%;' id='tabel'>
		<thead>
			<tr>
				<th style='width:80px;'>NO</th>
				<th style='width:120px;'>ID</th>
				<th>Nama Barang</th>
				<th style='width:140px; '>Minggu ke-</th>
				<th style='width:140px;'>Bulan</th>
				<th style='width:100px;'>Tahun</th>
				<th style='width:100px;'>Jumlah barang</th>
				<?php if($_SESSION["level"] == 1) { ?><th style='width:100px;'>Tool</th><?php } ?>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
</div>
<?php if($_SESSION["level"] == 1) { ?>
<input type='hidden' id='input_id_detail_barang' style='display:none;'>
<div class='modal fade' id='dlg' role='dialog' aria-hidden='true' data-backdrop='static' data-keyboard='false'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></button>
				<div class='modal-title' id='judul_input'></div>
			</div>
			<div class='modal-body'>
				<div class='form-group'>
					<label>barang:</label>
					<select class='form-control' id='input_id_barang'>
						<option value='' selected='selected'>-- Pilih Barang --</option>
						<?php
							$query = mysqli_query($conn, "SELECT * FROM `tb_barang`");
							while($row = mysqli_fetch_assoc($query)) {
								echo "<option value='".$row["id_barang"]."'>".$row["id_barang"]." - ".htmlspecialchars($row["nama_barang"])."</option>";
							}
						?>
					</select>
				</div>

				<div class='form-group'>
					<label>Periode:</label>
					<select class='form-control' id='input_periode'>
						<option value='' selected='selected'>-- Pilih Periode --</option>
						<option value='1'>Januari</option>
						<option value='2'>Februari</option>
						<option value='3'>Maret</option>
						<option value='4'>April</option>
						<option value='5'>Mei</option>
						<option value='6'>Juni</option>
						<option value='7'>Juli</option>
						<option value='8'>Agustus</option>
						<option value='9'>September</option>
						<option value='10'>Oktober</option>
						<option value='11'>November</option>
						<option value='12'>Desember</option>
					</select>
				</div>
				<div class='form-group'>
					<label>Tahun:</label>
					<input type='number' class='form-control' id='input_tahun'>
				</div>
				<div class='form-group' id='tambah_jml_b'>
					<label>Jumlah barang:</label>
					<input type='number' class='form-control' id='input_jumlah_barang' min='0'>
				</div>
				<div class='form-group'>
					<input type='number' class='form-control' id='input_minggu'>
				</div>
				<div id='tambah_forecast'>
					<div class='form-group'>
						<label>Acuan Prediksi:</label>
						<select class='form-control' id='input_acuan' onchange='cek_acuan();'>
							<option value='' selected='selected'>-- Pilih Acuan Prediksi --</option>
							<option value='1'>1 Minggu</option>
							<option value='2'>2 Minggu</option>
							<option value='3'>3 Minggu</option>
							<option value='4'>4 Minggu</option>
							<option value='0'>Data Keseluruhan</option>
						</select>
					</div>
					<div class='form-group' id='show_keseluruhan' style='display:none;'>
						<label>Data Keseluruhan Dimulai Dari Tahun:</label>
						<input type='number' class='form-control' value='2014' id='input_tahun_mulai'>
					</div>
					<div class='form-group'>
						<label>Alpha:</label>
						<input type='number' class='form-control' min='0.1' max='0.9' step='0.1' value='0.1' id='input_alpha'>
					</div>
					<label>* Jumlah barang terisi otomatis</label>
				</div>
			</div>
			<div class='modal-footer'>
				<button type='button' class='btn btn-default btn-tunggu' onclick='simpan_forecast();' id='input_simpan_forecast'><i class='glyphicon glyphicon-ok'></i>&nbsp;Simpan</button>
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
				<div class='modal-title'><b><i class='glyphicon glyphicon-trash'></i>&nbsp;Hapus Detail barang</b></div>
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
<?php } ?>
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
				{"targets": 1, "className": "text-center"},
				{"targets": 3, "visible": false},
				{"targets": 4, "className": "text-center"},
				{"targets": 5, "className": "text-center"},
				{"targets": 6, "className": "text-right"}<?php if($_SESSION["level"] == 1) { ?>,
				{"targets": 7, "orderable": false},
				{"targets": 8, "visible": false}<?php } ?>
			],
		});
	});
	<?php if($_SESSION["level"] == 1) { ?>
	function edit(baris) {
		var kolom = TabelData.row(baris).data();
		$("#input_id_detail_barang").val(kolom[0]);
		$("#input_id_barang").val(kolom[1]);
		$("#input_periode").val(kolom[7][0]);
		$("#input_tahun").val(kolom[4]);
		$("#input_jumlah_barang").val(kolom[7][1]);
		$("#tambah_jml_b").show();
		$("#tambah_forecast").hide();
		$("#input_simpan_forecast").hide();
		$("#input_simpan").hide();
		$("#input_update").show();
		$("#judul_input").html("<b><i class='glyphicon glyphicon-pencil'></i>&nbsp;Edit Detail Pelanggan</b>");
		$("#dlg").modal("show");
	}
	function update() {
		var id_detail_barang = $("#input_id_detail_barang").val();
		var id_barang = $("#input_id_barang").val();
		var periode = $("#input_periode").val();
		var tahun = $("#input_tahun").val();
		var jumlah_barang = $("#input_jumlah_barang").val();
		if(id_barang == "" || periode == "" || tahun == "" || jumlah_barang == "") {
			alert("Semua kolom harus terisi.");
			return;
		}
		if(isNaN(jumlah_barang)) {
			alert("Daya harus angka.");
			return;
		}
		$(".close").hide();
		$(".btn-tunggu").attr("disabled", "disabled");
		$.post("halaman/<?php echo $halaman; ?>/update.php", {"id_detail_barang": id_detail_barang, "id_barang": id_barang, "periode": periode, "tahun": tahun, "jumlah_barang": jumlah_barang}, function(data) {
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
		$("#input_id_detail_barang").val(kolom[0]);
		$("#konfirmasi_hapus").html(kolom[2]);
		$("#dlg_hapus").modal("show");
	}
	function hapus() {
		$(".close").hide();
		$(".btn-tunggu").attr("disabled", "disabled");
		$.post("halaman/<?php echo $halaman; ?>/hapus.php", {"id_detail_barang": $("#input_id_detail_barang").val()}, function(data) {
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
		$("#input_minggu").val("1");
		$("#input_periode").val("");
		$("#input_tahun").val("<?php echo date("Y"); ?>");
		$("#input_jumlah_barang").val("");
		$("#tambah_jml_b").show();
		$("#tambah_forecast").hide();
		$("#input_simpan_forecast").hide();
		$("#input_simpan").show();
		$("#input_update").hide();
		$("#judul_input").html("<b><i class='glyphicon glyphicon-plus'></i>&nbsp;Tambah Detail barang</b>");
		$("#dlg").modal("show");
	}
	function simpan() {
		var id_barang = $("#input_id_barang").val();
		var minggu = $("#input_minggu").val();
		var periode = $("#input_periode").val();
		var tahun = $("#input_tahun").val();
		var jumlah_barang = $("#input_jumlah_barang").val();
		if(id_barang == "" || periode == "" || tahun == "" || jumlah_barang == "") {
			alert("Semua kolom harus terisi.");
			return;
		}
		if(isNaN(jumlah_barang)) {
			alert("Data harus angka.");
			return;
		}
		$(".close").hide();
		$(".btn-tunggu").attr("disabled", "disabled");
		$.post("halaman/<?php echo $halaman; ?>/simpan.php", {"id_barang": id_barang, "minggu": minggu,"periode": periode, "tahun": tahun, "jumlah_barang": jumlah_barang}, function(data) {
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
	function tambah_forecast() {
		$("#input_id_barang").val("");
		$("#input_periode").val("");
		$("#input_tahun").val("<?php echo date("Y"); ?>");
		$("#input_acuan").val("");
		$("#input_tahun_mulai").val("2014");
		$("#input_alpha").val("0.1");
		$("#tambah_jml_b").hide();
		$("#tambah_forecast").show();
		$("#input_simpan_forecast").show();
		$("#input_simpan").hide();
		$("#input_update").hide();
		$("#show_keseluruhan").hide();
		$("#judul_input").html("<b><i class='glyphicon glyphicon-plus'></i>&nbsp;Tambah Detail barang (Forecast)</b>");
		$("#dlg").modal("show");
	}
	function simpan_forecast() {
		var id_barang = $("#input_id_barang").val();
		var minggu = $("#input_minggu").val();
		var acuan = $("#input_acuan").val();
		var periode = $("#input_periode").val();
		var tahun = $("#input_tahun").val();
		var tahun_mulai = $("#input_tahun_mulai").val();
		var alpha = $("#input_alpha").val();
		if(id_barang == "" || periode == "" || tahun == "" || acuan == "" || alpha == "" || (acuan == "0" && tahun_mulai == "")) {
			alert("Semua kolom harus terisi.");
			return;
		}
		if(isNaN(alpha)) {
			alert("Alpha harus angka.");
			return;
		}
		if(alpha < 0) {
			alert("Alpha tidak boleh kurang dari 0,1.");
			return;
		}
		if(alpha > 0.9) {
			alert("Alpha tidak boleh lebih dari 0,9.");
			return;
		}
		$(".close").hide();
		$(".btn-tunggu").attr("disabled", "disabled");
		$.post("halaman/<?php echo $halaman; ?>/simpan_forecast.php", {"id_barang": id_barang, "minggu": minggu, "periode": periode, "tahun": tahun, "acuan": acuan, "tahun_mulai": tahun_mulai, "alpha": alpha}, function(data) {
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
	function cek_acuan() {
		var acuan = $("#input_acuan").val();
		if(acuan == "0") {
			$("#show_keseluruhan").show();
		} else {
			$("#show_keseluruhan").hide();
		}
	}
	<?php } ?>
	document.getElementById("input_minggu").style.visibility = "hidden";
</script>