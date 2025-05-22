<?php 

/*
 * HISTORY VERSIONING
 * PROGRAMMER 
 * INITIAL              VERSION         DATE(YYYY-MM-DD)        CHANGE DESCRIPTION
 * ========================================================================================================
 * RAMDAN                1.0                                     Create
 * IKHSAN                1.1            2018-09-08               ganti syntax mysql/query
 *
 */

// Memuat konfigurasi 
include_once("konfigurasi.php");

if(!isset($_SESSION)) {
	session_start();
}

// Cek variable yang dikirim 
if(!isset($_POST['username'])) {
	echo "Tidak ada Username";
} 
if(!isset($_POST['password'])) {
	echo "Tidak ada Password";
}

// Proses variable 
$username = $_POST['username']; 
$password_post_md5 = md5($_POST['password']);  

// Membuat SQL 
$sql_user = "SELECT * FROM s_user"
			. " WHERE username LIKE '$username'"
			. " AND password LIKE '$password_post_md5'"
			. " LIMIT 1";

tulisLog('proses_login.php -> cek user -> sql_user : '.$sql_user);

// $query_user = mysql_query($sql_user); 
$query_user = $con->query($sql_user);

if ($query_user->num_rows > 0) {

	// mengambil variable dari hasil query
	while ($row = $query_user->fetch_assoc()) {
	
		$id_user = $row['id']; 

		// set variable untuk session
		$_SESSION['user_id'] = $row['id'];
		$_SESSION['user_nama'] = $row['nama'];
		$_SESSION['user_username'] = $row['username'];
		$_SESSION['user_hakakses'] = $row['hak_akses'];

		tulisLog('proses_login.php -> id_user : '.$id_user);

		$waktu_login = date('Y-m-d H:i:s', time());
		$waktu_kadaluarsa = date('Y-m-d H:i:s', time()+(24*3600)); 
		$key_login = time() . rand(1000,9000);

		// Detect IP User 
		$ip = info_client_ip_getenv(); 

		// Detect Informasi PC dan Browser 
		$pc_dan_browser = info_pc_dan_browser(); 

		// Catat user login dalam database 
		$sql_login = "INSERT INTO s_login"
					. " (key_login, id_user,"
					. " waktu_login, waktu_kadaluarsa,"
					. " status, ip,"
					. " pc_dan_browser)"
					. " VALUES ('$key_login', '$id_user',"
					. " '$waktu_login', '$waktu_kadaluarsa',"
					. " '1', '$ip', '$pc_dan_browser')"; 

		tulisLog('proses_login.php -> insert ke s_login -> sql_login : '.$sql_login);

		if ( runUpdateInsertQuery($sql_login) ) {
			tulisLog('proses_login.php -> insert ke s_login -> SUCCESS');
		} else {
			tulisLog('proses_login.php -> insert ke s_login -> FAILED : ' . mysqli_error($con));
		}

		tulisLog('proses_login.php -> LOGIN SUKSES!!');

		header("location:home.php"); 
		exit; 

	}

} else {
	
	tulisLog('proses_login.php -> LOGIN GAGAL!! Username atau Password salah.'); 
	exit;	

}

?>