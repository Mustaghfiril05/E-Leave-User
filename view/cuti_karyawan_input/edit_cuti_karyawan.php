<!-- Begin Page Content -->
<div class="container-fluid" style="height:661px;">
<ol class="breadcrumb" style="background-color: #3a3a3a;"> 
		<li class="breadcrumb-item">
			<a href="<?= base_url('daily_routine')?>" style="color: white;">Back to list Daily Report</a>
		</li>
		<li class="breadcrumb-item active">Overview</li>
	</ol>
	<!-- Page Heading -->
	<!-- <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1> -->
<hr>
	<?php if (validation_errors()) : ?>
		<div class="alert alert-danger" role="alert">
			<?= validation_errors(); ?>
		</div>
	<?php endif; ?>

	<?= $this->session->flashdata('message'); ?>
<style>
    textarea.form-control {
    height: 10rem;
}
</style>
	<?= form_open_multipart('daily_report/edit_daily_report'); ?>
	<div class="table-responsive" style="height:650px; width: 74rem; overflow-x:hidden;" >
    <fieldset>
    <center>
         <div class="col-lg-8 col-md-6">
         <input type="hidden" class="form-control" id="id_daily" name="id_daily" value="<?= $dailyroutine['id_daily']; ?>">

            <div class="form-group row">
			  <strong for="komponen" class="col-form-label">Komponen &nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&nbsp;&nbsp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;:</strong>
				<div class="col-sm-2">
                <input type="text" class="form-control" id="komponen" name="komponen" value="<?= $dailyroutine['komponen']; ?>" readonly>
				</div>
			</div>

            <div class="form-group row">
			  <strong for="komponen" class="col-form-label">PIC Pelaksana &nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;:</strong>
				<div class="col-sm-5">
                <input type="text" class="form-control" id="pic_it" name="pic_it" value="<?= $dailyroutine['pic_it']; ?>" readonly>
				</div>
			</div>

            <div class="form-group row">
			  <strong for="komponen" class="col-form-label">Support PIC &nbsp;&ensp;&nbsp;&ensp;&nbsp;&nbsp;&nbsp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;:</strong>
				<div class="col-sm-5">
                <input type="text" class="form-control" id="support" name="support" value="<?= $dailyroutine['support']; ?>" readonly>
				</div>
			</div>

            <div class="form-group row">
			  <strong for="komponen" class="col-form-label">Support Vendor &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&ensp;&nbsp;&ensp;:</strong>
				<div class="col-sm-5">
                <select name="id_kontraktor" id="id_kontraktor" class="form-control">
			  			<?php foreach ($kontraktor as $l) : ?>
							<?php if( $l['id_kontraktor'] == $dailyroutine['id_kontraktor'] ) : ?>
				  		<option value="<?= $l['id_kontraktor']; ?>" selected> <?= $l['kontraktor']; ?></option>>
							<?php else : ?>
				  		<option value="<?= $l['id_kontraktor']; ?>"><?= $l['kontraktor']; ?></option>
							<?php endif ; ?>
			  			<?php endforeach; ?> 
					</select>
				</div>
			</div>

            <div class="form-group row">
			  <strong for="komponen" class="col-form-label">Requester &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&ensp;&nbsp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;:</strong>
				<div class="col-sm-4">
                <select name="requester" id="requester" class="form-control">
			  			<?php foreach ($userr as $l) : ?>
							<?php if( $l['id_user'] == $dailyroutine['requester'] ) : ?>
				  		<option value="<?= $l['id_user']; ?>" selected> <?= $l['name']; ?></option>>
							<?php else : ?>
				  		<option value="<?= $l['id_user']; ?>"><?= $l['name']; ?></option>
							<?php endif ; ?>
			  			<?php endforeach; ?> 
					</select>
				</div>
			</div>

            <div class="form-group row">
			  <strong for="komponen" class="col-form-label">Device &ensp;&nbsp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;:</strong>
				<div class="col-sm-4">
                <select name="id_dvc" id="id_dvc" class="form-control">
                    <?php foreach ($device as $of) : ?>
                      <?php if( $of['id_device'] == $dailyroutine['id_dvc'] ) : ?>
                        <option value="<?= $of['id_device']; ?>" selected><?= $of['device']; ?></option>
                      <?php else : ?>
                        <option value="<?= $of['id_device']; ?>" ><?= $of['device']; ?></option>
                      <?php endif ; ?>
                    <?php endforeach; ?>
                  </select>
				</div>
			</div>

            <div class="form-group row">
			  <strong for="komponen" class="col-form-label">Pengguna Device  &nbsp;&ensp;&nbsp;&nbsp;&ensp;&nbsp;:</strong>
				<div class="col-sm-4">
                <select name="pengguna_device" id="pengguna_device" class="form-control">
			  			<?php foreach ($inv as $l) : ?>
							<?php if( $l['id'] == $dailyroutine['pengguna_device'] ) : ?>
				  		<option value="<?= $l['id']; ?>" selected> <?= $l['pengguna_device']; ?></option>>
							<?php else : ?>
				  		<option value="<?= $l['id']; ?>"><?= $l['pengguna_device']; ?></option>
							<?php endif ; ?>
			  			<?php endforeach; ?> 
					</select>
				</div>
			</div>

            <div class="form-group row">
			  <strong for="komponen" class="col-form-label">Department &ensp;&nbsp;&nbsp;&nbsp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;:</strong>
				<div class="col-sm-3">
                <select name="id_departement" id="id_departement" class="form-control">
			  			<?php foreach ($departement as $l) : ?>
							<?php if( $l['id_dep'] == $dailyroutine['id_departement'] ) : ?>
				  		<option value="<?= $l['id_dep']; ?>" selected> <?= $l['jenis_departement']; ?></option>>
							<?php else : ?>
				  		<option value="<?= $l['id_dep']; ?>"><?= $l['jenis_departement']; ?></option>
							<?php endif ; ?>
			  			<?php endforeach; ?> 
					</select>
				</div>
			</div>

            <div class="form-group row">
			  <strong for="komponen" class="col-form-label">Jenis Aktivitas &nbsp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;:</strong>
				<div class="col-sm-2">
                <input type="text" class="form-control" id="jenis_aktivitas" name="jenis_aktivitas" value="<?= $dailyroutine['jenis_aktivitas']; ?>" readonly>
				</div>
			</div>

            <div class="form-group row">
			  <strong for="komponen" class="col-form-label">Masalah &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&ensp;&nbsp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;:</strong>
				<div class="col-sm-8">
                <textarea type="text" class="form-control" id="masalah" name="masalah" value=""><?= $dailyroutine['masalah']; ?></textarea>
				</div>
			</div>

            <div class="form-group row">
			  <strong for="deskripsi" class="col-form-label">Deskripsi &nbsp;&nbsp;&ensp;&nbsp;&nbsp;&ensp;&nbsp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;:</strong>
				<div class="col-sm-8">
                <textarea type="text" class="form-control" id="deskripsi" name="deskripsi" value=""><?= $dailyroutine['deskripsi']; ?></textarea>
				</div>
			</div>

            <div class="form-group row">
			  <strong for="pemecahan_masalah" class="col-form-label">Solusi &nbsp;&nbsp;&nbsp;&nbsp;&ensp;&nbsp;&nbsp;&ensp;&nbsp;&nbsp;&ensp;&nbsp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;&nbsp;&ensp;:</strong>
				<div class="col-sm-8">
                <textarea type="text" class="form-control" id="pemecahan_masalah" name="pemecahan_masalah" value=""><?= $dailyroutine['pemecahan_masalah']; ?></textarea>
				</div>
			</div>

            <div class="form-group row">
			  <strong for="tanggal_kejadian">Tanggal/Jam Kejadian :</strong>
			  <div class="col-sm-4">
                <input type="date" class="form-control" id="tanggal_kejadian" name="tanggal_kejadian" value="<?= $dailyroutine['tanggal_kejadian']; ?>" readonly>
              </div>
              
              <div class="col-sm-4">
                <input type="time" class="form-control" id="jam_kejadian" name="jam_kejadian" value="<?= $dailyroutine['jam_kejadian']; ?>" readonly>
              </div>
			</div>

			<div class="form-group row">
			  <strong for="tanggal_selesai">Tanggal/Jam Selesai &nbsp;&ensp;:</strong>
			  <div class="col-sm-4">
                <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="<?= $dailyroutine['tanggal_selesai']; ?>" readonly>
              </div>
              <div class="col-sm-4">
                <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" value="<?= $dailyroutine['jam_selesai']; ?>" readonly>
              </div>
			</div>
            

                   <hr>             
            <div class="form-group ">
				<div class="col-sm-3">
					<button type="submit" class="btn btn-success  btn-block">Save</button>
				</div>
			</div>
            

            <!-- <div class="form-group row">
            <strong for="support" class="col-form-label">Support &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&nbsp;:</strong>
            &nbsp;&ensp;
                <div class="col-sm-4">
				    <?php foreach ($admin as $l) : ?>
                        <div class="row">
                        <input name="support" id="support" type="checkbox" value="<?= $l['username']; ?>">&ensp;<?= $l['username']; ?>
                        </div>
					<?php endforeach; ?>
				</div>
			</div> -->

         </div>
</center>
	</fieldset>
	</div>

	</form>
	<br>
</div>
</div>

<script src="<?= base_url('assets/'); ?>home/js/jquery-3.5.1.min.js"></script>
<!-- /.container-fluid -->
<script type="text/javascript">
	$(document).ready(function() {
		$("body").on("change", "#komponen", function(e) {
			if ($(this).val() == "Website") {
				$("#div_komponen").hide();
			} else $("#div_komponen").show();
		});
	});
	
	$("#id_departement").change(function(){
		load_pic_departemen($(this).val());
	});
	function load_pic_departemen(id_departemen){
		$.ajax({
	        url: "daily_report/pic_per_departemen/"+id_departemen,	       
	        success: function(response) {
	          $("#div_pic").html(response);
	        }
      });
	}

	$("#id_dvc").change(function(){
		load_device_pengguna($(this).val());
	});
	function load_device_pengguna(id){
		$.ajax({
	        url: "daily_report/device_per_pengguna/"+id,	       
	        success: function(response) {
	          $("#div_dvc").html(response);
	        }
      });
	}
</script>


