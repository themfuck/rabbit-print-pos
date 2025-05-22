<?php 

/*
 * Masukkan setiap perubahan yang dilakukan di tabel ini.
 * 
 * NAMA                VERSI       TGL(DD-MM-YYYY)        PERUBAHAN
 * ========================================================================================================
 * Vandi                1.0         02-10-2018             Pembuatan awal (create)
 *
 */

include_once("konfigurasi.php");

$jenis_proses = $_POST['jenis_proses'];
$pelanggan_id = $_POST['pelanggan_id'];
$result = array();
$resultTmp = array();
$response = array();
$username = $_SESSION['user_username'];

if ($jenis_proses == "hapus_pelanggan") {

	tulisLog("proses_daftar_pelanggan.php -> hapus_pelanggan");
	$del_pelanggan = "DELETE FROM pelanggan WHERE pelanggan_id = " . $pelanggan_id;

	tulisLog("proses_daftar_pelanggan.php -> del_pelanggan : ".$del_pelanggan);
    if ( runUpdateInsertQuery($del_pelanggan) ) {

		tulisLog("proses_daftar_pelanggan.php -> del_pelanggan -> BERHASIL");
		$response = array('response_code' => '00', 'response_msg' => 'Pelanggan Berhasil dihapus');
		echo json_encode($response);
		exit();
    } else {

		tulisLog("proses_daftar_pelanggan.php -> del_pelanggan -> GAGAL");
		$response = array('response_code' => '99', 'response_msg' => 'Pelanggan Gagal dihapus : '.mysqli_error($con));
		echo json_encode($response);
		exit();

	}

} elseif ($jenis_proses == "ubah_pelanggan") {
	
	$pelanggan_nama = $_POST['pelanggan_nama'];
	$pelanggan_telepon = $_POST['pelanggan_telepon'];
	$pelanggan_alamat = $_POST['pelanggan_alamat'];

	tulisLog("proses_daftar_pelanggan.php -> ubah_pelanggan");
	
	$upd_pelanggan = "UPDATE pelanggan SET"
					. " upd_usr = '".$username."', upd_tms = NOW(),"
					. " pelanggan_nama = '".$pelanggan_nama."',"
					. " pelanggan_telp = '".$pelanggan_telepon."',"
					. " pelanggan_alamat = '".$pelanggan_alamat."'"
					. " WHERE pelanggan_id = ".$pelanggan_id;

	tulisLog("proses_daftar_pelanggan.php -> upd_pelanggan : ".$upd_pelanggan);

	if ( runUpdateInsertQuery($upd_pelanggan) ) {

		tulisLog("proses_daftar_pelanggan.php -> upd_pelanggan -> BERHASIL");
		$response = array('response_code' => '00', 'response_msg' => 'Data pelanggan Berhasil diubah');
		echo json_encode($response);
		exit();

	} else {

		tulisLog("proses_daftar_pelanggan.php -> upd_pelanggan -> GAGAL");
		$response = array('response_code' => '99', 'response_msg' => 'Pelanggan Gagal dihapus : '.mysqli_error($con));
		echo json_encode($response);
		exit();

	}

} elseif ($jenis_proses == "tambah_pelanggan") {
	
	$pelanggan_nama = $_POST['pelanggan_nama'];
	$pelanggan_telepon = $_POST['pelanggan_telepon'];
	$pelanggan_alamat = $_POST['pelanggan_alamat'];

	tulisLog("proses_daftar_pelanggan.php -> tambah_pelanggan");
	
	$ins_pelanggan = "INSERT INTO pelanggan"
					. " (cre_tms, upd_tms, cre_usr, upd_usr,"
					. " pelanggan_nama, pelanggan_telp, pelanggan_alamat)"
					. " VALUES"
					. " ( NOW(), NOW(), '".$username."', '".$username."',"
					. " '$pelanggan_nama','$pelanggan_telepon','$pelanggan_alamat' )";

	tulisLog("proses_daftar_pelanggan.php -> ins_pelanggan : ".$ins_pelanggan);

	if ( runUpdateInsertQuery($ins_pelanggan) ) {

		tulisLog("proses_daftar_pelanggan.php -> ins_pelanggan -> BERHASIL");
		$response = array('response_code' => '00', 'response_msg' => 'Data pelanggan Berhasil Ditambah');
		echo json_encode($response);
		exit();

	} else {

		tulisLog("proses_daftar_pelanggan.php -> ins_pelanggan -> GAGAL");
		$response = array('response_code' => '99', 'response_msg' => 'Pelanggan Gagal Ditambah : '.mysqli_error($con));
		echo json_encode($response);
		exit();

	}
}

?>