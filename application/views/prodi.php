<div class="row">
	<div class="col-md-9">
		<div class="card card-primary card-outline">
			<div class="card-header">
				<h4 class="card-title">Program Studi <br> <small>Universitas Bumigora</small></h4>
				<div class="card-tools">
					<a data-toggle="modal" data-target="#modal" data-act='tambah' data-tab='prodi' class="btn btn-sm btn-primary pop-up">
						Tambah &nbsp; <i class="fa fa-plus"></i>
					</a>
				</div>
			</div>
			<div class="card-body">
				<table id="datatables" class="table table-no-bordered" cellspacing="0" width="100%" style="width:100%">
					<thead class="text-primary">
						<th class="text-center">No.</th>
						<th class="text-left">Program Studi</th>
						<th class="text-center disabled-sorting">Pilihan</th>
					</thead>
					<tbody>
						<?php foreach ($prodi as $key => $value): ?>
							<tr>
								<td class="text-center"><?php echo $key+1 ?>.</td>
								<td class="text-left"><?php echo $value['nama_prodi'] ?></td>
								<td class="text-center">
									<a class="btn btn-sm btn-warning btn-ubah pop-up" data-toggle="modal" data-target="#modal" data-act='ubah' data-tab='prodi' idnya="<?php echo $value['id_prodi'] ?>">
										<i class="fa fa-edit" data-toggle="tooltip" data-placement="left" title="Edit"></i>
									</a>
									<a class="btn btn-sm btn-danger btn-hps" href="<?php echo base_url('prodi/hapus/'.$value['id_prodi']) ?>">
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
						<input type="text" class="form-control form-control-border" name="nama_prodi" placeholder="Program Studi">
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
            formulir('prodi', 'tambah');
        });

		$('.btn-ubah').on('click', function() {
        	var id = $(this).attr("idnya");

        	$.ajax({
                type:'POST',
                url:'<?php echo base_url("prodi/detail") ?>',
                data: 'id_prodi='+id,
                dataType: 'json',
                beforeSend: function() { $(".bg").show(); },
                complete: function() { $(".bg").hide(); },
                success:function(hasil)
                {
                    $('#form input[name=id_prodi]').val(hasil.id_prodi);
                    $('#form input[name=nama_prodi]').val(hasil.nama_prodi);
                }
            });
        });

		$(document).on('click', '.ubah', function(e) {
            e.preventDefault();
            formulir('prodi', 'ubah');
        });

        $('.btn-hps').on('click', function(e) {
        	e.preventDefault();
        	hapus($(this).attr('href'));
        });
	});
</script>