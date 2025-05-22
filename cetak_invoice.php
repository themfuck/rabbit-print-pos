<html moznomarginboxes mozdisallowselectionprint>
<head>
    <title>Print</title>
    <meta http-equiv="refresh" content="3; url=daftar_invoice.php">
    <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
</head>
<?php 
include_once("head.php"); 
include_once("konfigurasi.php"); 
$tgl_awal  = $_GET['tgl_awal'];
$tgl_akhir = $_GET['tgl_akhir'];
$total = 0;

$sel_invoice = "SELECT SUM(inv.invoice_keluar_total_harga) AS total, plg.pelanggan_nama"
            . " FROM invoice_keluar inv"
            . " JOIN pelanggan plg ON plg.pelanggan_id = inv.invoice_keluar_pelanggan_id"
            . " WHERE inv.invoice_keluar_tanggal_kirim BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."'"
            . " GROUP BY plg.pelanggan_nama"
            . "";

tulisLog('cetak_invoice.php -> sel_invoice : ' . $sel_invoice);

$query_inv = $con->query($sel_invoice);

?>
<body>
<h4 style="text-align:left;">REKAP INVOICE</h4>
<p><?php echo $tgl_awal." - ".$tgl_akhir; ?></p>
<div class="box-body">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $num = 0;
                while ($row = $query_inv->fetch_assoc()) {
                    // (double) $total += (double) $row['invoice_keluar_total_harga'];
                    (double) $total += (double) $row['total'];
                    $num++;
                    echo ""
                    . "<tr>"
                        . "<td>" . $num . "</td>"
                        . "<td>" . $row['pelanggan_nama'] . "</td>"
                        // . "<td>" . number_format($row['invoice_keluar_total_harga']) . "</td>"
                        . "<td>" . number_format($row['total']) . "</td>"
                    . "</tr>";
                }
            ?>
        </tbody>
        <tfoot>
            <p style="float: right">TOTAL : <?php echo number_format($total); ?></p>
        </tfoot>
    </table>
</div>
<script>
    window.print();
</script>
</body>
</html>