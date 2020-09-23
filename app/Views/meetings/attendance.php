<?php
session_start();
?>

<div class="container">
    <h6>Jumlah Mahasiswa yang hadir: <?= count($form06); ?></h6>
    <h6>Daftar Mahasiswa yang hadir:</h6>
    <table class="table table-borderless">
        <thead>
            <tr>
                <th class="text-center" scope="col">No.</th>
                <th scope="col">NRM</th>
                <th scope="col">Nama</th>
                <th scope="col">Waktu kehadiran</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < count($form06); $i++) : ?>
            <tr>
                <th class="text-center" scope="row"><?= $i + 1; ?></th>
                <td><?= $form06[$i]['id_mhs']; ?></td>
                <td><?= $form06[$i]['nama_mhs']; ?></td>
                <td><?= $form06[$i]['created_at']; ?></td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>
</div>