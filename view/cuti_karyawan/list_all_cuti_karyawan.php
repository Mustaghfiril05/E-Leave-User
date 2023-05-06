<!-- Begin Page Content -->
<style>
  body {
     background-color: #f9f9fa
 }

 .flex {
     -webkit-box-flex: 1;
     -ms-flex: 1 1 auto;
     flex: 1 1 auto
 }

 @media (max-width:991.98px) {
     .padding {
         padding: 1.5rem
     }
 }

 @media (max-width:767.98px) {
     .padding {
         padding: 1rem
     }
 }

 .padding {
     padding: 5rem
 }

 .container {
     margin-top: 100px
 }

 .progress.progress-md {
     height: 5px
 }

 .template-demo .progress {
     margin-top: 1.5rem
 }

 .progress {
     border-radius: 10px;
     height: 10px
 }
</style>
<div class="container-fluid" style="background-color: white; height: 800px;">
<ol class="breadcrumb" style="background-color: #3a3a3a;"> 
            <li class="breadcrumb-item">
              <a href="" style="color: white;">List ALL E-Leave</a>
            </li>
            <li class="breadcrumb-item active" style="color: white;">Overview</li>
          </ol>
<!-- Page Heading -->
<!-- <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1> -->

  <?php if(validation_errors()) : ?>
    <div class="alert alert-danger" role="alert">
      <?= validation_errors(); ?>
    </div>
   <?php endif; ?>

   <?= $this->session->flashdata('message'); ?>
   <style>
    .card {
    flex: 1 1 auto;
    padding: 1.25rem;
    border-radius: 2rem;
    background-color: #ffffff;
}

.row1 {
    display: flex;
    flex-wrap: wrap;
    margin-right: -6.75rem;
    margin-left: -0.75rem;
    margin-bottom: 0rem;
}
   </style>

<!-- <form class="form-inline" method="get" action="<?= base_url('cuti_karyawan/index'); ?>"></form> -->
<form class="form-inline" method="get" action="">
  <div class="navbar-form navbar-left">
  <select name="filter" id="filter" class="form-control">
			  <option value="">--Pilih Berdasarkan--</option>
			  <option value="1">Per Tanggal</option>
			  <option value="2">Per Bulan</option>
			  <option value="3">Per Tahun</option>
	</select>
  <input style="display:none" type="date" id="form-tanggal" name="tanggal" class="form-control datepicker" autocomplete="off" />
              <select  style="display:none" id="form-bulan" name="bulan" class="form-control">
		                <option value="">--Pilih Bulan--</option>
		                <option value="1">Januari</option>
		                <option value="2">Februari</option>
		                <option value="3">Maret</option>
		                <option value="4">April</option>
		                <option value="5">Mei</option>
		                <option value="6">Juni</option>
		                <option value="7">Juli</option>
		                <option value="8">Agustus</option>
		                <option value="9">September</option>
		                <option value="10">Oktober</option>
		                <option value="11">November</option>
		                <option value="12">Desember</option>
		          </select>
                <select style="display:none" name="tahun" class="form-control" id="form-tahun">
		                <option value="">--Pilih Tahun--</option>
		                <?php
						        foreach($option_tahun as $data){ //Ambil data tahun dari model yang dikirim dari controller
							      echo '<option value="'.$data->tahun.'">'.$data->tahun.'</option>';
						        }
		                ?>
		          </select>
              <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-search"></i></button>
  </div>
          <div class="col text-right">
          <a href="list_all" class="btn btn-success btn-sm">Reset Filter</a>
          <!-- <a href="" class="btn btn-success btn-sm"><i class="fas fa-download"></i>&nbsp;Excel</a> -->
          <a href="<?php echo $url_export; ?>" class="btn btn-success btn-sm"><i class="fas fa-download"></i>&nbsp;Excel</a>
          </div>
</form>


 <hr>
<div class="card shadow mb-4">
<div class="card-header py-1" style="background-color:#000000;">
</div>
<div class="card-body" style="height:37rem; width:auto;">
<div class="table-responsive" style="height:35rem;">
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="20%" >
<thead class="thead">
<tr >
      <th scope="col" class='text-center' style="color:black; font-size: 14px;">Tanggal Pembuatan</th>
      <th scope="col" class='text-center' style="color:black; font-size: 14px;">Status</th>
      <th scope="col" class='text-center' style="color:black; font-size: 14px;">No. Badge</th>
      <th scope="col" class='text-center' style="color:black; font-size: 14px;">Name</th>
      <th scope="col" class='text-center' style="color:black; font-size: 14px;">Department</th>
      <th scope="col" class='text-center' style="color:black; font-size: 14px;">Cuti yang Di minta</th>
      <th scope="col" class='text-center' style="color:black; font-size: 14px;">Tahun Cuti</th>
      <th scope="col" class='text-center' style="color:black; font-size: 14px;">Delegasi</th>
      <th scope="col" class='text-center' style="color:black; font-size: 14px;">Action</th> 
  </tr>
</thead>
<tbody>
  <?php 
  if( ! empty($ecuti)){
  foreach ($ecuti as $d) : ?>
    <tr>
        <td class='text-center' style="color:black; font-size: 14px;"><?=date('d M Y',strtotime($d['tanggal_pembuatan']))?></td>
        <td class='text-center' style="color:black; font-size: 14px;">
        <?php
          if($d['status'] == 'Request') {
           ?>
           <span class="badge badge-warning">Request</span>
          <?php }?>

          <?php
          if($d['status'] == 'Approve HOD') {
           ?>
           <span class="badge badge-success">Approved HOD</span>
          <?php }?>

          <?php
          if($d['status'] == 'Approve BOD') {
           ?>
           <span class="badge badge-primary">Approve BOD</span>
          <?php }?>

          <?php
          if($d['status'] == 'Approve HRD') {
           ?>
           <span class="badge badge-primary">Approve HRD</span>
          <?php }?>

          <?php
          if($d['status'] == 'Rejected HOD') {
           ?>
           <span class="badge badge-danger">Rejected HOD</span>
          <?php }?>

          <?php
          if($d['status'] == 'Rejected BOD') {
           ?>
           <span class="badge badge-danger">Rejected BOD</span>
          <?php }?>

          <?php
          if($d['status'] == 'Rejected HRD') {
           ?>
           <span class="badge badge-danger">Rejected HRD</span>
          <?php }?>

          <?php
          if($d['status'] == 'Rejected') {
           ?>
           <span class="badge badge-danger">Rejected</span>
          <?php }?>

        </td>
        <td class='text-center' style="color:black; font-size: 14px;"><?= $d['id_user']; ?></td>
        <td class='text-center' style="color:black; font-size: 14px;"><?= $d['name']; ?></td>
        <td class='text-center' style="color:black; font-size: 14px;"><?= $d['jenis_departement']; ?></td>
        <td class='text-center' style="color:black; font-size: 14px;">
        <a href="" onclick="open_list_wmr('<?php echo  $d['id_cuti']?>')" class="badge badge-info" data-toggle="modal" data-target="#edit">
        <?= $d['jumlah'];?> Hari</a></td>
        <td class='text-center' style="color:black; font-size: 14px;"><?= $d['tahun']; ?></td>
        <td class='text-center' style="color:black; font-size: 14px;">
        <a href="" onclick="open_list_delegasi('<?php echo  $d['id_cuti']?>')" class="badge badge-danger" data-toggle="modal" data-target="#edit1">
        <?php
            // $nm['wmr'] = $nm['wmr'];
            echo count(explode(",",$d['delegasi'])). " Orang";
        ?>
        </a>
      </td>
        <td class='text-center' style="color: black; font-size: 14px;">

        <?php
          if($d['status'] == 'Approve HRD') {
           ?>
            <a class="badge badge-success" title="Detail & Progress" href="<?= base_url('cuti_karyawan_input/edit_hrd/') . $d['id_cuti']; ?>"><i class="fas fa-eye"></i></a>

        <?php } else {?>   
          <a class="badge badge-success" title="Detail & Progress" href="<?= base_url('cuti_karyawan/detail_all/') . $d['id_cuti']; ?>"><i class="fas fa-eye"></i></a>
          <!-- <a class="badge badge-warning" title="Finish Report" data-toggle="modal" data-target="#edit<?php echo $d['id_cuti']; ?>"><i class="fa fa-list"></i></a> -->
          <!-- <a class="badge badge-info" title="Print Report" href="<?= base_url('cuti_karyawan/cetak_cuti_karyawan/') . $d['id_cuti']; ?>"><i class="fa fa-print"></i></a> -->
          <!-- <a class="badge badge-danger" title="Resend Mail" href="<?= base_url('resend_mail/index/') . $d['id_cuti']; ?>"><i class="fas fa-paper-plane"></i></a> -->
          <!-- <a class="btn btn-success btn-sm btn-sm" href="<?= base_url('cuti_karyawan/edit_cuti_karyawan/') . $d['id_cuti']; ?>"><i class="fas fa-pen"></i></a> -->
        </td>
        <?php } ?>
    </tr>
<?php endforeach; ?>
<?php } ?>
</tbody>
</table>	
 </div>
</div>

<div class='modal'  id='edit' tabindex="-1" role="dialog" aria-labelledby="newDepModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content" style="width: 24rem;">
  <div class="modal-header">
  <center>
    <h5 class="modal-title" id="newDepModalLabel"><b style="font-size: 14px; text-align:center;">Detail Tanggal Cuti</b></h5>
  </center>  
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body" id="content_wmr">
  </div>
</div>
</div>
</div>

<div class='modal'  id='edit1' tabindex="-1" role="dialog" aria-labelledby="newDepModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
  <div class="modal-header">
  <center>
    <h5 class="modal-title" id="newDepModalLabel" style="font-size: 14px;"><b>Detail Delegasi</b></h5>
  </center>  
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body" id="content_delegasi">
  </div>
</div>
</div>
</div>
</div>
</div>
  </div>

<script src="<?= base_url('assets/'); ?>home/js/jquery-3.5.1.min.js"></script>
<!-- /.container-fluid -->
<script type="text/javascript">
	$("#id_departement").change(function(){
		load_pic_departemen($(this).val());
	});
	function load_pic_departemen(id_departemen){
		$.ajax({
	        url: "cuti_karyawan/pic_per_departemen/"+id_departemen,	       
	        success: function(response) {
	          $("#div_pic").html(response);
	        }
      });
	}

  function open_list_wmr(id_cuti){
    $("#content_wmr").html("progress...").load("<?php echo base_url()?>cuti_karyawan_input/detail_jumlah/"+id_cuti);
  }

  function open_list_delegasi(id_cuti){
    $("#content_delegasi").html("progress...").load("<?php echo base_url()?>cuti_karyawan_input/detail_delegasi/"+id_cuti);
  }
</script>

<script type="text/javascript">
	$(document).ready(function() {
    $("#form-tanggal, #form-bulan, #form-tahun").hide();
		$("body").on("change", "#filter", function(e) {
      if($(this).val() == '1'){ // Jika filter nya 1 (per tanggal)
        $('#form-bulan, #form-tahun').hide(); // Sembunyikan form bulan dan tahun
        $('#form-tanggal').show(); // Tampilkan form tanggal
      }else if($(this).val() == '2'){ // Jika filter nya 2 (per bulan)
        $('#form-tanggal').hide(); // Sembunyikan form tanggal
        $('#form-bulan, #form-tahun').show(); // Tampilkan form bulan dan tahun
      }else{ // Jika filternya 3 (per tahun)
        $('#form-tanggal, #form-bulan').hide(); // Sembunyikan form tanggal dan bulan
        $('#form-tahun').show(); // Tampilkan form tahun
            }
      $('#form-tanggal input, #form-bulan select, #form-tahun select').val(''); // Clear data pada textbox tanggal, combobox bulan & tahun
        });
	});
</script>


