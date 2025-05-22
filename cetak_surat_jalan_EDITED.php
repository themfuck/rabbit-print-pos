<?php 
include_once('konfigurasi.php'); 
$nomor_suratjalan = $_GET['nomor_suratjalan'];
$nomor_suratjalan_mn = "";
$tgl_kirim = "";
$nama_pelanggan = "";
$alamat_pelanggan = "";
$nama_barang = "";
$qty_barang = "";
$satuan = "";
$dibuat_oleh = "";
$barang = "";
$totalqty = "";
$totalpck = "";
$jenis_pesanan = "";
$num = 1;
$table_detail = '';
$detail_suratjalan = '';
$table_rows = '';
$keterangan = '';
$detail_suratjalan = '';
$nama_warna = '';
$nama_warnaTmp = '';
$keterangan = '';
$detail_suratjalan = "";
$nama_warna = "";
$nama_warnaTmp = "";
$keterangan = "";
$detail_suratjalan = "";
$nama_warna = "";
$nama_warnaTmp = "";


$sel_pesanan = "SELECT sj.*, p.pelanggan_nama, p.pelanggan_telp, p.pelanggan_alamat, u.nama AS createby"
            . " FROM surat_jalan sj"
            . " JOIN pelanggan p ON p.pelanggan_id = sj.surat_jalan_pelanggan_id"
            . " JOIN s_user u ON u.username = sj.cre_usr"
            . " WHERE sj.surat_jalan_key = '" . $nomor_suratjalan . "'";

            tulisLog('cetak_surat_jalan.php -> sel_pesanan : ' . $sel_pesanan);
$query_psn = $con->query($sel_pesanan);
while ($row = $query_psn->fetch_assoc()) {
    $nomor_suratjalan_mn = $row["surat_jalan_nomor"];
    $tgl_kirim = date("d-m-Y");
    $nama_pelanggan = $row["pelanggan_nama"];
    $alamat_pelanggan = $row["pelanggan_alamat"];
    $dibuat_oleh = $row["createby"];
    $totalqty = $row["surat_jalan_total"];
    $jenis_pesanan = $row["surat_jalan_jenis_pesanan"];
}

$sel_detailsj = "SELECT sj.*, dp.*"
            . " FROM surat_jalan_detail sj"
            . " JOIN detail_pesanan dp ON dp.detail_pesanan_key = sj.surat_jalan_detail_pesanan_key"
            . " WHERE surat_jalan_detail_surat_jalan_key = '".$nomor_suratjalan."'";

tulisLog('cetak_surat_jalan.php -> sel_detailsj : ' . $sel_detailsj);
$query_psn2 = $con->query($sel_detailsj);
$num = 1;
$table_body = array();
$list_table_detail = array();
$belakang = 0;
while ($row = $query_psn2->fetch_assoc()) {
    $detail_suratjalan = "";

    if ($jenis_pesanan == "U" || $jenis_pesanan == "E") {
        $detail_suratjalan .= $row['surat_jalan_detail_subtotal_qty']. " x " . $row['surat_jalan_detail_subtotal_pack'] . ", ";
    }

    if (empty($table_body)) {
        $list_table_detailTmp = array(
            'detail_pesanan_detail_key'         => $row['surat_jalan_detail_pesanan_key'],
            'detail_pesanan_detail_kain'        => $row['surat_jalan_detail_kain'],
            'detail_pesanan_nama_warna'         => $row['surat_jalan_detail_nama_warna'],
            'detail_pesanan_ukuran'             => $row['detail_pesanan_ukuran'],
            'detail_pesanan_sisi_kanan'         => $row['detail_pesanan_sisi_kanan'],
            'detail_pesanan_sisi_kiri'          => $row['detail_pesanan_sisi_kiri'],
            'detail_pesanan_sisi_blkng'         => $row['detail_pesanan_sisi_blkng'],
            'detail_pesanan_qty'                => $row['surat_jalan_detail_subtotal_qty'],
            'detail_pesanan_satuan'             => $row['surat_jalan_detail_satuan_qty'],
            'detail_pesanan_qty_pack'           => $row['surat_jalan_detail_subtotal_pack'],
            'detail_pesanan_keterangan'         => $row['surat_jalan_detail_keterangan']
        );
        array_push($list_table_detail, $list_table_detailTmp);

        if ($jenis_pesanan == "U" || $jenis_pesanan == "E") {
            $qty = (double) $row['surat_jalan_detail_subtotal_qty'] * (double) $row['surat_jalan_detail_subtotal_pack'];
        } else {
            $qtyTmp = (int) $row['detail_pesanan_sisi_kanan'] + (int) $row['detail_pesanan_sisi_kiri'] + (int) $row['detail_pesanan_sisi_blkng'];
            if ((int) $row['detail_pesanan_sisi_blkng'] > 0) {
                $qty = $qtyTmp / 3;
            } else {
                $qty = $qtyTmp / 2;
            }
        }

        $table_bodyTmp = array(
            'surat_jalan_detail_pesanan_key'    => $row['surat_jalan_detail_pesanan_key'] . ",",
            'surat_jalan_detail_nama_barang'    => $row['surat_jalan_detail_nama_barang'],
            'surat_jalan_detail_jenis_print'    => $row['surat_jalan_detail_jenis_print'],
            'surat_jalan_detail_warna'          => $row['surat_jalan_detail_warna'],
            'surat_jalan_detail_satuan_qty'     => $row['surat_jalan_detail_satuan_qty'],
            'surat_jalan_detail_subtotal_qty'   => $qty,
            'surat_jalan_detail_subtotal_pack'  => $row['surat_jalan_detail_subtotal_pack'],
            'surat_jalan_detail_satuan_pack'    => $row['surat_jalan_detail_satuan_pack'],
            'detail_suratjalan'                 => rtrim($detail_suratjalan, ", ")
        );
        array_push($table_body, $table_bodyTmp);

    } else {
        $list_table_detailTmp = array(
            'detail_pesanan_detail_key'         => $row['surat_jalan_detail_pesanan_key'],
            'detail_pesanan_detail_kain'        => $row['surat_jalan_detail_kain'],
            'detail_pesanan_nama_warna'         => $row['surat_jalan_detail_nama_warna'],
            'detail_pesanan_ukuran'             => $row['detail_pesanan_ukuran'],
            'detail_pesanan_sisi_kanan'         => $row['detail_pesanan_sisi_kanan'],
            'detail_pesanan_sisi_kiri'          => $row['detail_pesanan_sisi_kiri'],
            'detail_pesanan_sisi_blkng'         => $row['detail_pesanan_sisi_blkng'],
            'detail_pesanan_qty'                => $row['surat_jalan_detail_subtotal_qty'],
            'detail_pesanan_satuan'             => $row['surat_jalan_detail_satuan_qty'],
            'detail_pesanan_qty_pack'           => $row['surat_jalan_detail_subtotal_pack'],
            'detail_pesanan_keterangan'         => $row['surat_jalan_detail_keterangan']
        );
        array_push($list_table_detail, $list_table_detailTmp);

        foreach ($table_body as $key => $value) {
            if ( $value['surat_jalan_detail_nama_barang'] == $row['surat_jalan_detail_nama_barang']
                 && $value['surat_jalan_detail_jenis_print'] == $row['surat_jalan_detail_jenis_print'] ) {

                unset($table_body[$key]);

                if ($jenis_pesanan == "U" || $jenis_pesanan == "E") {
                    $qty += (double) $row['surat_jalan_detail_subtotal_qty'] * (double) $row['surat_jalan_detail_subtotal_pack'];
                } else {
                    $qtyTmp = (int) $row['detail_pesanan_sisi_kanan'] + (int) $row['detail_pesanan_sisi_kiri'] + (int) $row['detail_pesanan_sisi_blkng'];
                    if ((int) $row['detail_pesanan_sisi_blkng'] > 0) {
                        $qtyTmp = $qtyTmp / 3;
                        $qty += $qtyTmp;
                    } else {
                        $qtyTmp = $qtyTmp / 2;
                        $qty += $qtyTmp;
                    }
                }

                $table_bodyTmp = array(
                'surat_jalan_detail_pesanan_key'    => $value['surat_jalan_detail_pesanan_key'] . $row['surat_jalan_detail_pesanan_key'] . ",",
                'surat_jalan_detail_nama_barang'    => $value['surat_jalan_detail_nama_barang'],
                'surat_jalan_detail_jenis_print'    => $value['surat_jalan_detail_jenis_print'],
                'surat_jalan_detail_warna'          => $value['surat_jalan_detail_warna'],
                'surat_jalan_detail_satuan_qty'     => $value['surat_jalan_detail_satuan_qty'],
                'surat_jalan_detail_subtotal_qty'   => $qty,
                'surat_jalan_detail_subtotal_pack'  => (double) $value['surat_jalan_detail_subtotal_pack'] + (double) $row['surat_jalan_detail_subtotal_pack'],
                'surat_jalan_detail_satuan_pack'    => $value['surat_jalan_detail_satuan_pack'],
                'detail_suratjalan'                 => rtrim($detail_suratjalan .= $value['detail_suratjalan'], ", ")
                );

            } else {

                if ($jenis_pesanan == "U" || $jenis_pesanan == "E") {
                    $qty = (double) $row['surat_jalan_detail_subtotal_qty'] * (double) $row['surat_jalan_detail_subtotal_pack'];
                } else {
                    $qtyTmp = (int) $row['detail_pesanan_sisi_kanan'] + (int) $row['detail_pesanan_sisi_kiri'] + (int) $row['detail_pesanan_sisi_blkng'];
                    if ((int) $row['detail_pesanan_sisi_blkng'] > 0) {
                        $qty = $qtyTmp / 3;
                    } else {
                        $qty = $qtyTmp / 2;
                    }
                }

                $table_bodyTmp = array(
                    'surat_jalan_detail_pesanan_key'    => $row['surat_jalan_detail_pesanan_key'] . ",",
                    'surat_jalan_detail_nama_barang'    => $row['surat_jalan_detail_nama_barang'],
                    'surat_jalan_detail_jenis_print'    => $row['surat_jalan_detail_jenis_print'],
                    'surat_jalan_detail_warna'          => $row['surat_jalan_detail_warna'],
                    'surat_jalan_detail_satuan_qty'     => $row['surat_jalan_detail_satuan_qty'],
                    'surat_jalan_detail_subtotal_qty'   => $qty,
                    'surat_jalan_detail_subtotal_pack'  => $row['surat_jalan_detail_subtotal_pack'],
                    'surat_jalan_detail_satuan_pack'    => $row['surat_jalan_detail_satuan_pack'],
                    'detail_suratjalan'                 => rtrim($detail_suratjalan, ", ")
                );
            }
        }
        array_push($table_body, $table_bodyTmp);
    }
}

$file_name = 'Surat_Jalan_' . $nomor_suratjalan . '.pdf';

$content = '
<style type="text/css">
.tabel {
    text-transform: uppercase;
}
.tabel th {
  display: table-cell;
  vertical-align: inherit;
  text-align: center;
}
.body,
.table,
.div {
    font-size: 12px !important;
}
.thead tr th {
    text-align: center;
    font-size: 14px !important;
}
.container {
    position: relative;
}

.bottomleft {
    position: absolute;
    bottom: 8px;
    left: 16px;
}
.cologne {
    padding: 3px !important;

}
table .no_border,
.no_border thead tr,
.no_border thead tr th,
.no_border tr th,
.no_border tr,
.no_border tr td {
    border: none !important;
}
.size_num {
    text-align: center;
}
.detail_rows {
    padding: 10px;
}
.main_rows {
    padding-bottom: 10px;
}
</style>';
$content .= '
<link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
</head>
<body>
<div style="padding: 0 10px 0px 25px">
<h3>SURAT JALAN</h3>
<table width="1000" border="0" cellspacing="0" cellpadding="3">
    <tr>
        <td class="main_rows" width="100">No. Pengiriman</td>
        <td class="main_rows" width="180">: '.$nomor_suratjalan.'</td>
        <td class="main_rows" width="10"></td>
        <td class="main_rows" width="100">Tanggal Kirim</td>
        <td class="main_rows" width="200">: '.$tgl_kirim.'</td>
    </tr>
    <tr>
        <td class="main_rows" width="100" valign="top">No. Surat Jalan</td>
        <td class="main_rows" width="180">: '.$nomor_suratjalan_mn.'</td>
        <td class="main_rows" width="10"></td>
        <td class="main_rows" width="100">Kepada</td>
        <td class="main_rows" width="200">: '.$nama_pelanggan.'</td>
    </tr>
    <tr>
        <td class="main_rows" width="100" valign="top"></td>
        <td class="main_rows" width="180"></td>
        <td class="main_rows" width="10"></td>
        <td class="main_rows" width="100">Alamat</td>
        <td class="main_rows" width="200">: '.$alamat_pelanggan.'</td>
    </tr>
</table>
<br>
<table border="0.5" cellspacing="0" class="tabel">
    <thead>
        <tr>
            <th height="20" valign="middle" width="30">No</th>
            <th height="20" valign="middle" width="320">Nama Barang</th>
            <th height="20" valign="middle" width="50">QTY</th>
            <th height="20" valign="middle" width="50">Satuan</th>
            <th height="20" valign="middle" width="250">Keterangan</th>
        </tr>
    </thead>';

$keterangan = "";
$num = 1;
$table_detail = "";
$detail_suratjalan = "";
foreach ($table_body as $value) {
    $detail_suratjalan = $value['detail_suratjalan'];

    if ($jenis_pesanan == "U") {
        $detail_suratjalan = "";
        $nama_warna = "";
        $nama_warnaTmp = "";
        $keterangan = "";
        $table_detail = "<table class='no_border'>";
        foreach ($list_table_detail as $vl) {
            if ($nama_warnaTmp != $vl['detail_pesanan_nama_warna']) {
                $nama_warnaTmp = $vl['detail_pesanan_nama_warna'];
                $nama_warna = $nama_warnaTmp;
            } else {
                $nama_warnaTmp = $vl['detail_pesanan_nama_warna'];
                $nama_warna = "";
            }
            $keterangan = "";
            if ($vl["detail_pesanan_keterangan"] != "") {
                $keterangan = "<td>+ ".$vl['detail_pesanan_keterangan']. "</td>";
            }
            $table_detail .= ""
            . "<tr>"
                . "<td style='max-width: 120px; min-width: 80px'>" . $nama_warna . "</td>"
                . "<td style='min-width: 40px'> " . $vl["detail_pesanan_detail_kain"] . " </td>"
                . "<td style='width: 60px'> - " . $vl["detail_pesanan_qty"] . " x " . $vl["detail_pesanan_qty_pack"] . "</td>"
                . "<td style='width: 20px'>" . $vl["detail_pesanan_satuan"] . "</td>"
                . $keterangan
            . "</tr>";
        }
        $table_detail .= "</table>";

    } else if ($jenis_pesanan == "E") {
        $detail_suratjalan = "";
        $nama_warna = "";
        $nama_warnaTmp = "";
        $keterangan = "";
        $table_detail = "<table class='no_border'>";
        foreach ($list_table_detail as $vl) {
            if ($nama_warnaTmp != $vl['detail_pesanan_nama_warna']) {
                $nama_warnaTmp = $vl['detail_pesanan_nama_warna'];
                $nama_warna = $nama_warnaTmp;
            } else {
                $nama_warnaTmp = $vl['detail_pesanan_nama_warna'];
                $nama_warna = "";
            }
            $keterangan = "";
            if ($vl["detail_pesanan_keterangan"] != "") {
                $keterangan = "<td>+ ".$vl['detail_pesanan_keterangan']. "</td>";
            }
            $table_detail .= ""
            . "<tr>"
                . "<td style='max-width: 120px; min-width: 80px'>" . $nama_warna . "</td>"
                . "<td style='min-width: 40px'> " . $vl["detail_pesanan_detail_kain"] . " </td>"
                . "<td style='width: 40px'> = " . $vl["detail_pesanan_ukuran"] . "</td>"
                . "<td style='width: 40px'>, " . $vl["detail_pesanan_qty"] . "</td>"
                . "<td style='width: 20px'>" . $vl["detail_pesanan_satuan"] . "</td>"
                . $keterangan
            . "</tr>";
        }
        $table_detail .= "</table>";

    } else if ($jenis_pesanan == "A" || $jenis_pesanan == "B" || $jenis_pesanan == "C" || $jenis_pesanan == "D") {
        $detail_suratjalan = "";
        $nama_warna = "";
        $nama_warnaTmp = "";
        $table_detail = "<table class='no_border'>";
        if ($jenis_pesanan == "A") {
            $table_header = "<thead>"
                . "<tr>"
                    . "<th style='max-width: 120px; min-width: 80px'></th>"
                    . "<th style='width: 40px'></th>"
                    . "<th style='width: 30px'></th>"
                    . "<th width='45'>Kanan</th>"
                    . "<th width='45'>Kiri </th>"
                    . "<th></th>"
                . "</tr>"
            . "</thead>";
        } else if ($jenis_pesanan == "B") {
            $table_header = "<thead>"
                . "<tr>"
                    . "<th style='max-width: 120px; min-width: 80px'></th>"
                    . "<th style='width: 40px'></th>"
                    . "<th style='width: 30px'></th>"
                    . "<th width='45'>Cowo</th>"
                    . "<th width='45'>Cewe</th>"
                    . "<th></th>"
                . "</tr>"
            . "</thead>";
        } else if ($jenis_pesanan == "C") {
            $table_header = "<thead>"
                . "<tr>"
                    . "<th style='max-width: 120px; min-width: 80px'></th>"
                    . "<th style='width: 40px'></th>"
                    . "<th style='width: 30px'></th>"
                    . "<th width='45'>Depan</th>"
                    . "<th width='45'>L. Kanan</th>"
                    . "<th width='45'>L. Kiri</th>"
                    . "<th></th>"
                . "</tr>"
            . "</thead>";
        }
        foreach ($list_table_detail as $vl) {
            if ($nama_warnaTmp != $vl['detail_pesanan_nama_warna']) {
                $nama_warnaTmp = $vl['detail_pesanan_nama_warna'];
                $nama_warna = $nama_warnaTmp;
            } else {
                $nama_warnaTmp = $vl['detail_pesanan_nama_warna'];
                $nama_warna = "";
            }
            if ( strpos($value['surat_jalan_detail_pesanan_key'], $vl["detail_pesanan_detail_key"]) !== false ) {
                $table_detail .= "<tr>"
                . "<td style='max-width: 120px; min-width: 80px'>" . $nama_warna . "</td>"
                . "<td style='min-width: 40px'>" . $vl["detail_pesanan_detail_kain"] . "</td>"
                . "<td style='width: 30px'>" . $vl["detail_pesanan_ukuran"] . "</td>"
                . "<td width='45' class='size_num'>" . $vl["detail_pesanan_sisi_kanan"] . "</td>"
                . "<td width='45' class='size_num'>" . $vl["detail_pesanan_sisi_kiri"] . "</td>";
                if ( $vl["detail_pesanan_sisi_blkng"] > 0 && $jenis_pesanan == "A" ) {
                    $table_detail .= "<td width='45' class='size_num'>" . $vl["detail_pesanan_sisi_blkng"] . "</td>";
                    $table_header = "<thead>"
                        . "<tr>"
                            . "<th style='max-width: 120px; min-width: 80px'></th>"
                            . "<th style='width: 40px'></th>"
                            . "<th style='width: 30px'></th>"
                            . "<th width='45'>Kanan</th>"
                            . "<th width='45'>Kiri </th>"
                            . "<th width='45'>Blkg</th>"
                        . "</tr>"
                    . "</thead>";
                } else if ( $vl["detail_pesanan_sisi_blkng"] > 0 && $jenis_pesanan == "C" ) {
                    $table_detail .= "<td width='45' class='size_num'>" . $vl["detail_pesanan_sisi_blkng"] . "</td>";
                    $table_header = "<thead>"
                        . "<tr>"
                            . "<th style='max-width: 120px; min-width: 80px'></th>"
                            . "<th style='width: 40px'></th>"
                            . "<th style='width: 30px'></th>"
                            . "<th width='45'>Depan</th>"
                            . "<th width='45'>L. Kanan</th>"
                            . "<th width='45'>L. Kiri</th>"
                            . "<th></th>"
                        . "</tr>"
                    . "</thead>";
                }
                $keterangan = "";
                if ($vl["detail_pesanan_keterangan"] !== "") {
                    $keterangan = "<td> ".$vl['detail_pesanan_keterangan']. "</td>";
                }
                $table_detail .= $keterangan;
                $table_detail .= "</tr>";
            }
        }
        $table_detail .= $table_header . "</table>";

    }
    $col_keterangan = $value["surat_jalan_detail_subtotal_pack"] ." ". $value["surat_jalan_detail_satuan_pack"];
    if ($value["surat_jalan_detail_satuan_qty"] == $value["surat_jalan_detail_satuan_pack"]) {
        $col_keterangan = "";
    }
    $tbody .= "<tr>"
            . "<td class='detail_rows' width='20' valign='top' style='text-align: center'>" . $num . "</td>"
            . "<td class='detail_rows' style='white-space: pre-line; min-width: 100px; max-width: 350px'>" 
                . $value['surat_jalan_detail_nama_barang'] ."<br>"
                . $value['surat_jalan_detail_jenis_print'] ."<br>"
                . $value['surat_jalan_detail_warna'] ." Warna <br><br>"
                . $detail_suratjalan
                . $table_detail
            . "</td>"
            . "<td class='detail_rows' width='30' valign='top' align='center'>" . $value["surat_jalan_detail_subtotal_qty"] . "</td>"
            . "<td class='detail_rows' width='30' valign='top' align='center'>" . $value["surat_jalan_detail_satuan_qty"] . "</td>"
            . "<td class='detail_rows' width='150' valign='top' style='white-space: pre-line;'>" 
                . $col_keterangan
            . "</td>"
        . "</tr>";
    $num++;
}

$content .= '<tbody>'.$tbody.'</tbody>';

$content .= '
<tfoot style="border: 0px">
    <tr>
        <td height="20" valign="middle"width="20px"></td>
        <td height="20" valign="middle"style="padding-left: 10px">Total</td>
        <td height="20" valign="middle"align="center">'.$totalqty.'</td>
        <td height="20" valign="middle"></td>
        <td height="20" valign="middle"></td>
    </tr>
</tfoot>
</table>
<br><br>
<table width="1000" border="0" cellspacing="0" cellpadding="3" style="bottom: 10;">
    <tr>
        <td align="center" class="main_rows" width="340">Tanda Tangan Penerima</td>
        <td class="main_rows" width="340"></td>
        <td class="main_rows" width="340"></td>
    </tr>
    <tr>
        <td align="center" valign="bottom" class="main_rows" width="340" height="40">.........................................</td>
        <td class="main_rows" width="340"></td>
        <td class="main_rows" width="340"></td>
    </tr>
    <tr>
        <td align="center" class="main_rows" width="340">Dibuat Oleh</td>
        <td align="center" class="main_rows" width="340">Disetujui Oleh</td>
        <td align="center" class="main_rows" width="340">Disetujui Oleh</td>
    </tr>
    <tr>
        <td align="center" valign="bottom" class="main_rows" width="340" height="40">.........................................</td>
        <td align="center" valign="bottom" class="main_rows" width="340" height="40">.........................................</td>
        <td align="center" valign="bottom" class="main_rows" width="340" height="40">.........................................</td>
    </tr>
</table>
</div>
</body>
</html>';

require_once('plugins/html2pdf/html2pdf.class.php');
$file_name = 'Surat_Jalan_' . $nomor_suratjalan . '.pdf';
$resolution = array(241.3, 279.4);
// $html2pdf = new HTML2PDF('L','$resolution','en');
$html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', 0);
// $html2pdf = new HTML2PDF('L', 'A4', 'en', true, 'UTF-8', 0);
$html2pdf->WriteHTML($content);
$html2pdf->Output($file_name, 'I');

// echo $content;

?>