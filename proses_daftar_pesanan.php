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
// $dateNow = new Date();
$formatDateNow = date('d-m-Y');
$formatDateNow2 = date('Y-m-d');

if ($jenis_proses == "lihat_detail") {

	tulisLog("proses_daftar_pesanan.php -> lihat_detail");
	$sel_detailpsn = "SELECT * FROM detail_pesanan WHERE detail_pesanan_no_pesanan = '".$nomor_pesanan."'";
	
	tulisLog("proses_daftar_pesanan.php -> sel_detailpsn : ".$sel_detailpsn);

	$query_psn = $con->query($sel_detailpsn);
	while ($row = $query_psn->fetch_assoc()) {
		array_push($resultTmp, $row);
	}

	foreach ($resultTmp as $value) {
		$resultTmp = array(
			'detail_pesanan_nama_barang' => $value['detail_pesanan_nama_barang'],
			'detail_pesanan_jenis_print' => $value['detail_pesanan_jenis_print'],
			'detail_pesanan_qty' => $value['detail_pesanan_qty'],
			'detail_pesanan_satuan' => $value['detail_pesanan_satuan'],
			'detail_pesanan_qty_packing' => $value['detail_pesanan_qty_packing'],
			'detail_pesanan_jenis_packing' => $value['detail_pesanan_jenis_packing'],
			'detail_pesanan_warna' => $value['detail_pesanan_warna']
			// 'detail_pesanan_keterangan' => $value['detail_pesanan_keterangan']
		);
		array_push($result, $resultTmp);
	}

	echo json_encode($result);

} elseif ($jenis_proses == "hapus_pesanan") {

	tulisLog("proses_daftar_pesanan.php -> hapus_detail");
	$del_pesanan = "DELETE FROM pesanan WHERE pesanan_no_pesanan = '" . $nomor_pesanan . "'";

	tulisLog("proses_daftar_pesanan.php -> del_pesanan : ".$del_pesanan);

	if ( runUpdateInsertQuery($del_pesanan) ) {

		tulisLog("proses_daftar_pesanan.php -> del_pesanan -> BERHASIL");

		$del_detailpsn = "DELETE FROM detail_pesanan WHERE detail_pesanan_no_pesanan = '" . $nomor_pesanan . "'";

		tulisLog("proses_daftar_pesanan.php -> del_detailpsn : ".$del_detailpsn);

		if ( runUpdateInsertQuery($del_detailpsn) ) {

			tulisLog("proses_daftar_pesanan.php -> del_detailpsn -> BERHASIL");

			$response = array('response_code' => '00', 'response_msg' => 'Pesanan berhasil dihapus');
			echo json_encode($response);
			exit();

		} else {

			tulisLog("proses_daftar_pesanan.php -> del_detailpsn -> GAGAL");

			$response = array('response_code' => '99', 'response_msg' => 'Pesanan gagal dihapus');
			echo json_encode($response);
			exit();
		}

	} else {

		tulisLog("proses_daftar_pesanan.php -> del_detailpsn -> GAGAL");
		$response = array('response_code' => '99', 'response_msg' => 'Pesanan gagal dihapus');
		echo json_encode($response);
		exit();
	}

} elseif ($jenis_proses == "update_pesanan") {

	tulisLog("proses_daftar_pesanan.php -> update_pesanan");

	$pesanan_no_surat_jalan_masuk = $_POST['pesanan_no_surat_jalan_masuk'];
	$pesanan_jenis_pesanan = $_POST['pesanan_jenis_pesanan'];
	$pesanan_tanggal_pesanan = $_POST['pesanan_tanggal_pesanan'];
	$pesanan_pelanggan_id = $_POST['pesanan_pelanggan_id'];
	$pesanan_total_qty = $_POST['pesanan_total_qty'];
	$pesanan_total_qty_packing = $_POST['pesanan_total_qty_packing'];
	$jml_barang_skrg = $_POST['jml_barang_skrg'];
	$list_barang = $_POST['list_barang'];
	$array_brg = json_decode( $list_barang, true );
	$cnt_barang = count($array_brg);

	tulisLog("proses_daftar_pesanan.php -> array_brg : ".json_encode($array_brg));

	$upd_psn = "UPDATE pesanan p"
			. " SET"
			. " p.upd_tms = NOW(),"
			. " p.upd_usr = '" . $username . "',"
			. " p.pesanan_no_surat_jalan_masuk = '" .$pesanan_no_surat_jalan_masuk. "',"
			. " p.pesanan_jenis_pesanan = '" .$pesanan_jenis_pesanan. "',"
			. " p.pesanan_tanggal_pesanan = '" .$pesanan_tanggal_pesanan. "',"
			. " p.pesanan_pelanggan_id = " .$pesanan_pelanggan_id. ","
			. " p.pesanan_total_qty = " .$pesanan_total_qty. ","
			. " p.pesanan_total_qty_packing = " .$pesanan_total_qty_packing
			. " WHERE p.pesanan_no_pesanan = '".$nomor_pesanan."'";

	tulisLog("proses_daftar_pesanan.php -> upd_psn : ".$upd_psn);

	if ( runUpdateInsertQuery($upd_psn) ) {

		tulisLog("proses_daftar_pesanan.php -> upd_psn -> BERHASIL");

		foreach ($array_brg as $value) {

			$upd_detailpsn = "UPDATE detail_pesanan p"
				. " SET"
				. " p.upd_tms = NOW(),"
				. " p.upd_usr = '" . $username . "',"
				. " p.detail_pesanan_nama_barang = '" .$value['detail_pesanan_nama_barang']. "',"
				. " p.detail_pesanan_jenis_print = '" .$value['detail_pesanan_jenis_print']. "',"
				. " p.detail_pesanan_qty = " .$value['detail_pesanan_qty']. ","
				. " p.detail_pesanan_satuan = '" .$value['detail_pesanan_satuan']. "',"
				. " p.detail_pesanan_qty_packing = " .$value['detail_pesanan_qty_packing']. ","
				. " p.detail_pesanan_jenis_packing = '" .$value['detail_pesanan_jenis_packing']. "',"
				. " p.detail_pesanan_warna = " .$value['detail_pesanan_warna']
				// . " p.detail_pesanan_keterangan = '" .$value['detail_pesanan_keterangan'] ."'"
				. " WHERE P.detail_pesanan_no_pesanan = '".$nomor_pesanan."'"
				. " AND p.detail_pesanan_id = ".$value['detail_pesanan_id'];

			tulisLog("proses_daftar_pesanan.php -> upd_detailpsn : ".$upd_detailpsn);

			if ( runUpdateInsertQuery($upd_detailpsn) ) {
				tulisLog("proses_daftar_pesanan.php -> upd_detailpsn -> BERHASIL");
			}

		}

		$response = array('response_code' => '00', 'response_msg' => 'Pesanan berhasil diubah');
		echo json_encode($response);
		exit();

	} else {
		$response = array('response_code' => '99', 'response_msg' => 'Pesanan gagal diubah : '. mysqli_error($con));
		echo json_encode($response);
		exit();
	}

} elseif ($jenis_proses == "buat_suratjalan") {

	tulisLog("proses_daftar_pesanan.php -> buat_suratjalan");

	$surat_jalan_tanggal = $formatDateNow2;
	$nomor_pesanan = $_POST['surat_jalan_nomor_pesanan'];
	$nomor_suratjalan = $_POST['surat_jalan_nomor'];
	$id_pelanggan = $_POST['surat_jalan_pelanggan_id'];
	$jenis_pesanan = $_POST['surat_jalan_jenis_pesanan'];
	$nama_barang = "";
	$qty_barang = 0;
	$qty_packing = 0;
	$keterangan = "";
	$satuan = "";
	$list_barang = $_POST['list_barang'];
	$array_brg = json_decode( $list_barang, true );
	$surat_jalan_key = getKodeSuratJalan($formatDateNow);

	tulisLog('proses_daftar_pesanan.php -> list_barang : '.$list_barang);

	$sql_pesanan = "INSERT INTO surat_jalan "
			. " (cre_tms, upd_tms, cre_usr, upd_usr,"
			. " surat_jalan_key,"
			. " surat_jalan_nomor,"
			. " surat_jalan_tanggal,"
			. " surat_jalan_jenis_pesanan,"
			. " surat_jalan_pelanggan_id,"
			. " surat_jalan_no_pesanan,"
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
			. " 'B'"
			. ")"
			. "";

	tulisLog('proses_daftar_pesanan.php -> insert ke surat_jalan -> sql_pesanan : '.$sql_pesanan);

	if ( runUpdateInsertQuery($sql_pesanan) ) {
		tulisLog('proses_daftar_pesanan.php -> insert ke surat_jalan -> BERHASIL');
		$sql_detail = "INSERT INTO surat_jalan_detail "
				. " (cre_tms, upd_tms, cre_usr, upd_usr,"
				. " surat_jalan_detail_surat_jalan_key,"
				. " surat_jalan_detail_kain,"
				. " surat_jalan_detail_nama_warna,"
				. " surat_jalan_detail_pesanan_key,"
				. " surat_jalan_detail_nama_barang,"
				. " surat_jalan_detail_jenis_print,"
				. " surat_jalan_detail_warna,"
				. " surat_jalan_detail_subtotal_qty,"
				. " surat_jalan_detail_satuan_qty,"
				. " surat_jalan_detail_subtotal_pack,"
				. " surat_jalan_detail_satuan_pack,"
				. " surat_jalan_detail_keterangan)"
				. " VALUES "
				. "";
		$surat_jalan_total = 0;
		foreach ($array_brg as $value) {
			$comma = ",";
			if ( !next( $array_brg ) ) {
		        $comma = "";
		    }
		    $value_detail .= "("
				. " NOW(), NOW(), '".$username."', '".$username."',"
				. " '".$surat_jalan_key."',"
				. " '" . $value['surat_jalan_detail_kain'] . "',"
				. " '" . $value['surat_jalan_detail_nama_warna'] . "',"
				. " '" . $value['surat_jalan_detail_pesanan_key'] . "',"
				. " '" . $value['surat_jalan_detail_nama_barang'] . "',"
				. " '" . $value['surat_jalan_detail_jenis_print'] . "',"
				. " "  . $value['surat_jalan_detail_warna'] . ","
				. " "  . $value['surat_jalan_detail_subtotal_qty'] . ","
				. " '" . $value['surat_jalan_detail_satuan_qty'] . "',"
				. " " . $value['surat_jalan_detail_subtotal_pack'] . ","
				. " '" . $value['surat_jalan_detail_satuan_pack'] . "',"
				. " '" . $value['surat_jalan_detail_keterangan'] . "'"
				. " ''"
				. ")"
				. $comma;
			$surat_jalan_total += $value['surat_jalan_detail_subtotal_qty'];
		}
		$sql_detail .= $value_detail;
		tulisLog('proses_daftar_pesanan.php -> insert ke surat_jalan_detail -> sql_detail : '.$sql_detail);

		if ( runUpdateInsertQuery($sql_detail) ) {

			$upd_psn = "UPDATE surat_jalan p"
				. " SET"
				. " p.upd_tms = NOW(),"
				. " p.upd_usr = '" . $username . "',"
				. " p.surat_jalan_total = " . $surat_jalan_total
				. " WHERE p.surat_jalan_key = '".$surat_jalan_key."'";
			runUpdateInsertQuery($upd_psn);

			// foreach ($array_brg as $value) {
			// 	$upd_psn = "UPDATE detail_pesanan p"
			// 		. " SET"
			// 		. " p.upd_tms = NOW(),"
			// 		. " p.upd_usr = '" . $username . "',"
			// 		. " p.detail_pesanan_status = '".$surat_jalan_key."'"
			// 		. " WHERE p.detail_pesanan_no_pesanan = '".$nomor_pesanan."'"
			// 		. " AND p.detail_pesanan_key = '".$value['surat_jalan_detail_pesanan_key']."'";
			// 	runUpdateInsertQuery($upd_psn);
			// }

			tulisLog("proses_daftar_pesanan.php -> upd_psn : ".$upd_psn);

			tulisLog('proses_daftar_pesanan.php -> insert ke surat_jalan_detail -> BERHASIL');
			$response = array('response_code' => '00', 'response_msg' => 'Surat jalan berhasil dicetak dan disimpan',
						'surat_jalan_key' => $surat_jalan_key);
			tulisLog('proses_daftar_pesanan.php -> response : '.$response);
			echo json_encode($response);
			exit();

		} else {

			tulisLog('proses_daftar_pesanan.php -> insert ke surat_jalan_detail -> GAGAL : '.mysqli_error($con));
			$response = array('response_code' => '99', 'response_msg' => 'Surat jalan gagal dicetak dan disimpan : ' . mysqli_error($con));
			tulisLog('proses_daftar_pesanan.php -> response : '.$response);
			echo json_encode($response);
			exit();

		}

	} else {
		tulisLog('proses_daftar_pesanan.php -> insert ke surat_jalan -> GAGAL : ' . mysqli_error($con));
		$response = array('response_code' => '99', 'response_msg' => 'Pesanan gagal disimpan : ' . mysqli_error($con));
		echo json_encode($response);
		exit();
	}

} elseif ($jenis_proses == "print_suratjalan") {

	tulisLog("proses_daftar_pesanan.php -> print_suratjalan");

	$surat_jalan_tanggal = $formatDateNow;
	$nomor_suratjalan = $_POST['surat_jalan_nomor'];
	$id_pelanggan = $_POST['surat_jalan_pelanggan_id'];
	$nama_barang = "";
	$status_suratjalan = $_POST['surat_jalan_status'];
	$qty_barang = 0;
	$qty_packing = 0;
	$keterangan = "";

	$sel_psn = "SELECT * FROM surat_jalan WHERE surat_jalan_no_pesanan = '".$nomor_pesanan."'";

	tulisLog("proses_daftar_pesanan.php -> sel_psn : ".$sel_psn);

	$query_psn = $con->query($sel_psn);
	while ($row = $query_psn->fetch_assoc()) {
		$qty_barang = $row['pesanan_total_qty'];
		$qty_packing = $row['pesanan_total_qty_packing'];
	}

	// $surat_jalan_total_barang = (int)$qty_barang * (int)$qty_packing;
	$surat_jalan_total_barang = (int)$qty_barang;
	$keterangan = $qty_barang + " x " + $qty_packing;
	
	if ($status_suratjalan == "") {
		tulisLog("proses_daftar_pesanan.php -> status_suratjalan : ".$status_suratjalan);

		$sql_pesanan = "INSERT INTO surat_jalan "
				. " (cre_tms, upd_tms, cre_usr, upd_usr,"
				. " surat_jalan_nomor,"
				. " surat_jalan_tanggal,"
				. " surat_jalan_pelanggan_id,"
				. " surat_jalan_no_pesanan,"
				. " surat_jalan_total_barang,"
				. " surat_jalan_status"
				. ")"
				. " VALUES"
				. " (NOW(), NOW(), '".$username."', '".$username."',"
				. " '".$nomor_suratjalan."',"
				. " '$surat_jalan_tanggal',"
				. " ".$id_pelanggan.","
				. " '".$nomor_pesanan."',"
				. " $surat_jalan_total_barang,"
				. " ''"
				. ")"
				. "";

		tulisLog('proses_daftar_pesanan.php -> insert ke surat_jalan -> sql_pesanan : '.$sql_pesanan);

		if ( runUpdateInsertQuery($sql_pesanan) ) {

			tulisLog('proses_daftar_pesanan.php -> insert ke surat_jalan -> BERHASIL');

			$upd_psn = "UPDATE pesanan p"
				. " SET"
				. " p.upd_tms = NOW(),"
				. " p.upd_usr = '" . $username . "',"
				// . " p.pesanan_status = 'Sudah Dicetak'"
				. " WHERE p.pesanan_no_pesanan = '".$nomor_pesanan."'";

			tulisLog("proses_daftar_pesanan.php -> upd_psn : ".$upd_psn);

			runUpdateInsertQuery($upd_psn);

			// select detail pesanan
			$sel_psn = "SELECT *"
				. " FROM detail_pesanan"
				. " WHERE detail_pesanan_no_pesanan = '".$nomor_pesanan."'";

			tulisLog("proses_daftar_pesanan.php -> sel_psn : ".$sel_psn);

			$query_psn = $con->query($sel_psn);
			while ($row = $query_psn->fetch_assoc()) {
				array_push($resultTmp, $row);
			}

			$sql_detail = "INSERT INTO surat_jalan_detail "
					. " (cre_tms, upd_tms, cre_usr, upd_usr,"
					. " surat_jalan_detail_surat_jalan_key,"
					. " surat_jalan_detail_nama_barang,"
					. " surat_jalan_detail_subtotal_barang,"
					. " surat_jalan_detail_satuan,"
					. " surat_jalan_detail_keterangan)"
					. " VALUES "
					. "";

			$value_detail = "";
			$subtotal_barang = 0;

			foreach ($resultTmp as $value) {
				
				$comma = ",";
				if ( !next( $resultTmp ) ) {
			        $comma = "";
			    }

			    // $subtotal_barang = (int)$value['detail_pesanan_qty'] * (int)$value['detail_pesanan_qty_packing'];
			    $subtotal_barang = (float)$value['detail_pesanan_qty'] * (int)$value['detail_pesanan_qty_packing'];
			    $keterangan = $value['detail_pesanan_qty'] . " x " . $value['detail_pesanan_qty_packing'];

				$value_detail .= "("
					. " NOW(), NOW(), '".$username."', '".$username."',"
					. " '" . $nomor_suratjalan . "',"
					. " '" . $value['detail_pesanan_nama_barang'] . "',"
					// . " " . $value['detail_pesanan_qty'] . ","
					. " " .$subtotal_barang. ","
					. " '"  . $value['detail_pesanan_satuan'] . "',"
					. " '" .$keterangan. "'"
					. ")"
					. $comma;

			}

			$sql_detail .= $value_detail;
			tulisLog('proses_daftar_pesanan.php -> insert ke surat_jalan_detail -> sql_detail : '.$sql_detail);

			if ( runUpdateInsertQuery($sql_detail) ) {

				tulisLog('proses_daftar_pesanan.php -> insert ke surat_jalan_detail -> BERHASIL');
				
				$response = array('response_code' => '00', 'response_msg' => 'Surat jalan berhasil dicetak dan disimpan');

				tulisLog('proses_daftar_pesanan.php -> response : '.$response);

				echo json_encode($response);

				exit();

			} else {

				tulisLog('proses_daftar_pesanan.php -> insert ke surat_jalan_detail -> GAGAL : '.mysqli_error($con));

				$response = array('response_code' => '99', 'response_msg' => 'Surat jalan gagal 4 dan disimpan : ' . mysqli_error($con));

				tulisLog('proses_daftar_pesanan.php -> response : '.$response);

				echo json_encode($response);
				exit();

			}

		} else {
			tulisLog('proses_daftar_pesanan.php -> insert ke surat_jalan -> GAGAL : ' . mysqli_error($con));

			$response = array('response_code' => '99', 'response_msg' => 'Pesanan gagal disimpan : ' . mysqli_error($con));
			echo json_encode($response);
			exit();
		}

	} else {

		tulisLog("proses_daftar_pesanan.php -> status_suratjalan : ".$status_suratjalan);
		$response = array('response_code' => '11', 'response_msg' => 'Surat jalan sudah pernah dicetak');
		echo json_encode($response);
		exit();

	}

} 
?>