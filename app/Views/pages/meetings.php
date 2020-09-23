<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>

<div class="row pt-5 pb-2">
    <div class="col-md-5">
        <h4><?= $course[0]['nama_matkul']; ?><br><?= $course[0]['id']; ?></h4>
        <p>Dosen : <?= $course[0]['nama_dosen']; ?></p>
        <p>Jumlah SKS : <?= $course[0]['jml_sks']; ?></p>
        <?php if ($course[0]['id_pj'] == null) : ?>
        <p>Pertemuan belum dapat dimulai apabila PJ belum tersedia</p>
    </div>

    <?php else : ?>
    <p>PJ : <?= $nama_pj; ?> (<?= $id_pj; ?>)</p>
</div>
<div class="col-md-7">
    <?php foreach ($form05 as $f) : ?>
    <a href="/Meetings/meeting_detail/<?= $f['id_form05'] ?>" type="button" class="btn btn-lg btn-block"
        style="border-color: #32a852;">Pertemuan
        Ke-<?= $f['id_form05']; ?></a>
    <?php endforeach; ?>
</div>
<?php endif; ?>
</div>

<?= $this->endSection(); ?>