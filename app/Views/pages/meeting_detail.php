<?php
session_start();
?>

<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>

<div class="row pt-5 align-items-center">
    <div class="col-5">
        <h4><?= $nama_matkul; ?><br><?= $_SESSION['id_kelas']; ?></h4>
    </div>
    <div class="col-7">
        <h5 class="mt-3">Detail Pertemuan ke-<?= $form05[0]['id_form05']; ?></h5>
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <th scope="row" style="color:#32a852">Tanggal dan waktu mulai</th>
                    <td><?= $form05[0]['hari_tanggal']; ?></td>
                </tr>
                <tr>
                    <th scope="row" style="color:#32a852">Waktu selesai</th>
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