<div style='font-size:20px;font-weight:bold;padding-top:6px;'>Laporan</div>
<hr/>
<div class='table-responsive'>
	<table class='table table-striped table-bordered' style='width:100%;' id='tabel'>
		<thead>
			<tr>
				<th style='width:80px;'>NO</th>
				<th style='width:100px;'>ID barang</th>
				<th>Nama barang</th>
				<th style='width:120px;'>Periode</th>
				<th style='width:100px;'>Tahun</th>
				<th style='width:80px;'>Acuan</th>
				<th style='width:70px;'>Alpha</th>
				<th style='width:100px;'>Hasil (barang)</th>
				<th style='width:100px;'>Tool</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
</div>
<input type='hidden' id='input_id_forecasting' style='display:none;'>
<div class='modal fade' id='dlg_cetak' role='dialog' aria-hidden='true' data-backdrop='static' data-keyboard='false'>
	<div class='modal-dialog modal-lg'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'><i class='glyphicon glyphicon-remove'></i></button>
				<div class='modal-title'><b><i class='glyphicon glyphicon-print'></i>&nbsp;Cetak Laporan</b></div>
			</div>
			<div class='modal-body'>
				<div style='border:1px solid black;width:100%;' id='show_print'>
					<center>
						<h4>Laporan Forecasting Jumlah barang Penjualan Liquid Freebase dan Salt di CV.Gressvape Balongpanggang :</h4><br/>
						<div style='border:1px solid black;width:300px;font-size:18px;' id='show_id_barang'>-</div><br/>
						<div style='border:1px solid black;width:300px;font-size:18px;' id='show_nama_barang'>-</div><br/>
						<h4 id='show_hasil'></h4>
					</center>
				</div>
			</div>
			<div class='modal-footer'>
				<button type='button' class='btn btn-default btn-tunggu' onclick='cetak_laporan();'><i class='glyphicon glyphicon-print'></i>&nbsp;Cetak</button>
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
				{"targets": 1, "className": "text-center"},
				{"targets": 3, "className": "text-center"},
				{"targets": 4, "className": "text-center"},
				{"targets": 5, "className": "text-center"},
				{"targets": 6, "className": "text-right"},
				{"targets": 7, "className": "text-right"},
				{"targets": 8, "orderable": false}
			],
		});
	});
	function cetak(baris) {
		var kolom = TabelData.row(baris).data();
		$("#show_id_barang").html(kolom[1]);
		$("#show_nama_barang").html(kolom[2]);
		$("#show_hasil").html("Forecast Jumlah barang Pada Periode " + kolom[3] + " " + kolom[4] + " Diperkirakan " + kolom[7] + " barang ");
		$("#dlg_cetak").modal("show");
	}
	function cetak_laporan() {
		$("#show_print").print();
	}
</script>