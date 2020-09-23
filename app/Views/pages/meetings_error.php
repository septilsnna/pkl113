<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>

<div class="row pt-5">
    <div class="col">
        <h4><?= $course[0]['nama_matkul']; ?><br><?= $course[0]['id']; ?></h4>
        <p>Pertemuan belum dapat dimulai apabila PJ belum tersedia</p>
    </div>
</div>

<?= $this->endSection(); ?>