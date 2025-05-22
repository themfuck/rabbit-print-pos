<?php 


// Memuat konfigurasi 
include_once("konfigurasi.php");

tulisLog('logout.php -> user akan logout : ' . $_SESSION['user_id']);

$upd_login = "UPDATE s_login SET status = 0"
			. " WHERE id_user = " . $_SESSION['user_id'];

tulisLog('logout.php -> upd_login : ' . $upd_login);

if ( runUpdateInsertQuery($upd_login) ) {

	tulisLog('logout.php -> LOGOUT SUCCESS!');
	session_unset();
	session_destroy();
	header("location:index.php");

} else {

	tulisLog('logout.php -> LOGOUT FAILED! : ' . mysqli_error($con));
	header("location:home.php");

}

$mysqli->close();

?>