<?php 
/*
 * Masukkan setiap perubahan yang dilakukan di tabel ini.
 * 
 * NAMA                VERSI       TGL(DD-MM-YYYY)        PERUBAHAN
 * ========================================================================================================
 * VANDI                1.0         18-09-2018            Pembuatan awal (create)
 *
 */
include_once("head.php"); 
include_once("konfigurasi.php"); 
tulisLog('daftar_surat_jalan.php -> membuka halaman');
$user_id = $_SESSION['user_id'];
$user_nama = $_SESSION['user_nama'];
$sel_daftar_surat_jalan = "SELECT * FROM surat_jalan ORDER BY surat_jalan_id DESC";
tulisLog('daftar_surat_)jalan.php -> sel_daftar_surat_jalan : ' . $sel_daftar_surat_jalan);
$query_sj = $con->query($sel_daftar_surat_jalan);
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
            <h1>
              Surat Jalan
            </h1>
        </section>
      <section class="content">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">Daftar Surat Jalan</h3>
          </div>
          <div class="box-body">
            <a href="daftar_pesanan.php"><button type="button" class="btn btn-primary">Tambah Surat Jalan</button></a>
            <table id="tbl_suratjalan" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>ID</th>
                  <th>No. Surat Jalan</th>
                  <th>No. Surat Jalan Masuk</th>
                  <th>Tanggal</th>
                  <th>Pelanggan</th>
                  <th>Dibuat Oleh</th>
                  <th>Total</th>
                  <th>Status</th>
                  <th>Menu</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                if ($query_sj->num_rows > 0) {

                  $num = 0;
                  while ($row = $query_sj->fetch_assoc()) {

                    $sel_plg = "SELECT pelanggan_nama FROM pelanggan WHERE pelanggan_id = ".$row['surat_jalan_pelanggan_id'];
                    tulisLog('daftar_surat_jalan.php -> sel_plg -> ' . $sel_plg);
                    $query_plg = $con->query($sel_plg);
                    while ($row2 = $query_plg->fetch_assoc()) {
                      $nama_pelanggan = $row2['pelanggan_nama'];
                    }

                    $sel_user = "SELECT nama FROM s_user WHERE username = '".$row['cre_usr']."'";
                    tulisLog('daftar_surat_jalan.php -> sel_user -> ' . $sel_user);
                    $query_usr = $con->query($sel_user);
                    while ($row3 = $query_usr->fetch_assoc()) {
                      $nama_user = $row3['nama'];
                    }

                    $num++;
                    echo ""
                    . "<tr>"
                      . "<td>" . $num . "</td>"
                      . "<td>" . $row['surat_jalan_id'] . "</td>"
                      . "<td>" . $row['surat_jalan_key'] . "</td>"
                      . "<td style='background: linen'>" . $row['surat_jalan_nomor'] . "</td>"
                      . "<td>" . $row['surat_jalan_tanggal'] . "</td>"
                      . "<td>" . $nama_pelanggan . "</td>"
                      . "<td>" . $nama_user . "</td>"
                      . "<td>" . $row['surat_jalan_total'] . "</td>"
                      . "<td>" . $row['surat_jalan_status'] . "</td>"
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

  <div class="modal fade" id="modal-lihat-sjalan">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title">Lihat Surat Jalan</h4>
            </div>
            <div class="box-body" style="padding: 15px">
              <div class="col-md-12" style="padding-left: 0">
                 <p class="col-md-4" style="padding-left: 0"><strong>No. Surat Jalan</strong></p>
                 <p class="col-md-6" id="v_noSuratJalan"></p>
              </div>
              <div class="col-md-12" style="padding-left: 0">
                 <p class="col-md-4" style="padding-left: 0"><strong>Tanggal Kirim</strong></p>
                 <p class="col-md-6" id="v_tglKirim"></p>
              </div>
              <div class="col-md-12" style="padding-left: 0">
                 <p class="col-md-4" style="padding-left: 0"><strong>Nama Pelanggan</strong></p>
                 <p class="col-md-6" id="v_namaPelanggan"></p>
              </div>
              <div class="col-md-12" style="padding-left: 0">
                 <p class="col-md-4" style="padding-left: 0"><strong>Dibuat Oleh</strong></p>
                 <p class="col-md-6" id="v_dibuatOleh"></p>
              </div>
              <table id="tbl_lihat_sjalan" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
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

  <div class="modal fade" id="modal-hapus-pelanggan">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title">Hapus surat jalan ini ?</h4>
            </div>
            <div class="box-body" style="padding: 15px">
              <div class="col-md-12" style="padding-left: 0">
                 <p class="col-md-4" style="padding-left: 0"><strong>No. Surat Jalan</strong></p>
                 <p class="col-md-6" id="d_nama"></p>
              </div>
              <div class="col-md-12" style="padding-left: 0">
                 <p class="col-md-4" style="padding-left: 0"><strong>No. Surat Jalan Masuk</strong></p>
                 <p class="col-md-6" id="d_telepon"></p>
              </div>
              <div class="col-md-12" style="padding-left: 0">
                 <p class="col-md-4" style="padding-left: 0"><strong>Tanggal</strong></p>
                 <p class="col-md-6" id="d_alamat"></p>
              </div>
              <div class="col-md-12" style="padding-left: 0">
                 <p class="col-md-4" style="padding-left: 0"><strong>Pelanggan</strong></p>
                 <p class="col-md-6" id="d_harga"></p>
              </div>
            </div>
            <div class="modal-footer">
          <button type="button" class="btn btn-custom" data-dismiss="modal" aria-label="Close" onclick="$( '#btnHapusPelanggan').unbind('click');">Batal</button>
          <button id="btnHapusPelanggan" type="button" class="btn btn-danger">Hapus<i class="fa fa-trash"></i>
          </button>
            </div>
        </div>
      </div>
  </div>

<?php include_once("footer.php"); ?>
<script>
    $(document).ready(function() {
        setSidebarToActive();
    });

    $(function () {
      var tabel = $('#tbl_suratjalan').DataTable({
        'paging'      : true,
        'lengthChange': false,
        'searching'   : true,
        'ordering'    : true,
        'info'        : false,
        'autoWidth'   : true,
        'language' : {
          'search'           : '<i class="fa fa-search"></i>',
          'searchPlaceholder': 'Pencarian',
          'paginate' : {
              'previous' : '<i class="fa fa-angle-left"></i>',
              'next'     : '<i class="fa fa-angle-right"></i>'
          }
        },
        'columnDefs' : [ 
          {
              'targets' : -1,
              'data'    : null,
              'defaultContent' : 
                // '<button class="btn btn-custom btnView"><i class="fa fa-eye"></i></button>'
                // + '<button style="margin-left: 3px" class="btn btn-primary btnPrint"><i class="fa fa-print"></i></button>'
                '<button style="margin-left: 3px" class="btn btn-primary btnPrint">Lihat <i class="fa fa-print"></i></button>'
                + '<button style="margin-left: 3px" class="btn btn-danger btnDelete">Hapus <i class="fa fa-trash"></i></button>'
          }, 
          { "visible": false, "targets": 1 },
          // { "visible": false, "targets": 2 },
          // { "width": "120px", "targets": 3 },
          // { "width": "120px", "targets": -1 },
          { "visible": false, "targets": 8 }
        ]
      });

      $('#tbl_suratjalan tbody').on('click', 'button', function () {
          var data = tabel.row($(this).parents('tr')).data();
          var no_suratjalan = data[2];
          var btnType = tabel.row($(this).parents('tr')).selector.rows.prevObject["0"].classList[2];

          if (btnType == "btnView") {
            
            document.getElementById('v_noSuratJalan').innerText = data[2];
            document.getElementById('v_tglKirim').innerText = data[3];
            document.getElementById('v_namaPelanggan').innerText = data[4];
            document.getElementById('v_dibuatOleh').innerText = data[6];

            $('#on_loading_modal').modal({
                show: true,
                keyboard: false,
                backdrop: 'static'
            });
            
            $.ajax({
              type: "POST",
              dataType: "JSON",
              url: "proses_daftar_sjalan.php",
              data: {
                jenis_proses : "lihat_detail",
                no_suratjalan : no_suratjalan
              },
              success: function(response) {

                console.log(response);
                var body = "";

                for (var i = 0; i < response.length; i++) {
                  var cnt = i + 1;
                  body += "<tr>"
                          + "<td>" + cnt + "</td>"
                          + "<td>" + response[i]['surat_jalan_detail_nama_barang'] + "</td>"
                          + "<td>" + response[i]['surat_jalan_detail_subtotal_barang'] + "</td>"
                          + "<td>" + response[i]['surat_jalan_detail_keterangan'] + "</td>"
                        + "</tr>";
                }
                body += "";

                $("#tbl_lihat_sjalan").find('tbody').empty();

                $('#tbl_lihat_sjalan tbody').append(body);

                $('#on_loading_modal').modal('hide');

                    $('#modal-lihat-sjalan').modal({
                      show: true,
                      keyboard: false,
                      backdrop: 'static'
                    });
              },
              error: function(msg){
                  showFailedMessage('Pesanan gagal di simpan.' + JSON.stringify(msg));
              }
            });

          } else if (btnType == "btnPrint") {

            // $('#on_loading_modal').modal({
            //     show: true,
            //     keyboard: false,
            //     backdrop: 'static'
            // });
            // window.location = "cetak_surat_jalan.php?nomor_suratjalan="+data[2];
            window.open("cetak_surat_jalan.php?nomor_suratjalan="+data[2]);

          } else if (btnType == "btnDelete") {
            $('#btnHapusPelanggan').bind('click', function(event) {
              hapusPelanggan(data[2]);
              $( this ).unbind( event );
            });

            document.getElementById('d_nama').innerText = data[2];
            document.getElementById('d_telepon').innerText = data[3];
            document.getElementById('d_alamat').innerText = data[4];
            document.getElementById('d_harga').innerText = data[5];

            $('#modal-hapus-pelanggan').modal({
                show: true,
                keyboard: false,
                backdrop: 'static'
            });

          }
      });

    });

    function hapusPelanggan(key) {

      $('#modal-hapus-pelanggan').modal('hide');

      $('#on_loading_modal').modal({
          show: true,
          keyboard: false,
          backdrop: 'static'
      });

      $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "proses_daftar_sjalan.php",
        data: {
          jenis_proses : "hapus_srtjalan",
          no_suratjalan : key
        },
        success: function(response) {
            if (response.response_code == "00") {
                showSuccessMessage('Surat jalan berhasil di hapus.');
            } else {
                showFailedMessage(response.response_msg);
            }
        },
        failed : function() {
            showFailedMessage('Hapus surat jalan gagal.');
        },
        error : function() {
            showFailedMessage('Hapus surat jalan gagal 2.');
        }
      });
    }

</script>
</body>
</html>