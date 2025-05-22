<html moznomarginboxes mozdisallowselectionprint>
<head>
    <title>Print</title>
    <meta http-equiv="refresh" content="3; url=daftar_invoice.php">
    <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
</head>
<?php 
// include_once("head.php"); 
include_once("konfigurasi.php"); 
$tgl_awal  = date('Y-m-d', strtotime($_GET['tgl_awal']) );
$tgl_akhir  = date('Y-m-d', strtotime($_GET['tgl_akhir']) );
$id_pelanggan = $_GET['id_pelanggan'];
$total = 0;
$pelanggan_nama = "";
$dibuat_oleh = "";

$sel_invoice = "SELECT dinv.*, inv.*, sj.surat_jalan_nomor, sj.surat_jalan_tanggal"
			. " FROM invoice_keluar inv"
			. " JOIN invoice_keluar_detail dinv ON dinv.invoice_keluar_detail_no_invoice = inv.invoice_keluar_no_invoice"
			. " JOIN surat_jalan sj ON sj.surat_jalan_key = dinv.invoice_keluar_detail_surat_jalan_nomor"
			. " WHERE inv.invoice_keluar_tanggal_kirim BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."'"
			. " AND inv.invoice_keluar_pelanggan_id = ".$id_pelanggan;

tulisLog('cetak_invoice.php -> sel_invoice : ' . $sel_invoice);

$query_inv = $con->query($sel_invoice);

$sel_plg = "SELECT pelanggan_nama FROM pelanggan WHERE pelanggan_id = ".$id_pelanggan;
tulisLog('cetak_invoice.php -> sel_plg : ' . $sel_plg);
$query_plg = $con->query($sel_plg);
while ($row = $query_plg->fetch_assoc()) {
	$pelanggan_nama = $row['pelanggan_nama'];
}

?>
<body>
<h4 style="text-align:left;">INVOICE</h4>
<p><?php echo $pelanggan_nama; ?></p>
<p><?php echo $tgl_awal." - ".$tgl_akhir; ?></p>
<div class="box-body">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <center>
                <th>No</th>
                <th>Jenis Print</th>
                <th>Qty</th>
                <th>Satuan</th>
                <th>No. Surat Jalan</th>
                <th>Tgl Kirim</th>
                <th>Harga</th>
                <th>Sub Total</th>
                </center>
            </tr>
        </thead>
        <tbody>
            <?php
                $num = 0;
                while ($row = $query_inv->fetch_assoc()) {
                    (double) $total += (double) $row['total'];
                    $num++;
                    echo ""
                    . "<tr>"
                        . "<td>" . $num . "</td>"
                        . "<td>" . $row['invoice_keluar_detail_jenis_print'] . "</td>"
                        . "<td>" . $row['invoice_keluar_detail_total_barang'] . "</td>"
                        . "<td>" . $row['invoice_keluar_detail_satuan'] . "</td>"
                        . "<td>" . $row['surat_jalan_nomor'] . "</td>"
                        . "<td>" . $row['surat_jalan_tanggal'] . "</td>"
                        . "<td style='text-align: right'>" . number_format($row['invoice_keluar_detail_harga']) . "</td>"
                        . "<td style='text-align: right'>" . number_format($row['invoice_keluar_detail_subtotal_harga']) . "</td>"
                    . "</tr>";
                    $total = $total + $row['invoice_keluar_detail_subtotal_harga'];
                }
            ?>
        </tbody>
        <tfoot>
            <p style="float: right">TOTAL : <?php echo number_format($total); ?></p>
        </tfoot>
    </table>
</div>
        <br>
        <br>
        <br>
    <table width="100%" style="bottom: 10px">

<!--         <tr>
            <td width="230" align="center"><b> Hormat Kami </b><br><br>
            <br><br><br></td>
            <td width="286"></td>
            </tr>  
        <tr>
            <td align="center"><br/>
            <b>.........................................</b><br /><br /><br />
            </td>   
        </tr> -->
        <tr>
            <!-- <td align="center" style="width: 33.3%"><b>Dibuat Oleh</b></td> -->
            <td align="center" style="width: 50%"><b>Hormat Kami</b></td>
            <td align="center" style="width: 50%"><b>Penerima</b></td>
        </tr>
        <tr>
            <td align="center" style="width: 50%"><b>&nbsp;</b></td>
            <td align="center" style="width: 50%"><b>&nbsp;</b></td>
            <td align="center" style="width: 50%"><b>&nbsp;</b></td>
        </tr>
        <tr>
            <td align="center" style="width: 50%"><b>&nbsp;</b></td>
            <td align="center" style="width: 50%"><b>&nbsp;</b></td>
            <td align="center" style="width: 50%"><b>&nbsp;</b></td>
        </tr>
        <tr>
            <td align="center" style="width: 50%"><b>&nbsp;</b></td>
            <td align="center" style="width: 50%"><b>&nbsp;</b></td>
            <td align="center" style="width: 50%"><b>&nbsp;</b></td>
        </tr>
        <tr>
            <td align="center" style="width: 50%"><b>&nbsp;</b></td>
            <td align="center" style="width: 50%"><b>&nbsp;</b></td>
            <td align="center" style="width: 50%"><b>&nbsp;</b></td>
        </tr>
        <tr>
            <td align="center" style="width: 50%"><b>&nbsp;</b></td>
            <td align="center" style="width: 50%"><b>&nbsp;</b></td>
            <td align="center" style="width: 50%"><b>&nbsp;</b></td>
        </tr>
        <tr>
            <!-- <td align="center" style="width: 33.3%" valign="top">(<b><?php echo $dibuat_oleh;?></b>)</td> -->
            <td align="center" style="width: 50%" valign="top"><b>.........................................</b></td>
            <td align="center" style="width: 50%" valign="top"><b>.........................................</b></td>
        </tr>
    </table>
<script>
	window.print();
</script>
</body>
</html>