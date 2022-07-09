<?php
session_start();
include "../../koneksi.php";
$perhitungan = intval($_POST["perhitungan"]);
$acuan = intval($_POST["acuan"]);
$alpha = floatval($_POST["alpha"]);
$dari = $_POST["dari"];
$dari_bulan = intval($_POST["dari_bulan"]) < 10 ? "0".$_POST["dari_bulan"] : $_POST["dari_bulan"];
$sampai = $_POST["sampai"];
$sampai_bulan = intval($_POST["sampai_bulan"]) < 10 ? "0".$_POST["sampai_bulan"] : $_POST["sampai_bulan"];
$periode = intval($_POST["periode"]);


$bulan = array(null, "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

if($perhitungan === 1) {
	$query = mysqli_query($conn, "SELECT * FROM tb_detail_barang WHERE id_barang='".$_POST["id_barang"]."' AND CONCAT(tahun, '-', IF(periode<10, CONCAT('0', periode), periode), '-01') BETWEEN '".$dari."-".$dari_bulan."-01' AND '".$sampai."-".$sampai_bulan."-01' ORDER BY tahun ASC, periode ASC, minggu ASC");
} else {
	$limit = $acuan == 0 ? "" : " LIMIT ".$acuan;
	$periode = $periode < 10 ? "0".$periode : $periode;
	$query = mysqli_query($conn, "SELECT * FROM (SELECT * FROM tb_detail_barang WHERE id_barang='".$_POST["id_barang"]."' AND CONCAT(tahun,'-',IF(periode<10, CONCAT('0', periode), periode))<'".$_POST["tahun"]."-".$periode."' ORDER BY tahun DESC, periode DESC".$limit.") AS a ORDER BY tahun ASC, periode ASC, minggu ASC");
}

if(mysqli_num_rows($query) < 3) {
	echo "<center><div style='font-size:20px;'>Data acuan tidak cukup.</div></center>";
	exit;
}

$arr = array();
$counter = 0;

if($perhitungan == 2 || $acuan === 0) {
	while($row = mysqli_fetch_assoc($query)) {
		$jml_p = floatval($row["jumlah_barang"]);
		if($counter == 0) {
			$arr[$counter] = array(
				"periode"	=> $bulan[intval($row["periode"])],
				"minggu"	=> $row["minggu"],
				"tahun"		=> $row["tahun"],
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
				"periode"	=> $bulan[intval($row["periode"])],
				"minggu"	=> $row["minggu"],
				"tahun"		=> $row["tahun"],
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
			$ftm = ($arr[$counter-1]["at"]+($arr[$counter-1]["bt"]*1)+(0.5*(($arr[$counter-1]["ct"]*1))));
			$arr[$counter] = array(
				"periode"	=> $bulan[intval($row["periode"])],
				"minggu"	=> $row["minggu"],
				"tahun"		=> $row["tahun"],
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
				"mape"		=> abs($jml_p-$ftm)/$jml_p,
				"no"		=> $counter
			);
		} 
		$counter++;
	}

	$absolut = 0;
	$mape = 0;
	
	echo "
	<div class='table-responsive'>
		<table class='table table-bordered table-striped' style='width:100%;'>
			<thead>
				<tr>
					<th style='width:140px;'>Periode</th>
					<th style='width:100px;'>Tahun</th>
					<th style='width:100px;'>Jumlah barang(Xt)</th>
					<th>s't</th>
					<th>s''t</th>
					<th>s'''t</th>
					<th>at</th>
					<th>bt</th>
					<th>ct</th>
					".($_SESSION["level"] === 3 ? "" : "<th>Ft+m</th>")."
	";
	if($perhitungan === 1 && $acuan === 0 && $_SESSION["level"] !== 3) {
		echo "
					<th>error</th>
					<th>|error|/xt</th>
		";
	}
	echo "
				</tr>
			</thead>
			<tbody>
	";
	
	$last_arr = count($arr) - 1;

	foreach($arr as $a) {
		if($perhitungan === 1 && $acuan === 0 && $arr[$last_arr] === $a) {
			if($_SESSION["level"] === 3) {
				echo "
					<tr>
						<td colspan='9' style='text-align:center;font-size:18px;'>HASIL FORECAST <b>".$_SESSION["nama"]."</b> PADA PERIODE <b>".$dari."-".$sampai."</b> MENGHASILKAN NILAI ERROR MAD <b>".($a["absolut"] === "" ? "" : number_format($a["absolut"], 3, ",", "."))."</b> dan MAPE <b>".($a["mape"] === "" ? "" : number_format($a["mape"], 3, ",", "."))."</b></td>
					</tr>
				";
			} else {
				echo "
					<tr style='font-weight:bold;'>
						<td>".$a["periode"]."</td>
						<td style='text-align:center;'>".$a["tahun"]."</td>
						<td style='text-align:center;'>".$a["jml_p"]."</td>
						<td style='text-align:right;'>".number_format($a["s't"], 3, ",", ".")."</td>
						<td style='text-align:right;'>".number_format($a["s''t"], 3, ",", ".")."</td>
						<td style='text-align:right;'>".number_format($a["s'''t"], 3, ",", ".")."</td>
						<td style='text-align:right;'>".($a["at"] === "" ? "" : number_format($a["at"], 3, ",", "."))."</td>
						<td style='text-align:right;'>".($a["bt"] === "" ? "" : number_format($a["bt"], 3, ",", "."))."</td>
						<td style='text-align:right;'>".($a["ct"] === "" ? "" : number_format($a["ct"], 3, ",", "."))."</td>
						<td style='text-align:right;'>".($a["Ft+m"] === "" ? "" : number_format($a["Ft+m"], 3, ",", "."))."</td>
						<td style='text-align:right;'>".($a["error"] === "" ? "" : number_format($a["error"], 3, ",", "."))."</td>
						<td style='text-align:right;'>".($a["mape"] === "" ? "" : number_format($a["mape"], 3, ",", "."))."</td>
					</tr>
				";
			}
		} else {
			echo "
				
					<tr>
						<td>".$a["periode"]."</td>
						<td style='text-align:center;'>".$a["tahun"]."</td>
						<td style='text-align:center;'>".$a["jml_p"]."</td>
						<td style='text-align:right;'>".number_format($a["s't"], 3, ",", ".")."</td>
						<td style='text-align:right;'>".number_format($a["s''t"], 3, ",", ".")."</td>
						<td style='text-align:right;'>".number_format($a["s'''t"], 3, ",", ".")."</td>
						<td style='text-align:right;'>".($a["at"] === "" ? "" : number_format($a["at"], 3, ",", "."))."</td>
						<td style='text-align:right;'>".($a["bt"] === "" ? "" : number_format($a["bt"], 3, ",", "."))."</td>
						<td style='text-align:right;'>".($a["ct"] === "" ? "" : number_format($a["ct"], 3, ",", "."))."</td>
						<td style='text-align:right;'>".($a["Ft+m"] === "" ? "" : number_format($a["Ft+m"], 3, ",", "."))."</td>
						<td style='text-align:right;'>".($a["error"] === "" ? "" : number_format($a["error"], 3, ",", "."))."</td>
						<td style='text-align:right;'>".($a["mape"] === "" ? "" : number_format($a["mape"], 3, ",", "."))."</td>
						".($_SESSION["level"] === 3 ? "" : "<td style='text-align:right;'></td>")."
			";
			if($perhitungan === 1 && $acuan === 0 && $_SESSION["level"] !== 3) {
				echo "
						<td></td>
						<td></td>
						<td></td>
				";
			}
			echo "
					</tr>
			";
		}
		$last_at = floatval($a["at"]);
		$last_bt = floatval($a["bt"]);
		$last_ct = floatval($a["ct"]);
		$absolut += floatval($a["absolut"]);
		$mape += floatval($a["mape"]);
	}
	
	if($perhitungan === 2 || $acuan > 0) {
		if($perhitungan === 2) {
			if($_SESSION["level"] === 3) {
				echo "
					<tr>
						<td colspan='10' style='text-align:center;font-size:18px;'>HASIL FORECAST <b>".$_SESSION["nama"]."</b> PADA PERIODE <b>".strtoupper($bulan[intval($periode)])." ".$_POST["tahun"]."</b> ADALAH SEBESAR <b>".number_format(($last_at+($last_bt*1)+(0.5*(pow(($last_ct*1), 2)))), 3, ",", ".")."</b></td>
					</tr>
				";
			} else {
				echo "
					<tr style='font-weight:bold;'>
						<td colspan='9'	style='text-align:center'>Hasil Prediksi (".$bulan[intval($periode)]." ".$_POST["tahun"].")</td>
						<td style='text-align:right;'>".number_format(($last_at+($last_bt*1)+(0.5*(pow(($last_ct*1), 2)))), 3, ",", ".")."</td>
					</tr>
				";
			}
		} else {
			echo "
				<tr>
					<td colspan='10'>&nbsp;</td>
				</tr>
				<tr>
					<td colspan='9' style='text-align:center;font-weight:bold;'>TOTAL |error|</td>
					<td style='text-align:right;font-weight:bold;'>".number_format($absolut, 3, ",", ".")."</td>
				</tr>
				<tr>
					<td colspan='9' style='text-align:center;font-weight:bold;'>TOTAL |error|/xt</td>
					<td style='text-align:right;font-weight:bold;'>".number_format($absolut, 3, ",", ".")."</td>
				</tr>
				<tr>
					<td colspan='9' style='text-align:center;font-weight:bold;'>MAD</td>
					<td style='text-align:right;font-weight:bold;'>".number_format(($absolut/count($arr)), 3, ",", ".")."</td>
				</tr>
				<tr>
					<td colspan='9' style='text-align:center;font-weight:bold;'>MAPE</td>
					<td style='text-align:right;font-weight:bold;'>".number_format((($mape/count($arr))*100), 3, ",", ".")."%</td>
				</tr>
			</tbody>
		</table>
	</div>
			";
		}
	}
} else {
	$arr2 = array();
	$arr3 = array();
	
	while($row = mysqli_fetch_assoc($query)) {
		$arr[] = array(
			"periode"	=> intval($row["periode"]),
			"tahun"		=> $row["tahun"],
			"minggu"	=> $row["minggu"],
			"jml_p"		=> floatval($row["jumlah_barang"])
		);
		$arr3[] = array(
			"periode"	=> $bulan[intval($row["periode"])],
			"tahun"		=> $row["tahun"],
			"jml_p"		=> floatval($row["jumlah_barang"]),
			"Ft+m"		=> "",
			"error"		=> "",
			"absolut"	=> "",
			"mape"		=> ""
		);
	}
	
	$jml = count($arr) - ($acuan + 1);
	$acn = $acuan;
	
	for($i = 0; $i <= $jml; $i++) {
		for($j = $i; $j < ($i + $acuan); $j++) {
			$jml_p = $arr[$j]["jml_p"];
			if($counter == 0) {
				$arr2[$counter] = array(
					"periode"	=> $arr[$j]["periode"],
					"minggu"	=> $arr[$j]["minggu"],
					"tahun"		=> $arr[$j]["tahun"],
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
				$s1t = (($alpha*$jml_p)+((1-$alpha)*$arr2[$counter-1]["s't"]));
				$s2t = (($alpha*$s1t)+((1-$alpha)*$arr2[$counter-1]["s''t"]));
				$s3t = (($alpha*$s2t)+((1-$alpha)*$arr2[$counter-1]["s'''t"]));
				$arr2[$counter] = array(
					"periode"	=> $arr[$j]["periode"],
					"minggu"	=> $arr[$j]["minggu"],
					"tahun"		=> $arr[$j]["tahun"],
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
				$s1t = (($alpha*$jml_p)+((1-$alpha)*$arr2[$counter-1]["s't"]));
				$s2t = (($alpha*$s1t)+((1-$alpha)*$arr2[$counter-1]["s''t"]));
				$s3t = (($alpha*$s2t)+((1-$alpha)*$arr2[$counter-1]["s'''t"]));
				$ftm = ($arr2[$counter-1]["at"]+($arr2[$counter-1]["bt"]*1)+(0.5*(pow(($arr2[$counter-1]["ct"]*1), 2))));
				$arr2[$counter] = array(
					"periode"	=> $arr[$j]["periode"],
					"tahun"		=> $arr[$j]["tahun"],
					"minggu"	=> $arr[$j]["minggu"],
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
			}
			$counter++;
		}
		
		$counter = 0;
		$absolut = 0;
		$mape = 0;
		
		echo "
		<div class='table-responsive'>
			<table class='table table-bordered table-striped' style='width:100%;'>
				<thead>
					<tr>
						<th style='width:140px;'>Periode</th>
						<th style='width:100px;'>Tahun</th>
						<th style='width:100px;'>Jumlah barang(Xt)</th>
						<th>s't</th>
						<th>s''t</th>
						<th>s'''t</th>
						<th>at</th>
						<th>bt</th>
						<th>ct</th>
						<th>Ft+m</th>
					</tr>
				</thead>
				<tbody>
		";
		
		foreach($arr2 as $a) {
			echo "
				
					<tr>
						<td>".$bulan[$a["periode"]]."</td>
						<td style='text-align:center;'>".$a["tahun"]."</td>
						<td style='text-align:center;'>".$a["jml_p"]."</td>
						<td style='text-align:right;'>".number_format($a["s't"], 3, ",", ".")."</td>
						<td style='text-align:right;'>".number_format($a["s''t"], 3, ",", ".")."</td>
						<td style='text-align:right;'>".number_format($a["s'''t"], 3, ",", ".")."</td>
						<td style='text-align:right;'>".($a["at"] === "" ? "" : number_format($a["at"], 3, ",", "."))."</td>
						<td style='text-align:right;'>".($a["bt"] === "" ? "" : number_format($a["bt"], 3, ",", "."))."</td>
						<td style='text-align:right;'>".($a["ct"] === "" ? "" : number_format($a["ct"], 3, ",", "."))."</td>
						<td style='text-align:right;'>".($a["Ft+m"] === "" ? "" : number_format($a["Ft+m"], 3, ",", "."))."</td>
					</tr>
			";
			$last_at = floatval($a["at"]);
			$last_bt = floatval($a["bt"]);
			$last_ct = floatval($a["ct"]);
			$absolut += floatval($a["absolut"]);
			$mape += floatval($a["mape"]);
			$last_periode = intval($a["periode"]);
			$last_tahun = intval($a["tahun"]);
		}
		
		if($acn < count($arr)) {
			$arr3[$acn]["Ft+m"] = ($last_at+($last_bt*1)+(0.5*(pow(($last_ct*1), 2))));
			$arr3[$acn]["error"] = $arr3[$acn]["jml_p"] - $arr3[$acn]["Ft+m"];
			$arr3[$acn]["absolut"] = abs($arr3[$acn]["error"]);
			$arr3[$acn]["mape"] = $arr3[$acn]["absolut"]/$arr3[$acn]["jml_p"];
			$acn++;
		}
		
		if($last_periode === 12) {
			$last_periode = 1;
			$last_tahun++;
		} else {
			$last_periode++;
		}
		
		echo "
					<tr>
						<td colspan='9' style='text-align:center;font-weight:bold;'>Hasil Prediksi Minggu ke - ".$a['minggu']." (".$bulan[$last_periode]." ".$last_tahun.")</td>
						<td style='text-align:right;font-weight:bold;'>".number_format(($last_at+($last_bt*1)+(0.5*(pow(($last_ct*1), 2)))), 3, ",", ".")."</td>
					</tr>
				</tbody>
			</table>
		</div>
		";
	}
	
	echo "
	<hr/>
	<br/>
	<div class='table-responsive'>
		<table class='table table-bordered table-striped' style='width:100%;'>
			<thead>
				<tr>
					<th style='width:140px;'>Periodey</th>
					<th style='width:100px;'>Tahun</th>
					<th style='width:100px;'>jml_p</th>
					<th>Ft+m</th>
					<th>error</th>
					<th>|error|</th>
					<th>|error|/xt</th>
				</tr>
			</thead>
			<tbody>
	";
	
	$absolut = 0;
	$mape = 0;
	
	foreach($arr3 as $a) {
		echo "
			
				<tr>
					<td>".$a["periode"]."</td>
					<td style='text-align:center;'>".$a["tahun"]."</td>
					<td style='text-align:center;'>".$a["jml_p"]."</td>
					<td style='text-align:right;'>".($a["Ft+m"] === "" ? "" : number_format($a["Ft+m"], 3, ",", "."))."</td>
					<td style='text-align:right;'>".($a["error"] === "" ? "" : number_format($a["error"], 3, ",", "."))."</td>
					<td style='text-align:right;'>".($a["absolut"] === "" ? "" : number_format($a["absolut"], 3, ",", "."))."</td>
					<td style='text-align:right;'>".($a["mape"] === "" ? "" : number_format($a["mape"], 3, ",", "."))."</td>
				</tr>
		";
		$absolut += floatval($a["absolut"]);
		$mape += floatval($a["mape"]);
	}
	if($_SESSION["level"] === 3) {
		echo "
				<tr>
					<td colspan='7' style='text-align:center;font-size:18px;'>HASIL FORECAST <b>".$_SESSION["nama"]."</b> PADA PERIODE <b>".$dari."-".$sampai."</b> MENGHASILKAN NILAI ERROR MAD <b>".number_format(($absolut/(count($arr3)-$acuan)), 3, ",", ".")."</b> dan MAPE <b>".number_format((($mape/(count($arr3)-$acuan))*100), 3, ",", ".")."</b></td>
				</tr>
			</tbody>
		</table>
	</div>
		";
	} else {
		echo "
				<tr>
					<td colspan='7'>&nbsp;</td>
				</tr>
				<tr>
					<td colspan='6' style='text-align:center;font-weight:bold;'>TOTAL |error|</td>
					<td style='text-align:right;font-weight:bold;'>".number_format($absolut, 3, ",", ".")."</td>
				</tr>
				<tr>
					<td colspan='6' style='text-align:center;font-weight:bold;'>TOTAL |error|/xt</td>
					<td style='text-align:right;font-weight:bold;'>".number_format($mape, 3, ",", ".")."</td>
				</tr>
				<tr>
					<td colspan='6' style='text-align:center;font-weight:bold;'>MAD</td>
					<td style='text-align:right;font-weight:bold;'>".number_format(($absolut/(count($arr3)-$acuan)), 3, ",", ".")."</td>
				</tr>
				<tr>
					<td colspan='6' style='text-align:center;font-weight:bold;'>MAPE</td>
					<td style='text-align:right;font-weight:bold;'>".number_format((($mape/(count($arr3)-$acuan))*100), 3, ",", ".")."%</td>
				</tr>
			</tbody>
		</table>
	</div>
		";
	}
}
echo "<table class='table table-bordered table-striped'>
<tbody>
	<tr>
		<td align='center'></td>
	</tr>
</tbody>
</table>";
?>
<div class='t' style='width:80%; margin: 0 auto;'>
<center><h3 style='padding:10px'>Chart Forecast</h3></center>
	<canvas id='forecast'></canvas>
</div>
<script>
const labels = [
	<?php
        for ($j=0; $j < count($arr) ; $j++) { 
            echo '"'.$arr[$j]['periode'].'",';
        }    
    ?>
];
  const data = {
    labels: labels,
    datasets: [{
      label: 'Data Aktual',
      backgroundColor: 'rgb(0, 0, 0)',
      borderColor: 'rgb(0, 0, 0)',
      data: [<?php
        for ($k=0; $k < count($arr) ; $k++) { 
            echo '"'.$arr[$k]['jml_p'].'",';
        }
      ?>],
    },
    {
      label: 'Data Peramalan',
      backgroundColor: 'rgb(252, 32, 8)',
      borderColor: 'rgb(252, 32, 8)',
      data: [<?php
        for ($l=0; $l < count($arr) ; $l++) { 
            echo '"'.$arr[$l]['Ft+m'].'",';
        }
      ?>],
    }]
  };

  const config = {
    type: 'line',
    data: data,
    options: {}
  };
  const forecast = new Chart(
    document.getElementById('forecast'),
    config
  );
</script>