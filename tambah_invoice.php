<?php 

include_once("head.php"); 
include_once("konfigurasi.php"); 

tulisLog('tambah_invoice.php -> membuka halaman');

$user_id = $_SESSION['user_id'];
$user_nama = $_SESSION['user_nama'];
$pelanggan_id = $_GET['pelanggan_id'];
$pelanggan_nama = $_GET['pelanggan_nama'];
$bulan = $_GET['bulan'];
$tahun = $_GET['tahun'];
$username = $_SESSION['user_username'];

$sel_pelanggan = "SELECT p.* FROM pelanggan p"
              . " JOIN surat_jalan sj ON p.pelanggan_id = sj.surat_jalan_pelanggan_id"
              . " WHERE p.pelanggan_id > 0"
              . " AND sj.surat_jalan_status = 'B'"
              . " GROUP BY p.pelanggan_nama";

tulisLog('tambah_invoice.php -> sel_pelanggan : ' . $sel_pelanggan);

$list_pelanggan = $con->query($sel_pelanggan);

$sel_sj = "SELECT p.pelanggan_nama, p.pelanggan_telp, p.pelanggan_alamat,"
		. " sj.*, sjd.surat_jalan_detail_nama_barang, sjd.surat_jalan_detail_jenis_print, sjd.surat_jalan_detail_satuan_qty,"
		. " invk.invoice_keluar_detail_harga, invk.invoice_keluar_detail_subtotal_harga" // V 1.1 [S.E]
		. " FROM surat_jalan sj"
		. " JOIN surat_jalan_detail sjd ON sjd.surat_jalan_detail_surat_jalan_key = sj.surat_jalan_key"
		. " JOIN pelanggan p ON p.pelanggan_id = sj.surat_jalan_pelanggan_id"
		. " LEFT JOIN invoice_keluar_detail invk ON invk.invoice_keluar_detail_surat_jalan_nomor = sj.surat_jalan_key" // V 1.1 [S.E]
		. " WHERE sj.surat_jalan_pelanggan_id = ".$pelanggan_id
		. " AND sj.surat_jalan_status = 'B'"
		. " AND sj.surat_jalan_tanggal LIKE '".$tahun."-".$bulan."%'"
		. " GROUP BY sjd.surat_jalan_detail_surat_jalan_key, sjd.surat_jalan_detail_nama_barang"
		. " ORDER BY sjd.surat_jalan_detail_surat_jalan_key"
		."";

tulisLog('tambah_invoice.php -> sel_sj : ' . $sel_sj);

$list_suratjalan = $con->query($sel_sj);
?>

<body class="hold-transition skin-blue-light sidebar-collapse sidebar-mini">
	<style type="text/css">
		tbody tr td input[readonly] {
			border: none;
			text-transform: uppercase;
		}
	</style>
	<div class="wrapper">
		<header class="main-header">
			<?php include_once("logo-header.php"); ?>
		</header>
	  	<aside class="main-sidebar">
        	<?php include_once("menu.php"); ?>
	  	</aside>
		<div class="content-wrapper">
			<section class="content-header">
		      	<h1>Tambah Invoice</h1>
		    </section>
			<section class="content">
				<div class="box box-primary">
					<div class="box-header">
						<div class="form-group col-md-4">
                            <label for="nama_pelanggan">Pelanggan</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="nama_pelanggan" placeholder="Nama Pelanggan" value="<?php echo $pelanggan_nama; ?>">
                                <div class="input-group-addon" data-target="#modal_pelanggan" data-toggle="modal">
                                    <span class="btn-custom">Pilih</span>
                                </div>
                            </div>
                            <input type="text" style="display: none" id="pelanggan_id" value="<?php echo $pelanggan_id; ?>">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="jenis_pesanan">Bulan</label>
                            <select class="form-control" id="bulan_inv" name="bulan_inv">
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="jenis_pesanan">Tahun</label>
                            <select class="form-control" id="tahun_inv" name="tahun_inv">
                            	<?php
                            	for ($i=2016; $i <= 2030; $i++) { 
                            		if ($i == "2019") {
	                                	echo "<option value='".$i."' selected>".$i."</option>";
	                                } else {
	                                	echo "<option value='".$i."'>".$i."</option>";
	                                }
                            	}
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                        	<button type="button" id="btnCari" style="margin-top: 22px" class="btn btn-primary">Cari</button>
                        </div>
					</div>
					<div class="box-body">
                        <button type="button" id="btnKembali" class="btn btn-custom" onclick="window.location='daftar_invoice.php'">Kembali</button>
                        <button type="button" id="btnSimpan" class="btn btn-success">Simpan</button>
                        <p style="float: right; margin-right: 20px; font-size: 30px; font-weight: bold" id="total_harga">0</p>
						<table id="tbl_suratjalan" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>Id</th>
									<!-- V 1.1 [S] -->
									<!-- <th style="display: none">Key</th> -->
									<th>Surat Jalan Key</th>
									<!-- V 1.1 [E] -->
									<th>No. Surat Jalan</th>
									<th>Motif</th>
									<th>Tanggal Kirim</th>
									<th>Total Qty</th>
									<th>Satuan</th>
									<th>Harga</th>
									<th>Total Harga</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								if ($list_suratjalan->num_rows > 0) {
									tulisLog('tambah_invoice.php -> jml. pelanggan : ' . count($rs));
									$num = 0;
									while ($row = $list_suratjalan->fetch_assoc()) {
										$num++;
										// V 1.1 [S]
										if ($row['invoice_keluar_detail_harga'] == null) {
											$row['invoice_keluar_detail_harga'] = 0;
										}
										if ($row['invoice_keluar_detail_subtotal_harga'] == null) {
											$row['invoice_keluar_detail_subtotal_harga'] = 0;
										}
										// V 1.1 [E]
										echo ""
										. "<tr class='txtMult'>"
											. "<td>" . $num . "</td>"
											. "<td>"
												. "<input type='text' value='" .$row['surat_jalan_id']. "' readonly>"
											. "</td>"
											// V 1.1 [S]
											// . "<td style='display: none'>"
											// 	. "<input id='surat_jalan_key' type='text' value='" .$row['surat_jalan_key']. "' readonly>"
											// . "</td>"
											. "<td>"
												. "<input id='surat_jalan_key' type='text' value='" .$row['surat_jalan_key']. "' readonly>"
											. "</td>"
											// V 1.1 [E]
											. "<td>"
												. "<input id='invoice_keluar_detail_surat_jalan_nomor' type='text' value='" .$row['surat_jalan_nomor']. "' readonly>"
											. "</td>"
											. "<td>"
												. "<input id='invoice_keluar_detail_jenis_print' type='text' value='" .$row['surat_jalan_detail_jenis_print']. "' readonly>"
											. "</td>"
											. "<td>"
												. "<input type='text' style='width: 90px' value='" .$row['surat_jalan_tanggal']. "' readonly>"
											. "</td>"
											. "<td>"
												. "<input id='invoice_keluar_detail_total_barang' type='text' class='val1' style='width: 80px' value='" .$row['surat_jalan_total']. "' readonly>"
											. "</td>"
											. "<td>"
												. "<input id='invoice_keluar_detail_satuan' type='text' style='width: 80px' value='" .$row['surat_jalan_detail_satuan_qty']. "' readonly>"
											. "</td>"
											. "<td>"
												. "<input id='invoice_keluar_detail_harga' class='val2' type='text' value='".$row['invoice_keluar_detail_harga']."'>"
											. "</td>"
											. "<td>"
												. "<input id='invoice_keluar_detail_subtotal_harga' class='multTotal' type='text' value='".$row['invoice_keluar_detail_subtotal_harga']."' readonly>"
											. "</td>"
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

	<div class="modal fade" id="modal_pelanggan">
		<div class="modal-dialog modal-md" >
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Pilih Pelanggan</h4>
				</div>
				<div class="box-body" style="padding: 15px">
					<table id="tbl_pilihplg" class="table table-bordered table-hover">
						<thead>
						 <tr>
						    <th>No</th>
						    <th>ID</th>
						    <th>Nama</th>
						    <th>Alamat</th>
						 </tr>
						</thead>
						<tbody>
						<?php
						if ($list_pelanggan->num_rows > 0) {
						    $num = 0;
						    while ($row = $list_pelanggan->fetch_assoc()) {
						          $num++;
						          echo ""
						          . "<tr>"
						                . "<td>" . $num . "</td>"
						                . "<td>" . $row['pelanggan_id'] . "</td>"
						                . "<td>" . $row['pelanggan_nama'] . "</td>"
						                . "<td>" . $row['pelanggan_alamat'] . "</td>"
						          . "</tr>";
						    }
						}
						?>
						</tbody>
					</table>
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
  		var list_sj = new Array();
  		var save_invoice = {};
    	var tabel = $('#tbl_suratjalan').DataTable({
	  		'paging'      : false,
	  		'lengthChange': false,
	  		'searching'   : false,
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
		        // { "width":"50px", "targets": 3 },
		        // { "width":"50px", "targets": 4 },
		        // { "width":"50px", "targets": 5 },
		        // { "width" : "10px", "targets": 2 },
		        { "visible" : false, "targets": 1 }
		        // { "visible": false, "targets": 2 }
	        ]
  	    });

    	$(".val2").on('keyup', function(){
		    var n = parseInt($(this).val().replace(/\D/g,''),10);
		    $(this).val(n.toLocaleString());
		});

  	    $('#tbl_suratjalan tbody').on('keyup', 'tr', function () {
			var mult = 0;
			$(".txtMult").each(function() {
				var $val1 = $('.val1', this).val();
				var $val2 = $('.val2', this).val();
				$val2 = $val2.replace(",", "");
				var $total = ($val1 * 1) * ($val2 * 1)
				$('.multTotal', this).val(parseInt( $total ).toLocaleString());
				mult += $total;
			});

			$("#total_harga").text(mult.toLocaleString());
      	});

      	var table = $('#tbl_pilihplg').DataTable({
	          'select'      : true,
	          'paging'      : true,
	          'lengthChange': false,
	          'searching'   : true,
	          'ordering'    : true,
	          'info'        : false,
	          'autoWidth'   : false,
	          'pageLength'  : 8,
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
	            { "visible": false, "targets": 1 }
	          ]
	    });

      	// didieu lurrr
	    $('#tbl_pilihplg tbody').on( 'click', 'tr', function () {
            $(this).addClass('selected');
            var dt = table.row(this).data();
	    	document.getElementById('pelanggan_id').value = dt[1];
	    	document.getElementById('nama_pelanggan').value = dt[2];
	    	$('#modal_pelanggan').modal('hide');
      	});

      	$('#btnCari').on('click', function() {
      		var no = document.getElementById('pelanggan_id').value;
	    	var nama = document.getElementById('nama_pelanggan').value;
	    	var bln = document.getElementById('bulan_inv').value;
	    	var thn = document.getElementById('tahun_inv').value;
      		window.location = "tambah_invoice.php?pelanggan_id="+no+"&pelanggan_nama="+nama+"&bulan="+bln+"&tahun="+thn;
      	});

      	$('#btnSimpan').on('click', function() {
      		$('#on_loading_modal').modal({
                show: true,
                keyboard: false,
                backdrop: 'static'
            });

			document.querySelectorAll('#tbl_suratjalan tbody tr').forEach(function(row) {
				if (row.querySelector('td input[id="invoice_keluar_detail_harga"]').value != "0") {
					list_sj.push({
						'surat_jalan_key' : row.querySelector('td input[id="surat_jalan_key"]').value,
						'invoice_keluar_detail_surat_jalan_nomor' : row.querySelector('td input[id="invoice_keluar_detail_surat_jalan_nomor"]').value,
						'invoice_keluar_detail_jenis_print' : row.querySelector('td input[id="invoice_keluar_detail_jenis_print"]').value,
						'invoice_keluar_detail_total_barang' : row.querySelector('td input[id="invoice_keluar_detail_total_barang"]').value,
						'invoice_keluar_detail_satuan' : row.querySelector('td input[id="invoice_keluar_detail_satuan"]').value,
						'invoice_keluar_detail_harga' : row.querySelector('td input[id="invoice_keluar_detail_harga"]').value,
						'invoice_keluar_detail_subtotal_harga' : row.querySelector('td input[id="invoice_keluar_detail_subtotal_harga"]').value
					});
				}
			});

      		var no_plg = document.getElementById('pelanggan_id').value;
	    	var total = document.getElementById('total_harga').innerText;

	    	$.ajax({
                type : 'POST',
                url  : 'proses_daftar_invoice.php',
                dataType : 'JSON',
                data : {
                	'simpan_invoice'			  : 'Y',
                    'invoice_keluar_pelanggan_id' : no_plg,
                    'invoice_keluar_total_harga'  : total,
                    'list_invoice'   : JSON.stringify(list_sj)
                },
                success : function(response) {

                    console.log(JSON.stringify(response));

                    if (response.response_code == "00") {
                        showSuccessMessage('Pesanan berhasil di simpan.');
                        window.location = "tambah_invoice.php";
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
      	});

      	// $('#bulan_inv').selected;
      	var sel_bulan = "<?php echo $bulan; ?>";
      	var sel = document.querySelectorAll('#bulan_inv option');
      	for (var c = 0; c < sel.length; c++) {
      		if (sel[c].value == sel_bulan) {
	      		sel[c].selected = 'true';
	      	}
      	}
      	var sel_tahun = "<?php echo $tahun; ?>";
      	var sel = document.querySelectorAll('#tahun_inv option');
      	for (var c = 0; c < sel.length; c++) {
      		if (sel[c].value == sel_tahun) {
	      		sel[c].selected = 'true';
	      	}
      	}

  	});
</script>
</body>
</html>