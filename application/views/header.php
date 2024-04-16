<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?php echo base_url('assets/img/bumigora.png') ?>">

    <title>BUMIGORA</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/fontawesome.min.css') ?>">
    <!-- Animate -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/animate.min.css') ?>">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/sweetalert2.min.css') ?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/dataTables.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/dataTables.bootstrap4.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/adminlte.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/custom.css') ?>">

    <!-- jQuery -->
    <script src="<?php echo base_url('assets/js/jquery.min.js') ?>"></script>
</head>
<body class="hold-transition sidebar-mini accent-primary">
    <div class="bg" style="display: none;">
        <div class="sk-wave">
            <div class="sk-rect sk-rect1"></div>
            <div class="sk-rect sk-rect2"></div>
            <div class="sk-rect sk-rect3"></div>
            <div class="sk-rect sk-rect4"></div>
            <div class="sk-rect sk-rect5"></div>
        </div>
    </div>

    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <!-- <li class="nav-item d-none d-sm-inline-block">
                    <a href="index3.html" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li> -->
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('logout') ?>">
                        <i class="fas fa-power-off"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
                <img src="<?php echo base_url('assets/img/bumigora.png') ?>" alt="BUMIGORA Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">BUMIGORA</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="<?php echo base_url('assets/img/user-placeholder.png') ?>" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">Administrator</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="<?php echo base_url('dashboard') ?>" class="nav-link <?php echo $this->uri->segment(1) == 'dashboard' ? 'active' : NULL ?>">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-item <?php if($this->uri->segment(1) == 'tahun' || $this->uri->segment(1) == 'prodi' ) { echo "menu-open"; } ?>">
                            <a href="#" class="nav-link <?php if($this->uri->segment(1) == 'tahun' || $this->uri->segment(1) == 'prodi') { echo "active"; } ?>">
                                <i class="nav-icon fas fa-database"></i>
                                <p>Data Master <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo base_url('tahun') ?>" class="nav-link <?php echo $this->uri->segment(1) == 'tahun' ? 'active' : NULL ?>">
                                        <i class="nav-icon far fa-circle"></i> <p>Tahun Akademik</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('prodi') ?>" class="nav-link <?php echo $this->uri->segment(1) == 'prodi' ? 'active' : NULL ?>">
                                        <i class="nav-icon far fa-circle"></i> <p>Program Studi</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item <?php if($this->uri->segment(1) == 'siska' && ($this->uri->segment(2) == 'arsip' || $this->uri->segment(2) == 'generate')) { echo "menu-open"; } ?>">
                            <a href="#" class="nav-link <?php if($this->uri->segment(1) == 'siska' && ($this->uri->segment(2) == 'arsip' || $this->uri->segment(2) == 'generate')) { echo "active"; } ?>">
                                <i class="nav-icon fas fa-file-excel"></i>
                                <p>Data Siska <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo base_url('siska/arsip') ?>" class="nav-link <?php echo $this->uri->segment(1) == 'siska' && $this->uri->segment(2) == 'arsip' ? 'active' : NULL ?>">
                                        <i class="nav-icon far fa-circle"></i> <p>Arsip</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('siska/generate') ?>" class="nav-link <?php echo $this->uri->segment(1) == 'siska' && $this->uri->segment(2) == 'generate' ? 'active' : NULL ?>">
                                        <i class="nav-icon far fa-circle"></i> <p>Generate</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item <?php if($this->uri->segment(1) == 'feeder' && ($this->uri->segment(2) == 'arsip' || $this->uri->segment(2) == 'generate')) { echo "menu-open"; } ?>">
                            <a href="#" class="nav-link <?php if($this->uri->segment(1) == 'feeder' && ($this->uri->segment(2) == 'arsip' || $this->uri->segment(2) == 'generate')) { echo "active"; } ?>">
                                <i class="nav-icon fas fa-file-excel"></i>
                                <p>Data Feeder <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo base_url('feeder/arsip') ?>" class="nav-link <?php echo $this->uri->segment(1) == 'feeder' && $this->uri->segment(2) == 'arsip' ? 'active' : NULL ?>">
                                        <i class="nav-icon far fa-circle"></i> <p>Arsip</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo base_url('feeder/generate') ?>" class="nav-link <?php echo $this->uri->segment(1) == 'feeder' && $this->uri->segment(2) == 'generate' ? 'active' : NULL ?>">
                                        <i class="nav-icon far fa-circle"></i> <p>Generate</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="<?php echo base_url('perbandingan') ?>" class="nav-link <?php echo $this->uri->segment(1) == 'perbandingan' ? 'active' : NULL ?>">
                                <i class="nav-icon fas fa-compress-alt"></i>
                                <p>Perbandingan</p>
                            </a>
                        </li>

                        <!-- <li class="nav-item">
                            <a href="<?php // echo base_url('siska') ?>" class="nav-link <?php // echo $this->uri->segment(1) == 'siska' ? 'active' : NULL ?>">
                                <i class="nav-icon fas fa-file-excel"></i>
                                <p>Data Siska</p>
                            </a>
                        </li> -->

                        <!-- <li class="nav-item">
                            <a href="<?php echo base_url('feeder') ?>" class="nav-link <?php echo $this->uri->segment(1) == 'feeder' ? 'active' : NULL ?>">
                                <i class="nav-icon fas fa-file-excel"></i>
                                <p>Data Feeder</p>
                            </a>
                        </li> -->
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <!-- <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Starter Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Starter Page</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content pt-3 pb-1">
                <div class="container-fluid">