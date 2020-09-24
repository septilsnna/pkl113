<?php
session_start();
?>

<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>

<h2 class="py-3"><?= $title; ?> Ke-<?= $_SESSION['id_form05']; ?></h2>
<form action="../Config/save_meeting" method="post">
    <?= csrf_field(); ?>
    <div class="form-group row px-3">
        <div class="col-md-8 py-2">
            <label for=" hari_tanggal">Hari/Tanggal</label>
            <input type="datetime-local" class="form-control" id="hari_tanggal" name="hari_tanggal" required>
        </div>
        <div class="col-md-8 py-2">
            <label for="pokok_bahasan">Pokok Bahasan</label>
            <input type="text" class="form-control" id="pokok_bahasan" name="pokok_bahasan"
                placeholder="Materi yang akan dibahas" required>
        </div>
        <div class="col-md-8 py-2">
            <label for="batas_presensi">Batas Waktu Presensi <br><small style="color: red;">Secara default, sistem akan
                    mengatur
                    batas waktu presensi yaitu 50 menit dikalikan dengan jumlah sks. Atau Anda dapat memasukan
                    waktu
                    berakhirnya presensi secara manual dibawah ini</small></label>
            <input type="time" class="form-control" id="batas_presensi" name="batas_presensi"
                placeholder="Dalam waktu jam dan menit">
            <!--<div class="form-check pt-2">
                <input class="form-check-input" type="radio" name="batas_presensi" id="batas_presensi_default"
                    value="default">
                <label class="form-check-label" for="batas_presensi_default">
                    Sesuai waktu berakhirnya sks
                </label>
            </div>-->
        </div>
        <div class="col-md-7 py-4">
            <button type="submit" class="btn btn-block"
                style="background-color: #32a852; color:white">Tambahkan!</button>
        </div>
    </div>
</form>

<?= $this->endSection(); ?>