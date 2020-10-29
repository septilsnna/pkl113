<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'course';
    protected $allowedFields = ['id', 'id_matkul', 'nama_matkul', 'nama_dosen', 'id_pj', 'nama_pj', 'jml_sks', 'hari', 'jam_mulai', 'jam_selesai'];
}