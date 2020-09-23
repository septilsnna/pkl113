<?php
session_start();
?>

<div class="container">
    <p>Jumlah Mahasiswa yang hadir: <?= count($form06); ?></p>
    <p>Daftar Mahasiswa yang hadir:</p>
    <form action="/Meetings/verify_presence" method="post">
        <?= csrf_field(); ?>
        <table class="table table-borderless">
            <thead>
                <tr>
                    <th class="text-center" scope="col">No.</th>
                    <th scope="col">NRM</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Waktu kehadiran</th>
                    <th class="text-center" scope="col">Verifikasi kehadiran</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; $i < count($form06); $i++) : ?>
                <tr>
                    <th class="text-center" scope="row"><?= $i + 1; ?></th>
                    <td><?= $form06[$i]['id_mhs']; ?></td>
                    <td><?= $form06[$i]['nama_mhs']; ?></td>
                    <td><?= $form06[$i]['created_at']; ?></td>
                    <td class="text-center"><input type="checkbox" name="mahasiswa[]" id="mahasiswa"
                            value=<?= $form06[$i]['id_mhs']; ?> - <?= $form06[$i]['nama_mhs']; ?>></td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
        <div class="form-group row">
            <div class="col mt-3">
                <button type="submit" class="btn btn-lg btn-block" style="background-color: #32a852; color:white"
                    name="verif">Verifikasi Sekarang</button>
            </div>
        </div>
    </form>
</div>