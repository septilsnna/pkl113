<?php

namespace App\Controllers;

session_start();

use App\Models\Form05Model;
use App\Models\Form06Model;
use App\Models\CourseModel;

class Config extends BaseController
{
    protected $form05Model;
    protected $form06Model;
    protected $courseModel;

    public function __construct()
    {
        $this->form05Model = new Form05Model();
        $this->form06Model = new Form06Model();
        $this->courseModel = new CourseModel();
    }

    public function login($id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://103.8.12.212:36880/siakad_api/api/as400/login",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array('username' => $this->request->getVar('username'), 'password' => $this->request->getVar('password'), 'captcha_id' => $id, 'securid' => $this->request->getVar('securid')),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $res = json_decode($response, true);

        $_SESSION['auth'] = $res['Authorization'];
        $_SESSION['name'] = $res['nama'];
        $_SESSION['username'] = $res['username'];
        $_SESSION['mode'] = $res['mode'];

        return redirect()->to('/');
    }

    public function save_meeting()
    {
        $id_matkul = $this->courseModel->where('id', $_SESSION['id_kelas'])->findAll();

        $data = [
            'id_form05' => $_SESSION['id_form05'],
            'id_kelas' => $_SESSION['id_kelas'],
            'id_matkul' => $id_matkul[0]['id_matkul'],
            'hari_tanggal' => $this->request->getVar('hari_tanggal'),
            'batas_presensi' => $this->request->getVar('batas_presensi'),
            'pokok_bahasan' => $this->request->getVar('pokok_bahasan'),
            'created_at' => date("Y-m-d H:i:s")
        ];

        $this->form05Model->insert($data);

        return redirect()->to('/Home/meetings/' . $_SESSION['id_kelas']);
    }

    public function save_presence()
    {
        $id_matkul = $this->courseModel->where('id', $_SESSION['id_kelas'])->findAll();
        //var_dump($id_matkul);

        $data = [
            'id_form05' => (int)$_SESSION['id_form05'],
            'id_kelas' => $_SESSION['id_kelas'],
            'id_matkul' => $id_matkul[0]['id_matkul'],
            'id_mhs' => $_SESSION['username'],
            'nama_mhs' => $_SESSION['name'],
            'created_at' => date("Y-m-d H:i:s"),

        ];

        $this->form06Model->insert($data);

        return redirect()->to(base_url('/Meetings/meeting_detail/' . $_SESSION['id_form05']));
    }

    public function verify_presence()
    {
        $mahasiswa = $_POST['mahasiswa'];

        foreach ($mahasiswa as $mhs) {
            $this->form06Model
                ->where(array('id_form05' => $_SESSION['id_form05'], 'id_kelas' => $_SESSION['id_kelas'], 'id_mhs' => $mhs))
                ->set(['updated_at' => date("Y-m-d H:i:s")])
                ->update();
        }

        return redirect()->to('/Home/meetings/' . $_SESSION['id_kelas']);
    }

    public function verify_meeting()
    {
        $this->form05Model
            ->where(array('id_form05' => $_SESSION['id_form05'], 'id_kelas' => $_SESSION['id_kelas']))
            ->set(['updated_at' => date("Y-m-d H:i:s")])
            ->update();

        return redirect()->to('/Home/meetings/' . $_SESSION['id_kelas']);
    }

    public function mau()                       // SELESAI
    {
        $this->courseModel->where('id', $_SESSION['id_kelas'])->set(['id_pj' => $_SESSION['username'], 'nama_pj' => $_SESSION['name']])->update();

        $course1 = $this->courseModel->where('id', $_SESSION['id_kelas'])->findAll();

        // menghitung jumlah pertemuan
        $jml_temu = 0;
        if ($course1[0]['jml_sks'] == '1') {
            $jml_temu = 8;
        } else {
            $jml_temu = 16;
        }

        $date = date_create("2020-10-18");
        $date_begin = date_add($date, date_interval_create_from_date_string($course1[0]['hari'] . " days"));

        // menambahkan jumlah pertemuan sesuai jml_sks
        for ($a = 1; $a <= ($jml_temu); $a++) {
            $start = date_format(date_create(date_format($date, "Y-m-d") . " " . $course1[0]['jam_mulai']), "Y-m-d H:i:s");
            date_add($date_begin, date_interval_create_from_date_string("7 days"));
            $data = [
                'id_form05' => $a,
                'id_kelas' => $_SESSION['id_kelas'],
                'id_matkul' => $course1[0]['id_matkul'],
                'jam_mulai' => $course1[0]['jam_mulai'],
                'jam_selesai' => $course1[0]['jam_selesai'],
                'tanggal' => date_format($date, "Y-m-d"),
                'batas_presensi' => date("H:i:s", strtotime('+30 minutes', strtotime($start)))
            ];

            $this->form05Model->insert($data);
        }

        return redirect()->to('../course');
    }

    public function logout()                    // SELESAI
    {
        session_unset();
        session_destroy();
        return redirect()->to('/');
    }
}