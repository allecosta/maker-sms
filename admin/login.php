<?php require_once '../config.php'; ?>

<!DOCTYPE html>
<html lang="pt-br" style="height: auto;">

<?php require_once '../inc/header.php'; ?>

<body class="hold-transition login-page dark-mode">
    <script>
        start_loader()
    </script>
    <style>
        body {
            background-image: url(<?= validateImage($_settings->info("cover")); ?>);
            background-size: cover;
            background-repeat: no-repeat;
        }

        .login-title {
            text-shadow: 2px 2px black;
        }
    </style>
    <h1 class="text-center py-5 login-title"><strong><?= $_settings->info("name"); ?></strong></h1>
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="./" class="h1"><strong>Login</strong></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Faça login para iniciar sua sessão</p>
                <form id="login-frm" method="POST">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="username" placeholder="Usuário" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Senha">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8"></div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                        </div>
                    </div>  
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>

    <script>
        $(document).ready(function() {
            end_loader();
        })
    </script>  
</body>
</html>