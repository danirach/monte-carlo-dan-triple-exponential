<?php
session_start();
include "../../koneksi.php";
$barang = $_POST['barang'];
$tahun = $_POST['tahun'];
$a = $_POST['a'];
$c = $_POST['c'];
$m = $_POST['m'];
$z = $_POST['z'];
$sql = mysqli_query($conn, "SELECT * FROM tb_detail_barang WHERE id_barang='".$barang."' AND tahun='".$tahun."'  ");
$stok = mysqli_query($conn, "SELECT * FROM tb_detail_barang WHERE id_barang='".$barang."' AND tahun='".$tahun."'  ");
$jumlah = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlah_barang) as total FROM tb_detail_barang WHERE id_barang='".$barang."' AND tahun='".$tahun."'  "));
$bulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
?>
    <?php
        $data_probabilitas= array();
        $jumlah_barang= array();
        while($r = mysqli_fetch_array($sql)){
            $probabilitas = $r['jumlah_barang']/$jumlah['total'];
            $data_probabilitas[] = $probabilitas;
            $jumlah_barang[] = $r['jumlah_barang'];
        }
 ?>       
 
<?php 
    $probabilitas_kumlatif = [];
    for ($i=0; $i < count($data_probabilitas) ; $i++) {
        if ($probabilitas_kumlatif==[]) {
            $bilangan = $data_probabilitas[0];
        }else {
            $bilangan = $probabilitas_kumlatif[$i-1] + $data_probabilitas[$i];
        }
        array_push($probabilitas_kumlatif,$bilangan);       
    };
?>

<?php
    $zi = [];
    for ($d=0; $d < count($jumlah_barang); $d++) { 
        if ($zi == []) {
            $bilangan2 = (($a*$z)+$c)%$m;
        }else {
            $bilangan2 = (($a*$zi[$d-1])+$c)%$m;
        }
        array_push($zi,$bilangan2);
    }
?>

<?php
    $ui = [];
    for ($e=0; $e < count($jumlah_barang); $e++) { 
        $bilangan3 = $zi[$e]/$m;
        array_push($ui,$bilangan3);
    }
?>

 <?php
    $hasil_peramalan = [];
    for ($f=0; $f < count($jumlah_barang) ; $f++) {
        for ($g=0; $g < count($jumlah_barang) ; $g++) { 
            if ($ui[$f]< $probabilitas_kumlatif[0]) {
                $bilangan4 = $jumlah_barang[0];
            }
            elseif ($ui[$f] > $probabilitas_kumlatif[$g] ) {
             $bilangan4 = $jumlah_barang[$g+1];   
            } 
        }
        array_push($hasil_peramalan,$bilangan4);
    }
?> 
<?php
    $mae = [];
    for ($k=0; $k < count($jumlah_barang); $k++) { 
        $bilangan6 = $hasil_peramalan[$k] - $jumlah_barang[$k];
        array_push($mae,$bilangan6);
    }
?>
<table class="table table-bordered">
    <thead>
    <tr>
        <td>No</td>
        <td>Stok</td>
        <td>DISTRIBUSI PROBABILITAS </td>
        <td>DISTRIBUSI PROBABILITAS KUMLATIF</td>
        <td>INTERVAL ANGKA ACAK</td>
        <td>ZI</td>
        <td>UI</td>
        <td>Hasil Peramalan</td>
        <td>MAE</td>
        <td>|MAE|</td>
    </tr>
    </thead>
    <tbody>
        <?php
            for ($x=0; $x < count($jumlah_barang) ; $x++) { 
                ?>
                <tr>
                    <td><?php echo $x+1 ?></td>
                    <td><?php echo $jumlah_barang[$x]?></td>
                    <td><?php echo round($data_probabilitas[$x],3)?></td>
                    <td><?php echo round($probabilitas_kumlatif[$x],3)?></td>
                    <?php
                        if (isset($probabilitas_kumlatif[$x-1])) {
                            ?>
                            <td><?php echo round($probabilitas_kumlatif[$x-1],3) ?> - <?php echo round($probabilitas_kumlatif[$x],3) ?></td>
                        <?php
                    }else {
                        ?>
                        <td><?php echo 0 ?> - <?php echo round($probabilitas_kumlatif[$x],3) ?></td>
                        <?php
                    }
                    ?>
                    <td><?php echo $zi[$x]?></td> 
                    <td><?php echo round($ui[$x],3)?></td>
                    <td><?php echo $hasil_peramalan[$x]?></td>
                    <td><?php echo $hasil_peramalan[$x] - $jumlah_barang[$x]?></td>
                    <td><?php echo $mae[$x] / $jumlah_barang[$x]?></td> 
                </tr>
            <?php
            }
        ?>
    </tbody>
</table>
<div class="t" style='width:80%; margin: 0 auto;'>
    <center><h3 style="padding:10px">Chart Monte</h3></center>
        <canvas id="myChart"></canvas>
</div>
<script>
  const labels = [
    <?php
        for ($j=0; $j < count($jumlah_barang) ; $j++) { 
            echo '"'.$bulan[$j].'",';
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
        for ($k=0; $k < count($jumlah_barang) ; $k++) { 
            echo '"'.$jumlah_barang[$k].'",';
        }
      ?>],
    },
    {
      label: 'Data Peramalan',
      backgroundColor: 'rgb(252, 32, 8)',
      borderColor: 'rgb(252, 32, 8)',
      data: [<?php
        for ($l=0; $l < count($hasil_peramalan) ; $l++) { 
            echo '"'.$hasil_peramalan[$l].'",';
        }
      ?>],
    }]
  };

  const config = {
    type: 'line',
    data: data,
    options: {}
  };
  const myChart = new Chart(
    document.getElementById('myChart'),
    config
  );
</script>
