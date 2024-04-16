<form method="post" enctype="multipart" action="<?php echo base_url('dashboard/export') ?>">
    <input type="hidden" name="file" value="file">
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="card-title">SISKA</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>File Excel Siska</label>
                        <input type="file" class="form-control form-control-border" name="file_siska" placeholder="File Excel Siska">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="card-title">FEEDER</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>File Excel Feeder</label>
                        <input type="file" class="form-control form-control-border" name="file_feeder" placeholder="File Excel Feeder">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>