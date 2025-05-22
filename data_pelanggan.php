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

  tulisLog('pelanggan.php -> sel_pelanggan : ' . $sel_pelanggan);

  $query_plg = $con->query($sel_pelanggan);

?>

<div class="modal fade" id="modal-default">
  <div class="modal-dialog">
     <div class="modal-content">
        <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title">Data Pelanggan</h4>
        </div>
        <div class="box-body">
           <table id="example1" class="table table-bordered table-hover">
              <!-- header tabel (nama kolom) -->
              <thead>
                 <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                 </tr>
              </thead>
              <!-- isi tabel  -->
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
        <div class="modal-footer">
           <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
           <button type="button" class="btn btn-primary">Pilih</button>
        </div>
     </div>
     <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/demo.js"></script>

<script>
  $(document).ready(function() {
  	// document.getElementById("p_namaUser").innerText = "<?php echo $user_nama; ?>";

  	setSidebarToActive();
   
  });
    
  $(function () {
    $('#example1').DataTable({'select'      : true});
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
      'select'      : true
    })
  })
</script>