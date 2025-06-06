<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Sistem Pendukung Keputusan Metode ENTROPY MOORA</title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/') ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet" />

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/') ?>css/sb-admin-2.min.css" rel="stylesheet" />
    <link rel="shortcut icon" href="<?= base_url('assets/') ?>img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?= base_url('assets/') ?>img/favicon.ico" type="image/x-icon">
</head>

<body class="bg-gradient-danger">
    <nav class="navbar navbar-expand-lg navbar-dark bg-white shadow-lg pb-3 pt-3 font-weight-bold">
        <div class="container">
            <a class="navbar-brand text-danger" style="font-weight: 900;" href="<?= base_url('') ?>"> <i
                    class="fa fa-database mr-2 rotate-n-15"></i> Sistem Pendukung Keputusan Metode ENTROPY MOORA</a>
        </div>
    </nav>

    <div class="container">
        <!-- Outer Row -->
        <div class="row d-plex justify-content-between mt-5">
            <div class="col-xl-6 col-lg-6 col-md-6 mt-5">
                <div class="card bg-none o-hidden border-0 my-5 text-white" style="background: none;">
                    <div class="text-justify card-body p-0">
                        <h4 style="font-weight: 800;">Sistem Pendukung Keputusan Metode ENTROPY MOORA</h4>
                        <p class="pt-4">
                            Metode Entropy dapat digunakan untuk menentukan bobot dengan menghasilkan kriteria dengan
                            variasi nilai yang tertinggi dan bobot yang tinggi. Bobot adalah tingkatan kepentingan
                            relatif dari kriteria yang digunakan dalam suatu penilaian.bobot kriteria dalam suatu
                            penilaian ditentukan melalui opini pengambil keputusan.
                        </p>
                        <p>
                            Metode multi-objective optimization on the basis of ratio analysis (MOORA) adalah metode
                            yang diperkenalkan oleh Brauers dan Zavadkas. Metode yang relatif baru ini pertama kali
                            digunakan oleh Brauers dalam suatu pengambilan dengan multi-kriteria. Metode ini memiliki
                            tingkat selektifitas yang baik dalam menentukan suatu alternatif.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-xl-5 col-lg-5 col-md-5 mt-5">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Login Account</h1>
                                    </div>
                                    <?php if ($this->session->flashdata('login_message')): ?>
                                    <div class="alert alert-danger">
                                        <?= $this->session->flashdata('login_message') ?>
                                    </div>
                                    <?php endif; ?>

                                    <form class="user" action="<?php echo site_url('Login/login'); ?>" method="post">
                                        <div class="form-group">
                                            <input required autocomplete="off" type="text"
                                                class="form-control form-control-user" id="exampleInputUser"
                                                placeholder="Username" name="username" />
                                        </div>
                                        <div class="form-group">
                                            <input required autocomplete="off" type="password"
                                                class="form-control form-control-user" id="exampleInputPassword"
                                                name="password" placeholder="Password" />
                                        </div>
                                        <button name="submit" type="submit" class="btn btn-danger btn-user btn-block"><i
                                                class="fas fa-fw fa-sign-in-alt mr-1"></i> Masuk</button>

                                        <div class="text-center mt-3">
                                            <a class="small text-danger" href="<?= site_url('Register'); ?>">Buat Akun
                                                Baru</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('assets/') ?>vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets/') ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets/') ?>vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('assets/') ?>js/sb-admin-2.min.js"></script>
</body>

</html>