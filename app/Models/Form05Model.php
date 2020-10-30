<?php

namespace App\Models;

use CodeIgniter\Model;

class Form05Model extends Model
{
    protected $table = 'form_05';
    protected $allowedFields = ['id_form05', 'id_kelas', 'id_matkul', 'tanggal', 'jam_mulai', 'jam_selesai', 'batas_presensi', 'pokok_bahasan', 'status', 'jml_mhs_hadir', 'updated_at'];
    //protected $useTimestamps = true;
}