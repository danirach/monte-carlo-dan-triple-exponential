<div style='font-size:20px;font-weight:bold;padding-top:6px;'>Monte</div>
<div class="row">
    <div class="col-sm-8 col-sm-offset-2">
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
    <div class="form-group">
    <label>Tahun</label>
    <input type="number" name="tahun" id="tahun" min="2018" value="2018"  class="form-control" required>
</div>
<div class="form-group">
    <label>A</label>
    <input type="number" name="a" id="a" min="0"   class="form-control" required>
</div>
<div class="form-group">
    <label>C</label>
    <input type="number" name="c" id="c" min="0"   class="form-control" required>
</div>
<div class="form-group">
    <label>M</label>
    <input type="number" name="m" id="m" min="0"   class="form-control" required>
</div>
<div class="form-group">
    <label>z0/X</label>
    <input type="number" name="z" id="z" min="0"   class="form-control" required>
</div>
<div class='form-group'>
	<button class='btn btn-success' style='width:100px;' onclick='proses();' id='tb_proses'>Proses</button>
</div>
</div>
</div>
<div class='row'>
	<div class='col-sm-12'>
		<div id='hasil_proses'>
			<center><div style='font-size:20px;'>Hasil Proses</div></center>
		</div>
	</div>
</div>

<script type='text/javascript'>
    function proses() {
    var barang = $("#input_id_barang").val();
	var tahun = $("#tahun").val();
    var a = $("#a").val();
    var c = $("#c").val();
    var m = $("#m").val();
    var z = $("#z").val();
	$("#tb_proses").attr("disabled", "disabled");
	$("#hasil_proses").html("<center><div style='font-size:20px;'>Memuat...</div></center>");
	$.post("halaman/<?php echo $halaman; ?>/proses.php", {"barang": barang,"tahun": tahun,"a": a,"c": c,"m": m,"z": z,}, function(data) {
		$("#hasil_proses").html(data);
	}).error(function() {
		$("#hasil_proses").html("<center><div style='font-size:20px;'>Gagal memproses.</div></center>");
	}).always(function() {
		$("#tb_proses").removeAttr("disabled");
	});
}
</script>