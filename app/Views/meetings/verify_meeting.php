<?php
session_start();
?>

<div class="container">
    <p class="text-center">Jumlah Mahasiswa yang hadir: <?= $form05[0]['jml_mhs_hadir']; ?></p>
    <form action="/Meetings/verify_meeting" method="post">
        <?= csrf_field(); ?>
        <div class="form-group row">
            <div class="col">
                <button type="submit" class="btn btn-lg btn-block" style="background-color: #32a852; color:white"
                    name="verif">Verifikasi Sekarang</button>
            </div>
        </div>
    </form>
    </form>
</div>