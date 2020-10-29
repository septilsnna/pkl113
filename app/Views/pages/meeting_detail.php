<?php
session_start();
?>

<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>

<div class="row pt-5 align-items-center">
    <div class="col-md-5">
        <h4><?= $nama_matkul; ?><br><?= $_SESSION['id_kelas']; ?></h4>
    </div>
    <div class="col-md-7">
        <h5 class="mt-3">Detail Pertemuan ke-<?= $form05[0]['id_form05']; ?></h5>
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <th scope="row" style="color:#32a852">Tanggal</th>
                    <td><?= $tanggal; ?></td>
                </tr>
                <tr>
                    <th scope="row" style="color:#32a852">Waktu Mulai</th>
                    <td><?= $form05[0]['jam_mulai']; ?></td>
                </tr>
                <tr>
                    <th scope="row" style="color:#32a852">Waktu Selesai</th>
                    <td><?= $form05[0]['jam_selesai']; ?></td>
                </tr>
                <tr>
                    <th scope="row" style="color:#32a852">Batas Waktu Presensi</th>
                    <td><?= $end; ?></td>
                </tr>
                <tr>
                    <th scope="row" style="color:#32a852">Pokok Bahasan</th>
                    <td><?= $form05[0]['pokok_bahasan']; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


<?= $this->endSection(); ?>