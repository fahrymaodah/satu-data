<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="<?php echo base_url('assets/img/bumigora.png') ?>">

    <title>Login Satu Data | BUMIGORA</title>
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/login.css') ?>">
</head>
<body>
    <!-- style="background: url('<?php // echo base_url('assets/img/login.jpg') ?>'); background-size: cover;" -->
    <section class="login vh-100" style="background-color: #e1e1e1;">
        <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
            <div class="container">
                <div class="row">
                    <div class="col-10 offset-1 col-md-6 offset-md-3 col-lg-4 offset-lg-4 shadow-lg bg-white rounded-3">
                        <div class="card-body">
                            <div class="brand-wrapper text-center">
                                <img src="<?php echo base_url("assets/img/bumigora.png") ?>" alt="logo" class="logo">
                            </div>
                            <h6 class="text-center fw-bolder">Selamat datang di Satu Data <br>Universitas Bumigora</h6>
                            <form method="post">
                                <div class="form-floating mb-2">
                                    <?php $validEmail = set_value('email') != '' && form_error('email') == '' ? 'valid' : 'invalid' ?>
                                    <input type="text" name="email" id="email" class="form-control <?php echo checkColumn('email') ?>" placeholder="Email" value="<?php echo set_value('email') ?>">
                                    <div class="invalid-feedback"><?php echo form_error('email') ?></div>
                                    <label for="email">Email</label>
                                </div>
                                <div class="form-floating mb-2">
                                    <input type="password" name="password" id="password" class="form-control <?php echo checkColumn('password') ?>" placeholder="Password" value="<?php echo set_value('password') ?>">
                                    <div class="invalid-feedback"><?php echo form_error('password') ?></div>
                                    <label for="password">Password</label>
                                </div>
                                <div class="d-grid gap-2 mb-4">
                                    <button class="btn btn-primary btn-lg">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>