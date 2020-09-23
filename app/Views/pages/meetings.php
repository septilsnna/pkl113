<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>

<div class="row pt-5 pb-2">
    <div class="col-5">
        <h4><?= $course[0]['nama_matkul']; ?><br><?= $course[0]['id']; ?></h4>
        <p>PJ : <?= $nama_pj; ?> (<?= $id_pj; ?>)</p>
    </div>
    <div class="col-7">
        <?php foreach ($form05 as $f) : ?>
        <a href="/Meetings/meeting_detail/<?= $f['id_form05'] ?>" type="button" class="btn btn-lg btn-block"
            style="border-color: #32a852;">Pertemuan
            Ke-<?= $f['id_form05']; ?></a>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection(); ?>