<?php
include "../../koneksi.php";
$minggu = intval($_POST["minggu"]);
$acuan = intval($_POST["acuan"]);
$periode = intval($_POST["periode"]);
$tahun = intval($_POST["tahun"]);
$alpha = floatval($_POST["alpha"]);
$tahun_mulai = $_POST["tahun_mulai"];
$bulan = array(null, "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
$sql = "SELECT * FROM tb_detail_barang WHERE id_barang='".$_POST["id_barang"]."' AND periode=".$periode." AND tahun=".$tahun;
// echo $sql;

$query = mysqli_query($conn, $sql);
if(mysqli_num_rows($query) > 0) {
	echo "Gagal menyimpan data. Data " . $bulan[$periode] . " " . $tahun . " sudah ada.";
	exit;
}

if($acuan === 0) {
	$query = mysqli_query($conn, "SELECT * FROM tb_detail_barang WHERE id_barang='".$_POST["id_barang"]."' AND tahun>=".$tahun_mulai." ORDER BY tahun ASC, periode ASC");
} else {
	$acuan = $acuan == 0 ? "" : " LIMIT ".$acuan;
	$periode = $periode < 10 ? "0".$periode : $periode;
	$sql="SELECT * FROM (SELECT * FROM tb_detail_barang WHERE id_barang='".$_POST["id_barang"]."' AND CONCAT(tahun,'-',IF(periode<10, CONCAT('0', periode), periode))<'".$tahun."-".$periode."' ORDER BY tahun DESC, periode DESC".$acuan.") AS a ORDER BY tahun ASC, periode ASC";
	// echo $sql;
	// exit;
	$query = mysqli_query($conn, $sql);
}

if(mysqli_num_rows($query) < 1) {
	echo "Gagal menyimpan data. Data acuan tidak cukup.";
	exit;
}

$counter = 0;
$arr = array();
$last_at = 0;
$last_bt = 0;
$last_ct = 0;
$absolut = 0;
$mape = 0;

while($row = mysqli_fetch_assoc($query)) {
	$jml_b = floatval($row["jumlah_barang"]);
	if($counter == 0) {
		$arr[$counter] = array(
			"periode"	=> $bulan[intval($row["periode"])],
			"tahun"		=> $row["tahun"],
			"jml_b"		=> $jml_b,
			"s't"		=> $jml_b,
			"s''t"		=> $jml_b,
			"s'''t"		=> $jml_b,
			"at"		=> "",
			"bt"		=> "",
			"ct"		=> "",
			"Ft+m"		=> "",
			"error"		=> "",
			"absolut"	=> "",
			"mape"		=> ""
		);
	} else if($counter == 1) {
		$s1t = (($alpha*$jml_b)+((1-$alpha)*$arr[$counter-1]["s't"]));
		$s2t = (($alpha*$s1t)+((1-$alpha)*$arr[$counter-1]["s''t"]));
		$s3t = (($alpha*$s2t)+((1-$alpha)*$arr[$counter-1]["s'''t"]));
		$arr[$counter] = array(
			"periode"	=> $bulan[intval($row["periode"])],
			"tahun"		=> $row["tahun"],
			"jml_b"		=> $jml_b,
			"s't"		=> $s1t,
			"s''t"		=> $s2t,
			"s'''t"		=> $s3t,
			"at"		=> ((3*$s1t)-(3*$s2t)+$s3t),
			"bt"		=> (($alpha/(2*pow((1-$alpha), 2)))*(((6-(5*$alpha))*$s1t)-((10-(8*$alpha))*$s2t)+((4-(3*$alpha))*$s3t))),
			"ct"		=> ((pow($alpha, 2)/pow((1-$alpha), 2))*($s1t-(2*$s2t)+$s3t)),
			"Ft+m"		=> "",
			"error"		=> "",
			"absolut"	=> "",
			"mape"		=> ""
		);
	} else {
		$s1t = (($alpha*$jml_b)+((1-$alpha)*$arr[$counter-1]["s't"]));
		$s2t = (($alpha*$s1t)+((1-$alpha)*$arr[$counter-1]["s''t"]));
		$s3t = (($alpha*$s2t)+((1-$alpha)*$arr[$counter-1]["s'''t"]));
		$ftm = ($arr[$counter-1]["at"]+($arr[$counter-1]["bt"]*1)+(0.5*(pow(($arr[$counter-1]["ct"]*1), 2))));
		$arr[$counter] = array(
			"periode"	=> $bulan[intval($row["periode"])],
			"tahun"		=> $row["tahun"],
			"jml_b"		=> $jml_b,
			"s't"		=> $s1t,
			"s''t"		=> $s2t,
			"s'''t"		=> $s3t,
			"at"		=> ((3*$s1t)-(3*$s2t)+$s3t),
			"bt"		=> (($alpha/(2*pow((1-$alpha), 2)))*(((6-(5*$alpha))*$s1t)-((10-(8*$alpha))*$s2t)+((4-(3*$alpha))*$s3t))),
			"ct"		=> ((pow($alpha, 2)/pow((1-$alpha), 2))*($s1t-(2*$s2t)+$s3t)),
			"Ft+m"		=> $ftm,
			"error"		=> $jml_b-$ftm,
			"absolut"	=> abs($jml_b-$ftm),
			"mape"		=> abs($jml_b-$ftm)/$jml_b
		);
		$last_at = floatval($arr[$counter]["at"]);
		$last_bt = floatval($arr[$counter]["bt"]);
		$last_ct = floatval($arr[$counter]["ct"]);
		$absolut += floatval($arr[$counter]["absolut"]);
		$mape += floatval($arr[$counter]["mape"]);
	}
	$counter++;
}

$data = array(
	"mad"				=> $absolut/count($arr),
	"mape"				=> ($mape/count($arr))*100,
	"hasil"				=> $last_at+($last_bt*1)+(0.5*(pow(($last_ct*1), 2)))
);

$query = mysqli_query($conn, "INSERT INTO tb_detail_barang VALUES (NULL, '".$_POST["id_barang"]."', ".intval($minggu).", ".intval($periode).", ".$tahun.", ".round($data["hasil"], 0).")");
$query = $query && mysqli_query($conn, "INSERT INTO tb_prediksi VALUES (NULL, '".$_POST["id_barang"]."', ".intval($minggu).", ".intval($periode).", ".$tahun.", ".intval($_POST["acuan"]).", ".floatval($_POST["alpha"]).", ".$data["hasil"].")");

if($query) {
	echo "berhasil";
} else {
	echo "Gagal menyimpan data.";
}