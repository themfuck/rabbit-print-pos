<?php 

/*
 * Masukkan setiap perubahan yang dilakukan di tabel ini.
 * 
 * NAMA                VERSI       TGL(DD-MM-YYYY)        PERUBAHAN
 * ========================================================================================================
 * IKHSAN                1.0       27-09-2018             Pembuatan awal (create)
 *
 */

include_once("konfigurasi.php");

$jenis_proses = $_POST['jenis_proses'];
$nomor_pesanan = $_POST['nomor_pesanan'];
$result = array();
$resultTmp = array();
$response = array();
$username = $_SESSION['user_username'];
$formatDateNow = date('d-m-Y');
$formatDateNow2 = date('Y-m-d');

tulisLog("proses_surat_jalan.php -> buat_suratjalan");

$surat_jalan_tanggal = $formatDateNow2;
$nomor_pesanan = $_POST['surat_jalan_nomor_pesanan'];
$nomor_suratjalan = $_POST['surat_jalan_nomor'];
$id_pelanggan = $_POST['surat_jalan_pelanggan_id'];
$jenis_pesanan = $_POST['surat_jalan_jenis_pesanan'];
$nama_barang = "";
$qty_barang = 0;
$qty_packing = 0;
$surat_jalan_total = 0;
$keterangan = "";
$satuan = "";
$list_barang = $_POST['list_barang'];
$array_brg = json_decode( $list_barang, true );
$surat_jalan_key = getKodeSuratJalan($formatDateNow);

tulisLog('proses_surat_jalan.php -> list_barang : '.$list_barang);

// $sql_pesanan = "INSERT INTO surat_jalan "
// 		. " (cre_tms, upd_tms, cre_usr, upd_usr,"
// 		. " surat_jalan_key,"
// 		. " surat_jalan_nomor,"
// 		. " surat_jalan_tanggal,"
// 		. " surat_jalan_jenis_pesanan,"
// 		. " surat_jalan_pelanggan_id,"
// 		. " surat_jalan_no_pesanan,"
// 		. " surat_jalan_total,"
// 		. " surat_jalan_status"
// 		. ")"
// 		. " VALUES"
// 		. " (NOW(), NOW(), '".$username."', '".$username."',"
// 		. " '".$surat_jalan_key."',"
// 		. " '".$nomor_suratjalan."',"
// 		. " '$surat_jalan_tanggal',"
// 		. " '".$jenis_pesanan."',"
// 		. " ".$id_pelanggan.","
// 		. " '".$nomor_pesanan."',"
// 		. " ".$surat_jalan_total.","
// 		. " 'B'"
// 		. ")"
// 		. "";

tulisLog('proses_surat_jalan.php -> insert ke surat_jalan -> sql_pesanan : '.$sql_pesanan);

// if ( runUpdateInsertQuery($sql_pesanan) ) {
tulisLog('proses_surat_jalan.php -> insert ke surat_jalan -> BERHASIL');
$sql_detail = "INSERT INTO surat_jalan_detail "
		. " (cre_tms, upd_tms, cre_usr, upd_usr,"
		. " surat_jalan_detail_surat_jalan_key,"
		. " surat_jalan_detail_kain,"
		. " surat_jalan_detail_nama_warna,"
		. " surat_jalan_detail_pesanan_key,"
		. " surat_jalan_detail_nama_barang,"
		. " surat_jalan_detail_jenis_print,"
		. " surat_jalan_detail_warna,"
		. " surat_jalan_detail_ukuran,"
		. " surat_jalan_detail_subtotal_qty,"
		. " surat_jalan_detail_satuan_qty,"
		. " surat_jalan_detail_subtotal_pack,"
		. " surat_jalan_detail_satuan_pack,"
		. " surat_jalan_detail_keterangan)"
		. " VALUES "
		. "";
$surat_jalan_total = 0;
$surat_jalan_subtotal = 0;
foreach ($array_brg as $value) {
	$comma = ",";
	if ( !next( $array_brg ) ) {
        $comma = "";
    }

    $surat_jalan_detail_subtotal_qty = $value['surat_jalan_detail_subtotal_qty'];
    // if ($jenis_pesanan == "A" || $jenis_pesanan == "C") {
    //     $surat_jalan_detail_subtotal_qty = $surat_jalan_detail_subtotal_qty / 3;
    // } else if ($jenis_pesanan == "B" || $jenis_pesanan == "D") {
    //     $surat_jalan_detail_subtotal_qty = $surat_jalan_detail_subtotal_qty / 2;
    // }

    $value_detail .= "("
		. " NOW(), NOW(), '".$username."', '".$username."',"
		. " '".$surat_jalan_key."',"
		. " '" . $value['surat_jalan_detail_kain'] . "',"
		. " '" . $value['surat_jalan_detail_nama_warna'] . "',"
		. " '" . $value['surat_jalan_detail_pesanan_key'] . "',"
		. " '" . $value['surat_jalan_detail_nama_barang'] . "',"
		. " '" . $value['surat_jalan_detail_jenis_print'] . "',"
		. " "  . $value['surat_jalan_detail_warna'] . ","
		. " '" . $value['surat_jalan_detail_ukuran'] . "',"
		. " "  . $surat_jalan_detail_subtotal_qty . ","
		. " '" . $value['surat_jalan_detail_satuan_qty'] . "',"
		. " " . $value['surat_jalan_detail_subtotal_pack'] . ","
		. " '" . $value['surat_jalan_detail_satuan_pack'] . "',"
		. " '" . $value['surat_jalan_detail_keterangan'] . "'"
		. ")"
		. $comma;
	if ($jenis_pesanan == "A" || $jenis_pesanan == "B" || $jenis_pesanan == "C" || $jenis_pesanan == "D") {
		$surat_jalan_total += $value['surat_jalan_detail_subtotal_qty'];
	} else if ($jenis_pesanan == "U" || $jenis_pesanan == "E") {
		$surat_jalan_subtotal = ($value['surat_jalan_detail_subtotal_qty'] * $value['surat_jalan_detail_subtotal_pack']);
		$surat_jalan_total += $surat_jalan_subtotal;
	}
}
$sql_detail .= $value_detail;
tulisLog('proses_surat_jalan.php -> insert ke surat_jalan_detail -> sql_detail : '.$sql_detail);

if ( runUpdateInsertQuery($sql_detail) ) {

	tulisLog('proses_surat_jalan.php -> insert ke surat_jalan_detail -> BERHASIL');
	$response = array('response_code' => '00', 'response_msg' => 'Detail surat jalan berhasil dicetak dan disimpan',
				'surat_jalan_key' => $surat_jalan_key);
	tulisLog('proses_surat_jalan.php -> response : '.$response);

	$sql_pesanan = "INSERT INTO surat_jalan "
		. " (cre_tms, upd_tms, cre_usr, upd_usr,"
		. " surat_jalan_key,"
		. " surat_jalan_nomor,"
		. " surat_jalan_tanggal,"
		. " surat_jalan_jenis_pesanan,"
		. " surat_jalan_pelanggan_id,"
		. " surat_jalan_no_pesanan,"
		. " surat_jalan_total,"
		. " surat_jalan_status"
		. ")"
		. " VALUES"
		. " (NOW(), NOW(), '".$username."', '".$username."',"
		. " '".$surat_jalan_key."',"
		. " '".$nomor_suratjalan."',"
		. " '$surat_jalan_tanggal',"
		. " '".$jenis_pesanan."',"
		. " ".$id_pelanggan.","
		. " '".$nomor_pesanan."',"
		. " ".$surat_jalan_total.","
		. " 'B'"
		. ")"
		. "";
		tulisLog("proses_surat_jalan.php -> sql_pesanan : ".$sql_pesanan);

	if ( runUpdateInsertQuery($sql_pesanan) ) {

		tulisLog('proses_surat_jalan.php -> insert ke surat_jalan -> BERHASIL');
		$response = array('response_code' => '00', 'response_msg' => 'Surat jalan berhasil dicetak dan disimpan',
					'surat_jalan_key' => $surat_jalan_key);
		tulisLog('proses_surat_jalan.php -> response : '.$response);
		echo json_encode($response);
		exit();

	} else {

		tulisLog('proses_surat_jalan.php -> insert ke surat_jalan_detail -> GAGAL');
		$response = array('response_code' => '99', 'response_msg' => 'Surat jalan tidak dapat dicetak dan disimpan',
					'surat_jalan_key' => $surat_jalan_key);
		tulisLog('proses_surat_jalan.php -> response : '.$response);
		echo json_encode($response);
		exit();

	}

	// tulisLog("proses_surat_jalan.php -> upd_psn : ".$upd_psn);

	// tulisLog('proses_surat_jalan.php -> insert ke surat_jalan_detail -> BERHASIL');
	// $response = array('response_code' => '00', 'response_msg' => 'Surat jalan berhasil dicetak dan disimpan',
	// 			'surat_jalan_key' => $surat_jalan_key);
	// tulisLog('proses_surat_jalan.php -> response : '.$response);
	// echo json_encode($response);
	// exit();

} else {

	tulisLog('proses_surat_jalan.php -> insert ke surat_jalan_detail -> GAGAL : '.mysqli_error($con));
	$response = array('response_code' => '99', 'response_msg' => 'Surat jalan gagal dicetak dan disimpan : ' . mysqli_error($con));
	tulisLog('proses_surat_jalan.php -> response : '.$response);
	echo json_encode($response);
	exit();

}

// } else {
// 	tulisLog('proses_surat_jalan.php -> insert ke surat_jalan -> GAGAL : ' . mysqli_error($con));
// 	$response = array('response_code' => '99', 'response_msg' => 'Pesanan gagal disimpan : ' . mysqli_error($con));
// 	echo json_encode($response);
// 	exit();
// }

?>