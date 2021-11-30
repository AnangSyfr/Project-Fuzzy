<?php
 	$conn = mysqli_connect('localhost','root','','db_fuzzy');
 ?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Fuzzy Database</title>
    <style type='text/css'>
    body {
        font-family: Verdana;
        font-size: 12px;
    }

    .ratakiri {
        text-align: left;
    }

    .ratatengah {
        text-align: center;
    }

    .ratakanan {
        text-align: right;
    }

    .bg2,
    .bg3,
    .bg4 {
        background-color: #2596be;
    }

    .bg5,
    .bg6,
    .bg7 {
        background-color: #be2596;
    }

    .bg8,
    .bg9,
    .bg10 {
        background-color: #96be25;
    }

    th,
    td {
        padding: .3em .5em;
    }

    th {
        background-color: #EEEEEE;
    }
    </style>
</head>

<body>
    <?php
        function cek_selected($cek,$value) {
            if($cek==$value)
                echo "selected=\"selected\"";
        }

        function format_desimal($nn,$des) {
            return number_format($nn,$des,",",".");
        }

        function get_namakelompok($id_kelompok){
            $conn = $conn = mysqli_connect('localhost','root','','db_fuzzy');
            $hasil = mysqli_query($conn,"SELECT * FROM tb_kelompok WHERE id='$id_kelompok'");
            $row = mysqli_fetch_assoc($hasil);
            return $row['nama_kelompok'];
        }
        
        function derajat_keanggotaan($nilai, $bawah, $tengah, $atas){
            // $selisih = $atas - $bawah;
            if($nilai<$bawah) 
                $DA=0;

            elseif (($nilai>=$bawah) && ($nilai<=$tengah))
            if($bawah<=0) 
                $DA=1;
            
            else 
                $DA=($nilai-$bawah) / ($tengah-$bawah);
                
            elseif(($nilai>$tengah) && ($nilai<=$atas)) 
                $DA=($atas-$nilai) / ($atas-$tengah);
            
            elseif($nilai>$atas) 
                $DA=0;
            
            return $DA;
        }

        $ux[][] = NULL; //variabel utk data derajat keanggotaaan

        $kelompok =isset($_GET['kelompok'])?$_GET['kelompok']:1;
        $hasil_kelompok = mysqli_query($conn,"SELECT * FROM tb_kelompok WHERE id='$kelompok'");
        $row_kelompok = mysqli_fetch_assoc($hasil_kelompok);
        $hasil =mysqli_query($conn,"SELECT * FROM tb_kriteria");
        $jumkol =mysqli_num_rows($hasil);

 	?>

    <h2> Data Siswa & Derajat Keanggotaan</h2>

    <table border="1" cellpadding="3" style="border-collapse:collapse;">
        <tr>
            <th width="17" rowspan="2"><strong>No </strong></th>
            <th width="100" rowspan="2"><strong>Nama</strong></th>
            <th width="28" rowspan="2"><strong>Nilai</strong></th>
            <th width="37" rowspan="2"><strong>Minat</strong></th>
            <th width="78" rowspan="2"><strong>Bakat</strong></th>
            <th colspan="3"><strong>(&#956;[x]) <?php echo get_namakelompok(1);?></strong></th>
            <th colspan="3"><strong>(&#956;[x]) <?php echo get_namakelompok(2);?></strong></th>
            <th colspan="3"><strong>(&#956;[x]) <?php echo get_namakelompok(3);?></strong></th>
        </tr>
        <tr>
            <?php
                $hasil = mysqli_query($conn,"SELECT * FROM tb_kriteria WHERE kelompok='1'");
                while($row=mysqli_fetch_assoc($hasil)){
 	        ?>
            <th><strong><?php echo $row['nama_kriteria'];?></strong></th>
            <?php
 	            }
 	        ?>

            <?php
                $hasil =mysqli_query($conn,"SELECT * FROM tb_kriteria WHERE kelompok='2'");
                while($row=mysqli_fetch_assoc($hasil)){
 	        ?>
            <th><strong><?php echo $row['nama_kriteria'];?></strong></th>
            <?php
 	            }
 	        ?>

            <?php
                $hasil = mysqli_query($conn,"SELECT * FROM tb_kriteria WHERE kelompok='3'");
                while($row = mysqli_fetch_assoc($hasil)){
 	        ?>
            <th><strong><?php echo $row['nama_kriteria'];?></strong></th>
            <?php
 	            }
 	        ?>
        </tr>
        <?php
            $hasil = mysqli_query($conn,"SELECT * FROM tb_siswa");
            $no = 1;
            while($row= mysqli_fetch_assoc($hasil)){
 	    ?>
        <tr>
            <td><?php echo $no ?></td>
            <td><?php echo $row['nama']; ?></td>
            <td class="ratakanan"><?php echo $row['nilai']; ?></td>
            <td class="ratakanan"><?php echo $row['minat']; ?></td>
            <td class="ratakanan"><?php echo $row['bakat']; ?></td>
            <?php
                $hasil2 =mysqli_query($conn,"SELECT * FROM tb_kriteria WHERE kelompok='1'");
                while($row2=mysqli_fetch_assoc($hasil2)){
                    $u = derajat_keanggotaan($row['nilai'], $row2['bawah'],
                    $row2['tengah'], $row2['atas']);
                    $ux[$row['id']][$row2['id']] = $u;
                    $bg = "ratakanan";
                    if(isset($_GET['nilai']) && ($row2['id']==$_GET['nilai']))
                        $bg .= " bg".$row2['id'];
 	        ?>
            <td class="<?php echo $bg;?>"><?php echo format_desimal($u,2);?></td>
            <?php 
		        } 
                $hasil2 = mysqli_query($conn,"SELECT * FROM tb_kriteria WHERE kelompok='2'");
                while($row2=mysqli_fetch_assoc($hasil2)){
                    $u = derajat_keanggotaan($row['minat'],$row2['bawah'],$row2['tengah'],$row2['atas']);
                    $ux[$row['id']][$row2['id']] = $u;
                    $bg = "ratakanan";
                    if(isset($_GET['minat']) && ($row2['id']==$_GET['minat']))
                        $bg .= " bg".$row2['id'];
 	        ?>
            <td class="<?php echo $bg;?>"><?php echo format_desimal($u,2);?></td>
            <?php
 		        }
                $hasil2 = mysqli_query($conn,"SELECT * FROM tb_kriteria WHERE kelompok='3'");
                while($row2 = mysqli_fetch_assoc($hasil2)){
                    $u = derajat_keanggotaan($row['bakat'], $row2['bawah'],
                    $row2['tengah'], $row2['atas']);
                    $ux[$row['id']][$row2['id']] = $u;
                    $bg = "ratakanan";
                    if(isset($_GET['bakat']) && ($row2['id']==$_GET['bakat']))
                        $bg .= " bg".$row2['id'];
 	        ?>
            <td class="<?php echo $bg;?>"><?php echo format_desimal($u,2);?></td>
            <?php
 	            }
 	        ?>
        </tr>
        <?php
		    $no++;
 	        }
 	    ?>
    </table>
    <br />

    <h2><strong>QUERY</strong></h2>
    <form action="" method="GET">
        <select name="nilai">
            <option value="1" <?php if(isset($_GET['nilai'])) cek_selected($_GET['nilai'],1); ?>>Nilai Buruk</option>
            <option value="2" <?php if(isset($_GET['nilai'])) cek_selected($_GET['nilai'],2); ?>>Nilai Cukup</option>
            <option value="3" <?php if(isset($_GET['nilai'])) cek_selected($_GET['nilai'],3); ?>>Nilai Bagus</option>
        </select>
        <select name="opr_a">
            <option value="OR" <?php if(isset($_GET['opr_a'])) cek_selected($_GET['opr_a'],"OR"); ?>>OR</option>
            <option value="AND" <?php if(isset($_GET['opr_a'])) cek_selected($_GET['opr_a'],"AND"); ?>>AND</option>
        </select>
        <select name="minat">
            <option value="4" <?php if(isset($_GET['minat'])) cek_selected($_GET['minat'],4); ?>>Minat Rendah</option>
            <option value="5" <?php if(isset($_GET['minat'])) cek_selected($_GET['minat'],5); ?>>Minat Sedang</option>
            <option value="6" <?php if(isset($_GET['minat'])) cek_selected($_GET['minat'],6); ?>>Minat Tinggi</option>
        </select>
        <select name="opr_b">
            <option value="OR" <?php if(isset($_GET['opr_b'])) cek_selected($_GET['opr_b'],"OR"); ?>>OR</option>
            <option value="AND" <?php if(isset($_GET['opr_b'])) cek_selected($_GET['opr_b'],"AND"); ?>>AND</option>
        </select>
        <select name="bakat">
            <option value="7" <?php if(isset($_GET['bakat'])) cek_selected($_GET['bakat'],7); ?>>Bakat Tidak Berbakat
            </option>
            <option value="8" <?php if(isset($_GET['bakat'])) cek_selected($_GET['bakat'],8); ?>>Bakat Biasa</option>
            <option value="9" <?php if(isset($_GET['bakat'])) cek_selected($_GET['bakat'],9); ?>>Bakat Berbakat</option>
        </select>

        <input type="submit" value="Submit">
        <form>

            <br />
            <br />

            <h2><strong>HASIL</strong></h2>
            <?php
                if (isset($_GET['opr_a']) && isset($_GET['opr_b'])) {
                    $opr_a = $_GET['opr_a'];
                    $opr_b = $_GET['opr_b'];
                    $nilai = $_GET['nilai'];
                    $minat = $_GET['minat'];
                    $bakat = $_GET['bakat'];
                    $hasil = mysqli_query($conn,"SELECT id,nama FROM tb_siswa");
                    while($row=mysqli_fetch_assoc($hasil)) {
                
                        //ambil data derajat keanggotaan

                        $c1 = $ux[$row['id']][$nilai];
                        $c2 = $ux[$row['id']][$minat];
                        $c3 = $ux[$row['id']][$bakat];
                        //tentukan operasi
                        if ($opr_a == "OR" && $opr_b == "OR"){
                            $cc = max($c1,$c2,$c3);
                        } 
                        else if ($opr_a == "OR" && $opr_b == "AND") {
                            $satu = max($c1,$c2);
                            $cc = min($satu,$c3);
                        } 
                        else if ($opr_a == "AND" && $opr_b == "OR") {
                            $satu = max($c1,$c2);
                            $cc = min($satu,$c3);
                        } 
                        else {
                            $cc= min($c1,$c2,$c3);
                        }

                        //tampilkan
                        if ($cc > 0) 
                            echo $row['nama']." : [".format_desimal($cc,2)."]<br />";
                    } 
                    //end while
                }

            ?>
</body>

</html>