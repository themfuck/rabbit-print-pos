<?php 

/*
 * HISTORY VERSIONING
 * PROGRAMMER 
 * INITIAL              VERSION         DATE(YYYY-MM-DD)        CHANGE DESCRIPTION
 * ========================================================================================================
 * RAMDAN                1.0                                     Create
 * IKHSAN                1.1            2018-09-08               ganti syntax mysql/query
 *																 menambahkan php logger
 *
 */

// memanggil logger
require 'vendor/autoload.php';
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('RabbitPrint');
$log->pushHandler(new StreamHandler(__DIR__.'\logs\rabbitLog_'.date('Ymd').'.log', Logger::WARNING));
$log->setTimezone(new DateTimeZone('Asia/Jakarta'));

// Variable Koneksi ke Database 
// ===================================================== 

$host = 'localhost';
$user = 'root';
$pass = 'root123';
$db = 'db_rabbitprint';
$port = '3306'; // 1.1 [S.E]

// ===================================================== 

// Setting Time zone
date_default_timezone_set('Asia/Jakarta'); 
$waktu_server = date('Y-m-d H:i:s', time());

// Memulai session baru
if(!isset($_SESSION)) {
	session_start();
}

// Setting tidak menampilkan error 
error_reporting(0); 

// Membuat Koneksi ke database 
// 1.1 [S]
// $con = mysql_connect($host,$user,$pass);
$con = new mysqli($host, $user, $pass, $db, $port);
// 1.1 [E]

// Menangani kondisi koneksi 
if (!$con) {
	die('Tidak ada koneksi ke Database : ' . mysql_error());
} 

// Memilih Database 
// mysql_select_db($db, $con); // 1.1 [S.E]

// Memuat functions 
include("functions.php");

// Memuat informasi User Login 
// Mendeteksi informasi tentang user login 
// $datauserlogin = fb_cek_user_login(); 

// Data berikut ini ada saat user login 
// Memuat informasi Sudah Login atau Belum 
// $user_sedang_login bernilai true saat user sedang login 
// if(isset($datauserlogin[0])) {
// 	$user_sedang_login = $datauserlogin[0]; 
// } else {
// 	$user_sedang_login = false; 
// }

// Data berikut ini ada saat user login 
// Memuat informasi Hak Akses 
// $hak_akses bernilai 1 sampai 10, jika user sedang login 
// if(isset($datauserlogin[2]['hak_akses'])) {
// 	$hak_akses = $datauserlogin[2]['hak_akses']; 
// } else {
// 	$hak_akses = 0; 
// }

?>