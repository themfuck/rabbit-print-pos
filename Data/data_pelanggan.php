<?php 
   /*
    * Masukkan setiap perubahan yang dilakukan di tabel ini.
    * 
    * NAMA                VERSI       TGL(DD-MM-YYYY)        PERUBAHAN
    * ========================================================================================================
    * IKHSAN                1.0       12-09-2018             Pembuatan awal (create)
    * RAMDAN                1.0       14-09-2018             Data Pelanggan
    *
    */
   
include_once("../head.php"); 
include_once("../konfigurasi.php"); 

$user_id = $_SESSION['user_id'];
$user_nama = $_SESSION['user_nama'];
$sel_pelanggan = "SELECT * FROM pelanggan";

tulisLog('data_pelanggan.php -> sel_pelanggan : ' . $sel_pelanggan);

$query_plg = $con->query($sel_pelanggan);

?>

<div class="modal fade" id="modal-pelanggan">
  <div class="modal-dialog">
     <div class="modal-content">
        <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title">Data Pelanggan</h4>
        </div>
        <div class="box-body">
          <table id="tabel_datapelanggan" class="table table-bordered table-hover">
              <thead>
                 <tr>
                    <th>No</th>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                 </tr>
              </thead>
              <tbody>
              <?php 
              if ($query_plg->num_rows > 0) {
                    tulisLog('data_pelanggan.php -> jml. pelanggan : ' . count($query_plg));
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
<?php include_once("../footer.php"); ?>
<script>
  $(function () {
      var table = $('#tabel_datapelanggan').DataTable({
          'select'      : true,
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
              }
          },
          'columnDefs' : [
            { "visible": false, "targets": 1 }
          ]
      });

      $('#tabel_datapelanggan tbody').on( 'click', 'tr', function () {
          if ( $(this).hasClass('selected') ) {
              $(this).removeClass('selected');
          }
          else 
          {
              table.$('tr.selected').removeClass('selected');
              $(this).addClass('selected');

              var dt = table.row(this).data();
              document.getElementById('nama_pelanggan').value = dt[2];
              document.getElementById('no_pelanggan').value = dt[1];
              $('#modal-pelanggan').modal('hide');
          }
      });

  });
</script>