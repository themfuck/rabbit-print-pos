<?php 

/*
 * Masukkan setiap perubahan yang dilakukan di tabel ini.
 * 
 * NAMA                VERSI       TGL(DD-MM-YYYY)        PERUBAHAN
 * ========================================================================================================
 * IKHSAN                1.0       12-09-2018             Pembuatan awal (create)
 * Vandi                 1.1       03-10-2018             Penambahan Fungsi hapus
 */

include_once("head.php"); 
include_once("konfigurasi.php"); 

tulisLog('pelanggan.php -> membuka halaman');

$user_id = $_SESSION['user_id'];
$user_nama = $_SESSION['user_nama'];

$sel_pelanggan = "SELECT * FROM pelanggan";

tulisLog('pelanggan.php -> sel_pelanggan : ' . $sel_pelanggan);

$query_plg = $con->query($sel_pelanggan);

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
			<!-- judul halaman -->
			<section class="content-header">
		      	<h1>
		        	Pelanggan
		      	</h1>
		    </section>
			<section class="content">
				<!-- setiap content (isi) halaman dibuat disini -->
				<div class="box box-primary">
					<div class="box-header">
						<h3 class="box-title">Daftar Pelanggan</h3>
					</div>
					<div class="box-body">
						<button type="button" class="btn btn-primary" data-target="#modal-tambah-pelanggan" data-toggle="modal">Tambah Pelanggan</button>
						<table id="tbl_pelanggan" class="table table-bordered table-hover">
							<!-- header tabel (nama kolom) -->
							<thead>
								<tr>
									<th>No</th>
									<th>Id</th>
									<th>Nama</th>
									<th>Telepon</th>
									<th>Alamat</th>
									<th width="200">Menu</th>
								</tr>
							</thead>
							<tbody>
								<?php 
              
								if ($query_plg->num_rows > 0) {

									tulisLog('pelanggan.php -> jml. pelanggan : ' . count($rs));

									$num = 0;
									while ($row = $query_plg->fetch_assoc()) {
										$num++;
										echo ""
										. "<tr>"
											. "<td>" . $num . "</td>"
											. "<td>" . $row['pelanggan_id'] . "</td>"
											. "<td>" . $row['pelanggan_nama'] . "</td>"
											. "<td>" . $row['pelanggan_telp'] . "</td>"
											. "<td>" . $row['pelanggan_alamat'] . "</td>"
											. "<td width='200'></td>"
										. "</tr>";

									}
								}
								?>
							</tbody>
							<!-- footer tabel -->
						</table>
					</div>
				</div>
			</section>
		</div>

	</div>


<div class="modal fade" id="modal-ubah-pelanggan">
	  	<div class="modal-dialog modal-md" >
	     	<div class="modal-content">
		        <div class="modal-header">
		           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		           <span aria-hidden="true">&times;</span></button>
		           <h4 class="modal-title">Ubah Data Pelanggan</h4>
		        </div>
		        <div class="box-body" style="padding: 15px">
			        <div class="col-md-12" style="padding-left: 0; margin-bottom: 10px">
			           <p class="col-md-2" style="padding-left: 0"><strong>Nama</strong></p>
			           <input type="" name="" class="col-md-10" id="e_nama"></input>
			        </div>
			        <div class="col-md-12" style="padding-left: 0; margin-bottom: 10px">
			           <p class="col-md-2" style="padding-left: 0"><strong>Telepon</strong></p>
			           <input type="" name="" class="col-md-10" id="e_telepon"></input>
			        </div>
			        <div class="col-md-12" style="padding-left: 0; margin-bottom: 10px">
			           <p class="col-md-2" style="padding-left: 0"><strong>Alamat</strong></p>
			           <input type="" name="" class="col-md-10" id="e_alamat"></input>
			        </div>
		        </div>
		        <div class="modal-footer">
		           <button type="button" class="btn btn-custom" data-dismiss="modal" aria-label="Close">Tutup</button>
		           <button id="btnUbahPelanggan" type="button" class="btn btn-success">Simpan</button>
		        </div>
	     	</div>
	  	</div>
	</div>

	<div class="modal fade" id="modal-tambah-pelanggan">
	  	<div class="modal-dialog modal-md" >
	     	<div class="modal-content">
		        <div class="modal-header">
		           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		           <span aria-hidden="true">&times;</span></button>
		           <h4 class="modal-title">Tambah Data Pelanggan</h4>
		        </div>
		        <div class="box-body" style="padding: 15px">
			        <div class="col-md-12" style="padding-left: 0; margin-bottom: 10px">
			           <p class="col-md-2" style="padding-left: 0"><strong>Nama</strong></p>
			           <input type="" name="" class="col-md-10" id="a_nama"></input>
			        </div>
			        <div class="col-md-12" style="padding-left: 0; margin-bottom: 10px">
			           <p class="col-md-2" style="padding-left: 0"><strong>Telepon</strong></p>
			           <input type="" name="" class="col-md-10" id="a_telepon"></input>
			        </div>
			        <div class="col-md-12" style="padding-left: 0; margin-bottom: 10px">
			           <p class="col-md-2" style="padding-left: 0"><strong>Alamat</strong></p>
			           <input type="" name="" class="col-md-10" id="a_alamat"></input>
			        </div>
		        </div>
		        <div class="modal-footer">
		           <button type="button" class="btn btn-custom" data-dismiss="modal" aria-label="Close">Tutup</button>
		           <button id="btnTambahPelanggan" type="button" class="btn btn-success">Simpan</button>
		        </div>
	     	</div>
	  	</div>
	</div>

	<div class="modal fade" id="modal-hapus-pelanggan">
	  	<div class="modal-dialog modal-md">
	     	<div class="modal-content">
		        <div class="modal-header">
		           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		           <span aria-hidden="true">&times;</span></button>
		           <h4 class="modal-title">Hapus pelanggan ini ?</h4>
		        </div>
		        <div class="box-body" style="padding: 15px">
			        <div class="col-md-12" style="padding-left: 0">
			           <p class="col-md-4" style="padding-left: 0"><strong>Nama</strong></p>
			           <p class="col-md-6" id="d_nama"></p>
			        </div>
			        <div class="col-md-12" style="padding-left: 0">
			           <p class="col-md-4" style="padding-left: 0"><strong>Telepon</strong></p>
			           <p class="col-md-6" id="d_telepon"></p>
			        </div>
			        <div class="col-md-12" style="padding-left: 0">
			           <p class="col-md-4" style="padding-left: 0"><strong>Alamat</strong></p>
			           <p class="col-md-6" id="d_alamat"></p>
			        </div>
		        </div>
		        <div class="modal-footer">
					<button type="button" class="btn btn-custom" data-dismiss="modal" aria-label="Close" onclick="$( '#btnHapusPelanggan').unbind('click');">Batal</button>
					<button id="btnHapusPelanggan" type="button" class="btn btn-danger">Hapus<i class="fa fa-trash"></i>
					</button>
		        </div>
	     	</div>
	  	</div>
	</div>

<?php include_once("footer.php"); ?>
<script>
  
  	$(document).ready(function() {
      	// document.getElementById("p_namaUser").innerText = "<?php echo $user_nama; ?>";

      	setSidebarToActive();

  	});

  	$(function () {

    	var tabel=$('#tbl_pelanggan').DataTable({
	  		'paging'      : true,
	  		'lengthChange': false,
	  		'searching'   : true,
	  		'ordering'    : true,
	  		'info'        : false,
	  		'autoWidth'   : true,
	  		'language' : {
			    'search'           : '<i class="fa fa-search"></i>',
			    'searchPlaceholder': 'Pencarian',
			    'paginate' : {
			        'previous' : '<i class="fa fa-angle-left"></i>',
			        'next'     : '<i class="fa fa-angle-right"></i>'
			    },
			    'emptyTable'   : 'Tidak ada data untuk ditampilkan.'
		    },
		    'columnDefs' : [ 
			    {
		            'targets' : -1,
		            'data'    : null,
		            'defaultContent' : 
		            	'<button style="margin-left: 3px" class="btn btn-custom btnEdit">Ubah <i class="fa fa-edit"></i></button>'
		            	+ '<button style="margin-left: 3px" class="btn btn-danger btnDelete">Hapus <i class="fa fa-trash"></i></button>'
		        }, 
		        { "width":"50px", "targets": 0 },
		        { "width":"100px", "targets": -1 },
		        { "visible": false, "targets": 1 }
	        ]
  	    });


		$('#tbl_pelanggan tbody').on('click', 'button', function () {
	        var data = tabel.row($(this).parents('tr')).data();
	        var pelanggan_id = data[1];
	        // get class button yang di click
	        var btnType = tabel.row($(this).parents('tr')).selector.rows.prevObject["0"].classList[2];

	        if (btnType == "btnEdit") {

	        	$('#btnUbahPelanggan').bind('click', function(event) {
					ubahPelanggan(data[1]);
					$( this ).unbind( event );
				});
	        	
	        	$('#modal-ubah-pelanggan').modal();
	        	document.getElementById('e_nama').value = data[2];
	        	document.getElementById('e_telepon').value = data[3];
	        	document.getElementById('e_alamat').value = data[4];

	        } else if (btnType == "btnDelete") {

	        	$('#btnHapusPelanggan').bind('click', function(event) {
					hapusPelanggan(data[1]);
					$( this ).unbind( event );
				});

	        	document.getElementById('d_nama').innerText = data[2];
	        	document.getElementById('d_telepon').innerText = data[3];
	        	document.getElementById('d_alamat').innerText = data[4];

	        	$('#modal-hapus-pelanggan').modal({
	        		show: true,
			        keyboard: false,
			        backdrop: 'static'
	        	});

	        }

	    });

        $('#btnTambahPelanggan').bind('click', function(event) {
			tambahPelanggan();
			$( this ).unbind( event );
		});

  	});

	function hapusPelanggan(no) {

		$('#modal-hapus-pelanggan').modal('hide');

		$('#on_loading_modal').modal({
            show: true,
            keyboard: false,
            backdrop: 'static'
        });

		$.ajax({
			type: "POST",
			dataType: "JSON",
			url: "proses_daftar_pelanggan.php",
			data: {
				jenis_proses : "hapus_pelanggan",
				pelanggan_id : no
			},
			success: function(response) {
				if (response.response_code == "00") {
                    showSuccessMessage('Pelanggan berhasil di hapus.');
                } else {
                    showFailedMessage(response.response_msg);
                }
			},
            failed : function() {
                showFailedMessage('Hapus Pelanggan Gagal.');
            },
            error : function() {
                showFailedMessage('Hapus Pelanggan Gagal 2.');
            }
		});
	}

	function ubahPelanggan(no) {

		$('#modal-ubah-pelanggan').modal('hide');

		$('#on_loading_modal').modal({
            show: true,
            keyboard: false,
            backdrop: 'static'
        });

		$.ajax({
			type: "POST",
			dataType: "JSON",
			url: "proses_daftar_pelanggan.php",
			data: {
				jenis_proses      : "ubah_pelanggan",
				pelanggan_id      : no,
				pelanggan_nama    : document.getElementById("e_nama").value,
				pelanggan_telepon : document.getElementById("e_telepon").value,
				pelanggan_alamat  : document.getElementById("e_alamat").value
			},
			success: function(response) {
				if (response.response_code == "00") {
                    showSuccessMessage(response.response_msg);
                } else {
                    showFailedMessage(response.response_msg);
                }
			},
            failed : function() {
                showFailedMessage('Hapus Pelanggan Gagal.');
            },
            error : function() {
                showFailedMessage('Hapus Pelanggan Gagal.');
            }
		});
	}

    function tambahPelanggan() {

		$('#modal-tambah-pelanggan').modal('hide');

		$('#on_loading_modal').modal({
            show: true,
            keyboard: false,
            backdrop: 'static'
        });

		$.ajax({
			type: "POST",
			dataType: "JSON",
			url: "proses_daftar_pelanggan.php",
			data: {
				jenis_proses      : "tambah_pelanggan",
				pelanggan_id      : 0,
				pelanggan_nama    : document.getElementById("a_nama").value,
				pelanggan_telepon : document.getElementById("a_telepon").value,
				pelanggan_alamat  : document.getElementById("a_alamat").value
			},
			success: function(response) {
				if (response.response_code == "00") {
                    showSuccessMessage(response.response_msg);
                } else {
                    showFailedMessage(response.response_msg);
                }
			},
            failed : function() {
                showFailedMessage('Tambah Pelanggan Gagal.');
            },
            error : function() {
                showFailedMessage('Tambah Pelanggan Gagal.');
            }
		});
	}

</script>
</body>
</html>