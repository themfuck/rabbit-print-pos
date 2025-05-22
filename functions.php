<?php 

/*
 * Masukkan setiap perubahan yang dilakukan di tabel ini.
 * 
 * NAMA                VERSI       TGL(DD-MM-YYYY)        PERUBAHAN
 * ========================================================================================================
 * IKHSAN                1.0       12-09-2018             Pembuatan awal (create)
 *
 */

// Detect IP
function info_client_ip_getenv() {
     $ipaddress = '';
     if (getenv('HTTP_CLIENT_IP'))
         $ipaddress = getenv('HTTP_CLIENT_IP');
     else if(getenv('HTTP_X_FORWARDED_FOR'))
         $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
     else if(getenv('HTTP_X_FORWARDED'))
         $ipaddress = getenv('HTTP_X_FORWARDED');
     else if(getenv('HTTP_FORWARDED_FOR'))
         $ipaddress = getenv('HTTP_FORWARDED_FOR');
     else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
     else if(getenv('REMOTE_ADDR'))
         $ipaddress = getenv('REMOTE_ADDR');
     else
         $ipaddress = 'UNKNOWN';

     return $ipaddress; 
}

// Detect PC 
function info_pc_dan_browser() { 
	$ua = $_SERVER["HTTP_USER_AGENT"];
	return strtolower($ua); 
} 

function runUpdateInsertQuery($query){
	global $con;

	if(mysqli_query($con, $query)){
		return true;
	} else {
		return false;
	}
}

function tulisLog(String $msg, $ind = "W"){
	global $log;
	global $logUsername;
	global $logToken;
	global $logIp;

	$msg = $logUsername." | ".$logToken." | ".$logIp." | ".$msg;

	for ($i=0;$i<10;$i++){
		$msg = str_replace("  ", " ", $msg);
	}

	if(strcasecmp("W", $ind)==0){
		$log->warning($msg);
	}elseif(strcasecmp("E", $ind)==0){
		$log->error($msg);
	}
}

function cekJumlahUserLogin() {
	global $con;

	$jml_user_login = 0;
	$seluser = "SELECT * FROM s_login sl"
				. " WHERE sl.id_user = ".$_SESSION['user_id']
				. " AND sl.id_user > 0"
				. " AND sl.status = 1";

	tulisLog('functions.php -> cekJumlahUserLogin() -> $seluser : '.$seluser);

	$rs = $conn->query($seluser);

	if ($rs->num_rows > 0) {

		while ($row = $rs->fetch_assoc()) {
			$user_login = $row;
		}

		$jml_user_login = count($user_login);
	}

	tulisLog('functions.php -> cekJumlahUserLogin() -> jml login : '.$jml_user_login);

	return $jml_user_login;

}

function getKodePesanan($sel_date) {
	global $con;

	$sel_date = date_create($sel_date);
	$sel_date = date_format($sel_date, 'Ymd');
	$psn_key = "PSN/".$sel_date."/";
	$selectkey = "SELECT MAX(pesanan.pesanan_no_pesanan) AS psnkey FROM pesanan"
				. " WHERE pesanan.pesanan_no_pesanan LIKE '%". $psn_key ."%'";
	tulisLog('functions.php -> getKodePesanan() -> selectkey : '.$selectkey);
	$rs = $con->query($selectkey);
	while ($row = $rs->fetch_assoc()) {
		$psn_key = $row['psnkey'];
	}
	if($psn_key == null || $psn_key == " ") {
		$psn_key = "PSN/".$sel_date."/001";
	} else {
		$split_date = explode("/", $psn_key);
		if ($split_date[1] == $sel_date) {
			$num = intval($split_date[2]);
			$num = $num + 1;
			$num = str_pad($num,3,"0",STR_PAD_LEFT);
			$psn_key = $split_date[0]."/".$split_date[1]."/".$num;
		} else {
			$psn_key = "PSN/".$sel_date."/001";
		}
	}
	tulisLog('functions.php -> getKodePesanan() -> psn_key : '.$psn_key);
	return $psn_key;
}

function getKodeInvoice() {
	global $con;
	$sel_date = date('Ymd');
	$psn_key = "INV/".$sel_date."/";
	$selectkey = "SELECT MAX(inv.invoice_keluar_no_invoice) AS psnkey FROM invoice_keluar inv"
				. " WHERE inv.invoice_keluar_no_invoice LIKE '%". $psn_key ."%'";
	tulisLog('functions.php -> getKodeInvoice() -> selectkey : '.$selectkey);
	$rs = $con->query($selectkey);
	while ($row = $rs->fetch_assoc()) {
		$psn_key = $row['psnkey'];
	}
	if($psn_key == null || $psn_key == " ") {
		$psn_key = "INV/".$sel_date."/001";
	} else {
		$split_date = explode("/", $psn_key);
		if ($split_date[1] == $sel_date) {
			$num = intval($split_date[2]);
			$num = $num + 1;
			$num = str_pad($num,3,"0",STR_PAD_LEFT);
			$psn_key = $split_date[0]."/".$split_date[1]."/".$num;
		} else {
			$psn_key = "INV/".$sel_date."/001";
		}
	}
	tulisLog('functions.php -> getKodeInvoice() -> psn_key : '.$psn_key);
	return $psn_key;
}

function getDetailPesananKey() {
	global $con;
	// 0000000001
	$detail_key = "0000000000";
	$selectkey = "SELECT MAX(detail_pesanan_key) AS detail_key FROM detail_pesanan;";

	tulisLog('functions.php -> getDetailPesananKey() -> selectkey : '.$selectkey);

	$rs = $con->query($selectkey);
	while ($row = $rs->fetch_assoc()) {
		$detail_key = $row['detail_key'];
	}

	if($detail_key == null || $detail_key == " ") {
		$detail_key = "0000000001";
	} else {
		$num = intval(substr($detail_key,0,11));
		$num = $num+1;
		$detail_key = str_pad($num,10,"0",STR_PAD_LEFT);
	}
	tulisLog('functions.php -> getDetailPesananKey() -> detail_key : '.$detail_key);
	return $detail_key;
}

function getDetailPesanan($id_pesanan, $nomor_pesanan) {
	global $con;
	$selpsn = "SELECT psn.pesanan_id, psn.pesanan_no_pesanan, dps.* "
			. " FROM pesanan psn, detail_pesanan dps"
			. " WHERE dps.detail_pesanan_no_pesanan = '".$nomor_pesanan."'"
			. " AND psn.pesanan_no_pesanan = dps.detail_pesanan_no_pesanan"
			. " AND psn.pesanan_id = ".$id_pesanan;
	tulisLog('functions.php -> getDetailPesanan -> selpsn : '.$selpsn);
	$rs = $con->query($selpsn);
	return $rs;
}

function getKodeSuratJalan($sel_date) {
	global $con;
	$sel_date = date_create($sel_date);
	$sel_date = date_format($sel_date, 'Ymd');
	$psn_key = "SRJ/".$sel_date."/";
	$selectkey = "SELECT MAX(surat_jalan.surat_jalan_key) AS psnkey FROM surat_jalan"
				. " WHERE surat_jalan.surat_jalan_key LIKE '%". $psn_key ."%'";
	tulisLog('functions.php -> getKodeSuratJalan() -> selectkey : '.$selectkey);
	$rs = $con->query($selectkey);
	while ($row = $rs->fetch_assoc()) {
		$psn_key = $row['psnkey'];
	}
	if($psn_key == null || $psn_key == " ") {
		$psn_key = "SRJ/".$sel_date."/001";
	} else {
		$split_date = explode("/", $psn_key);
		if ($split_date[1] == $sel_date) {
			$num = intval($split_date[2]);
			$num = $num + 1;
			$num = str_pad($num,3,"0",STR_PAD_LEFT);
			$psn_key = $split_date[0]."/".$split_date[1]."/".$num;
		} else {
			$psn_key = "SRJ/".$sel_date."/001";
		}
	}
	tulisLog('functions.php -> getKodeSuratJalan() -> psn_key : '.$psn_key);
	return $psn_key;
}

function js_str($s)
{
	return '"' . addcslashes($s, "\0..\37\"\\") . '"';
}

function js_int($s)
{
	return addcslashes($s, "\0..\37\"\\");
}

function js_array($array)
{
	$temp = array_map('js_str', $array);
	return '[' . implode(',', $temp) . ']';
}

function js_arrayInt($array)
{
	$temp = array_map('js_int', $array);
	return '[' . implode(',', $temp) . ']';
}

?>