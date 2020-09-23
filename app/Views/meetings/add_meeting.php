<?php
session_start();
?>

<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>

<h2 class="py-3"><?= $title; ?> Ke-<?= $_SESSION['id_form05']; ?></h2>
<form action="../Meetings/save_meeting" method="post">
    <?= csrf_field(); ?>
    <div class="form-group row">
        <label for="hari_tanggal" class="col-sm-2 col-form-label">Hari/Tanggal:</label>
        <div class="col-sm-10">
            <input type="datetime-local" class="form-control" id="hari_tanggal" name="hari_tanggal" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="pokok_bahasan" class="col-sm-2 col-form-label">Pokok Bahasan:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="pokok_bahasan" name="pokok_bahasan" required>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-7 offset-md-5">
            <button type="submit" class="btn btn-block"
                style="background-color: #32a852; color:white">Tambahkan!</button>
        </div>
    </div>
</form>

<?= $this->endSection(); ?>