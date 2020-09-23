<?php

namespace App\Models;

use CodeIgniter\Model;

class Users extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'username';


    /*public function get_data($username, $password)
    {
        return $this->db->table('users')->where(array('username' => $username, 'password' => $password))->get()->getRowArray();
    }*/
}