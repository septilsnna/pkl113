<?php

namespace App\Models;

use CodeIgniter\Model;

class Form06Model extends Model
{
    protected $table = 'form_06';
    protected $allowedFields = ['id_form05', 'id_kelas', 'id_matkul', 'id_mhs', 'nama_mhs', 'verified', 'updated_at'];
    //protected $useTimestamps = true;
}