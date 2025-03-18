<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Register - Sistem Pendukung Keputusan</title>

    <link href="<?= base_url('assets/') ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" />
    <link href="<?= base_url('assets/') ?>css/sb-admin-2.min.css" rel="stylesheet" />
</head>

<body class="bg-gradient-danger">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-lg-6">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Create an Account</h1>
                                    </div>
                                    <?php $error = $this->session->flashdata('message'); ?>
                                    <?php if ($error): ?>
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                            <?= $error; ?>
                                        </div>
                                    <?php endif; ?>

                                    <form class="user" action="<?= site_url('Register/store'); ?>" method="post">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="nama" placeholder="Nama Lengkap" required />
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" name="email" placeholder="Email" required />
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="username" placeholder="Username" required />
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" name="password" placeholder="Password" required />
                                        </div>
                                        <input type="hidden" name="privilege" value="2" />
                                        <button type="submit" class="btn btn-danger btn-user btn-block">Register Account</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="<?= site_url('Login'); ?>">Already have an account? Login!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/') ?>vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets/') ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/') ?>vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="<?= base_url('assets/') ?>js/sb-admin-2.min.js"></script>
</body>

</html>