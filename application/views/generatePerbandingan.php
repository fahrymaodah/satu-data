<div class="row">
	<div class="col-md-12">
		<div class="card card-primary card-outline">
			<div class="card-header">
				<h4 class="card-title">File Excel Generate Perbandingan Nilai Siska dan Feeder <br> <small>Universitas Bumigora</small></h4>
				<div class="card-tools">
					<a data-toggle="modal" data-target="#modal" data-act='tambah' data-tab='generate' class="btn btn-sm btn-primary pop-up">
						Tambah &nbsp; <i class="fa fa-plus"></i>
					</a>
				</div>
			</div>
			<div class="card-body">
				<table id="datatables" class="table table-no-bordered" cellspacing="0" width="100%" style="width:100%">
					<thead class="text-primary">
						<th class="text-center">No.</th>
						<th class="text-left">Program Studi</th>
						<th class="text-left">Angkatan</th>
						<th class="text-center disabled-sorting">Pilihan</th>
					</thead>
					<tbody>
						<?php foreach ($generate as $key => $value): ?>
							<tr>
								<td class="text-center"><?php echo $key+1 ?>.</td>
								<td class="text-left"><?php echo $value['nama_prodi'] ?></td>
								<td class="text-left"><?php echo $value['angkatan_generate'] ?></td>
								<td class="text-center">
									<a class="btn btn-sm btn-success" target="_blank" href="<?php echo base_url('assets/file/generate/'.$value['file_generate']) ?>">
										<i class="fa fa-download" data-toggle="tooltip" data-placement="left" title="Download"></i>
									</a>
									<!-- <a class="btn btn-sm btn-warning btn-ubah pop-up" data-toggle="modal" data-target="#modal" data-act='ubah' data-tab='generate' idnya="<?php echo $value['id_generate'] ?>">
										<i class="fa fa-edit" data-toggle="tooltip" data-placement="left" title="Edit"></i>
									</a> -->
									<a class="btn btn-sm btn-danger btn-hps" href="<?php echo base_url('perbandingan/hapus/'.$value['id_generate']) ?>">
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
						<label>Program Studi</label>
						<select name="id_prodi" class="form-check form-control form-control-border">
							<option selected disabled>Pilih Program Studi</option>
							<?php foreach ($prodi as $key => $value): ?>
								<option value="<?php echo $value['id_prodi'] ?>"><?php echo $value['nama_prodi'] ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="form-group">
						<label>Angkatan</label>
						<select name="angkatan_generate" class="form-check form-control form-control-border">
							<option selected disabled>Pilih Angkatan</option>
							<?php foreach ($tahun as $key => $value): ?>
								<option value="<?php echo $value['nama_tahun'] ?>"><?php echo $value['nama_tahun'] ?></option>
							<?php endforeach ?>
						</select>
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Tutup</button>
					<button type="button" class="btn btn-sm btn-primary generate">Simpan</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		var pesan = '<?php echo $this->session->flashdata('pesan') ?>';
		if (pesan != '') { swal(pesan); }

		$(document).on('click', '.generate', function(e) {
            e.preventDefault();
            formulir('perbandingan', 'tambah',);
        });

		// $('.btn-ubah').on('click', function() {
        // 	var id = $(this).attr("idnya");

        // 	$.ajax({
        //         type:'POST',
        //         url:'<?php echo base_url("perbandingan/generateDetail") ?>',
        //         data: 'id_generate='+id,
        //         dataType: 'json',
        //         beforeSend: function() { $(".bg").show(); },
        //         complete: function() { $(".bg").hide(); },
        //         success:function(hasil)
        //         {
        //             $('#form input[name=id_generate]').val(hasil.id_generate);
        //             $('#form select[name=id_prodi]').val(hasil.id_prodi);
        //             $('#form select[name=angkatan_generate]').val(hasil.angkatan_generate);
        //         }
        //     });
        // });

		// $(document).on('click', '.generateUbah', function(e) {
        //     e.preventDefault();
        //     formulir('perbandingan', 'generateUbah');
        // });

        $('.btn-hps').on('click', function(e) {
        	e.preventDefault();
        	hapus($(this).attr('href'));
        });
	});
</script>