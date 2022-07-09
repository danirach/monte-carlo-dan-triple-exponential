<?php
include "../../koneksi.php";
$acuan = intval($_POST["acuan"]);
$dari = $_POST["dari"];
$dari_bulan = intval($_POST["dari_bulan"]) < 10 ? "0".$_POST["dari_bulan"] : $_POST["dari_bulan"];
$sampai = $_POST["sampai"];
$sampai_bulan = intval($_POST["sampai_bulan"]) < 10 ? "0".$_POST["sampai_bulan"] : $_POST["sampai_bulan"];

$bulan = array(null, "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

$query = mysqli_query($conn, "SELECT * FROM tb_detail_barang WHERE id_barang='".$_POST["id_barang"]."' AND CONCAT(tahun, '-', IF(periode<10, CONCAT('0', periode), periode), '-01') BETWEEN '".$dari."-".$dari_bulan."-01' AND '".$sampai."-".$sampai_bulan."-01' ORDER BY tahun ASC, periode ASC");	

//$query = mysqli_query($conn, "SELECT * FROM tb_detail_barang WHERE id_barang='".$_POST["id_barang"]."' AND (periode>=$dari_bulan AND tahun>=$dari) AND (periode<=$sampai_bulan AND tahun<=$sampai) ORDER BY tahun ASC, periode ASC");	

if(mysqli_num_rows($query) < 3) {
	echo json_encode(array("status"=>"<center><div style='font-size:20px;'>Data acuan tidak cukup.</div></center>"));
	exit;
}

$query2 = mysqli_query($conn, "SELECT nama_barang FROM tb_barang WHERE id_barang='".$_POST["id_barang"]."'");	

if($query2 && mysqli_num_rows($query2) > 0) {
	$row2 = mysqli_fetch_assoc($query2);
	$nama_barang = htmlspecialchars($row2["nama_barang"]);
} else {
	$nama_barang = "<center>-</center>";
}

$xt = array();
$arr = array();
$hasil = array();

if($acuan === 0) {
	while($row = mysqli_fetch_assoc($query)) {
		$xt[] = floatval($row["jumlah_barang"]);
	}
	
	for($alpha = 0.1; $alpha < 0.9; $alpha+=0.1) {
		$absolut = 0;
		$mape = 0;
		$counter = 0;
		foreach($xt as $jml_p) {
			if($counter == 0) {
				$arr[$counter] = array(
					"jml_p"		=> $jml_p,
					"s't"		=> $jml_p,
					"s''t"		=> $jml_p,
					"s'''t"		=> $jml_p,
					"at"		=> "",
					"bt"		=> "",
					"ct"		=> "",
					"Ft+m"		=> "",
					"error"		=> "",
					"absolut"	=> "",
					"mape"		=> ""
				);
			} else if($counter == 1) {
				$s1t = (($alpha*$jml_p)+((1-$alpha)*$arr[$counter-1]["s't"]));
				$s2t = (($alpha*$s1t)+((1-$alpha)*$arr[$counter-1]["s''t"]));
				$s3t = (($alpha*$s2t)+((1-$alpha)*$arr[$counter-1]["s'''t"]));
				$arr[$counter] = array(
					"jml_p"		=> $jml_p,
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
				$s1t = (($alpha*$jml_p)+((1-$alpha)*$arr[$counter-1]["s't"]));
				$s2t = (($alpha*$s1t)+((1-$alpha)*$arr[$counter-1]["s''t"]));
				$s3t = (($alpha*$s2t)+((1-$alpha)*$arr[$counter-1]["s'''t"]));
				$ftm = ($arr[$counter-1]["at"]+($arr[$counter-1]["bt"]*1)+(0.5*(pow(($arr[$counter-1]["ct"]*1), 2))));
				$arr[$counter] = array(
					"jml_p"		=> $jml_p,
					"s't"		=> $s1t,
					"s''t"		=> $s2t,
					"s'''t"		=> $s3t,
					"at"		=> ((3*$s1t)-(3*$s2t)+$s3t),
					"bt"		=> (($alpha/(2*pow((1-$alpha), 2)))*(((6-(5*$alpha))*$s1t)-((10-(8*$alpha))*$s2t)+((4-(3*$alpha))*$s3t))),
					"ct"		=> ((pow($alpha, 2)/pow((1-$alpha), 2))*($s1t-(2*$s2t)+$s3t)),
					"Ft+m"		=> $ftm,
					"error"		=> $jml_p-$ftm,
					"absolut"	=> abs($jml_p-$ftm),
					"mape"		=> abs($jml_p-$ftm)/$jml_p
				);
				$absolut += floatval($arr[$counter]["absolut"]);
				$mape += floatval($arr[$counter]["mape"]);
			}
			$counter++;
		}
		
		$hasil[] = array(
			"alpha"			=> $alpha,
			"|error|"		=> $absolut,
			"|error|/xt"	=> $mape,
			"mad"			=> $absolut/count($arr),
			"mape"			=> $mape/count($arr)
		);
	}


} else {


	$arr2 = array();
	while($row = mysqli_fetch_assoc($query)) {
		$xt[] = floatval($row["jumlah_barang"]);
		$arr2[] = array(
			"jml_p"		=> floatval($row["jumlah_barang"]),
			"Ft+m"		=> "",
			"error"		=> "",
			"absolut"	=> "",
			"mape"		=> ""
		);
	}

	$jml_xt = count($xt);
	$jml = $jml_xt - ($acuan + 1);
	
	for($alpha = 0.1; $alpha < 0.9; $alpha+=0.1) {
		$acn = $acuan;

		for($i = 0; $i <= $jml; $i++) {
			$counter = 0;
			$last_at = 0;
			$last_bt = 0;
			$last_ct = 0;
			$absolut = 0;
			$mape = 0;
			for($j = $i; $j < ($i + $acuan); $j++) {
				$jml_p = $xt[$j];
				if($counter == 0) {
					$arr[$counter] = array(
						"jml_p"		=> $jml_p,
						"s't"		=> $jml_p,
						"s''t"		=> $jml_p,
						"s'''t"		=> $jml_p,
						"at"		=> "",
						"bt"		=> "",
						"ct"		=> "",
						"Ft+m"		=> "",
						"error"		=> "",
						"absolut"	=> "",
						"mape"		=> ""
					);
				} else if($counter == 1) {
					if($counter == 0) {
						$s1t = $jml_p;
						$s2t = $jml_p;
						$s3t = $jml_p;
					} else {
						$s1t = (($alpha*$jml_p)+((1-$alpha)*$arr[$counter-1]["s't"]));
						$s2t = (($alpha*$s1t)+((1-$alpha)*$arr[$counter-1]["s''t"]));
						$s3t = (($alpha*$s2t)+((1-$alpha)*$arr[$counter-1]["s'''t"]));
						$at = ((3*$s1t)-(3*$s2t)+$s3t);
						$bt = (($alpha/(2*pow((1-$alpha), 2)))*(((6-(5*$alpha))*$s1t)-((10-(8*$alpha))*$s2t)+((4-(3*$alpha))*$s3t)));
						$ct = ((pow($alpha, 2)/pow((1-$alpha), 2))*($s1t-(2*$s2t)+$s3t));
					}
					$arr[$counter] = array(
						"jml_p"		=> $jml_p,
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
					$s1t = (($alpha*$jml_p)+((1-$alpha)*$arr[$counter-1]["s't"]));
					$s2t = (($alpha*$s1t)+((1-$alpha)*$arr[$counter-1]["s''t"]));
					$s3t = (($alpha*$s2t)+((1-$alpha)*$arr[$counter-1]["s'''t"]));
					$ftm = ($arr[$counter-1]["at"]+($arr[$counter-1]["bt"]*1)+(0.5*(pow(($arr[$counter-1]["ct"]*1), 2))));
					$arr[$counter] = array(
						"jml_p"		=> $jml_p,
						"s't"		=> $s1t,
						"s''t"		=> $s2t,
						"s'''t"		=> $s3t,
						"at"		=> ((3*$s1t)-(3*$s2t)+$s3t),
						"bt"		=> (($alpha/(2*pow((1-$alpha), 2)))*(((6-(5*$alpha))*$s1t)-((10-(8*$alpha))*$s2t)+((4-(3*$alpha))*$s3t))),
						"ct"		=> ((pow($alpha, 2)/pow((1-$alpha), 2))*($s1t-(2*$s2t)+$s3t)),
						"Ft+m"		=> $ftm,
						"error"		=> $jml_p-$ftm,
						"absolut"	=> abs($jml_p-$ftm),
						"mape"		=> abs($jml_p-$ftm)/$jml_p
					);
					$last_at = floatval($arr[$counter]["at"]);
					$last_bt = floatval($arr[$counter]["bt"]);
					$last_ct = floatval($arr[$counter]["ct"]);
					$absolut += floatval($arr[$counter]["absolut"]);
					$mape += floatval($arr[$counter]["mape"]);
				}
				$counter++;
			}
			
			if($acn < $jml_xt) {
				$arr2[$acn]["Ft+m"] = ($last_at+($last_bt*1)+(0.5*(pow(($last_ct*1), 2))));
				$arr2[$acn]["error"] = $arr2[$acn]["jml_p"] - $arr2[$acn]["Ft+m"];
				$arr2[$acn]["absolut"] = abs($arr2[$acn]["error"]);
				$arr2[$acn]["mape"] = $arr2[$acn]["absolut"]/$arr2[$acn]["jml_p"];
				$acn++;
			}
		}
		
		$absolut = 0;
		$mape = 0;
		
		foreach($arr2 as $a) {
			$absolut += floatval($a["absolut"]);
			$mape += floatval($a["mape"]);
		}
		
		$hasil[] = array(
			"alpha"			=> $alpha,
			"|error|"		=> $absolut,
			"|error|/xt"	=> $mape,
			"mad"			=> $absolut/(count($arr2)-$acuan),
			"mape"			=> $mape/(count($arr2)-$acuan)
		);
	}
}

$arr_json = array();
$arr_json["series"] = array();
$arr_json["isi"] = "
<div class='table-responsive'>
	<table class='table table-striped table-bordered' style='width:100%;' id='tabel'>
		<thead>
			<tr>
				<th style='width:160px;'>ID barang</th>
				<th>Nama barang</th>
				<th style='width:80px;'>Alpha</th>
				<th style='width:120px;'>Tahun</th>
				<th style='width:120px;'>Acuan</th>
				<th style='width:100px;'>MAD</th>
				<th style='width:100px;'>MAPE</th>
			</tr>
		</thead>
		<tbody>
";
foreach($hasil as $h) {
	$arr_json["isi"] .= "
			<tr>
				<td style='text-align:center;'>".$_POST["id_barang"]."</td>
				<td>".$nama_barang."</td>
				<td style='text-align:center;'>".$h["alpha"]."</td>
				<td style='text-align:center;'>".$dari." - ".$sampai."</td>
				<td style='text-align:center;'>".($acuan === 0 ? "Semua" : $acuan." minggu")."</td>
				<td style='text-align:right;'>".number_format($h["mad"], 3, ",", ".")."</td>
				<td style='text-align:right;'>".number_format(($h["mape"]*100), 3, ",", ".")."%</td>
	";
	$arr_json["series"][] = round(($h["mape"] * 100), 3);
}
$arr_json["isi"] .= "
		</tbody>
	</table>
</div>
";
$arr_json["status"] = "berhasil";
echo json_encode($arr_json);