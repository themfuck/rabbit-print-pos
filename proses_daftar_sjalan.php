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
$no_suratjalan = $_POST['no_suratjalan'];
$result = array();
$resultTmp = array();
$response = array();
$username = $_SESSION['user_username'];

if ($jenis_proses == "lihat_detail") {

	tulisLog("proses_daftar_sjalan.php -> lihat_detail");
	$sel_detailsj = "SELECT * FROM surat_jalan_detail WHERE surat_jalan_detail_surat_jalan_nomor = '".$no_suratjalan."'";
	
	tulisLog("proses_daftar_sjalan.php -> sel_detailpsn : ".$sel_detailsj);

	$query_sj = $con->query($sel_detailsj);
	while ($row = $query_sj->fetch_assoc()) {
		array_push($resultTmp, $row);
	}

	foreach ($resultTmp as $value) {
		$resultTmp = array(
			'surat_jalan_detail_id' => $value['surat_jalan_detail_id'],
			'surat_jalan_detail_nama_barang' => $value['surat_jalan_detail_nama_barang'],
			'surat_jalan_detail_subtotal_barang' => $value['surat_jalan_detail_subtotal_barang'],
			'surat_jalan_detail_keterangan' => $value['surat_jalan_detail_keterangan']
		);
		array_push($result, $resultTmp);
	}

	echo json_encode($result);

} elseif ($jenis_proses == "hapus_srtjalan") {

	tulisLog("proses_daftar_sjalan.php -> hapus_srtjalan");
	// hapus surat jalan
	$del_srtjalan = "DELETE FROM surat_jalan WHERE surat_jalan_key = '" . $no_suratjalan . "'";
	tulisLog("proses_daftar_sjalan.php -> del_srtjalan : ".$del_srtjalan);

	if ( runUpdateInsertQuery($del_srtjalan) ) {
		// hapus detail surat jalan
		tulisLog("proses_daftar_sjalan.php -> del_srtjalan -> BERHASIL");
		$del_detailpsn = "DELETE FROM surat_jalan_detail WHERE surat_jalan_detail_surat_jalan_key = '" . $no_suratjalan . "'";
		tulisLog("proses_daftar_sjalan.php -> del_detailpsn : ".$del_detailpsn);

		if ( runUpdateInsertQuery($del_detailpsn) ) {
			// select (cari) apakah surat jalan sudah memiliki invoice
			tulisLog("proses_daftar_sjalan.php -> del_detailpsn -> BERHASIL");
			$no_invoice = '';
			$harga = '';
			$count_inv = 0;
			// get nomor dan harga dari invoice untuk surat jalan yg akan di hapus
			$sel_sj = "SELECT invoice_keluar_detail_no_invoice, invoice_keluar_detail_subtotal_harga FROM invoice_keluar_detail WHERE invoice_keluar_detail_surat_jalan_nomor = '" . $no_suratjalan . "'";
			tulisLog("proses_daftar_sjalan.php -> sel_sj : ".$sel_sj);
			$query_inv = $con->query($sel_sj);
			while ($row = $query_inv->fetch_assoc()) {
				$no_invoice = $row['invoice_keluar_detail_no_invoice'];
				$harga = $row['invoice_keluar_detail_subtotal_harga'];
			}
			// hitung jml detail invoice dari invoice nomor yg didapatkan
			$sel_sj = "SELECT COUNT(invoice_keluar_detail_no_invoice) AS cnt FROM invoice_keluar_detail WHERE invoice_keluar_detail_no_invoice = '".$no_invoice."'";
			$query_inv = $con->query($sel_sj);
			while ($row = $query_inv->fetch_assoc()) {
				$count_inv = $row['cnt'];
			}

			// hapus detail invoice
			$del_detailpsn = "DELETE FROM invoice_keluar_detail WHERE invoice_keluar_detail_surat_jalan_nomor = '" . $no_suratjalan . "'";
			tulisLog("proses_daftar_sjalan.php -> del_detailpsn : ".$del_detailpsn);

			if ( runUpdateInsertQuery($del_detailpsn) ) {
				$response = array('response_code' => '00', 'response_msg' => 'Surat jalan berhasil dihapus');

				if ($count_inv > 1) {
					// jika detail invoice > 1, update harga invoice tsb (dikurangi harga invoice surat jalan yg dihapus)		
					$harga_total = 0;
					$sel_sj = "SELECT invoice_keluar_total_harga FROM invoice_keluar WHERE invoice_keluar_no_invoice = '".$no_invoice."'";
					$query_sj = $con->query($sel_sj);
					while ($row = $query_sj->fetch_assoc()) {
						$harga_total = $row['invoice_keluar_total_harga'];
					}
					$harga_total = $harga_total - $harga;
					$del_detailpsn = "UPDATE invoice_keluar SET invoice_keluar_total_harga = '".$harga_total."' WHERE invoice_keluar_no_invoice = '".$no_invoice."'";
					if ( runUpdateInsertQuery($del_detailpsn) ) {
						$response = array('response_code' => '00', 'response_msg' => 'Surat jalan berhasil dihapus & harga invoice sudah diperbarui.');
					}

				} elseif ($count_inv == 1) {
					// jika detail invoice hanya 1, hapus detail invoice dan invoice nya
					$del_sj = "DELETE FROM invoice_keluar WHERE invoice_keluar_no_invoice = '".$no_invoice."'";
					if (runUpdateInsertQuery($del_sj)) {
						$response = array('response_code' => '00', 'response_msg' => 'Surat jalan berhasil dihapus.');
					} else {
						$response = array('response_code' => '99', 'response_msg' => 'Terjadi kesalahan saat menghapus surat jalan.');
					}
				}

				echo json_encode($response);
				exit();
			} else {
				$response = array('response_code' => '99', 'response_msg' => 'Surat jalan tidak dapat dihapus');
				echo json_encode($response);
				exit();
			}

		} else {

			tulisLog("proses_daftar_sjalan.php -> del_detailpsn -> GAGAL");

			$response = array('response_code' => '99', 'response_msg' => 'Surat jalan gagal dihapus');
			echo json_encode($response);
			exit();
		}

	} else {

		tulisLog("proses_daftar_sjalan.php -> del_detailpsn -> GAGAL");
		$response = array('response_code' => '99', 'response_msg' => 'Pesanan gagal dihapus');
		echo json_encode($response);
		exit();
	}

} elseif ($jenis_proses == "update_pesanan") {

	tulisLog("proses_daftar_sjalan.php -> update_pesanan");

	$pesanan_no_surat_jalan_masuk = $_POST['pesanan_no_surat_jalan_masuk'];
	$pesanan_jenis_pesanan = $_POST['pesanan_jenis_pesanan'];
	$pesanan_tanggal_pesanan = $_POST['pesanan_tanggal_pesanan'];
	$pesanan_pelanggan_id = $_POST['pesanan_pelanggan_id'];
	$pesanan_subtotal_qty = $_POST['pesanan_subtotal_qty'];
	$pesanan_subtotal_qty_packing = $_POST['pesanan_subtotal_qty_packing'];
	$jml_barang_skrg = $_POST['jml_barang_skrg'];
	$list_barang = $_POST['list_barang'];
	$array_brg = json_decode( $list_barang, true );
	$cnt_barang = count($array_brg);

	// tulisLog("proses_daftar_sjalan.php -> cnt_barang : ".$cnt_barang." : ".$jml_barang_skrg);

	$upd_psn = "UPDATE pesanan p"
			. " SET"
			. " p.upd_tms = NOW(),"
			. " p.upd_usr = '" . $username . "',"
			. " p.pesanan_no_surat_jalan_masuk = '" .$pesanan_no_surat_jalan_masuk. "',"
			. " p.pesanan_jenis_pesanan = '" .$pesanan_jenis_pesanan. "',"
			. " p.pesanan_tanggal_pesanan = '" .$pesanan_tanggal_pesanan. "',"
			. " p.pesanan_pelanggan_id = " .$pesanan_pelanggan_id. ","
			. " p.pesanan_subtotal_qty = " .$pesanan_subtotal_qty. ","
			. " p.pesanan_subtotal_qty_packing = " .$pesanan_subtotal_qty_packing
			. " WHERE P.pesanan_no_pesanan = '".$nomor_pesanan."'";

	tulisLog("proses_daftar_sjalan.php -> upd_psn : ".$upd_psn);

	if ( runUpdateInsertQuery($upd_psn) ) {

		tulisLog("proses_daftar_sjalan.php -> upd_psn -> BERHASIL");

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
				. " p.detail_pesanan_warna = " .$value['detail_pesanan_warna']. ","
				. " p.detail_pesanan_keterangan = '" .$value['detail_pesanan_keterangan'] ."'"
				. " WHERE P.detail_pesanan_no_pesanan = '".$nomor_pesanan."'"
				. " AND p.detail_pesanan_id = ".$value['detail_pesanan_id'];

			tulisLog("proses_daftar_sjalan.php -> upd_detailpsn : ".$upd_detailpsn);

			if ( runUpdateInsertQuery($upd_detailpsn) ) {
				tulisLog("proses_daftar_sjalan.php -> upd_detailpsn -> BERHASIL");
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
}

?>