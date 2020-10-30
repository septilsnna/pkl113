<div class="container">
    <div class="row mb-3 justify-content-end">
        <div class="col-md-7">
            <a href="" class="btn btn-block text-center" data-toggle="modal" data-target="#ModalEditButtonCenter"
                style="background-color: #32a852; color:white">Edit Pertemuan</a>

            <!-- Modal -->
            <div class="modal fade" id="ModalEditButtonCenter" tabindex="-1" role="dialog"
                aria-labelledby="ModalEditButtonCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalEditButtonLongTitle">Edit Pertemuan
                                Ke-<?= $_SESSION['id_form05']; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="/Config/edit_meeting" method="post">
                            <div class="modal-body">
                                <div class="form-group row">
                                    <label for="tanggal" class="col-md-5 col-form-label text-right">Tanggal</label>
                                    <div class="col-md-6">
                                        <input type="date" class="form-control" name="tanggal" id="tanggal"
                                            placeholder="<?= $form05[0]['tanggal'] ?>"
                                            min="<?= $form05[0]['tanggal'] ?>" value="<?= $form05[0]['tanggal'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="jam_mulai" class="col-md-5 col-form-label text-right">Waktu
                                        Mulai</label>
                                    <div class="col-md-6">
                                        <input type="time" class="form-control" name="jam_mulai" id="jam_mulai"
                                            placeholder="<?= $form05[0]['jam_mulai'] ?>"
                                            value="<?= $form05[0]['jam_mulai'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="jam_selesai" class="col-md-5 col-form-label text-right">Waktu
                                        Selesai</label>
                                    <div class="col-md-6">
                                        <input type="time" class="form-control" name="jam_selesai" id="jam_selesai"
                                            placeholder="<?= $form05[0]['jam_selesai'] ?>"
                                            value="<?= $form05[0]['jam_selesai'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="batas_presensi" class="col-md-5 col-form-label text-right">Batas Waktu
                                        Presensi</label>
                                    <div class="col-md-6">
                                        <input type="time" class="form-control" name="batas_presensi"
                                            id="batas_presensi" placeholder="<?= $form05[0]['batas_presensi'] ?>"
                                            value="<?= $form05[0]['batas_presensi'] ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="pokok_bahasan" class="col-md-5 col-form-label text-right">Pokok
                                        Bahasan</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="pokok_bahasan" id="pokok_bahasan"
                                            placeholder="<?= $form05[0]['pokok_bahasan'] ?>"
                                            value="<?= $form05[0]['pokok_bahasan'] ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn"
                                    style="background-color: #32a852; color:white">Perbarui</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>