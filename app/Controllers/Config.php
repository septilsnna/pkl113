<?php

namespace App\Controllers;

session_start();

class Config extends BaseController
{
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

        //var_dump($_SESSION['auth']);
        //var_dump($_SESSION['name']);
        //var_dump($_SESSION['username']);
        return redirect()->to('../Home/index');
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
}