<?php 

include_once("head.php"); 
include_once("konfigurasi.php"); 

$src_no_pesanan = "";
$list_pesanan = array();
$list_detail_pesanan = array();
$list_detail_pesananTmp = array();
$list_detail_pesananTmp2 = array();

tulisLog('surat_jalan.php -> load');
if ( isset($_GET['pesanan_no_pesanan']) ) {
    $src_no_pesanan = $_GET['pesanan_no_pesanan'];
    $sel_psn = "SELECT psn.*, dps.*, plg.pelanggan_nama AS pesanan_nama_plg, sj.surat_jalan_detail_subtotal_pack"
            . " FROM pesanan psn"
            . " JOIN detail_pesanan dps ON dps.detail_pesanan_no_pesanan = psn.pesanan_no_pesanan"
            . " JOIN pelanggan plg ON plg.pelanggan_id = psn.pesanan_pelanggan_id"
            . " LEFT JOIN surat_jalan_detail sj ON sj.surat_jalan_detail_pesanan_key = dps.detail_pesanan_key"
            . " WHERE psn.pesanan_no_pesanan = '".$src_no_pesanan."'";

    tulisLog("lihat_pesanan.php -> sel_psn : ".$sel_psn);
    $query_psn = $con->query($sel_psn);
    while ($row = $query_psn->fetch_assoc()) {
        $list_pesananTmp = array(
            'pesanan_id'                    => $row['pesanan_id'],
            'pesanan_no_pesanan'            => $row['pesanan_no_pesanan'],
            'pesanan_no_surat_jalan_masuk'  => $row['pesanan_no_surat_jalan_masuk'],
            'pesanan_jenis_pesanan'         => $row['pesanan_jenis_pesanan'],
            'pesanan_pelanggan_id'          => $row['pesanan_pelanggan_id'],
            'pesanan_nama_plg'              => $row['pesanan_nama_plg'],
            'pesanan_tanggal_pesanan'       => $row['pesanan_tanggal_pesanan'],
            'pesanan_total_qty'             => $row['pesanan_total_qty'],
            'pesanan_total_qty_packing'     => $row['pesanan_total_qty_packing']
        );
        array_push($list_pesanan, $list_pesananTmp);

        if (empty($list_detail_pesanan)) {
            $list_detail_pesananTmp = array(
                // 'detail_pesanan_id'            => $row['detail_pesanan_id'],
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
                'detail_pesanan_keterangan'    => $row['detail_pesanan_keterangan'],
                'surat_jalan_detail_subtotal_pack' => $row['surat_jalan_detail_subtotal_pack']
            );
        } else {
            $list_detail_pesananTmp = array(
                // 'detail_pesanan_id'            => $row['detail_pesanan_id'],
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
                'detail_pesanan_keterangan'    => $row['detail_pesanan_keterangan'],
                'surat_jalan_detail_subtotal_pack' => $row['surat_jalan_detail_subtotal_pack']
            );
            foreach ($list_detail_pesanan as $key => $value) {
                if ($value['detail_pesanan_key'] == $row['detail_pesanan_key']) {
                    unset($list_detail_pesanan[$key]);
                    
                    $list_detail_pesananTmp = array(
                        // 'detail_pesanan_id'            => $value['detail_pesanan_id'],
                        'detail_pesanan_key'           => $value['detail_pesanan_key'],
                        'detail_pesanan_nama_barang'   => $value['detail_pesanan_nama_barang'],
                        'detail_pesanan_jenis_print'   => $value['detail_pesanan_jenis_print'],
                        'detail_pesanan_warna'         => $value['detail_pesanan_warna'],
                        'detail_pesanan_nama_warna'    => $value['detail_pesanan_nama_warna'],
                        'detail_pesanan_detail_kain'   => $value['detail_pesanan_detail_kain'],
                        'detail_pesanan_ukuran'        => $value['detail_pesanan_ukuran'],
                        'detail_pesanan_sisi_kanan'    => $value['detail_pesanan_sisi_kanan'],
                        'detail_pesanan_sisi_kiri'     => $value['detail_pesanan_sisi_kiri'],
                        'detail_pesanan_sisi_blkng'    => $value['detail_pesanan_sisi_blkng'],
                        'detail_pesanan_qty'           => $value['detail_pesanan_qty'],
                        'detail_pesanan_satuan'        => $value['detail_pesanan_satuan'],
                        'detail_pesanan_qty_packing'   => $value['detail_pesanan_qty_packing'],
                        'detail_pesanan_jenis_packing' => $value['detail_pesanan_jenis_packing'],
                        'detail_pesanan_status'        => $value['detail_pesanan_status'],
                        'detail_pesanan_keterangan'    => $value['detail_pesanan_keterangan'],
                        'surat_jalan_detail_subtotal_pack' => $row['surat_jalan_detail_subtotal_pack'] + $value['surat_jalan_detail_subtotal_pack']
                    );
                }
            }
        }
        array_push($list_detail_pesanan, $list_detail_pesananTmp);
    }
}
?>

<body class="hold-transition skin-blue-light sidebar-collapse sidebar-mini">
<style type="text/css">
    .input_SJ {
        width: 100% !important;
    }
    .tab_input_ds {
        width: 100%;
        background-color: #ffffff;
        border: none;
        text-transform: uppercase;
    }
    tbody tr td {
        padding: 5px 2px 5px 5px !important;
    }
    thead tr th {
        font-size: 11px !important;
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
            <h1>Tambah Surat Jalan</h1>
        </section>  
        <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body" id="inputBarang">
                        <div class="form-group col-md-2" style="width: 200px">
                            <label for="no_pesanan">No Pesanan</label>
                            <input type="text" id="no_pesanan" class="form-control" value="<?php echo $list_pesanan[0]['pesanan_no_pesanan'];?>">
                        </div>
                        <div class="form-group col-md-2" style="width: 200px">
                            <label>No Surat Jalan Masuk</label>
                            <input type="text" id="no_surat_jalan" class="form-control" value="<?php echo $list_pesanan[0]['pesanan_no_surat_jalan_masuk'];?>">
                            <input type="hidden" id="jenis_pesanan" value="<?php echo $list_pesanan[0]['pesanan_jenis_pesanan'];?>">
                        </div>
                        <div class="form-group col-md-2" style="width: 280px">
                            <label>Pelanggan</label>
                            <input type="text" id="nama_pelanggan" class="form-control" value="<?php echo $list_pesanan[0]['pesanan_nama_plg'];?>">
                            <input type="hidden" id="no_pelanggan" value="<?php echo $list_pesanan[0]['pesanan_pelanggan_id'];?>">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Tgl Masuk Barang</label>
                            <div class="input-group date">
                                <input type="text" class="form-control pull-right" id="tgl_pesanan" value="<?php echo $list_pesanan[0]['pesanan_tanggal_pesanan'];?>">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-1" style="width: 120px">
                            <label>Total Qty</label>
                            <input type="text" id="total_qty" class="form-control" value="<?php echo $list_pesanan[0]['pesanan_total_qty'] .' '. $list_detail_pesanan[0]['detail_pesanan_satuan'];?>">
                        </div>
                        <div class="form-group col-md-1" style="width: 120px">
                            <label>Total Qty Packing</label>
                            <input type="text" id="total_qty_packing" class="form-control" value="<?php echo $list_pesanan[0]['pesanan_total_qty_packing'] .' '. $list_detail_pesanan[0]['detail_pesanan_jenis_packing'];?>">
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-12" style="padding: 0">
                                <button type="button" class="btn btn-custom" onclick="window.location='daftar_surat_jalan.php'">Kembali</button>
                                <button type="button" id="btnCetak" class="btn btn-primary" disabled="true">Cetak Surat Jalan</button>
                            </div><br/>
                        </div>
                        <!-- tabel detail pesanan -->
                        <table id="tabel_detail_pesanan" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 20px">No</th>
                                    <th style="display: none;">Key</th> <!-- hide -->
                                    <th>Nama Barang</th>
                                    <th>Motif</th>
                                    <th>Warna</th>
                                    <th>Kode Kain</th>
                                    <th>Nama Warna</th>
                                    <th>Ukuran</th>
                                    <th>Qty</th>
                                    <th>Satuan</th>
                                    <th>Qty Packing</th>
                                    <th>Jenis Packing</th>
                                    <th>Keterangan</th>
                                    <th>Pilih <br>
                                        <input type="checkbox" id="chk_pilihSemua"></input>
                                    </th>
                                    <th>Kirim Sebanyak</th>
                                    <th>Sisa</th>
                                    <th>Terkirim</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $num = 0;
                            foreach ($list_detail_pesanan as $value) {
                                // $num++;
                                // $kirim = $value['surat_jalan_detail_subtotal_pack'];
                                // if ( $kirim == null ) {
                                //     $kirim = 0;
                                // }
                                // $sisa = (int) $value['detail_pesanan_qty_packing'] - (int) $kirim;
                                $disable_input = '';
                                $num++;
                                $terkirim = $value['surat_jalan_detail_subtotal_pack'];
                                if ( $terkirim == null ) {
                                    $terkirim = 0;
                                }
                                $sisa = (int) $value['detail_pesanan_qty_packing'] - (int) $terkirim;
                                if ( (int) $terkirim > 0 && (int) $sisa == 0) {
                                    $disable_input = 'disabled';
                                }
                                echo ""
                                . "<tr>"
                                    . "<td style='width: 20px'>"
                                    . "<input class='tab_input_ds' name='0' value='".$num."' disabled='true'/>"
                                    . "</td>"
                                    . "<td style='display: none'>"
                                    . "<input class='tab_input_ds' name='1' value='".$value['detail_pesanan_key']."' disabled='true'/>"
                                    . "</td>"
                                    . "<td>"
                                    . "<input class='tab_input_ds' name='2' value='".$value['detail_pesanan_nama_barang']."' disabled='true'/>"
                                    . "</td>"
                                    . "<td>"
                                    . "<input class='tab_input_ds' name='3' value='".$value['detail_pesanan_jenis_print']."' disabled='true'/>"
                                    . "</td>"
                                    . "<td>"
                                    . "<input class='tab_input_ds' name='4' value='".$value['detail_pesanan_warna']."' disabled='true'/>"
                                    . "</td>"
                                    . "<td>"
                                    . "<input class='tab_input_ds' name='5' value='".$value['detail_pesanan_detail_kain']."' disabled='true'/>"
                                    . "</td>"
                                    . "<td>"
                                    . "<input class='tab_input_ds' name='6' value='".$value['detail_pesanan_nama_warna']."' disabled='true'/>"
                                    . "</td>"
                                    . "<td>"
                                    . "<input class='tab_input_ds' name='7' value='".$value['detail_pesanan_ukuran']."' disabled='true'/>"
                                    . "</td>"
                                    . "<td>"
                                    . "<input class='tab_input_ds' name='8' value='".$value['detail_pesanan_qty']."' disabled='true'/>"
                                    . "</td>"
                                    . "<td>"
                                    . "<input class='tab_input_ds' name='9' value='".$value['detail_pesanan_satuan']."' disabled='true'/>"
                                    . "</td>"
                                    . "<td>"
                                    . "<input class='tab_input_ds' name='10' id='qtypck_".$num."' value='".$value['detail_pesanan_qty_packing']."' disabled='true'/>"
                                    . "</td>"
                                    . "<td>"
                                    . "<input class='tab_input_ds' name='11' value='".$value['detail_pesanan_jenis_packing']."' disabled='true'/>"
                                    . "</td>"
                                    . "<td>"
                                    . "<input class='tab_input_ds' name='12' value='".$value['detail_pesanan_keterangan']."' disabled='true'/>"
                                    . "</td>"
                                    . "<td>"
                                    . "<input type='checkbox' class='checkbox_SJ' name='13' id='pilih_".$num."' ".$disable_input." onchange='setKirimByCheck(".$num.")'></input>"
                                    . "</td>"
                                    . "<td>"
                                    . "<input class='input_SJ' name='14' id='kirim_".$num."' value='0' ".$disable_input." onkeyup='setKirim(".$num.")'></input>"
                                    . "</td>"
                                    . "<td>"
                                    . "<input class='input_SJ' name='15' id='sisa_".$num."' value='".$sisa."' disabled='true'></input>"
                                    . "</td>"
                                    . "<td>"
                                    . "<input class='input_SJ' name='16' id='terkirim_".$num."' value='".$terkirim."' disabled='true'></input>"
                                    . "</td>"
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

<?php include_once("footer.php"); ?>
<script>
    function setKirim(num) {
        $("#sisa_"+num).val( ($("#qtypck_"+num).val() - $("#terkirim_"+num).val()) - $("#kirim_"+num).val() );

        if ($("#sisa_"+num).val() >= 0) {
            document.getElementById('btnCetak').disabled = false;
        } else {
            document.getElementById('btnCetak').disabled = true;
        }
    }

    function setKirimByCheck(num) {
        $("#kirim_"+num).val( $("#qtypck_"+num).val() - $("#terkirim_"+num).val() );
        $("#sisa_"+num).val( ($("#qtypck_"+num).val() - $("#terkirim_"+num).val()) - $("#kirim_"+num).val() );

        if ($("#sisa_"+num).val() >= 0) {
            document.getElementById('btnCetak').disabled = false;
        } else {
            document.getElementById('btnCetak').disabled = true;
        }
    }

    $(function () {

        var tabel_detail = $('#tabel_detail_pesanan').DataTable({
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
                // { "visible": false, "targets": 1 }, // key
                { "width": "20px", "targets": 0 }, // nomor
                { "width": "140px", "targets": 2 }, // nama brg
                { "width": "140px", "targets": 3 }, // motif
                { "width": "60px", "targets": 4 }, // warna
                { "width": "60px", "targets": 5 }, // kode kain
                { "width": "80px", "targets": 6 }, // nama warna
                { "width": "60px", "targets": 7 }, // ukuran
                { "width": "60px", "targets": 8 }, // qty
                { "width": "60px", "targets": 9 }, // satuan
                { "width": "60px", "targets": 10 }, // qty pack
                { "width": "80px", "targets": 11 }, // jns pack
                { "width": "100px", "targets": 12 }, // ket
                { "width": "40px", "targets": 13 }, // pilih
                { "width": "30px", "targets": 14 }, // kirim
                { "width": "30px", "targets": 15 }, // sisa
                { "width": "30px", "targets": 16 } // terkirim
            ]
        });

        $("#chk_pilihSemua").on('change', function() {
            var x = document.getElementsByClassName("checkbox_SJ");
            if (this.checked) {
                for (var i = 0; i < x.length; i++) {
                    if (x[i].disabled == false) {
                        var num = i + 1;
                        $("#kirim_"+num).val( $("#qtypck_"+num).val() - $("#terkirim_"+num).val() );
                        $("#pilih_"+num).attr( 'checked', true );
                        setKirimByCheck(num);
                    }
                }
            } else {
                for (var i = 0; i < x.length; i++) {
                    if (x[i].disabled == false) {
                        var num = i + 1;
                        $("#kirim_"+num).val( 0 );
                        $("#pilih_"+num).attr( 'checked', false );
                        $("#sisa_"+num).val( $("#qtypck_"+num).val() - $("#terkirim_"+num).val() );
                    }
                }
            }
        });  

        $(".checkbox_SJ").on('change', function() {
            var num = this.id;
            num = num.substring(num.length - 1);

            if (this.checked) {
                $("#kirim_"+num).val( $("#qtypck_"+num).val() + $("#terkirim_"+num).val() );
                $("#pilih_"+num).attr( 'checked', true );

                setKirimByCheck(num);
            } else {
                $("#kirim_"+num).val( 0 );
                $("#pilih_"+num).attr( 'checked', false );
                $("#sisa_"+num).val( $("#qtypck_"+num).val() - $("#terkirim_"+num).val() );
            }
        });      

        $("#btnCetak").on('click', function() {
            var list_sj = new Array();
            var x = document.getElementsByClassName("checkbox_SJ");
            var count_chk = 0;
            for (var i = 0; i < x.length; i++) {
                if (x[i].checked == true) {
                    count_chk++;
                }
            }
            if (count_chk == 0) {
                alert("Silahkan, pilih barang yang akan dikirim.");
                return false;
            }
            // PROSES SURJA
            document.querySelectorAll('#tabel_detail_pesanan tbody tr').forEach(function(row) {
                // console.log(row);
                if ( row.querySelector('td input[name="13"]').checked ) {
                    list_sj.push({
                        'surat_jalan_detail_pesanan_key'   : row.querySelector('td input[name="1"]').value,
                        'surat_jalan_detail_nama_barang'   : row.querySelector('td input[name="2"]').value,
                        'surat_jalan_detail_jenis_print'   : row.querySelector('td input[name="3"]').value,
                        'surat_jalan_detail_warna'         : row.querySelector('td input[name="4"]').value,
                        'surat_jalan_detail_kain'          : row.querySelector('td input[name="5"]').value,
                        'surat_jalan_detail_nama_warna'    : row.querySelector('td input[name="6"]').value,
                        'surat_jalan_detail_ukuran'        : row.querySelector('td input[name="7"]').value,
                        'surat_jalan_detail_subtotal_qty'  : row.querySelector('td input[name="8"]').value,
                        'surat_jalan_detail_satuan_qty'    : row.querySelector('td input[name="9"]').value,
                        'surat_jalan_detail_subtotal_pack' : row.querySelector('td input[name="14"]').value,
                        'surat_jalan_detail_satuan_pack'   : row.querySelector('td input[name="11"]').value,
                        'surat_jalan_detail_keterangan'    : row.querySelector('td input[name="12"]').value
                    });
                }
            });

            var no_pesanan     = document.getElementById('no_pesanan').value;
            var no_surat_jalan = document.getElementById('no_surat_jalan').value;
            var jenis_pesanan  = document.getElementById('jenis_pesanan').value;
            var no_pelanggan   = document.getElementById('no_pelanggan').value;

            console.log(JSON.stringify(list_sj));
            $.ajax({
                type : 'POST',
                url  : 'proses_surat_jalan.php',
                dataType : 'JSON',
                data : {
                    // 'jenis_proses'             : 'buat_suratjalan',
                    'surat_jalan_nomor_pesanan': no_pesanan,
                    'surat_jalan_nomor'        : no_surat_jalan,
                    'surat_jalan_pelanggan_id' : no_pelanggan,
                    'surat_jalan_no_pesanan'   : no_pesanan,
                    'surat_jalan_jenis_pesanan': jenis_pesanan,
                    'list_barang'              : JSON.stringify(list_sj)
                },
                success : function(response) {
                    console.log(JSON.stringify(response));
                    if (response.response_code == "00") {
                        // showSuccessMessage('Pesanan berhasil di simpan.');
                        window.location = "cetak_surat_jalan.php?nomor_suratjalan="+response.surat_jalan_key;
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

    });
</script>
</body>
</html>