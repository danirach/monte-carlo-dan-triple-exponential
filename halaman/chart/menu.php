<div style='font-size:20px;font-weight:bold;padding-top:6px;'>Chart</div>
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
				<option value='0'>Data Keseluruhan</option>
			</select>
		</div>
		<div class='row'>
			<div class='col-sm-3'>
				<div class='form-group'>
					<label>Dari Bulan:</label>
					<select class="form-control" id="input_bulan_dari">
						<option value="1" selected="selected">Januari</option>
						<option value="2">Februari</option>
						<option value="3">Maret</option>
						<option value="4">April</option>
						<option value="5">Mei</option>
						<option value="6">Juni</option>
						<option value="7">Juli</option>
						<option value="8">Agustus</option>
						<option value="9">September</option>
						<option value="10">Oktober</option>
						<option value="11">Nopember</option>
						<option value="12">Desember</option>
					</select>
				</div>
			</div>
			<div class='col-sm-3'>
				<div class='form-group'>
					<label>Tahun:</label>
					<input type='number' class='form-control' value='2014' id='input_dari'>
				</div>
			</div>
			<div class='col-sm-3'>
				<div class='form-group'>
					<label>Sampai Bulan:</label>
					<select class="form-control" id="input_bulan_sampai">
						<option value="1" selected="selected">Januari</option>
						<option value="2">Februari</option>
						<option value="3">Maret</option>
						<option value="4">April</option>
						<option value="5">Mei</option>
						<option value="6">Juni</option>
						<option value="7">Juli</option>
						<option value="8">Agustus</option>
						<option value="9">September</option>
						<option value="10">Oktober</option>
						<option value="11">Nopember</option>
						<option value="12">Desember</option>
					</select>
				</div>
			</div>
			<div class='col-sm-3'>
				<div class='form-group'>
					<label>Tahun:</label>
					<input type='number' class='form-control' value='2016' id='input_sampai'>
				</div>
			</div>
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
		<div id="grafik_mape" style='width:100%;height:400px;border:1px solid #ddd;display:none;'></div>
	</div>
</div>
<script type='text/javascript'>
function proses() {
	var id_barang = $("#input_id_barang").val();
	var acuan = $("#input_acuan").val();
	var dari = $("#input_dari").val();
	var dari_bulan = $("#input_bulan_dari").val();
	var sampai = $("#input_sampai").val();
	var sampai_bulan = $("#input_bulan_sampai").val();
	if(id_barang == "" || acuan == "" || dari_bulan == "" || dari == "" || sampai == "" || sampai_bulan == "") {
		alert("Semua kolom harus terisi.");
		return;
	}
	$("#grafik_mape").hide();
	$("#tb_proses").attr("disabled", "disabled");
	$("#hasil_proses").html("<center><div style='font-size:20px;'>Memuat...</div></center>");
	$.post("halaman/<?php echo $halaman; ?>/proses.php", {"id_barang": id_barang, "acuan": acuan, "dari_bulan": dari_bulan, "dari": dari, "sampai_bulan": sampai_bulan, "sampai": sampai}, function(data) {
		var hasil = $.parseJSON(data);
		if(hasil.status == "berhasil") {
			$("#hasil_proses").html(hasil.isi);
			$("#grafik_mape").show();
			grafik(hasil);
		} else {
			$("#hasil_proses").html(hasil.status);
		}
	}).error(function() {
		$("#hasil_proses").html("<center><div style='font-size:20px;'>Gagal memproses.</div></center>");
	}).always(function() {
		$("#tb_proses").removeAttr("disabled");
	});
}
function grafik(hasil) {
	var dari = $("#input_dari").val();
	var sampai = $("#input_sampai").val();
	Highcharts.chart('grafik_mape', {
		chart: {
			type: 'line'
		},
		title: {
			text: "Grafik MAPE untuk semua alpha menggunakan data tahun "+dari+" - "+sampai
		},
		tooltip: {
			valueSuffix: '%'
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			categories: ["Alpha 0,1", "Alpha 0,2", "Alpha 0,3", "Alpha 0,4", "Alpha 0,5", "Alpha 0,6", "Alpha 0,7", "Alpha 0,8", "Alpha 0,9"]
		},
		yAxis: {
			title: {
				text: 'MAPE'
			}
		},
		series: [
			{"name": "MAPE", "data": hasil.series}
		]
	});
}
</script>