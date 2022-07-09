<select class='form-control pull-right' style='width:140px; background-color: #ecf2f3;' id='input_perhitungan' onchange='ganti_perhitungan();'>
	<option value='1' selected='selected'>Tiap Tahun</option>
	<option value='2'>Tiap Periode</option>
</select>
<div style='font-size:20px;font-weight:bold;padding-top:6px;'>Forecast</div>
<hr/>
<div class='row'>
	<div class='col-sm-8 col-sm-offset-2'>
		<div class='form-group'>
			<label>barang:</label>
			<?php if($_SESSION["level"] != 3) { ?>
			<select class='form-control' id='input_id_barang'>
				<option value='' selected='selected'>-- Pilih barang --</option>
				<?php
					$query = mysqli_query($conn, "SELECT * FROM `tb_barang`");
					while($row = mysqli_fetch_assoc($query)) {
						echo "<option value='".$row["id_barang"]."'>".$row["id_barang"]." - ".htmlspecialchars($row["nama_barang"])."</option>";
					}
				?>
			</select>
			<?php } else { ?>
			<input type='text' class='form-control' value='<?php echo $_SESSION["id_user"]; ?>' id='input_id_barang' disabled='disabled'>
			<?php } ?>
		</div>
		<div class='form-group'>
			<label>Acuan Prediksi:</label>
			<select class='form-control' id='input_acuan'>
				<option value='' selected='selected'>-- Pilih Acuan Prediksi --</option>
				<option value='3'>3 Minggu</option>
				<option value='6'>6 Minggu</option>
				<option value='12'>12 Minggu</option>
				<option value='0'>Data Keseluruhan</option>
			</select>
		</div>
		<div class='row' id='tiap_minggu'>
			<div class='col-sm-3'>
				<div class='form-group'>
					<label>Dari Minggu:</label>
					<select class="form-control" id="input_minggu_dari">
					<option value="1" selected="selected">minggu ke 1</option>
						<option value="5">Minggu ke 2
						<option value="6">Minggu ke 3
						<option value="7">Minggu ke 4

					</select>
				</div>
			</div>
			<div class='col-sm-3'>
				<div class='form-group'>
					<label>Bulan:</label>
					<input type='periode' class='form-control' value='mei' id='input_dari'>
					
				</div>
			</div>
			<div class='col-sm-3'>
				<div class='form-group'>
					<label>Sampai Minggu:</label>
					<select class="form-control" id="input_minggu_sampai">
					<option value="1" selected="selected">minggu ke 1</option>
						<option value="5">Minggu ke 2
						<option value="6">Minggu ke 3
						<option value="7">Minggu ke 4

					</select>
				</div>
			</div>
			<div class='col-sm-3'>
				<div class='form-group'>
					<label>Tahun:</label>
					<input type='number' class='form-control' value='tahun' id='input_sampai'>
				</div>
			</div>
		</div>
		<div class='row' style='display:none;' id='tiap_periode'>
			<div class='col-sm-6'>
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
			</div>
			<div class='col-sm-6'>
				<div class='form-group'>
					<label>Tahun:</label>
					<input type='number' class='form-control' id='input_minggu' value='<?php echo date("Y"); ?>'>
				</div>
			</div>
		</div>
		<div class='form-group'>
			<label>Alpha:</label>
			<input type='number' class='form-control' min='0.1' max='0.9' step='0.1' value='0.1' id='input_alpha'>
		</div>
		<center>
			<div class='form-group'>
				<button class='btn btn-success' style='width:100px;' onclick='proses();' id='tb_proses'>Proses</button>
			</div>
		</center>
	</div>
</div>
<hr/>
<div class='row'>
	<div class='col-sm-12'>
		<div id='hasil_proses'>
			<center><div style='font-size:20px;'>Hasil Proses</div></center>
		</div>
	</div>
</div>
<script type='text/javascript'>
function proses() {
	var perhitungan = $("#input_perhitungan").val();
	var id_barang = $("#input_id_barang").val();
	var acuan = $("#input_acuan").val();
	var dari = $("#input_dari").val();
	var dari_minggu = $("#input_minggu_dari").val();
	var sampai = $("#input_sampai").val();
	var sampai_minggu = $("#input_minggu_sampai").val();
	var periode = $("#input_periode").val();
	var tahun = $("#input_tahun").val();
	var alpha = $("#input_alpha").val();
	if(id_barang == "" || acuan == "" || (perhitungan == "1" && (dari == "" || dari_minggu == "" || sampai == "" || sampai_minggu == "")) || (perhitungan == "2" && (periode == "" || tahun == "")) || alpha == "") {
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
	$("#tb_proses").attr("disabled", "disabled");
	$("#hasil_proses").html("<center><div style='font-size:20px;'>Memuat...</div></center>");
	$.post("halaman/<?php echo $halaman; ?>/proses.php", {"perhitungan": perhitungan, "id_barang": id_barang, "acuan": acuan, "dari_minggu": dari_minggu, "dari": dari, "sampai_minggu": sampai_minggu, "sampai": sampai, "periode": periode, "tahun": tahun, "alpha": alpha}, function(data) {
		$("#hasil_proses").html(data);
	}).error(function() {
		$("#hasil_proses").html("<center><div style='font-size:20px;'>Gagal memproses.</div></center>");
	}).always(function() {
		$("#tb_proses").removeAttr("disabled");
	});
}
function ganti_perhitungan() {
	var perhitungan = $("#input_perhitungan").val();
	if(perhitungan == "1") {
		$("#tiap_tahun").show();
		$("#tiap_periode").hide();
	} else {
		$("#tiap_tahun").hide();
		$("#tiap_periode").show();
	}
}
</script>