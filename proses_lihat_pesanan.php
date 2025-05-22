<?php 

include_once("konfigurasi.php");

$no_pesanan 	= $_POST['no_pesanan'];
$no_surat_jalan = $_POST['no_surat_jalan'];
$no_pelanggan   = $_POST['no_pelanggan'];
$jenis_pesanan  = $_POST['jenis_pesanan'];
$subtotal_qty   = 0;
$total_qty      = 0;
$subtotal_pck   = 0;
$total_pck      = 0;
$list_barang    = $_POST['list_barang'];
$array_brg		= json_decode( $list_barang, true );
$username		= $_SESSION['user_username'];
$response 		= array();
$detail_pesanan_status = "";

tulisLog("proses_lihat_pesanan.php -> load..");

// UPDATE PESANAN BERDASARKAN NO PESANAN
// $upd_pesanan = "UPDATE pesanan SET upd_tms = NOW()"
// 			. ", pesanan_no_surat_jalan_masuk = '" . $no_surat_jalan . "'"
// 			. ", pesanan_total_qty = " . $total_qty
// 			. ", pesanan_total_qty_packing = " . $total_pck
// 			. " WHERE pesanan_no_pesanan = '" . $no_pesanan . "'";
// tulisLog("proses_lihat_pesanan.php -> upd_pesanan : ".$upd_pesanan);
// if (runUpdateInsertQuery($upd_pesanan)) {
// 	tulisLog("proses_lihat_pesanan.php -> upd_pesanan : SUCCESS");
// } else {
// 	tulisLog('proses_lihat_pesanan.php -> update pesanan -> GAGAL : '.mysqli_error($con));
// 	$response = array('response_code' => '99', 'response_msg' => 'Pesanan gagal di update : ' . mysqli_error($con));
// 	echo json_encode($response);
// 	exit();
// }

foreach ($array_brg as $value) {
	$detail_pesanan_nama_barang = $value['detail_pesanan_nama_barang'];
	$detail_pesanan_jenis_print = $value['detail_pesanan_jenis_print'];
	$detail_pesanan_warna = $value['detail_pesanan_warna'];
	$detail_pesanan_satuan = $value['detail_pesanan_satuan'];
	$detail_pesanan_jenis_packing = $value['detail_pesanan_jenis_packing'];

	foreach ($value['packing_list'] as $pck_list) {

		$detail_pesanan_detail_kain = $pck_list['detail_pesanan_detail_kain'];
		$detail_pesanan_ukuran 		= $pck_list['detail_pesanan_ukuran'];
		$detail_pesanan_nama_warna	= $pck_list['detail_pesanan_nama_warna'];
		$detail_pesanan_sisi_kanan 	= $pck_list['detail_pesanan_sisi_kanan'];
		$detail_pesanan_sisi_kiri 	= $pck_list['detail_pesanan_sisi_kiri'];
		$detail_pesanan_sisi_blkng 	= $pck_list['detail_pesanan_sisi_blkng'];
		$detail_pesanan_qty 		= $pck_list['detail_pesanan_qty'];
		$detail_pesanan_qty_packing = $pck_list['detail_pesanan_qty_packing'];
		$detail_pesanan_keterangan  = $pck_list['detail_pesanan_keterangan'];
		$detail_pesanan_key         = $pck_list['detail_pesanan_key'];

		$sql_detail = "UPDATE detail_pesanan SET upd_tms = NOW()"
					. ", detail_pesanan_nama_barang = '"  .$detail_pesanan_nama_barang. "'"
					. ", detail_pesanan_jenis_print = '"  .$detail_pesanan_jenis_print. "'"
					. ", detail_pesanan_warna = " 		  .$detail_pesanan_warna
					. ", detail_pesanan_detail_kain = '"  .$detail_pesanan_detail_kain. "'"
					. ", detail_pesanan_nama_warna = '"	  .$detail_pesanan_nama_warna. "'"
					. ", detail_pesanan_ukuran = '"  	  .$detail_pesanan_ukuran. "'"
					. ", detail_pesanan_sisi_kanan = '"   .$detail_pesanan_sisi_kanan. "'"
					. ", detail_pesanan_sisi_kiri = '" 	  .$detail_pesanan_sisi_kiri. "'"
					. ", detail_pesanan_sisi_blkng = '"   .$detail_pesanan_sisi_blkng. "'"
					. ", detail_pesanan_qty = "   		  .$detail_pesanan_qty
					. ", detail_pesanan_satuan = '"		  .$detail_pesanan_satuan. "'"
					. ", detail_pesanan_jenis_packing = '".$detail_pesanan_jenis_packing. "'"
					. ", detail_pesanan_qty_packing = "   .$detail_pesanan_qty_packing
					. ", detail_pesanan_keterangan = '"	  .$detail_pesanan_keterangan. "'"
					. " WHERE detail_pesanan_key = '".$detail_pesanan_key."'";

		tulisLog('proses_lihat_pesanan.php -> update detail_pesanan -> sql_detail : '.$sql_detail);

		runUpdateInsertQuery($sql_detail);

	}
}

$select_detail = "SELECT detail_pesanan_qty, detail_pesanan_qty_packing FROM detail_pesanan WHERE detail_pesanan_no_pesanan = '".$no_pesanan."'";
tulisLog("proses_lihat_pesanan.php -> select_detail : ".$select_detail);
$query_psn = $con->query($select_detail);
while ($row = $query_psn->fetch_assoc()) {

	$detail_pesanan_qty 		= $row['detail_pesanan_qty'];
	$detail_pesanan_qty_packing = $row['detail_pesanan_qty_packing'];

    if ($jenis_pesanan == "U" || $jenis_pesanan == "E") {
		// hitung qty untuk update pesanan
		$qty = (double) $detail_pesanan_qty;
		$pck = (int) $detail_pesanan_qty_packing;
		$total_qty = $total_qty + ($qty * $pck);
		$total_pck = $total_pck + $pck;

	} else if ($jenis_pesanan == "A" || $jenis_pesanan == "B" || $jenis_pesanan == "C" || $jenis_pesanan == "D") {
		$divide = 0;
		if ( (double) $detail_pesanan_sisi_kanan > 0 ) {
			$divide++;
		}
		if ( (double) $detail_pesanan_sisi_kiri > 0 ) {
			$divide++;
		}
		if ( (double) $detail_pesanan_sisi_blkng > 0 ) {
			$divide++;
		}
		$pck = (double) $detail_pesanan_sisi_kanan + (double) $detail_pesanan_sisi_kiri + (double) $detail_pesanan_sisi_blkng;
		$total_pckTmp = $total_pckTmp + $pck;
		$qty = $total_pckTmp / $divide;
		$total_qtyTmp = $total_qtyTmp + $qty;

		$detail_pesanan_qty = $qty;
		$detail_pesanan_qty_packing = $pck;

		$total_qty = $total_qty + $total_qtyTmp;
		$total_pck = $total_pck + $total_pckTmp;
		$qty = 0;
		$pck = 0;
		$total_qtyTmp = 0;
		$total_pckTmp = 0;
	} 
}

$sql = "UPDATE pesanan p SET p.pesanan_total_qty = ".$total_qty.", p.pesanan_total_qty_packing = " .$total_pck
		. ", pesanan_no_surat_jalan_masuk = '" . $no_surat_jalan . "'"
		. " WHERE p.pesanan_no_pesanan = '".$no_pesanan."'";

if (runUpdateInsertQuery($sql)) {

	tulisLog('proses_lihat_pesanan.php -> update ke detail_pesanan -> BERHASIL');

	$response = array('response_code' => '00', 'response_msg' => 'Pesanan berhasil disimpan');
	echo json_encode($response);
	exit();

} else {

	tulisLog('proses_lihat_pesanan.php -> update ke detail_pesanan -> GAGAL : '.mysqli_error($con));
	$response = array('response_code' => '99', 'response_msg' => 'Detail pesanan gagal disimpan : ' . mysqli_error($con));
	echo json_encode($response);
	exit();

}

tulisLog('proses_lihat_pesanan.php -> page close.');


?>