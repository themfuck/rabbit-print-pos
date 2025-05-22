<?php 

/*
 * Masukkan setiap perubahan yang dilakukan di tabel ini.
 * 
 * NAMA                VERSI       TGL(DD-MM-YYYY)        PERUBAHAN
 * ========================================================================================================
 * IKHSAN                1.0       12-09-2018             Pembuatan awal (create)
 * VANDI                 1.1       18-09-2018             perubahan sidebar
 */

session_start();
?>

<section class="sidebar">
    <!-- <div class="user-panel" style="height: 70px;">
      <div class="info" style="position: initial">
          <p id="p_namaUser"></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div> -->
    <ul class="sidebar-menu"><li class="header">Menu Utama</li></ul>
    <ul id="mainSidebar" class="sidebar-menu" data-widget="tree">
      <li id="li_home">
        <a href="home.php">
          <i style="font-size: 18px" class="fa fa-home"></i> <span>Halaman Utama</span>
        </a>
      </li>
      <li id="li_pelanggan">
        <a href="daftar_pelanggan.php">
          <i class="fa fa-users"></i> <span>Pelanggan</span>
        </a>
      </li>
      <li id="li_pesanan">
        <a href="daftar_pesanan.php">
          <i style="font-size: 14px" class="fa fa-shopping-cart"></i> <span>Barang Masuk</span>
        </a>
      </li>
      <li id="li_suratjalan">
        <a href="daftar_surat_jalan.php">
          <i style="font-size: 14px" class="fa fa-plane"></i> <span>Surat Jalan</span>
        </a>
      </li>
      <!-- <li id="li_pesanan" class="treeview">
        <a href="#">
          <i style="font-size: 14px" class="fa fa-shopping-cart"></i> <span>Pesanan</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li>
            <a href="daftar_pesanan.php">
              <i class="fa fa-file-text-o"></i> <span>Daftar Pesanan</span>
            </a>
          </li>
          <li>
            <a href="pesanan.php">
              <i class="fa fa-shopping-cart"></i> <span>Tambah Pesanan</span>
            </a>
          </li>
        </ul>
      </li> -->
  
      <!-- <li id="li_pesanan" class="treeview">
        <a href="#">
          <i style="font-size: 14px" class="fa fa-plane"></i> <span>Surat Jalan</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          
          <li>
            <a href="daftar_pesanan.php">
              <i class="fa fa-truck"></i> <span>Tambah Surat Jalan</span>
            </a>
          </li>
          <li>
            <a href="daftar_surat_jalan.php">
              <i class="fa fa-file-text-o"></i> <span>Daftar Surat Jalan</span>
            </a>
          </li>
        </ul>
      </li> -->
      <?php
      if ( $_SESSION [ 'user_hakakses' ] == "admin" )
      {
      ?>
      <li id="li_invoice">
        <a href="daftar_invoice.php">
          <i class="fa fa-file"></i> <span>Invoice</span>
        </a>
      </li>      
      <?php
      }
      ?>
      <li id="li_logout">
        <a href="logout.php">
          <i style="color: red; font-size: 17px" class="fa fa-power-off"></i><span>Logout</span>
        </a>
      </li>

    </ul>
 
</section>
