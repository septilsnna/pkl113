<?php
session_start();
?>

<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>

<h2 class="text-center my-5"><?= $title; ?></h2>

<div class="row align-items-center">
    <?php for ($i = 0; $i <= count($course) - 1; $i++) : ?>
    <div class="col-md-4 my-3">
        <div class="card text-center" style="border-color: #32a852; background-color:#dbffe5">
            <div class="card-body py-4">
                <h5 class="card-title"><?= $course[$i][0]['nama_matkul']; ?></h5>
                <a href="../Home/meetings/<?= $course[$i][0]['id'] ?>" class="btn btn-block"
                    style="background-color: #32a852; color:white">Lihat Pertemuan</a>
            </div>
        </div>
    </div>
    <?php endfor; ?>
</div>

<?= $this->endSection(); ?>