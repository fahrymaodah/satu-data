<div class="row">
	<div class="col-md-12">
		<div class="card card-primary card-outline">
			<div class="card-header">
				<h4 class="card-title">File Excel Feeder <br> <small>Universitas Bumigora</small></h4>
				<div class="card-tools">
					<a data-toggle="modal" data-target="#modal" data-act='tambah' data-tab='feeder' class="btn btn-sm btn-primary pop-up">
						Tambah &nbsp; <i class="fa fa-plus"></i>
					</a>
				</div>
			</div>
			<div class="card-body">
				<table id="datatables" class="table table-no-bordered" cellspacing="0" width="100%" style="width:100%">
					<thead class="text-primary">
						<th class="text-center">No.</th>
						<th class="text-left">Periode</th>
						<th class="text-left">Program Studi</th>
						<th class="text-center disabled-sorting">Pilihan</th>
					</thead>
					<tbody>
						<?php foreach ($feeder as $key => $value): ?>
							<tr>
								<td class="text-center"><?php echo $key+1 ?>.</td>
								<td class="text-left">
									<?php echo $value['nama_tahun'] ?> <br> <small><?php echo ucfirst($value['semester_feeder']) ?></small>
								</td>
								<td class="text-left"><?php echo $value['nama_prodi'] ?></td>
								<td class="text-center">
									<a class="btn btn-sm btn-success" target="_blank" href="<?php echo base_url('assets/file/feeder/'.$value['file_feeder']) ?>">
										<i class="fa fa-download" data-toggle="tooltip" data-placement="left" title="Download"></i>
									</a>
									<a class="btn btn-sm btn-warning btn-ubah pop-up" data-toggle="modal" data-target="#modal" data-act='ubah' data-tab='feeder' idnya="<?php echo $value['id_feeder'] ?>">
										<i class="fa fa-edit" data-toggle="tooltip" data-placement="left" title="Edit"></i>
									</a>
									<a class="btn btn-sm btn-danger btn-hps" href="<?php echo base_url('feeder/hapus/'.$value['id_feeder']) ?>">
										<i class="fa fa-trash" data-toggle="tooltip" data-placement="left" title="Hapus"></i>
									</a>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" id="form">
				<div class="modal-body">
					<div class="form-group">
						<label>Tahun Akademik</label>
						<select name="id_tahun" class="form-check form-control form-control-border">
							<option selected disabled>Pilih Tahun Akademik</option>
							<?php foreach ($tahun as $key => $value): ?>
								<option value="<?php echo $value['id_tahun'] ?>"><?php echo $value['nama_tahun'] ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="form-group">
						<label>Semester</label>
						<select name="semester_feeder" class="form-check form-control form-control-border">
							<option selected disabled>Pilih Semester</option>
							<option value="ganjil">Ganjil</option>
							<option value="genap">Genap</option>
						</select>
					</div>
					<div class="form-group">
						<label>Program Studi</label>
						<select name="id_prodi" class="form-check form-control form-control-border">
							<option selected disabled>Pilih Program Studi</option>
							<?php foreach ($prodi as $key => $value): ?>
								<option value="<?php echo $value['id_prodi'] ?>"><?php echo $value['nama_prodi'] ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="form-group">
						<label>File Excel Feeder</label>
						<input type="file" class="form-control form-control-border" name="file_feeder" placeholder="File Excel Feeder">
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Tutup</button>
					<button type="button" class="btn btn-sm btn-primary action">Simpan</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		var pesan = '<?php echo $this->session->flashdata('pesan') ?>';
		if (pesan != '') { swal(pesan); }

		$(document).on('click', '.tambah', function(e) {
            e.preventDefault();
            formulir('feeder', 'tambah');
        });

		$('.btn-ubah').on('click', function() {
        	var id = $(this).attr("idnya");

        	$.ajax({
                type:'POST',
                url:'<?php echo base_url("feeder/detail") ?>',
                data: 'id_feeder='+id,
                dataType: 'json',
                beforeSend: function() { $(".bg").show(); },
                complete: function() { $(".bg").hide(); },
                success:function(hasil)
                {
                    $('#form input[name=id_feeder]').val(hasil.id_feeder);
                    $('#form select[name=id_tahun]').val(hasil.id_tahun);
                    $('#form select[name=semester_feeder]').val(hasil.semester_feeder);
                    $('#form select[name=id_prodi]').val(hasil.id_prodi);
                    // $('#form input[name=file_feeder]').val(hasil.file_feeder);
                }
            });
        });

		$(document).on('click', '.ubah', function(e) {
            e.preventDefault();
            formulir('feeder', 'ubah');
        });

        $('.btn-hps').on('click', function(e) {
        	e.preventDefault();
        	hapus($(this).attr('href'));
        });
	});
</script>