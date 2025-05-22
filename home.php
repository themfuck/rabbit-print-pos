<?php 

/*
 * Masukkan setiap perubahan yang dilakukan di tabel ini.
 * 
 * NAMA                VERSI       TGL(DD-MM-YYYY)        PERUBAHAN
 * ========================================================================================================
 * IKHSAN                1.0       12-09-2018             Pembuatan awal (create)
 *
 */

?>

<!DOCTYPE html>
<html>

<?php 

include_once("head.php"); 

$user_id = $_SESSION['user_id'];
$user_nama = $_SESSION['user_nama'];

?>

<body class="hold-transition skin-blue-light sidebar-collapse sidebar-mini">
	<div class="wrapper">

		<header class="main-header">
			<?php include_once("logo-header.php"); ?>
		</header>

	  	<aside class="main-sidebar">
        	<?php include_once("menu.php"); ?>
	  	</aside>

		<div class="content-wrapper">
			<section class="content">
				<!-- setiap content (isi) halaman dibuat disini -->
				<center>
					<h1>Selamat Datang</h1>
				</center>
			</section>
		</div>

	</div>

<?php include_once("footer.php"); ?>
<script>
  
  	$(document).ready(function() {
      	// document.getElementById("p_namaUser").innerText = "<?php echo $user_nama; ?>";

      	setSidebarToActive();

  	});

</script>
</body>
</html>