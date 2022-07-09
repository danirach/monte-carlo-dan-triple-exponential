<?php
include "../../koneksi.php";
$acuan = intval($_POST["acuan"]);
$periode = intval($_POST["periode"]);
$tahun = intval($_POST["tahun"]);
$alpha = floatval($_POST["alpha"]);
$tahun_mulai = $_POST["tahun_mulai"];

if($acuan === 0) {
	$query = mysqli_query($conn, "SELECT * FROM tb_detail_barang WHERE id_barang='".$_POST["id_barang"]."' AND tahun>=".$tahun_mulai." ORDER BY tahun ASC, periode ASC");
} else {
	$acuan = $acuan == 0 ? "" : " LIMIT ".$acuan;
	$periode = $periode < 10 ? "0".$periode : $periode;
	$query = mysqli_query($conn, "SELECT * FROM (SELECT * FROM tb_detail_barang WHERE id_barang='".$_POST["id_barang"]."' AND CONCAT(tahun,'-',IF(periode<10, CONCAT('0', periode), periode))<'".$tahun."-".$periode."' ORDER BY tahun DESC, periode DESC".$acuan.") AS a ORDER BY tahun ASC, periode ASC");
}

if(mysqli_num_rows($query) < 3) {
	echo "Gagal menyimpan data. Data acuan tidak cukup.";
	exit;
}

$bulan = array(null, "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
$counter = 0;
$arr = array();
while($row = mysqli_fetch_assoc($query)) {
	$kwh = floatval($row["jumlah_pasien"]);
	if($counter == 0) {
		$arr[$counter] = array(
			"periode"	=> $bulan[intval($row["periode"])],
			"tahun"		=> $row["tahun"],
			"kwh"		=> $kwh,
			"s't"		=> $kwh,
			"s''t"		=> $kwh,
			"s'''t"		=> $kwh,
			"at"		=> "",
			"bt"		=> "",
			"ct"		=> "",
			"Ft+m"		=> "",
			"error"		=> "",
			"absolut"	=> "",
			"mape"		=> ""
		);
	} else if($counter == 1) {
		$s1t = (($alpha*$kwh)+((1-$alpha)*$arr[$counter-1]["s't"]));
		$s2t = (($alpha*$s1t)+((1-$alpha)*$arr[$counter-1]["s''t"]));
		$s3t = (($alpha*$s2t)+((1-$alpha)*$arr[$counter-1]["s'''t"]));
		$arr[$counter] = array(
			"periode"	=> $bulan[intval($row["periode"])],
			"tahun"		=> $row["tahun"],
			"kwh"		=> $kwh,
			"s't"		=> $s1t,
			"s''t"		=> $s2t,
			"s'''t"		=> $s3t,
			"at"		=> ((3*$s1t)-(3*$s2t)+$s3t),
			"bt"		=> (0.062*((5.5*$s1t)-(9.2*$s2t)+(3.7*$s3t))),
			"ct"		=> (0.012*($s1t-(2*$s2t)+$s3t)),
			"Ft+m"		=> "",
			"error"		=> "",
			"absolut"	=> "",
			"mape"		=> ""
		);
	} else {
		$s1t = (($alpha*$kwh)+((1-$alpha)*$arr[$counter-1]["s't"]));
		$s2t = (($alpha*$s1t)+((1-$alpha)*$arr[$counter-1]["s''t"]));
		$s3t = (($alpha*$s2t)+((1-$alpha)*$arr[$counter-1]["s'''t"]));
		$ftm = ($arr[$counter-1]["at"]+($arr[$counter-1]["bt"]*1)+(0.5*(pow(($arr[$counter-1]["ct"]*1), 2))));
		$arr[$counter] = array(
			"periode"	=> $bulan[intval($row["periode"])],
			"tahun"		=> $row["tahun"],
			"kwh"		=> $kwh,
			"s't"		=> $s1t,
			"s''t"		=> $s2t,
			"s'''t"		=> $s3t,
			"at"		=> ((3*$s1t)-(3*$s2t)+$s3t),
			"bt"		=> (0.062*((5.5*$s1t)-(9.2*$s2t)+(3.7*$s3t))),
			"ct"		=> (0.012*($s1t-(2*$s2t)+$s3t)),
			"Ft+m"		=> $ftm,
			"error"		=> $kwh-$ftm,
			"absolut"	=> abs($kwh-$ftm),
			"mape"		=> abs($kwh-$ftm)/$kwh
		);
	}
	$counter++;
}

$absolut = 0;
$mape = 0;

foreach($arr as $a) {
	$last_at = floatval($a["at"]);
	$last_bt = floatval($a["bt"]);
	$last_ct = floatval($a["ct"]);
	$absolut += floatval($a["absolut"]);
	$mape += floatval($a["mape"]);
}

$data = array(
	"mad"				=> $absolut/count($arr),
	"mape"				=> ($mape/count($arr))*100,
	"hasil"				=> $last_at+($last_bt*1)+(0.5*(pow(($last_ct*1), 2)))
);

$query = mysqli_query($conn, "INSERT INTO tb_prediksi VALUES (NULL, '".$_POST["id_barang"]."', ".intval($periode).", ".$tahun.", ".intval($_POST["acuan"]).", ".floatval($_POST["alpha"]).", ".$data["mad"].", ".$data["mape"].", ".$data["hasil"].")");

if($query) {
	echo "berhasil";
} else {
	echo "Gagal menyimpan data.";
}