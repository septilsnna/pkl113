<?php
session_start();
?>

<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>

<h2 class="text-center my-5">Daftar Mata Kuliah</h2>

<div class="row align-items-center">
    <?php for ($i = 0; $i <= count($data) - 1; $i++) : ?>
    <div class="col-md-4 my-3">
        <div class="card text-center" style="border-color: #32a852; background-color:#dbffe5">
            <div class="card-body py-4">
                <h5 class="card-title"><?= $data[$i]['nama__matkul']; ?></h5>
                <p class="card-title"><?= $data[$i]['kelas_matkul']; ?></p>
                <a href="/meetings/index/<?= $data[$i]['kelas_matkul'] ?>" class="btn btn-block"
                    style="background-color: #32a852; color:white">Lihat Pertemuan</a>
            </div>
        </div>
    </div>
    <?php endfor; ?>
</div>

<?= $this->endSection(); ?>