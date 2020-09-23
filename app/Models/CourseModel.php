<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'course';
    protected $allowedFields = ['id_pj'];
}
