<?php 
/*
 * Masukkan setiap perubahan yang dilakukan di tabel ini.
 * 
 * NAMA                VERSI       TGL(DD-MM-YYYY)        PERUBAHAN
 * ========================================================================================================
 * IKHSAN                1.0       12-09-2018             Pembuatan awal (create)
 *
 */

include_once("konfigurasi.php");

$user_id = 0;

if ( isset($_SESSION['user_id']) ) {
	$user_id = $_SESSION['user_id'];
};

if ($user_id != 0) {
	header("location:home.php");
} else {
	header("location:form_login.php");
}

?>