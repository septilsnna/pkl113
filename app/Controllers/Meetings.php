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

    public function index($param)               // SELESAI
    {
        if ($_SESSION['auth'] == null) {        // user belum login -> base_url() -> controller home
            return redirect()->to('/');
        }                                       // user sudah login -> halaman pages/meetings

        $_SESSION['id_kelas'] = $param;
        $data1 = $_SESSION['course'];
        $course1 = [];

        // mengambil semua data matkul sesuai id_kelas
        foreach ($data1 as $d) {
            if ($d['kelas_matkul'] == $param) {
                $course1 = $d;
            }
        }

        $nidn =  explode('-', $course1['dosen_matkul']);                                // nidn
        $nama_dosen = explode('-', trim($course1['dosen_matkul'], " \t<br/>."));        // nama_dosen

        // mengambil data hari, jam mulai, dan jam selesai, sesuai id_kelas di web-services
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://103.8.12.212:36880/siakad_api/api/as400/penjadwalanDosen/" . $nidn[0] . "/113/" . $_SESSION['auth'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $res = json_decode($response, true);

        // mengambil semua data matkul sesuai id_kelas
        foreach ($res['isi'] as $d) {
            if ($d['kelas'] == $param) {
                $course1['hari'] = $d['hari'];
                $course1['awal'] = $d['awal'];
                $course1['akhir'] = $d['akhir'];
            }
        }

        // menyimpan data mata kuliah ke database
        $ins = [
            'id' => $course1['kelas_matkul'],
            'id_matkul' => $course1['kode_matkul'],
            'nama_matkul' => $course1['nama__matkul'],
            'nama_dosen' => $nama_dosen[1],
            'jml_sks' => $course1['arcmk_matkul'],
            'hari' => $course1['hari'],
            'jam_mulai' => $course1['awal'],
            'jam_selesai' => $course1['akhir']
        ];
        $this->courseModel->insert($ins);

        // mengambil data form05 dan course untuk ditampilkan di halaman meetings
        $form05_mhs = $this->form05Model->where(array('id_kelas' => $param, 'status' => 1))->findAll();
        $form05_dsn = $this->form05Model->where('id_kelas', $param)->findAll();
        $course = $this->courseModel->where('id', $param)->findAll();
        $pj_nama = $course[0]['nama_pj'];
        $pj_id = $course[0]['id_pj'];

        $data = [
            'title' => "Daftar Pertemuan",
            'course' => $course,
            'nama_pj' => $pj_nama,
            'id_pj' => $pj_id,
        ];

        if ($_SESSION['mode'] == 9) {               // login sebagai mahasiswa
            $data['form05'] = $form05_mhs;
            echo view('pages/meetings', $data);
            if ($course[0]['id_pj'] == null) {
                return view('button/pj_button');
            }
        } else {                                    // login sebagai dosen
            $data['form05'] = $form05_dsn;
            echo view('pages/meetings', $data);
            // if ($course[0]['id_pj'] != null) {
            //     return view('button/add_button');
            // }
        }
    }

    public function detail($param)
    {
        if ($_SESSION['auth'] == null) {
            return redirect()->to('/');
        }

        // mengambil semua data yang sesuai dengan id_form05 pada database
        $form05 = $this->form05Model->where(array('id_form05' => $param, 'id_kelas' => $_SESSION['id_kelas']))->findAll();
        $form06 = $this->form06Model->where(array('id_form05' => $param, 'id_kelas' => $_SESSION['id_kelas']))->findAll();
        $course = $this->courseModel->where('id', $_SESSION['id_kelas'])->findAll();

        $_SESSION['id_form05'] = $param;

        // mengupdate jumlah mahasiswa yang hadir
        // $this->form05Model
        //     ->where(array('id_form05' => $param, 'id_kelas' => $_SESSION['id_kelas']))
        //     ->set(['jml_mhs_hadir' => (int)count($form06)])
        //     ->update();

        // menghitung waktu seputar presensi
        $now = date("Y-m-d H:i:s");
        $start = date_format(date_create($form05[0]['tanggal'] . " " . $form05[0]['jam_mulai']), "Y-m-d H:i:s");
        $wkt = explode(':', $form05[0]['batas_presensi']);
        $end = '';
        if ($form05[0]['batas_presensi'] == '00:00:00') {
            // $end = date("Y-m-d H:i:s", strtotime('+' . ($course[0]['jml_sks'] * 50) . ' minutes', strtotime($start)));       // kalo offline
            $end = date("Y-m-d H:i:s", strtotime('+30 minutes', strtotime($start)));                                            // kalo online
        } else {
            $end = date_format(date_create($form05[0]['tanggal'] . " " . $form05[0]['batas_presensi']), "Y-m-d H:i:s");
        }

        // tanggal bulan tahun, pertemuan
        $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tgl = explode('-', $form05[0]['tanggal']);

        // menyiapkan data yang akan disisipkan
        $data = [
            'title' => 'Detail Pertemuan',
            'form05' => $form05,
            'form06' => $form06,
            'nama_matkul' => $course[0]['nama_matkul'],
            'tanggal' => (int)$tgl[2] . ' ' . $bulan[(int)$tgl[1]] . ' ' . $tgl[0],
            'end' => $end,
        ];

        // cek presensi mhs, kalo != null berarti udah presensi, kalo == null berarti blm presensi
        $presence = $this->form06Model->where(array('id_form05' => $param, 'id_kelas' => $_SESSION['id_kelas'], 'id_mhs' => $_SESSION['username']))->findAll();

        // cek verifikasi dosen, kalo verified = 0 berarti belom verif, kalo verified = 1 berarti udah di verif
        $verify_06 = $this->form06Model->where(array('id_form05' => $param, 'id_kelas' => $_SESSION['id_kelas'], 'verified' => 1))->findAll();

        // cek verifikasi mhs, kalo status = 1 berarti belom verif, kalo status = 2 berarti udah di verif
        $verify_05 = $this->form05Model->where(array('id_form05' => $param, 'id_kelas' => $_SESSION['id_kelas'], 'status' => 2))->findAll();

        // cek mhs pertama yg presensi
        $first = $this->form06Model->where(array('id_form05' => $param, 'id_kelas' => $_SESSION['id_kelas']))->findAll();
        $pj = $this->form06Model->where(array('id_form05' => $param, 'id_kelas' => $_SESSION['id_kelas'], 'id_mhs' => $course[0]['id_pj']))->findAll();

        if ($_SESSION['mode'] != 9) {                     // dosen
            if ($now < $start) {                            // kalo belom mulai presensi
                echo view('pages/meeting_detail', $data);
                echo view('button/edit_button', $data);
                return '<div class="container"><div class="row"><div class="col text-center"><div class="alert alert-info" role="alerrt">Presensi belum dimulai</div></div></div></div>';
            } else if ($now >= $start && $end >= $now) {    // kalo lagi jam perkuliahan
                echo view('pages/meeting_detail', $data);
                return view('meetings/attendance', $data);
            } else {                                        // kalo jam perkuliahan udah selesai
                if ($verify_06 != null) {                   // kalo udah diverif
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
                        if ($verify_05 != null) {           // pj udah presensi & verif
                            return '<div class="container"><div class="row"><div class="col text-center"><div class="alert alert-success" role="alerrt">Anda sudah melakukan verifikasi!</div></div></div></div>';
                        } else {                            // pj udah presensi tp blm verif
                            return view('meetings/verify_meeting', $data);
                        }
                    } else {                                // bukan pj, tp udah presensi
                        if ($pj == null && $first[0]['id_mhs'] == $_SESSION['username']) {
                            echo '<div class="container"><div class="row"><div class="col text-center"><div class="alert alert-warning" role="alert">Hari ini PJ tidak hadir, yuk bantu PJ untuk melakukan verifikasi!</div></div></div></div>';
                            if ($verify_05 != null) {       // pj udah presensi & verif
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

    // public function edit_meeting()
    // {
    //     if ($_SESSION['auth'] == null) {
    //         return redirect()->to('../Home/index');
    //     }

    //     $data = [
    //         'title' => 'Tambahkan Pertemuan',
    //         'pertemuan' => $_SESSION['id_form05']
    //     ];

    //     return view('meetings/edit_meeting', $data);
    // }

    //--------------------------------------------------------------------

}