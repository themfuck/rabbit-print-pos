<?php 

include_once("head.php"); 
include_once("konfigurasi.php"); 
include_once("Data/data_pelanggan.php"); 

$user_id = $_SESSION['user_id'];
$user_nama = $_SESSION['user_nama'];
$latest_detail_key = getDetailPesananKey();
tulisLog('pesanan.php -> load');

$user_id = $_SESSION['user_id'];
$user_nama = $_SESSION['user_nama'];
$nomor_pesanan = "";
$no_surat_jalan = "";
$jenis_pesanan = "";
$tgl_pesanan = ""; 
$id_pelanggan = ""; 
$nama_pelanggan = "";
$total_qty = 0;
$total_pck = 0;

if ( isset($_GET['nomor_pesanan']) && 
        ($_GET['nomor_pesanan'] != null || $_GET['nomor_pesanan'] != "") ) {
    $nomor_pesanan = $_GET['nomor_pesanan'];
} else {
    echo "<script>window.location = 'daftar_pesanan.php';</script>";
}

tulisLog('lihat_pesanan.php -> load');

$sel_psn = "SELECT * FROM pesanan WHERE pesanan_no_pesanan = '".$nomor_pesanan."'";
tulisLog("lihat_pesanan.php -> sel_psn : ".$sel_psn);
$query_psn = $con->query($sel_psn);
while ($row = $query_psn->fetch_assoc()) {
    $no_surat_jalan = $row['pesanan_no_surat_jalan_masuk'];
    $jenis_pesanan = $row['pesanan_jenis_pesanan'];
    $tgl_pesanan = date('d-m-Y', strtotime($row['pesanan_tanggal_pesanan']));
    $id_pelanggan = $row['pesanan_pelanggan_id'];
}

// SELECT NAMA PEMESAN
$sel_plg = "SELECT pelanggan_nama FROM pelanggan WHERE pelanggan_id = ".$id_pelanggan;
tulisLog("lihat_pesanan.php -> sel_plg : ".$sel_plg);
$query_plg = $con->query($sel_plg);
while ($row = $query_plg->fetch_assoc()) {
    $nama_pelanggan = $row['pelanggan_nama'];
}

// SELECT DETAIL PESANAN
$list_ubah_detail_pesanan = array();
$list_ubah_detail_pesananTmp = array();
$list_detail_pesanan = array();
$list_detail_pesananTmp = array();
$sel_detailpsn = "SELECT * FROM detail_pesanan WHERE detail_pesanan_no_pesanan = '".$nomor_pesanan."'";
tulisLog("lihat_pesanan.php -> sel_detailpsn : ".$sel_detailpsn);

$query_detail = $con->query($sel_detailpsn);
$detail_pesanan_qty = 0;
$detail_pesanan_qty_packing = 0;
$count = 0;
$detail_pesanan_ids = "";

// KG/Y/M dan PS hitungan harus beda
if ($query_detail->num_rows > 0) {
    while ($row = $query_detail->fetch_assoc()) {
        $surat_jalan_detail_keterangan = "";
        $detail_kain = "";
        $detail_keterangan = "";

        // list ubah pesanan
        $list_ubah_detail_pesananTmp = array(
            'detail_pesanan_id'            => $row['detail_pesanan_id'],
            'detail_pesanan_no_pesanan'    => $row['detail_pesanan_no_pesanan'],
            'detail_pesanan_key'           => $row['detail_pesanan_key'],
            'detail_pesanan_nama_barang'   => $row['detail_pesanan_nama_barang'],
            'detail_pesanan_jenis_print'   => $row['detail_pesanan_jenis_print'],
            'detail_pesanan_warna'         => $row['detail_pesanan_warna'],
            'detail_pesanan_nama_warna'    => $row['detail_pesanan_nama_warna'],
            'detail_pesanan_detail_kain'   => $row['detail_pesanan_detail_kain'],
            'detail_pesanan_ukuran'        => $row['detail_pesanan_ukuran'],
            'detail_pesanan_sisi_kanan'    => $row['detail_pesanan_sisi_kanan'],
            'detail_pesanan_sisi_kiri'     => $row['detail_pesanan_sisi_kiri'],
            'detail_pesanan_sisi_blkng'    => $row['detail_pesanan_sisi_blkng'],
            'detail_pesanan_qty'           => $row['detail_pesanan_qty'],
            'detail_pesanan_satuan'        => $row['detail_pesanan_satuan'],
            'detail_pesanan_qty_packing'   => $row['detail_pesanan_qty_packing'],
            'detail_pesanan_jenis_packing' => $row['detail_pesanan_jenis_packing'],
            'detail_pesanan_status'        => $row['detail_pesanan_status'],
            'detail_pesanan_keterangan'    => $row['detail_pesanan_keterangan']
        );
        array_push($list_ubah_detail_pesanan, $list_ubah_detail_pesananTmp);
    }
}
?>

<body class="hold-transition skin-blue-light sidebar-collapse sidebar-mini">
<style type="text/css">
    input[type="radio"] {
        margin-right: 5px !important;
    }
    #packing_sets {
        padding: 15px 0;
    }
    #packing_sets .col-md-4,
    #packing_sets .col-md-8 {
        margin-bottom: 20px;
        padding: 0 20px 0 0;
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
            <h1>Ubah Barang Masuk</h1>
        </section>  
        <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <input type="hidden" id="no_pesanan" name="no_pesanan" class="form-control" value="<?php echo $nomor_pesanan;?>">
                        <div id="inputBarang" class="col-md-12" style="padding: 0">
                            <div class="form-group col-md-3">
                                <label>No Surat Jalan Masuk</label>
                                <input type="text" id="no_surat_jalan" class="form-control" value="<?php echo $no_surat_jalan;?>" placeholder="No. Surat Jalan dari Pelanggan">
                                <input type="hidden" id="jenis_pesanan" value="">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nama_pelanggan">Pelanggan</label>
                                <div class="input-group">
                                    <input type="text" id="nama_pelanggan" name="nama_pelanggan" class="form-control" placeholder="Nama Pelanggan" disabled value="<?php echo $nama_pelanggan; ?>">
                                    <div class="input-group-addon" data-toggle="modal" data-target="#modal-pelanggan">
                                        <i class="fa fa-user"></i>
                                    </div>
                                </div>
                                <input type="text" style="display: none" id="no_pelanggan" value="<?php echo $id_pelanggan; ?>">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Tgl Masuk Barang</label>
                                <div class="input-group date">
                                    <input type="text" class="form-control pull-right" id="tgl_pesanan" placeholder="Tanggal pesanan diterima" value="<?php echo $tgl_pesanan;?>">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        <!-- INPUT BARANG -->
                        </div>
                        <div id="inputBarang" class="col-md-12" style="padding: 0">
                            <div class="form-group col-md-3">
                                <label for="nama_barang">Nama Barang</label>
                                <input type="text" id="nama_barang" name="nama_barang" class="form-control" placeholder="Nama Barang" style="text-transform: capitalize;">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="jenis_print">Motif</label>
                                <input type="text" id="jenis_print" name="jenis_print" class="form-control" placeholder="Motif" style="text-transform: capitalize;">
                            </div>
                            <div class="form-group col-md-1">
                                <label for="warna">Jumlah Warna</label>
                                <input type="text" id="warna" name="warna" class="form-control" placeholder="Jumlah Warna" onkeypress="return numbersOnly(event, this.value)">
                            </div>
                            <div class="form-group col-md-1" style="width: 150px">
                                <label for="satuan">Satuan</label>
                                <select class="form-control" id="satuan" name="satuan">
                                    <option value="Kg">Kilogram (kg)</option>
                                    <option value="PS">Pasang</option>
                                    <option value="y">Yard (y)</option>
                                    <option value="m">Meter (m)</option>
                                    <option value="PT">Potong</option>
                                </select>
                            </div>
                            <div class="form-group col-md-1" style="width: 120px">
                                <label for="packing">Packing</label>
                                <select class="form-control" id="packing" name="packing">
                                    <option value="Roll">Roll</option>
                                    <option value="PT">Potong</option>
                                    <option value="PS">Pasang</option>
                                </select>
                            </div>
                        </div>
                        <!-- PENGATURAN PACKING -->
                        <div class="col-md-8" style="padding: 0 0 10px 0; text-align: left;">
                            <button class="btn btn-primary" id="btnBuatPack">
                                Buat Packing
                            </button>
                        </div>
                        <div class="col-md-4" style="padding: 0 0 10px 0; text-align: right;">
                            <button type="button" class="btn btn-custom" onclick="window.location='daftar_pesanan.php'">Kembali</button>
                            <button type="button" id="btnSimpanPesanan" class="btn btn-success">Simpan</button>
                        </div><br/>
                        <!-- TABEL PESANAN -->
                        <table style="margin-top: 10px" id="tabel_detail_pesanan" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 20px">No</th>
                                    <th>Key</th>
                                    <th>Nama Barang</th>
                                    <th>Motif</th>
                                    <th>Warna</th>
                                    <th>Kode Kain</th>
                                    <th>Nama Warna</th>
                                    <th>Ukuran</th>
                                    <th>Kanan</th>
                                    <th>Kiri</th>
                                    <th>Blkg</th>
                                    <th>BS/BP/BK</th>
                                    <th>Qty</th>
                                    <th>Satuan</th>
                                    <th>Qty Packing</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $num = 0;
                                foreach ($list_ubah_detail_pesanan as $detail) {
                                    $num++;
                                    echo ""
                                    . "<tr>"
                                        . "<td>" . $num . "</td>"
                                        . "<td>" . $detail['detail_pesanan_key'] . "</td>"
                                        . "<td>" . $detail['detail_pesanan_nama_barang'] . "</td>"
                                        . "<td>" . $detail['detail_pesanan_jenis_print'] . "</td>"
                                        . "<td>" . $detail['detail_pesanan_warna'] . "</td>"
                                        . "<td>" . $detail['detail_pesanan_detail_kain'] . "</td>"
                                        . "<td>" . $detail['detail_pesanan_nama_warna'] . "</td>"
                                        . "<td>" . $detail['detail_pesanan_ukuran'] . "</td>"
                                        . "<td>" . $detail['detail_pesanan_sisi_kanan'] . "</td>"
                                        . "<td>" . $detail['detail_pesanan_sisi_kiri'] . "</td>"
                                        . "<td>" . $detail['detail_pesanan_sisi_blkng'] . "</td>"
                                        . "<td>" . rtrim($detail['detail_pesanan_keterangan'], ", ") . "</td>"
                                        . "<td>" . $detail['detail_pesanan_qty'] . "</td>"
                                        . "<td>" . $detail['detail_pesanan_satuan'] . "</td>"
                                        . "<td>" . $detail['detail_pesanan_qty_packing'] . "</td>"
                                        . "<td>" . $detail['detail_pesanan_jenis_packing'] . "</td>"
                                    . "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </section>
    </div>
</div>
<!-- MODAL PACKING -->
<div class="modal fade" id="modal_packing">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" aria-label="Close">
               <span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title">Tambah Packing</h4>
            </div>
            <div class="box-body" style="padding: 15px">
                <div>
                    <div class="col-md-12" id="inputBarang">
                        <div class="form-group col-md-3">
                            <label>Kode Kain</label><br>
                            <input type="text" class="form-control" id="set_kodekain">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Nama Warna</label><br>
                            <input type="text" class="form-control" id="set_namawarna">
                        </div>
                        <div class="form-group col-md-1" style="width: 100px">
                            <label>Qty</label><br>
                            <input type="text" class="form-control" id="set_qty">
                        </div>
                        <div class="form-group col-md-1" style="width: 100px">
                            <label>Satuan</label><br>
                            <input type="text" class="form-control" id="set_satuan" disabled>
                        </div>
                        <div class="form-group col-md-1" style="width: 100px">
                            <label>Qty Packing</label><br>
                            <input type="text" class="form-control" id="set_qtypacking">
                        </div>
                        <div class="form-group col-md-1" style="width: 100px">
                            <label>Satuan</label><br>
                            <input type="text" class="form-control" id="set_satuanpack" disabled>
                        </div>
                    </div>
                    <!-- KANAN -->
                    <div class="col-md-12" id="inputBarang">
                        <label>Ukuran</label>
                        <input type="hidden" id="set_ukuran"><br>
                        <div id="cbgroup_size_kain">
                            <?php
                                for ($i=1; $i <= 22; $i++) { 
                                    $sval = $i;
                                    if ($i > 16) {
                                        if ($i == 17) { $sval = "XS"; }
                                        if ($i == 18) { $sval = "S"; }
                                        if ($i == 19) { $sval = "M"; }
                                        if ($i == 20) { $sval = "L"; }
                                        if ($i == 21) { $sval = "XL"; }
                                        if ($i == 22) { $sval = "XXL"; }
                                    }
                                    echo '<label style="width: 60px">'
                                        . '<div class="icheckbox_minimal-red">'
                                            . '<input type="checkbox" name="cb_ukuran" value="'.$sval.'">'
                                        . '</div> ' . $sval
                                    . '</label>';
                                }
                            ?>
                            <label style="width: 80px">
                                <div class="icheckbox_minimal-red">
                                    <input type="checkbox" name="cb_ukuran" value="46 x 46">
                                </div> 46 x 46
                            </label>
                            <label style="width: 90px">
                                <div class="icheckbox_minimal-red">
                                    <input type="checkbox" name="cb_ukuran" value="100 x 100">
                                </div> 100 x 50
                            </label>
                            <label style="width: 90px">
                                <div class="icheckbox_minimal-red">
                                    <input type="checkbox" name="cb_ukuran" value="100 x 100">
                                </div> 150 x 225
                            </label>
                        </div><br>
                        <label>Potongan</label>
                        <input type="hidden" id="set_jenispesanan"><br>
                        <input type="radio" name="rb_potongan" value="A"><span style="margin-right: 10px">Kanan - Kiri - Belakang</span>
                        <input type="radio" name="rb_potongan" value="B"><span style="margin-right: 10px">Kanan - Kiri</span>
                        <input type="radio" name="rb_potongan" value="C"><span style="margin-right: 10px">Cowo - Cewe</span>
                        <input type="radio" name="rb_potongan" value="D"><span style="margin-right: 10px">Depan - Lengan Kanan - Lengan Kiri</span>
                        <input type="radio" name="rb_potongan" value="E"><span style="margin-right: 10px">Potongan Biasa</span><br>
                        <input type="radio" name="rb_potongan" value="U" checked><span style="margin-right: 10px">Tidak Pilih</span>
                        <br><br>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btnBatalPack" type="button" class="btn btn-danger" style="float: left">Batal</button>
                <button id="btnTambahPack" type="button" class="btn btn-custom">Tambah Packing</button>
                <button id="btnSimpanPack" type="button" class="btn btn-success" disabled="true">Selesai</button>
            </div>
        </div>
    </div>
</div>
<?php include_once("footer.php"); ?>
<script>
var latest_detail_key_JS = "<?php echo $latest_detail_key; ?>";
var latest_detail_key_JS_Tmp = latest_detail_key_JS;
var no_tabel = 1;
var tipe_packing = "U";

$(function() {
    $('input').keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
    $('input').keypress(function() {
        this.value = this.value.toLocaleUpperCase();
    });

    var tabel = $('#tabel_detail_pesanan').DataTable({
        'paging'      : false,
        'lengthChange': false,
        'searching'   : false,
        'ordering'    : false,
        'info'        : false,
        'autoWidth'   : false,
        'select'      : true,
        'language' : {
            'search'           : '<i class="fa fa-search"></i>',
            'searchPlaceholder': 'Pencarian',
            'paginate' : {
                'previous' : '<i class="fa fa-angle-left"></i>',
                'next'     : '<i class="fa fa-angle-right"></i>'
            }
        },
        "columnDefs": [
            { "visible": false, "targets": 1 }, // key
            { "width": "20px", "targets": 0 }, // nomor
            // { "width": "0px", "targets": 1 }, // key
            { "width": "140px", "targets": 2 }, // nama brg
            { "width": "140px", "targets": 3 }, // motif
            { "width": "50px", "targets": 4 }, // warna
            { "width": "80px", "targets": 5 }, // kode kain
            { "width": "80px", "targets": 6 }, // nama warna
            { "width": "50px", "targets": 7 }, // ukuran
            { "width": "50px", "targets": 8 }, // kanan
            { "width": "50px", "targets": 9 }, // kiri
            { "width": "50px", "targets": 10 }, // blkg
            { "width": "80px", "targets": 11 }, // bsbpbk
            { "width": "50px", "targets": 12 }, // qty
            { "width": "50px", "targets": 13 }, // satuan
            { "width": "50px", "targets": 14 }, // qtypack
            { "width": "50px", "targets": 15 } // satuanpack
        ]
    });

    $('#tgl_pesanan').datepicker({
        'setDate'     : new Date(),
        'format'      : "dd-mm-yyyy"
    });

    $('#set_satuan').val( $('#satuan').val() );
    $('#set_satuanpack').val( $('#packing').val() );
    $('#set_qtypacking').val( 1 );

    $('#satuan').on('change', function() {
        $('#set_satuan').val( $('#satuan').val() );
    });
    $('#packing').on('change', function() {
        $('#set_satuanpack').val( $('#packing').val() );
    });

    $("input[name='rb_potongan']").on('click', function() {
        var jpt = $("input[name='rb_potongan']:checked").val();
        tipe_packing = jpt;
        if ( jpt == "U" || jpt == "E" ) {
            $('#set_qtypacking').val( 1 );
        } else if ( jpt == "A" || jpt == "D" ) {
            $('#set_qtypacking').val( $('#set_qty').val() * 3 );
        } else if ( jpt == "B" || jpt == "C" ) {
            $('#set_qtypacking').val( $('#set_qty').val() * 2 );
        }
    });

    $('#btnBuatPack').on('click', function() {
        if ( $('#nama_barang').val() == "" || $('#jenis_print').val() == "" || $('#warna').val() == "" || $('#warna').val() == 0 ) {
            alert("Nama Barang, Motif, Warna tidak boleh kosong.");
            return false;
        }

        $('#modal_packing').modal({
            show: true,
            keyboard: false,
            backdrop: 'static'
        });
    });

    $('#btnTambahPack').on('click', function() {
        var jenis_pesanan = $("input[name='rb_potongan']:checked").val();
        var ukuran = [];
        var bsbpbk = "<input style='width: 120px' onkeypress='this.value=this.value.toLocaleUpperCase()' onkeyup='this.value=this.value.toLocaleUpperCase()'/>";
        $.each($("input[name='cb_ukuran']:checked"), function(){            
            ukuran.push($(this).val());
        });
        ukuran = ukuran.join(",");
        ukuran = ukuran.split(",");

        if (ukuran == "") { 
            // TIDAK ADA UKURAN YG DI PILIH
            ukuran = "";
            tabel.row.add( [
                no_tabel,
                latest_detail_key_JS_Tmp,
                $('#nama_barang').val().toLocaleUpperCase(),
                $('#jenis_print').val().toLocaleUpperCase(),
                $('#warna').val(),
                $('#set_kodekain').val(),
                $('#set_namawarna').val().toLocaleUpperCase(),
                ukuran,
                0,
                0,
                0,
                bsbpbk,
                $('#set_qty').val(),
                $('#set_satuan').val(),
                $('#set_qtypacking').val(),
                $('#set_satuanpack').val()
            ] ).draw( false ); 

            no_tabel++;
            latest_detail_key_JS_Tmp = parseInt(latest_detail_key_JS_Tmp) + 1;
            latest_detail_key_JS_Tmp = latest_detail_key_JS_Tmp.toString().padStart(10, "0");
        } else { 
            // ADA UKURAN YG DIPILIH
            if (jenis_pesanan == "U" || jenis_pesanan == "E") { 
                // JNS PESANAN / POTONGAN "U" ATAU "E"
                // U : TIDAK PILIH, E : POTONGAN BIASA
                for (var i = 0; i < ukuran.length; i++) {
                    tabel.row.add( [
                        no_tabel,
                        latest_detail_key_JS_Tmp,
                        $('#nama_barang').val().toLocaleUpperCase(),
                        $('#jenis_print').val().toLocaleUpperCase(),
                        $('#warna').val(),
                        $('#set_kodekain').val(),
                        $('#set_namawarna').val().toLocaleUpperCase(),
                        ukuran[i],
                        0,
                        0,
                        0,
                        bsbpbk,
                        $('#set_qty').val(),
                        $('#set_satuan').val(),
                        $('#set_qtypacking').val(),
                        $('#set_satuanpack').val()
                    ] ).draw( false ); 

                    no_tabel++;
                    latest_detail_key_JS_Tmp = parseInt(latest_detail_key_JS_Tmp) + 1;
                    latest_detail_key_JS_Tmp = latest_detail_key_JS_Tmp.toString().padStart(10, "0");
                }
            } else if (jenis_pesanan == "A" || jenis_pesanan == "D") { 
                // JNS PESANAN / POTONGAN "A" ATAU "D"
                // A : KANAN - KIRI - BLKG, D : DEPAN - L. KANAN - L. KIRI
                for (var i = 0; i < ukuran.length; i++) {
                    tabel.row.add( [
                        no_tabel,
                        latest_detail_key_JS_Tmp,
                        $('#nama_barang').val().toLocaleUpperCase(),
                        $('#jenis_print').val().toLocaleUpperCase(),
                        $('#warna').val(),
                        $('#set_kodekain').val(),
                        $('#set_namawarna').val().toLocaleUpperCase(),
                        ukuran[i],
                        $('#set_qty').val(),
                        $('#set_qty').val(),
                        $('#set_qty').val(),
                        bsbpbk,
                        $('#set_qty').val(),
                        $('#set_satuan').val(),
                        $('#set_qtypacking').val(),
                        $('#set_satuanpack').val()
                    ] ).draw( false ); 

                    no_tabel++;
                    latest_detail_key_JS_Tmp = parseInt(latest_detail_key_JS_Tmp) + 1;
                    latest_detail_key_JS_Tmp = latest_detail_key_JS_Tmp.toString().padStart(10, "0");
                }
            } else if (jenis_pesanan == "B" || jenis_pesanan == "C") { 
                // JNS PESANAN / POTONGAN "B" ATAU "C"
                // B : KANAN - KIRI, C : COWO - CEWE
                for (var i = 0; i < ukuran.length; i++) {
                    tabel.row.add( [
                        no_tabel,
                        latest_detail_key_JS_Tmp,
                        $('#nama_barang').val().toLocaleUpperCase(),
                        $('#jenis_print').val().toLocaleUpperCase(),
                        $('#warna').val(),
                        $('#set_kodekain').val(),
                        $('#set_namawarna').val().toLocaleUpperCase(),
                        ukuran[i],
                        $('#set_qty').val(),
                        $('#set_qty').val(),
                        0,
                        bsbpbk,
                        $('#set_qty').val(),
                        $('#set_satuan').val(),
                        $('#set_qtypacking').val(),
                        $('#set_satuanpack').val()
                    ] ).draw( false ); 

                    no_tabel++;
                    latest_detail_key_JS_Tmp = parseInt(latest_detail_key_JS_Tmp) + 1;
                    latest_detail_key_JS_Tmp = latest_detail_key_JS_Tmp.toString().padStart(10, "0");
                }
            }
        }

        $('#set_kodekain').val("");
        $('#set_namawarna').val("");
        $('#set_qty').val(0);
        $('#set_qtypacking').val(1);
        $("input[name='rb_potongan']:last").prop("checked", true);
        $("input[name='cb_ukuran']:checked").prop("checked", false);
        $("#btnSimpanPack").prop("disabled", false);

    });
    
    $("#btnBatalPack").on('click', function() {
        $('#set_kodekain').val("");
        $('#set_namawarna').val("");
        $('#set_qty').val(0);
        $('#set_qtypacking').val(1);
        $("input[name='rb_potongan']:last").prop("checked", true);
        $("input[name='cb_ukuran']:checked").prop("checked", false);

        $('#modal_packing').modal('hide');
    });

    $("#btnSimpanPack").on('click', function() {
        $('#nama_barang').val("");
        $('#jenis_print').val("");
        $('#warna').val(0);
        $("#satuan").prop("selectedIndex", 0);
        $("#packing").prop("selectedIndex", 0);

        $('#set_kodekain').val("");
        $('#set_namawarna').val("");
        $('#set_qty').val(0);
        $('#set_qtypacking').val(1);
        $("input[name='rb_potongan']:last").prop("checked", true);
        $("input[name='cb_ukuran']:checked").prop("checked", false);

        $('#modal_packing').modal('hide');
    });    

    $("#btnSimpanPesanan").on('click', function() {
        $('#on_loading_modal').modal({
            show: true,
            keyboard: false,
            backdrop: 'static'
        });

        var no_surat_jalan = document.getElementById('no_surat_jalan').value;
        var jenis_pesanan  = tipe_packing;
        var tgl_pesanan    = document.getElementById('tgl_pesanan').value;
        var no_pelanggan   = document.getElementById('no_pelanggan').value;
        var list_barang    = {};
        var save_barang    = {};
        list_barang        = tabel.rows().data();
        delete list_barang.context;
        delete list_barang.selector;
        delete list_barang.ajax;

        // console.log("list_barang : " + JSON.stringify(list_barang));

        for (var i = 0; i < list_barang.length; i++) {
            var keterangan = tabel.cell(i, 11).nodes().to$().find('input').val();

            save_barang[i] = {
                'detail_pesanan_key'            : list_barang[i][1],
                'detail_pesanan_nama_barang'    : list_barang[i][2],
                'detail_pesanan_jenis_print'    : list_barang[i][3],
                'detail_pesanan_warna'          : list_barang[i][4],
                'detail_pesanan_detail_kain'    : list_barang[i][5],
                'detail_pesanan_nama_warna'     : list_barang[i][6],
                'detail_pesanan_ukuran'         : list_barang[i][7],
                'detail_pesanan_sisi_kanan'     : list_barang[i][8],
                'detail_pesanan_sisi_kiri'      : list_barang[i][9],
                'detail_pesanan_sisi_blkng'     : list_barang[i][10],
                'detail_pesanan_keterangan'     : keterangan,
                'detail_pesanan_qty'            : list_barang[i][12],
                'detail_pesanan_satuan'         : list_barang[i][13],
                'detail_pesanan_qty_packing'    : list_barang[i][14],
                'detail_pesanan_jenis_packing'  : list_barang[i][15]
            }
        }
        console.log("save_barang : " + JSON.stringify(save_barang));
        $.ajax({
            type : 'POST',
            url  : 'proses_pesanan_update.php',
            dataType : 'JSON',
            data : {
                'satuan_pesanan' : "",
                'nomor_pesanan'  : document.getElementById('no_pesanan').value,
                'no_surat_jalan' : no_surat_jalan,
                'tgl_pesanan'    : tgl_pesanan,
                'no_pelanggan'   : no_pelanggan,
                'jenis_pesanan'  : jenis_pesanan,
                'tipe_packing'   : tipe_packing,
                'list_barang'    : JSON.stringify(save_barang)
            },
            success : function(response) {
                console.log("response : " + JSON.stringify(response));
                if (response.response_code == "00") {
                    showSuccessMessage('Pesanan berhasil di simpan.');
                } else {
                    showFailedMessage(response.response_msg);
                }
            },
            failed : function(response) {
                console.log("response : " + JSON.stringify(response));
                showFailedMessage('Pesanan tidak dapat di simpan.');
            },
            error : function(response) {
                console.log("response : " + JSON.stringify(response));
                showFailedMessage('Pesanan gagal di simpan.');
            }
        });

    }); 

});
</script>
</body>
</html>
