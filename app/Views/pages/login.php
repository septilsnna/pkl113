<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <title>Our Web</title>
</head>

<body style="background-color: #dbffe5;">
    <div class="container my-5">
        <div class="row align-items-center">
            <div class="col-md-7 my-5">
                <h2>Selamat Datang<br>di layanan presensi<br>Universitas Negeri Jakarta</h2>
                <img src="/img/Logo-unj.png" style="max-width:40%;" class="rounded float-left mt-3" alt="...">
            </div>
            <div class="col-md-5 mb-5">
                <div class="card align-items-center" style="background-color: #32a852;">
                    <div class="card-body">
                        <h3 style="color: white;" class="mb-4 text-center"><?= $title; ?></h3>
                        <form action="../Home/login" method="post">
                            <?= csrf_field(); ?>
                            <div class="form-group row">
                                <label for="username" class="col-sm-4 col-form-label" style="color: white">Username:
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="username" name="username" autofocus
                                        required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-sm-4 col-form-label"
                                    style="color: white">Password</label>
                                <div class="col-sm-7">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-7 offset-md-4">
                                    <button type="submit" class="btn btn-block"
                                        style="background-color: #2d964a; color: white">Masuk</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>
</body>

</html>