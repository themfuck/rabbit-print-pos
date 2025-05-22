<?php

/*
 * Masukkan setiap perubahan yang dilakukan di tabel ini.
 *
 * NAMA                VERSI       TGL(DD-MM-YYYY)        PERUBAHAN
 * ========================================================================================================
 * IKHSAN                1.0       16-10-2018             Pembuatan awal (create)
 *
 */

include_once("konfigurasi.php");

if (isset($_POST['simpan_invoice'])) {
	$invoice_keluar_pelanggan_id = $_POST['invoice_keluar_pelanggan_id'];
	$invoice_keluar_total_harga  = $_POST['invoice_keluar_total_harga'];
	$list_invoice    			 = $_POST['list_invoice'];
	$array_inv		  	   		 = json_decode( $list_invoice, true );
	$response 					 = array();
	$invoice_keluar_no_invoice   = getKodeInvoice();
	$username = $_SESSION['user_username'];
	$upd_suratjalan = "";

	tulisLog("tambah_invoice.php -> simpan_invoice [START]");
	$sql = "INSERT INTO invoice_keluar "
			. " (cre_tms, upd_tms, cre_usr, upd_usr,"
			. " invoice_keluar_no_invoice,"
			. " invoice_keluar_pelanggan_id,"
			. " invoice_keluar_tanggal_kirim,"
			. " invoice_keluar_total_harga)"
			. " VALUES"
			. " (NOW(), NOW(), '".$username."', '".$username."',"
			. " '$invoice_keluar_no_invoice',"
			. " $invoice_keluar_pelanggan_id,"
			. " '" . date("Y-m-d") . "',"
			. " " . str_replace(",", "", $invoice_keluar_total_harga)
			. ")"
			. "";
	tulisLog('tambah_invoice.php -> insert ke invoice -> sql : '.$sql);
	if ( runUpdateInsertQuery($sql) ) {
		$sql_detail = "INSERT INTO invoice_keluar_detail "
			. " (cre_tms, upd_tms, cre_usr, upd_usr,"
			. " invoice_keluar_detail_no_invoice,"
			. " invoice_keluar_detail_surat_jalan_nomor,"
			. " invoice_keluar_detail_jenis_print,"
			. " invoice_keluar_detail_total_barang,"
			. " invoice_keluar_detail_satuan,"
			. " invoice_keluar_detail_harga,"
			. " invoice_keluar_detail_subtotal_harga)"
			. " VALUES "
			. "";
		$value_detail = "";
		$comma = ",";
		$invoice_keluar_detail_no_invoice = $invoice_keluar_no_invoice;
		foreach ($array_inv as $value) {
			$value_detail .= "("
				. " NOW(), NOW(), '".$username."', '".$username."',"
				. " '" . $invoice_keluar_detail_no_invoice . "',"
				// . " '" . $value['invoice_keluar_detail_surat_jalan_nomor'] . "',"
				. " '" . $value['surat_jalan_key'] . "',"
				. " '" . $value['invoice_keluar_detail_jenis_print'] . "',"
				. " " . $value['invoice_keluar_detail_total_barang'] . ","
				. " '" . $value['invoice_keluar_detail_satuan'] . "',"
				. " " . str_replace(",", "", $value['invoice_keluar_detail_harga']) . ","
				. " " . str_replace(",", "", $value['invoice_keluar_detail_subtotal_harga'])
				. ")"
				. $comma;

			$upd_suratjalan .= "'".$value['surat_jalan_key']."',";
		}
		$upd_suratjalan = rtrim($upd_suratjalan, ",");
		$value_detail = rtrim($value_detail, ",");
		$sql_detail .= $value_detail;

		tulisLog('tambah_invoice.php -> insert ke invoice_keluar_detail -> sql_detail : '.$sql_detail);
		$query_upd = "UPDATE surat_jalan s SET s.surat_jalan_status = 'I' WHERE s.surat_jalan_key IN (".$upd_suratjalan.")";
		tulisLog('tambah_invoice.php -> query_upd : '.$query_upd);
		runUpdateInsertQuery($query_upd);

		if ( runUpdateInsertQuery($sql_detail) ) {
			tulisLog('tambah_invoice.php -> insert ke invoice_keluar_detail -> BERHASIL');
			$response = array('response_code' => '00', 'response_msg' => 'Pesanan berhasil disimpan');
			echo json_encode($response);
			tulisLog("tambah_invoice.php -> simpan_invoice [END]");
			exit();
		} else {
			tulisLog('tambah_invoice.php -> insert ke invoice_keluar_detail -> GAGAL : '.mysqli_error($con));
			$response = array('response_code' => '99', 'response_msg' => 'Detail pesanan gagal disimpan : ' . mysqli_error($con));
			echo json_encode($response);
			tulisLog("tambah_invoice.php -> simpan_invoice [END]");
			exit();
		}
	} else {
		tulisLog('tambah_invoice.php -> insert ke invoice_keluar -> GAGAL : '.mysqli_error($con));
		$response = array('response_code' => '99', 'response_msg' => 'Detail pesanan gagal disimpan : ' . mysqli_error($con));
		echo json_encode($response);
		tulisLog("tambah_invoice.php -> simpan_invoice [END]");
		exit();
	}
	// $value_detail = rtrim($value_detail, ",");
	// $sql_detail .= $value_detail;
	// tulisLog('tambah_invoice.php -> insert ke invoice_keluar_detail -> sql_detail : '.$sql_detail);
	// if ( runUpdateInsertQuery($sql_detail) ) {
	// 	tulisLog('tambah_invoice.php -> insert ke invoice_keluar_detail -> BERHASIL');
	// 	$response = array('response_code' => '00', 'response_msg' => 'Pesanan berhasil disimpan');
	// 	echo json_encode($response);
	// 	tulisLog("tambah_invoice.php -> simpan_invoice [END]");
	// 	exit();
	// } else {
	// 	tulisLog('tambah_invoice.php -> insert ke detail_pesanan -> GAGAL : '.mysqli_error($con));
	// 	$response = array('response_code' => '99', 'response_msg' => 'Detail pesanan gagal disimpan : ' . mysqli_error($con));
	// 	echo json_encode($response);
	// 	tulisLog("tambah_invoice.php -> simpan_invoice [END]");
	// 	exit();
	// }
} else {

	$jenis_proses = $_POST['jenis_proses'];
	$invoice_id = $_POST['invoice_id'];
	$invoice_nomor = $_POST['invoice_nomor'];

	if ($jenis_proses == "hapus_pelanggan") {

		tulisLog("proses_daftar_invoice.php -> hapus_pelanggan");
		$del_pelanggan = "DELETE FROM invoice_keluar WHERE invoice_keluar_id = " . $invoice_id;

		tulisLog("proses_daftar_invoice.php -> del_pelanggan : ".$del_pelanggan);
	    if ( runUpdateInsertQuery($del_pelanggan) ) {

			tulisLog("proses_daftar_invoice.php -> del_pelanggan -> BERHASIL");

			$del_pelanggan = "DELETE FROM invoice_keluar_detail WHERE invoice_keluar_detail_no_invoice = '" . $invoice_nomor . "'";

			if ( runUpdateInsertQuery($del_pelanggan) ) {
				$response = array('response_code' => '00', 'response_msg' => 'Pelanggan Berhasil dihapus');
				echo json_encode($response);
				exit();
			}
	    } else {

			tulisLog("proses_daftar_invoice.php -> del_pelanggan -> GAGAL");
			$response = array('response_code' => '99', 'response_msg' => 'Pelanggan Gagal dihapus : '.mysqli_error($con));
			echo json_encode($response);
			exit();

		}

	}

}
?>