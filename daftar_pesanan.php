<?php 

/*
 * Masukkan setiap perubahan yang dilakukan di tabel ini.
 * 
 * NAMA                VERSI       TGL(DD-MM-YYYY)        PERUBAHAN
 * ========================================================================================================
 * VANDI                1.0         15-09-2018            Pembuatan awal (create)
 * IKHSAN               1.1         23-09-2018            menambahkan tombol2 menu di baris tabel
 *														  menambahkan query menampilkan nama pelanggan di tabel
 *														  menambahkan tombol pada baris data tabel
 */

include_once("head.php"); 
include_once("konfigurasi.php"); 

tulisLog('daftar_pesanan.php -> membuka halaman');

$user_id = $_SESSION['user_id'];
$user_nama = $_SESSION['user_nama'];

// $sel_pesanan = "SELECT * FROM pesanan";
$sel_pesanan = "SELECT psn.*, plg.pelanggan_nama"
			. "	FROM pesanan psn"
			. " JOIN pelanggan plg ON plg.pelanggan_id = psn.pesanan_pelanggan_id"
			. " ORDER BY psn.pesanan_id DESC";

tulisLog('daftar_pesanan.php -> sel_pesanan : ' . $sel_pesanan);

$query_psn = $con->query($sel_pesanan);

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
	        <section class="content-header">
	            <h1>Daftar Barang Masuk</h1>
	        </section>
			<section class="content">
				<div class="box box-primary">
					<div class="box-body">
						<a href="pesanan.php"><button type="button" class="btn btn-primary">Tambah Barang Masuk</button></a>
						<table id="tbl_pesanan" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>ID Barang Masuk</th>
									<th>No. Barang Masuk</th>
									<th>No. Surat Jalan Masuk</th>
									<th>Tanggal Barang Masuk</th>
									<th>ID Pelanggan</th>
									<th>Pelanggan</th>
									<th>Total</th>
									<th>JPSN</th>
									<th>Menu</th>
								</tr>
							</thead>
							<!-- isi tabel  -->
							<tbody>
								<?php 
								if ($query_psn->num_rows > 0) {

									tulisLog('daftar_pesanan.php -> jml. pesanan : ' . count($rs));

									$num = 0;
									while ($row = $query_psn->fetch_assoc()) {
										$num++;
										echo ""
										. "<tr>"
											. "<td>" . $num . "</td>"
											. "<td>" . $row['pesanan_id'] . "</td>"
											. "<td>" . $row['pesanan_no_pesanan'] . "</td>"
											. "<td>" . $row['pesanan_no_surat_jalan_masuk'] . "</td>"
											. "<td>" . $row['pesanan_tanggal_pesanan'] . "</td>"
											. "<td>" . $row['pesanan_pelanggan_id'] . "</td>"
											. "<td>" . $row['pelanggan_nama'] . "</td>"
											. "<td>" . $row['pesanan_total_qty'] . "</td>"
											. "<td>" . $row['pesanan_jenis_pesanan'] . "</td>"
											. "<td></td>"
										. "</tr>";
									}
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</div>
	</div>

	<div class="modal fade" id="modal-lihat-pesanan">
	  	<div class="modal-dialog" style="width: 1000px">
	     	<div class="modal-content">
		        <div class="modal-header">
		           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		           <span aria-hidden="true">&times;</span></button>
		           <h4 class="modal-title">Lihat Pesanan</h4>
		        </div>
		        <div class="box-body" style="padding: 15px">
			        <div class="col-md-8" style="padding-left: 0">
			           <p class="col-md-4" style="padding-left: 0"><strong>No. Surat Jalan</strong></p>
			           <p class="col-md-6" id="v_noSuratJalan"></p>
			        </div>
			        <div class="col-md-8" style="padding-left: 0">
			           <p class="col-md-4" style="padding-left: 0"><strong>Jenis Pesanan</strong></p>
			           <p class="col-md-6" id="v_jenisPesanan"></p>
			        </div>
			        <div class="col-md-8" style="padding-left: 0">
			           <p class="col-md-4" style="padding-left: 0"><strong>Tanggal Pesanan</strong></p>
			           <p class="col-md-6" id="v_tglPesanan"></p>
			        </div>
			        <div class="col-md-8" style="padding-left: 0">
			           <p class="col-md-4" style="padding-left: 0"><strong>Nama Pelanggan</strong></p>
			           <p class="col-md-6" id="v_namaPelanggan"></p>
			        </div>
			        <!-- <div class="col-md-8" style="padding-left: 0">
			           <p class="col-md-4" style="padding-left: 0"><strong>Status</strong></p>
			           <p class="col-md-6" id="v_statusPesanan"></p>
			        </div> -->
			        <table id="tbl_lihat_pesanan" class="table table-bordered table-hover">
						<!-- header tabel (nama kolom) -->
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Barang</th>
								<th>Jenis Print (Motif)</th>
								<th>Jumlah</th>
								<th>Satuan</th>
								<th>Jumlah Packing</th>
								<th>Jenis Packing</th>
								<th>Warna</th>
								<!-- <th>Keterangan</th> -->
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
		        </div>
		        <div class="modal-footer">
		           <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Tutup</button>
		        </div>
	     	</div>
	  	</div>
	</div>

	<div class="modal fade" id="modal-ubah-pesanan">
	  	<div class="modal-dialog modal-lg">
	     	<div class="modal-content">
		        <div class="modal-header">
		           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		           <span aria-hidden="true">&times;</span></button>
		           <h4 class="modal-title">Ubah Pesanan</h4>
		        </div>
		        <div class="box-body">

		        </div>
	     	</div>
	  	</div>
	</div>

	<div class="modal fade" id="modal-hapus-pesanan">
	  	<div class="modal-dialog">
	     	<div class="modal-content">
		        <div class="modal-header">
		           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		           <span aria-hidden="true">&times;</span></button>
		           <h4 class="modal-title">Hapus pesanan ini ?</h4>
		        </div>
		        <div class="box-body" style="padding: 15px">
		           	<div class="col-md-12" style="padding-left: 0">
			           <p class="col-md-4" style="padding-left: 0"><strong>No. Surat Jalan</strong></p>
			           <p class="col-md-6" id="d_noSuratJalan"></p>
			        </div>
			        <div class="col-md-12" style="padding-left: 0">
			           <p class="col-md-4" style="padding-left: 0"><strong>Tanggal Pesanan</strong></p>
			           <p class="col-md-6" id="d_tglPesanan"></p>
			        </div>
			        <div class="col-md-12" style="padding-left: 0">
			           <p class="col-md-4" style="padding-left: 0"><strong>Nama Pelanggan</strong></p>
			           <p class="col-md-6" id="d_namaPelanggan"></p>
			        </div>
		        </div>
		        <div class="modal-footer">
					<button type="button" class="btn btn-custom" data-dismiss="modal" aria-label="Close" onclick="$('#btnHapusPesanan').unbind('click');">Batal</button>
					<button id="btnHapusPesanan" type="button" class="btn btn-danger">Hapus <i class="fa fa-trash"></i></button>
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
    	var tabel = $('#tbl_pesanan').DataTable({
	  		'paging'      : true,
	  		'lengthChange': false,
	  		'searching'   : true,
	  		'ordering'    : true,
	  		'info'        : false,
	  		'autoWidth'   : false,
	  		'language' : {
			    'search'           : '<i class="fa fa-search"></i>',
			    'searchPlaceholder': 'Pencarian',
			    'paginate' : {
			        'previous' : '<i class="fa fa-angle-left"></i>',
			        'next'     : '<i class="fa fa-angle-right"></i>'
			    },
			    'emptyTable'   : 'Tidak ada data untuk ditampilkan.'
		    },
		    // V 1.1 [S]
		    'columnDefs' : [ 
			    {
		            'targets' : -1,
		            'data'    : null,
		            'defaultContent' : 
		            	'<button style="margin-left: 3px" class="btn btn-primary btnView">Lihat <i class="fa fa-search"></i></button>'
		            	+ '<button style="margin-left: 3px" class="btn btn-success btnSend">Kirim <i class="fa fa-send"></i></button>'
		            	+ '<button style="margin-left: 3px" class="btn btn-danger btnDelete">Hapus <i class="fa fa-trash"></i></button>'
		        }, 
		        { "visible": false, "targets": 1 },
		        { "visible": false, "targets": 5 },
		        { "visible": false, "targets": 8 }
	        ]
	        // V 1.1 [E]
	  	});

	  	$('#tbl_lihat_pesanan').DataTable({
	  		'paging'      : false,
	  		'lengthChange': false,
	  		'searching'   : false,
	  		'ordering'    : false,
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
		    }
		});

    	// V 1.1 [S]
	  	$('#tbl_pesanan tbody').on('click', 'button', function () {
	        var data = tabel.row($(this).parents('tr')).data();
	        var pesanan_id = data[1];
	        var btnType = tabel.row($(this).parents('tr')).selector.rows.prevObject["0"].classList[2];

	        if (btnType == "btnSend") {
	        	$('#on_loading_modal').modal({
	                show: true,
	                keyboard: false,
	                backdrop: 'static'
	            });
	            window.location = "surat_jalan.php?pesanan_no_pesanan="+data[2];
	        } else if (btnType == "btnView") {
	        	$('#on_loading_modal').modal({
	                show: true,
	                keyboard: false,
	                backdrop: 'static'
	            });
	            window.location = "lihat_pesanan.php?nomor_pesanan="+data[2];
	        } else if (btnType == "btnDelete") {
	        	$('#btnHapusPesanan').bind('click', function(event) {
					hapusPesanan(data[2]);
					$( this ).unbind( event );
				});
	        	document.getElementById('d_noSuratJalan').innerText = data[3];
	        	document.getElementById('d_tglPesanan').innerText = data[4];
	        	document.getElementById('d_namaPelanggan').innerText = data[6];
	        	$('#modal-hapus-pesanan').modal({
    				show: true,
			        keyboard: false,
			        backdrop: 'static'
    			});
	        } 

	    });
	    // V 1.1 [E]
  	});
	
	function hapusPesanan(no) {

		$('#modal-hapus-pesanan').modal('hide');

		$('#on_loading_modal').modal({
            show: true,
            keyboard: false,
            backdrop: 'static'
        });

		$.ajax({
			type: "POST",
			dataType: "JSON",
			url: "proses_daftar_pesanan.php",
			data: {
				jenis_proses : "hapus_pesanan",
				nomor_pesanan : no
			},
			success: function(response) {
				if (response.response_code == "00") {
                    showSuccessMessage('Pesanan berhasil di hapus.');
                } else {
                    showFailedMessage(response.response_msg);
                }
			},
            failed : function() {
                showFailedMessage('Pesanan gagal di simpan.');
            },
            error : function() {
                showFailedMessage('Pesanan gagal di simpan.');
            }
		});
	}

</script>
</body>
</html>