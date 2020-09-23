<?php

namespace App\Models;

use CodeIgniter\Model;

class Form05Model extends Model
{
    protected $table = 'form_05';
    protected $allowedFields = ['id_form05', 'id_kelas', 'id_matkul', 'hari_tanggal', 'batas_presensi', 'pokok_bahasan', 'jml_mhs_hadir', 'updated_at'];
    //protected $useTimestamps = true;
}