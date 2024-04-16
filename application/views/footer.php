                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                Universitas Bumigora
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2024 <a href="https://adminlte.io">UBG</a></strong> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- Bootstrap 4 -->
    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- Bootsrap Notify -->
    <script src="<?php echo base_url('assets/js/notify.min.js') ?>"></script>
    <!-- SweetAlert2 -->
    <script src="<?php echo base_url('assets/js/sweetalert2.min.js') ?>"></script>
    <!-- DataTables -->
    <script src="<?php echo base_url('assets/js/dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/dataTables.bootstrap4.js') ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url('assets/js/adminlte.min.js') ?>"></script>
    <!-- Template -->
    <script src="<?php echo base_url('assets/js/plugin.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/template.js') ?>"></script>

    <script>
        $(document).ready(function() {
            plugin.initDataTables();
            // plugin.initFormExtendedDatetimepickers();
            // Javascript method's body can be found in assets/js/demos.js
        });
  </script>
</body>
</html>