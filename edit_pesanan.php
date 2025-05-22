<?php 

/*
* Masukkan setiap perubahan yang dilakukan di tabel ini.
* 
* NAMA                VERSI       TGL(DD-MM-YYYY)        PERUBAHAN
* ========================================================================================================
* IKHSAN-RAMDAN         1.0       12-09-2018             Pembuatan awal (create)
* Vandi                 1.1       25-09-2018             Penambahan Input Jenis Print
*/

include_once("head.php"); 
include_once("konfigurasi.php"); 
include_once("Data/data_pelanggan.php"); 

$user_id = $_SESSION['user_id'];
$user_nama = $_SESSION['user_nama'];

$no_surat_jalan = "";
$jenis_pesanan = "";
$tgl_pesanan = ""; 
$id_pelanggan = ""; 
$nama_pelanggan = "";
$nomor_pesanan = "";
$total_qty = 0;
$total_pck = 0;

tulisLog('pesanan.php -> load');
    
if ( isset($_POST['jenis_proses']) ) {

    tulisLog("pesanan.php -> ".$_POST['jenis_proses']);

    $nomor_pesanan = $_POST['nomor_pesanan'];

    $resultPsn = array();

    // SELECT PESANAN
    $sel_psn = "SELECT * FROM pesanan WHERE pesanan_no_pesanan = '".$nomor_pesanan."'";
    tulisLog("pesanan.php -> sel_psn : ".$sel_psn);
    $query_psn = $con->query($sel_psn);
    while ($row = $query_psn->fetch_assoc()) {
        $no_surat_jalan = $row['pesanan_no_surat_jalan_masuk'];
        $jenis_pesanan = $row['pesanan_jenis_pesanan'];
        $tgl_pesanan = $row['pesanan_tanggal_pesanan'];
        $id_pelanggan = $row['pesanan_pelanggan_id'];
        $total_qty = $row['pesanan_total_qty'];
        $total_pck = $row['pesanan_total_qty_packing'];
    }

    // SELECT NAMA PEMESAN
    $sel_plg = "SELECT pelanggan_nama FROM pelanggan WHERE pelanggan_id = ".$id_pelanggan;
    tulisLog("pesanan.php -> sel_plg : ".$sel_plg);
    $query_plg = $con->query($sel_plg);
    while ($row = $query_plg->fetch_assoc()) {
        $nama_pelanggan = $row['pelanggan_nama'];
    }

    // SELECT DETAIL PESANAN
    $listTmp = array();
    $list_detail_pesanan = array();
    $sel_detailpsn = "SELECT * FROM detail_pesanan WHERE detail_pesanan_no_pesanan = '".$nomor_pesanan."'";
    
    tulisLog("pesanan.php -> sel_detailpsn : ".$sel_detailpsn);

    $query_detail = $con->query($sel_detailpsn);

}

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
            <h1>Ubah Pesanan</h1>
        </section>
        <section class="content">
        <!-- setiap content (isi) halaman dibuat disini -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-body">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="no_surat_jalan">No Surat Jalan</label>
                                <input type="text" id="no_surat_jalan" name="no_surat_jalan" class="form-control" placeholder="No. Surat Jalan" value="<?php echo $no_surat_jalan; ?>">
                            </div>
                            <div class="form-group">
                                <label for="jenis_pesanan">Jenis Pesanan</label>
                                <select class="form-control" id="jenis_pesanan" name="jenis_pesanan">
                                    <option <?php if ($jenis_pesanan == "Pesanan Baru") echo 'selected' ; ?> value="Pesanan Baru">Pesanan Baru</option>
                                    <option <?php if ($jenis_pesanan == "Pesanan Retur") echo 'selected' ; ?> value="Pesanan Retur">Pesanan Retur</option>
                                    <option <?php if ($jenis_pesanan == "Pesanan Ulang") echo 'selected' ; ?> value="Pesanan Ulang">Pesanan Ulang</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tgl_pesanan">Tanggal Pesanan</label>
                                <div class="input-group date">
                                    <input type="text" class="form-control pull-right" id="tgl_pesanan" name="tgl_pesanan" placeholder="Tanggal Pesanan"  value="<?php echo $tgl_pesanan; ?>">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nama_pelanggan">Pelanggan</label>
                                <div class="input-group">
                                    <input type="text" id="nama_pelanggan" name="nama_pelanggan" class="form-control" placeholder="Nama Pelanggan" value="<?php echo $nama_pelanggan; ?>">
                                    <div class="input-group-addon" data-toggle="modal" data-target="#modal-pelanggan" style="background-color: #dd4b39">
                                        <span class="btn-danger">Pilih</span>
                                    </div>
                                </div>
                                <input type="text" style="display: none" id="no_pelanggan" value="<?php echo $id_pelanggan; ?>">
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                        <div id="inputBarang" class="col-md-12" style="padding: 0">
                            <div class="form-group col-md-3">
                                <label for="nama_barang">Nama Barang</label>
                                <input type="text" id="nama_barang" name="nama_barang" class="form-control" placeholder="Nama Barang">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="jenis_print">Jenis Print</label>
                                <input type="text" id="jenis_print" name="jenis_print" class="form-control" placeholder="Jenis Print">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="warna">Jumlah Warna</label>
                                <input type="text" id="warna" name="warna" class="form-control" placeholder="Jumlah Warna" onkeypress="return numbersOnly(event, this.value)">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="jumlah_brg">Qty</label>
                                <input type="text" id="jumlah_brg" name="jumlah_brg" class="form-control" placeholder="Jumlah" onkeypress="return numbersOnly(event, this.value)">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="satuan">Satuan</label>
                                <select class="form-control" id="satuan" name="satuan">
                                    <option value="Kg">Kilogram (kg)</option>
                                    <option value="PT">Potong</option>
                                    <option value="PS">Pasang</option>
                                    <option value="Y">Yard (y)</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="jumlah_pck">Qty (packing)</label>
                                <input type="text" id="jumlah_pck" name="jumlah_pck" class="form-control" placeholder="Qty" onkeypress="return numbersOnly(event, this.value)">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="packing">Packing</label>
                                <select class="form-control" id="packing" name="packing">
                                    <option value="Roll">Roll</option>
                                    <option value="Pt">Potong</option>
                                    <option value="Ps">Pasang</option>
                                </select>
                            </div>
                            <!-- <div class="form-group col-md-3">
                                <label for="keterangan">Keterangan</label>
                                <input type="text" id="keterangan" name="keterangan" class="form-control" placeholder="Keterangan">
                            </div> -->
                        </div>
                        <div class="col-md-12">
                            <!-- <button id="btnTambahBarang" class="btn btn-danger">Tambah Barang</button> -->
                            <button id="btnUbahBarang" class="btn btn-danger" disabled="true">Ubah Barang</button>
                        </div><br/>
                        <div class="col-md-12">
                            <table id="tabel_barang" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID</th>
                                        <th>Nama Barang</th>
                                        <th>Jenis Print</th>
                                        <th>Jumlah Warna</th>
                                        <th>Qty</th>
                                        <th>Satuan</th>
                                        <th>Qty (packing)</th>
                                        <th>Packing</th>
                                        <!-- <th>Keterangan</th> -->
                                        <th>Menu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if ($query_detail->num_rows > 0) {
                                        $num = 0;
                                        while ($row = $query_detail->fetch_assoc()) {
                                              $num++;
                                              echo ""
                                              . "<tr>"
                                                    . "<td>" . $num . "</td>"
                                                    . "<td>" . $row['detail_pesanan_id'] . "</td>"
                                                    . "<td>" . $row['detail_pesanan_nama_barang'] . "</td>"
                                                    . "<td>" . $row['detail_pesanan_jenis_print'] . "</td>"
                                                    . "<td>" . $row['detail_pesanan_warna'] . "</td>"
                                                    . "<td>" . $row['detail_pesanan_qty'] . "</td>"
                                                    . "<td>" . $row['detail_pesanan_satuan'] . "</td>"
                                                    . "<td>" . $row['detail_pesanan_qty_packing'] . "</td>"
                                                    . "<td>" . $row['detail_pesanan_jenis_packing'] . "</td>"
                                                    // . "<td>" . $row['detail_pesanan_keterangan'] . "</td>"
                                                    . "<td></td>"
                                              . "</tr>";
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <!-- <th></th> -->
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table><br>
                            <!-- <input type="text" id="subtotal_qty" name="subtotal_qty" style="display: none">
                            <input type="text" id="subtotal_pck" name="subtotal_pck" style="display: none"> -->
                            <input type="hidden" id="subtotal_qty" name="subtotal_qty" value="<?php echo $total_qty; ?>">
                            <input type="hidden" id="subtotal_pck" name="subtotal_pck" value="<?php echo $total_pck; ?>">
                            <div class="col-md-12" style="padding: 0">
                                <button type="button" id="btnSimpanPesanan" class="btn btn-danger">Simpan</button>
                            </div><br/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section>
    </div>
</div>
<?php include_once("footer.php"); ?>
<script>

    $(function () {
        // document.getElementById("p_namaUser").innerText = "<?php echo $user_nama; ?>";

        setSidebarToActive();
        var list_barang = new Array();
        var counter = <?= (int)$num + 1; ?>;

        $('#tgl_pesanan').datepicker({
            'setDate'     : new Date(),
            'format'      : "dd-mm-yyyy"
        });

        var tabel = $('#tabel_barang').DataTable({
            'paging'      : false,
            'lengthChange': false,
            'searching'   : false,
            'ordering'    : false,
            'info'        : false,
            'autoWidth'   : true,
            'select'      : true,
            'language' : {
                'search'           : '<i class="fa fa-search"></i>',
                'searchPlaceholder': 'Pencarian',
                'paginate' : {
                    'previous' : '<i class="fa fa-angle-left"></i>',
                    'next'     : '<i class="fa fa-angle-right"></i>'
                }
            },
            'scrollY'     : '20vh',
            "columnDefs": [
                {
                    'targets' : -1,
                    'data'    : null,
                    'defaultContent' : 
                        '<button style="margin-left: 3px" class="btn btn-custom btnEdit">Ubah <i class="fa fa-edit"></i></button>'
                        // + '<button style="margin-left: 3px" class="btn btn-danger btnDelete"><i class="fa fa-trash"></i></button>',
                }, 
                { "visible": false, "targets": 1 }
            ]
            // ,
            // "footerCallback": function ( row, data, start, end, display ) {
            //     var api = this.api(), data;
     
            //     // converting to interger to find total
            //     var intVal = function ( i ) {
            //         return typeof i === 'string' ?
            //             i.replace(/[\$,]/g, '')*1 :
            //             typeof i === 'number' ?
            //                 i : 0;
            //     };
     
            //     // computing column Total the complete result 
            //     var subtotal_qty = api
            //         .column( 5 )
            //         .data()
            //         .reduce( function (a, b) {
            //             return intVal(a) + intVal(b);
            //         }, 0 );
                    
                    
            //     var subtotal_pck = api
            //         .column( 7 )
            //         .data()
            //         .reduce( function (a, b) {
            //             return intVal(a) + intVal(b);
            //         }, 0 );
                
            //     // Update footer by showing the total with the reference of the column index 
            //     $( api.column( 5 ).footer() ).html(subtotal_qty);
            //     $( api.column( 7 ).footer() ).html(subtotal_pck);

            //     document.getElementById('subtotal_qty').value = subtotal_qty;
            //     document.getElementById('subtotal_pck').value = subtotal_pck;
            // }
        });

        // $('#btnTambahBarang').click(function() {

        //     var container = document.querySelector("#inputBarang")
        //     var matches = container.querySelectorAll("div.form-group > input");
        //     var full = true;

        //     for(var i = 0; i < matches.length; i++) {
        //         if ( matches[i].value == null || matches[i].value == "" ) {
        //             full = false;
        //         }
        //     }

        //     if (full) {
        //         tabel.row.add( [
        //             counter,
        //             0,
        //             $('#nama_barang').val(),
        //             $('#jenis_print').val(),
        //             $('#warna').val(),
        //             $('#jumlah_brg').val(),
        //             $('#satuan').val(),
        //             $('#jumlah_pck').val(),
        //             $('#packing').val(),
        //             $('#keterangan').val()
        //         ] ).draw( false ); 

        //         counter++;

        //         document.getElementById('nama_barang').value = "";
        //         document.getElementById('jenis_print').value = "";
        //         document.getElementById('warna').value = "";
        //         document.getElementById('jumlah_brg').value  = "";
        //         document.getElementById('jumlah_pck').value  = "";
        //         document.getElementById('keterangan').value = "";

        //         if ( tabel.data().count() ) {
        //             document.getElementById("btnSimpanPesanan").disabled = false;
        //         }

        //     } else {
        //         alert("data barang harus lengkap!");
        //     }

        // });

        $('#btnSimpanPesanan').click(function() {

            $('#on_loading_modal').modal({
                show: true,
                keyboard: false,
                backdrop: 'static'
            });

            list_barang = tabel.rows().data();
            delete list_barang.context;
            delete list_barang.selector;
            delete list_barang.ajax;

            // console.log("list_barang : " + JSON.stringify(list_barang));

            var no_surat_jalan = document.getElementById('no_surat_jalan').value;
            var jenis_pesanan  = document.getElementById('jenis_pesanan').value;
            var tgl_pesanan    = document.getElementById('tgl_pesanan').value;
            var no_pelanggan   = document.getElementById('no_pelanggan').value;
            var barang_tmp = new Array();
            var subtotal_qty = 0;
            var subtotal_pck = 0;

            for (var i = 0; i < list_barang.length; i++) {
                barang_tmp.push({
                        'detail_pesanan_id'            : list_barang[i][1],
                        'detail_pesanan_nama_barang'   : list_barang[i][2],
                        'detail_pesanan_jenis_print'   : list_barang[i][3],
                        'detail_pesanan_warna'         : list_barang[i][4],
                        'detail_pesanan_qty'           : list_barang[i][5],
                        'detail_pesanan_satuan'        : list_barang[i][6],
                        'detail_pesanan_qty_packing'   : list_barang[i][7],
                        'detail_pesanan_jenis_packing' : list_barang[i][8]
                        // 'detail_pesanan_keterangan'    : list_barang[i][9]
                    });

                subtotal_qty += list_barang[i][5] * list_barang[i][7];
                subtotal_pck += Number(list_barang[i][7]);

                document.getElementById('subtotal_qty').value = subtotal_qty;
                document.getElementById('subtotal_pck').value = subtotal_pck;

            }

            console.log("barang_tmp : " + JSON.stringify(barang_tmp));

            $.ajax({
                type : 'POST',
                url  : 'proses_daftar_pesanan.php',
                dataType : 'JSON',
                data : {
                    'jenis_proses'                  : "update_pesanan",
                    'nomor_pesanan'                 : "<?= $nomor_pesanan ?>",
                    'pesanan_no_surat_jalan_masuk'  : no_surat_jalan,
                    'pesanan_tanggal_pesanan'       : tgl_pesanan,
                    'pesanan_pelanggan_id'          : no_pelanggan,
                    'pesanan_jenis_pesanan'         : jenis_pesanan,
                    'pesanan_total_qty'             : subtotal_qty,
                    'pesanan_total_qty_packing'     : subtotal_pck,
                    'list_barang'                   : JSON.stringify(barang_tmp),
                    'jml_barang_skrg'               : <?= (int)$num; ?>
                },
                success : function(response) {

                    // console.log(JSON.stringify(response));

                    if (response.response_code == "00") {
                        showSuccessMessage('Pesanan berhasil di ubah.');

                        window.location = "daftar_pesanan.php";

                    } else {
                        showFailedMessage(response.response_msg);
                    }

                },
                failed : function() {
                    showFailedMessage('Pesanan gagal di ubah.');
                },
                error : function() {
                    showFailedMessage('Pesanan gagal di ubah.');
                }
            });

        });

        $('#tabel_barang tbody').on('click', 'button', function () {

            var data = tabel.row($(this).parents('tr')).data();
            var pesanan_id = data[1];
            var tbl0 = data[0];
            var btnType = tabel.row($(this).parents('tr')).selector.rows.prevObject["0"].classList[2];
            var idx = tabel.row($(this).parents('tr')).selector.rows.index();
            var tabelData = tabel.data();

            if (btnType == "btnEdit") {
                
                document.getElementById("nama_barang").value = data[2];
                document.getElementById("jenis_print").value = data[3];
                document.getElementById("warna").value = data[4];
                document.getElementById("jumlah_brg").value = data[5];
                document.getElementById("satuan").value = data[6];
                document.getElementById("jumlah_pck").value = data[7];
                document.getElementById("packing").value = data[8];
                // document.getElementById("keterangan").value = data[9];

                document.getElementById("btnUbahBarang").disabled = false;
                // document.getElementById("btnTambahBarang").disabled = true;

            } else if (btnType == "btnDelete") {

                document.getElementById('d_noSuratJalan').innerText = data[3];
                document.getElementById('d_jenisPesanan').innerText = data[4];
                document.getElementById('d_tglPesanan').innerText = data[5];
                document.getElementById('d_namaPelanggan').innerText = data[6];

                $('#modal-hapus-pesanan').modal({
                    show: true,
                    keyboard: false,
                    backdrop: 'static'
                });

            }

            $('#btnUbahBarang').bind('click', function(event) {
                var tmpData = {
                    "0" : tbl0,
                    "1" : pesanan_id,
                    "2" : document.getElementById("nama_barang").value,
                    "3" : document.getElementById("jenis_print").value,
                    "4" : document.getElementById("warna").value,
                    "5" : document.getElementById("jumlah_brg").value,
                    "6" : document.getElementById("satuan").value,
                    "7" : document.getElementById("jumlah_pck").value,
                    "8" : document.getElementById("packing").value
                    // "9" : document.getElementById("keterangan").value
                };

                tabelData[idx] = tmpData;
                tabel.clear().draw();
                tabel.rows.add(tabelData);
                tabel.columns.adjust().draw();
                document.getElementById("btnUbahBarang").disabled = true;
                // document.getElementById("btnTambahBarang").disabled = false;

                document.getElementById('nama_barang').value = "";
                document.getElementById('jenis_print').value = "";
                document.getElementById('warna').value = "";
                document.getElementById('jumlah_brg').value  = "";
                document.getElementById('jumlah_pck').value  = "";
                // document.getElementById('keterangan').value = "";

                $( this ).unbind( event );

            });
            
        });

    });

</script>
</body>
</html>