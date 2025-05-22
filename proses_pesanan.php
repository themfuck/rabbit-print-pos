<?php 

/*
 * Masukkan setiap perubahan yang dilakukan di tabel ini.
 * 
 * NAMA                VERSI       TGL(DD-MM-YYYY)        PERUBAHAN
 * ========================================================================================================
 * IKHSAN                1.0       16-09-2018             Pembuatan awal (create)
 * Vandi                 1.1       25-09-2018             Penambahan data Jenis Print
 * Ikhsan                1.2       18-09-2018             Mengubah response sql
 */

include_once("konfigurasi.php");

$no_surat_jalan = $_POST['no_surat_jalan'];
$jenis_pesanan  = $_POST['jenis_pesanan'];
$tgl_pesanan    = $_POST['tgl_pesanan'];
$no_pelanggan   = $_POST['no_pelanggan'];
$satuan_pesanan = $_POST['satuan_pesanan'];
$tipe_packing	= $_POST['tipe_packing'];
$subtotal_qty   = 0;
$total_qty      = 0;
$subtotal_pck   = 0;
$total_pck      = 0;
$list_barang    = $_POST['list_barang'];
$array_brg		= json_decode( $list_barang, true );
$username		= $_SESSION['user_username'];
$no_pesanan     = getKodePesanan($tgl_pesanan);
$response 		= array();

tulisLog("proses_pesanan.php -> load..");
// tulisLog("proses_pesanan.php -> list_barang : ".$array_brg);

// string query detail_pesanan
$sql_detail = "INSERT INTO detail_pesanan "
			. " ("
			. "cre_tms, upd_tms, cre_usr, upd_usr,"
			. " detail_pesanan_no_pesanan,"
			. " detail_pesanan_key,"
			. " detail_pesanan_nama_barang,"
			. " detail_pesanan_jenis_print,"
			. " detail_pesanan_warna,"
			. " detail_pesanan_detail_kain,"
			. " detail_pesanan_nama_warna,"
			. " detail_pesanan_ukuran,"
			. " detail_pesanan_sisi_kanan,"
			. " detail_pesanan_sisi_kiri,"
			. " detail_pesanan_sisi_blkng,"
			. " detail_pesanan_qty,"
			. " detail_pesanan_satuan,"
			. " detail_pesanan_qty_packing,"
			. " detail_pesanan_jenis_packing,"
			. " detail_pesanan_keterangan"
			.")"
			. " VALUES "
			. "";

$value_detail = "";
$detail_pesanan_nama_barang = "";
$detail_pesanan_jenis_print = "";
$detail_pesanan_warna = "";
$detail_pesanan_satuan = "";
$detail_pesanan_jenis_packing = "";
$qty = 0;
$pck = 0;
$total_qty = 0;
$total_pck = 0;
$divide = 0;
$comma = ",";

foreach ($array_brg as $value) {

	$value_detail .= "("
		. " NOW(), NOW(), '".$username."', '".$username."',"
		. " '" . $no_pesanan . "',"
		. " '" . $value['detail_pesanan_key'] . "',"
		. " '" . $value['detail_pesanan_nama_barang'] . "',"
		. " '" . $value['detail_pesanan_jenis_print'] . "',"
		. " " . $value['detail_pesanan_warna'] . ","
		. " '" . $value['detail_pesanan_detail_kain'] . "',"
		. " '" . $value['detail_pesanan_nama_warna'] . "',"
		. " '" . $value['detail_pesanan_ukuran'] . "',"
		. " " . $value['detail_pesanan_sisi_kanan'] . ","
		. " " . $value['detail_pesanan_sisi_kiri'] . ","
		. " " . $value['detail_pesanan_sisi_blkng'] . ","
		. " " . $value['detail_pesanan_qty'] . ","
		. " '" . $value['detail_pesanan_satuan'] . "',"
		. " " . $value['detail_pesanan_qty_packing'] . ","
		. " '" . $value['detail_pesanan_jenis_packing'] . "',"
		. " '" . $value['detail_pesanan_keterangan'] . "'"
		. ")"
		. $comma;

	// hitung total qty
	if ($tipe_packing == "U" || $tipe_packing == "E") {
		
		$qty = (double) $value['detail_pesanan_qty'];
		$pck = (int) $value['detail_pesanan_qty_packing'];
		$total_qty = $total_qty + ($qty * $pck);
		$total_pck = $total_pck + $pck;
		$qty = 0;
		$pck = 0;

	} else {
		$divide = 0;
		if ( (double) $value['detail_pesanan_sisi_kanan'] > 0 ) {
			$divide++;
		}
		if ( (double) $value['detail_pesanan_sisi_kiri'] > 0 ) {
			$divide++;
		}
		if ( (double) $value['detail_pesanan_sisi_blkng'] > 0 ) {
			$divide++;
		}
		$pck = (double) $value['detail_pesanan_sisi_kanan'] + (double) $value['detail_pesanan_sisi_kiri'] + (double) $value['detail_pesanan_sisi_blkng'];
		$total_pckTmp = $total_pckTmp + $pck;
		$qty = $total_pckTmp / $divide;
		$total_qtyTmp = $total_qtyTmp + $qty;
		$total_qty = $total_qty + $total_qtyTmp;
		$total_pck = $total_pck + $total_pckTmp;
		$qty = 0;
		$pck = 0;
		$total_qtyTmp = 0;
		$total_pckTmp = 0;
	}
}
$value_detail = rtrim($value_detail, ",");
$sql_detail .= $value_detail;
tulisLog('proses_pesanan.php -> string query detail_pesanan -> sql_detail : '.$sql_detail);

// string query pesanan
$sql_pesanan = "INSERT INTO pesanan "
			. " (cre_tms, upd_tms, cre_usr, upd_usr,"
			. " pesanan_no_pesanan,"
			. " pesanan_no_surat_jalan_masuk, pesanan_jenis_pesanan,"
			. " pesanan_tanggal_pesanan, pesanan_pelanggan_id,"
			. " pesanan_total_qty, pesanan_total_qty_packing)"
			. " VALUES"
			. " (NOW(), NOW(), '".$username."', '".$username."',"
			. " '$no_pesanan',"
			. " '$no_surat_jalan', '$jenis_pesanan',"
			. " '".date('Y-m-d', strtotime($tgl_pesanan))."', '$no_pelanggan',"
			. " $total_qty, $total_pck)"
			. "";
tulisLog('proses_pesanan.php -> string query pesanan -> sql_pesanan : '.$sql_pesanan);
tulisLog('proses_pesanan.php -> insert ke pesanan -> sql_pesanan : '.$sql_pesanan);

if ( runUpdateInsertQuery($sql_pesanan) ) {

	tulisLog('proses_pesanan.php -> insert ke pesanan -> BERHASIL');
	$response = array('response_code' => '00', 'response_msg' => 'Pesanan berhasil disimpan');
	// echo json_encode($response);

	if (sizeof($array_brg) > 0) {
		if ( runUpdateInsertQuery($sql_detail) ) {
			tulisLog('proses_pesanan.php -> insert ke detail_pesanan -> BERHASIL');
			$response = array('response_code' => '00', 'response_msg' => 'Detail pesanan berhasil disimpan');
			echo json_encode($response);
			exit();
		} else {
			// hapus pesanan
			// tulisLog("proses_pesanan.php -> hapus_srtjalan");
			// $del_srtjalan = "DELETE FROM pesanan WHERE pesanan_no_pesanan = '" . $no_pesanan . "'";
			// tulisLog("proses_pesanan.php -> del_srtjalan : ".$del_srtjalan);
			// runUpdateInsertQuery($del_srtjalan);

			tulisLog('proses_pesanan.php -> insert ke detail_pesanan -> GAGAL : '.mysqli_error($con));
			$response = array('response_code' => '99', 'response_msg' => 'Detail pesanan gagal disimpan : ' . mysqli_error($con));
			echo json_encode($response);
			exit();
		}
	} else {
		echo json_encode($response);
		exit();
	}

} else {
	tulisLog('proses_pesanan.php -> insert ke pesanan -> GAGAL : ' . mysqli_error($con));

	$response = array('response_code' => '99', 'response_msg' => 'Pesanan gagal disimpan : ' . mysqli_error($con));
	echo json_encode($response);
	exit();
}

tulisLog('proses_pesanan.php -> page close.');

?>