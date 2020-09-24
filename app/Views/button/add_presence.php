<div class="container">
    <form action="/Config/save_presence" method="post">
        <?= csrf_field(); ?>
        <div class="row">
            <div class="col">
                <button type="submit" class="btn btn-lg btn-block"
                    style="background-color: #32a852; color:white">Presensi Sekarang</button>
            </div>
        </div>
    </form>
</div>