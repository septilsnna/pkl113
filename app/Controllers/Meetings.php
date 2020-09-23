<?php

namespace App\Controllers;

session_start();
date_default_timezone_set("Asia/Bangkok");

use App\Models\Form05Model;
use App\Models\Form06Model;
use App\Models\CourseModel;

class Meetings extends BaseController
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

    public function meeting_detail($param)
    {
        if ($_SESSION['username'] == null) {
            return redirect()->to('../Home/index');
        }

        $form05 = $this->form05Model->where(array('id_form05' => $param, 'id_kelas' => $_SESSION['id_kelas']))->findAll();
        $form06 = $this->form06Model->where(array('id_form05' => $param, 'id_kelas' => $_SESSION['id_kelas']))->findAll();
        $course = $this->courseModel->where('id', $_SESSION['id_kelas'])->findAll();

        $_SESSION['id_form05'] = $param;

        // mengupdate jumlah mahasiswa yang hadir
        $this->form05Model
            ->where(array('id_form05' => $param, 'id_kelas' => $_SESSION['id_kelas']))
            ->set(['jml_mhs_hadir' => (int)count($form06)])
            ->update();

        $now = date("Y-m-d H:i:s");
        $start = $form05[0]['hari_tanggal'];
        $end = date("Y-m-d H:i:s", strtotime('+' . ($course[0]['jml_sks'] * 50) . ' minutes', strtotime($form05[0]['hari_tanggal'])));

        // cek presensi mhs, kalo != null berarti udah presensi, kalo == null berarti blm presensi
        $presence = $this->form06Model->where(array('id_form05' => $param, 'id_kelas' => $_SESSION['id_kelas'], 'id_mhs' => $_SESSION['username']))->findAll();

        // cek verifikasi dosen, kalo != null berarti belom verif, kalo == null berarti udah verif
        $verify_06 = $this->form06Model->where(array('id_form05' => $param, 'id_kelas' => $_SESSION['id_kelas'], 'updated_at' => null))->findAll();

        // cek verifikasi mhs, kalo != null berarti belom verif, kalo == null berarti udah verif
        $verify_05 = $this->form05Model->where(array('id_form05' => $param, 'id_kelas' => $_SESSION['id_kelas'], 'updated_at' => null))->findAll();

        // cek mhs pertama yg presensi
        $first = $this->form06Model->where(array('id_form05' => $param, 'id_kelas' => $_SESSION['id_kelas']))->findAll();
        $pj = $this->form06Model->where(array('id_form05' => $param, 'id_kelas' => $_SESSION['id_kelas'], 'id_mhs' => $course[0]['id_pj']))->findAll();

        $data = [
            'title' => 'Detail Pertemuan',
            'form05' => $form05,
            'form06' => $form06,
            'nama_matkul' => $course[0]['nama_matkul'],
            'end' => $end,
        ];

        if ($_SESSION['role'] == "1") {                     // dosen
            if ($now < $start) {                            // kalo belom mulai presensi
                echo view('pages/meeting_detail', $data);
                return '<div class="container"><div class="row"><div class="col text-center"><div class="alert alert-info" role="alerrt">Presensi belum dimulai</div></div></div></div>';
            } else if ($now >= $start && $end >= $now) {    // kalo lagi jam perkuliahan
                echo view('pages/meeting_detail', $data);
                return view('meetings/attendance', $data);
            } else {                                        // kalo jam perkuliahan udah selesai
                if ($verify_06 == null) {                   // kalo udah diverif
                    echo view('pages/meeting_detail', $data);
                    echo view('meetings/attendance', $data);
                    return '<div class="container"><div class="row"><div class="col text-center"><div class="alert alert-success" role="alerrt">Anda sudah melakukan verifikasi!</div></div></div>';
                } else {                                    // kalo belom diverif
                    echo view('pages/meeting_detail', $data);
                    return view('meetings/verify_presence', $data);
                }
            }
        } else {                                            // mahasiswa
            if ($now < $start) {                            // kalo belom mulai presensi
                echo view('pages/meeting_detail', $data);
                return '<div class="container"><div class="row"><div class="col text-center"><div class="alert alert-info" role="alert">Presensi belum dibuka</div></div></div></div>';
            } else if ($now >= $start && $end >= $now) {    // kalo lagi jam perkuliahan
                if ($presence == null) {                    // kalo belom presensi
                    echo view('pages/meeting_detail', $data);
                    return view('button/add_presence');
                } else {                                    // kalo udah presensi
                    echo view('pages/meeting_detail', $data);
                    return '<div class="container"><div class="row"><div class="col text-center"><div class="alert alert-success" role="alerrt">Anda sudah melakukan presensi!</div></div></div></div>';
                }
            } else {                                        // kalo jam perkuliahan udah selesai
                if ($presence == null) {                    // dan mhs belom presensi
                    echo view('pages/meeting_detail', $data);
                    echo '<div class="container"><div class="row"><div class="col text-center"><div class="alert alert-danger" role="alert">Waktu presensi sudah selesai, Anda tidak melakukan presensi!</div></div></div></div>';
                    if ($_SESSION['username'] == $course[0]['id_pj']) {     // kuasa PJ => ga presensi berarti gabisa verifikasi
                        return '<div class="container"><div class="row"><div class="col text-center"><div class="alert alert-danger" role="alert">Anda tidak dapat melakukan verifikasi!</div></div></div></div>';
                    }
                } else {                                    // dan mhs udah presensi
                    echo view('pages/meeting_detail', $data);
                    echo '<div class="container"><div class="row"><div class="col text-center"><div class="alert alert-success" role="alert">Waktu presensi sudah selesai, Anda telah melakukan presensi!</div></div></div></div>';
                    if ($_SESSION['username'] == $course[0]['id_pj']) {     // kuasa PJ
                        if ($verify_05 == null) {           // pj udah presensi & verif
                            return '<div class="container"><div class="row"><div class="col text-center"><div class="alert alert-success" role="alerrt">Anda sudah melakukan verifikasi!</div></div></div></div>';
                        } else {                            // pj udah presensi tp blm verif
                            return view('meetings/verify_meeting', $data);
                        }
                    } else {                                // bukan pj, tp udah presensi
                        if ($pj == null && $first[0]['id_mhs'] == $_SESSION['username']) {
                            echo '<div class="container"><div class="row"><div class="col text-center"><div class="alert alert-warning" role="alert">Hari ini PJ tidak hadir, yuk bantu PJ untuk melakukan verifikasi!</div></div></div></div>';
                            if ($verify_05 == null) {       // pj udah presensi & verif
                                return '<div class="container"><div class="row"><div class="col text-center"><div class="alert alert-success" role="alerrt">Anda sudah melakukan verifikasi!</div></div></div></div>';
                            } else {                        // pj udah presensi tp blm verif
                                return view('meetings/verify_meeting', $data);
                            }
                        }
                    }
                }
            }
        }
    }

    public function add_meeting()
    {
        if ($_SESSION['username'] == null) {
            return redirect()->to('../Home/index');
        }

        $pertemuan = $this->form05Model->where('id_kelas', $_SESSION['id_kelas'])->findAll();
        $_SESSION['id_form05'] = count($pertemuan) + 1;

        $data = [
            'title' => 'Tambahkan Pertemuan',
        ];

        return view('meetings/add_meeting', $data);
    }

    public function save_meeting()
    {
        $id_matkul = $this->courseModel->where('id', $_SESSION['id_kelas'])->findAll();

        $data = [
            'id_form05' => $_SESSION['id_form05'],
            'id_kelas' => $_SESSION['id_kelas'],
            'id_matkul' => $id_matkul[0]['id_matkul'],
            'hari_tanggal' => $this->request->getVar('hari_tanggal'),
            'pokok_bahasan' => $this->request->getVar('pokok_bahasan'),
            'created_at' => date("Y-m-d H:i:s")
        ];

        $this->form05Model->insert($data);

        return redirect()->to('/Home/meetings/' . $_SESSION['id_kelas']);
    }

    public function set_presence()
    {
        echo "Set Presence";
    }

    public function save_presence()
    {
        $id_matkul = $this->courseModel->where('id', $_SESSION['id_kelas'])->findAll();

        $data = [
            'id_form05' => (int)$_SESSION['id_form05'],
            'id_kelas' => $_SESSION['id_kelas'],
            'id_matkul' => $id_matkul[0]['id_matkul'],
            'id_mhs' => $_SESSION['username'],
            'nama_mhs' => $_SESSION['nama_user'],
            'updated_at' => null

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
    //--------------------------------------------------------------------

}