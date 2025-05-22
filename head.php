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
if(!isset($_SESSION['user_nama'])) {
    header("Location:index.php");
}
?>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Rabbit Jaya Print</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
	<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
	<link rel="stylesheet" href="bower_components/morris.js/morris.css">
	<link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
	<!-- <link rel="stylesheet" href="dist/css/google-font.css"> -->
	<link rel="stylesheet" href="dist/css/custom_css.css">
	<link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
	<link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="plugins/iCheck/all.css">
	<link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

	<!-- Khusus Script Untuk load header-->
    <!-- <script src="dist/js/jquery.min.js"></script> -->
    <script src="dist/js/jquery-3.3.1.min.js"></script>
    <script src="dist/js/jquery.dataTables.min.js"></script>
    <script src="dist/js/custom_js.js"></script>
	<script>
		function showSuccessMessage(message) {
			$('#success_msg_dialog').modal({
				show: true,
		        keyboard: false,
		        backdrop: 'static'
			});
			document.getElementById('success_message').innerText = message;
		}

		function showInfoMessage(message) {
			$('#info_msg_dialog').modal({
				show: true,
		        keyboard: false,
		        backdrop: 'static'
			});
			document.getElementById('info_message').innerText = message;
		}

		function showFailedMessage(message) {
			$('#failed_msg_dialog').modal({
				show: true,
		        keyboard: false,
		        backdrop: 'static'
			});
			document.getElementById('failed_message').innerText = message;
		}
	</script>
</head>

<div class="modal fade" id="on_loading_modal">
	<center>
		<img class="img_modal" style="top: 50%" src="dist/img/loading_icon.gif" width="50px" height="50px">
	</center>
</div>

<div id="success_msg_dialog" class="modal fade">
    <div class="modal-dialog modal-lg" style="max-width: 600px">
      	<div class="modal-content success-dialog">
          	<div class="modal-body" style="padding: 15px">
          		<h4 class="modal-title">Berhasil</h4><br>
            	<p id="success_message" style="font-size: 16px">Proses berhasil</p>
          	</div>
          	<div class="modal-footer">
          		<button class="btn" onclick="location.reload()" style="background-color: #00cc99; color: white; font-weight: bold;" data-dismiss="modal">Tutup</button>
          	</div>
      	</div>
    </div>
</div>

<div id="info_msg_dialog" class="modal fade">
    <div class="modal-dialog modal-lg" style="max-width: 600px">
      	<div class="modal-content info-dialog">
          	<div class="modal-body" style="padding: 15px">
          		<h4 class="modal-title">Informasi</h4><br>
            	<p id="info_message" style="font-size: 16px">Informasi</p>
          	</div>
          	<div class="modal-footer">
          		<button class="btn" onclick="location.reload()" style="background-color: #ffb900; color: white; font-weight: bold;" data-dismiss="modal">Tutup</button>
          	</div>
      	</div>
    </div>
</div>

<div id="failed_msg_dialog" class="modal fade">
    <div class="modal-dialog modal-lg" style="max-width: 600px">
      	<div class="modal-content failed-dialog">
          	<div class="modal-body" style="padding: 15px">
          		<h4 class="modal-title">Gagal</h4><br>
            	<p id="failed_message" style="font-size: 16px">Proses gagal</p>
          	</div>
          	<div class="modal-footer">
          		<button class="btn" onclick="location.reload()" style="background-color: #ff1a1a; color: white; font-weight: bold;" data-dismiss="modal">Tutup</button>
          	</div>
      	</div>
    </div>
</div>