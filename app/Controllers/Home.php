<?php

namespace App\Controllers;

session_start();

use App\Models\courseModel;
use App\Models\Form05Model;

class Home extends BaseController
{
	protected $courseModel;
	protected $form05Model;

	public function __construct()
	{
		$this->courseModel = new CourseModel();
		$this->form05Model = new Form05Model();
	}

	public function index()				// finish
	{
		if ($_SESSION['auth'] == null) {
			return redirect()->to('../Home/login');
		} else {
			return redirect()->to('../Home/course');
		}
	}


	public function login()				// finish
	{
		if ($_SESSION['auth'] == null) {
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => "http://103.8.12.212:36880/siakad_api/api/as400/captcha",
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
			$data = ['data' => $res['data']];

			return view('pages/login', $data);
		} else {
			return redirect()->to('../Home/course');
		}
	}

	public function logout()			// finish
	{
		session_unset();
		session_destroy();
		return redirect()->to('../Home/index');
	}

	public function course()			// finish
	{
		if ($_SESSION['auth'] == null) {
			return redirect()->to('../Home/index');
		} else {
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => "103.8.12.212:36880/siakad_api/api/as400/rencanaStudi",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => array('Authorization' => $_SESSION['auth'], 'nim' => $_SESSION['username'], 'semester' => '113'),
			));

			$response = curl_exec($curl);

			curl_close($curl);
			$res = json_decode($response, true);
			$_SESSION['course'] = $res['isi'];
			$data = ['data' => $res['isi']];

			return view('pages/course', $data);
		}
	}

	public function meetings($param)	// finish
	{
		if ($_SESSION['auth'] == null) {
			return redirect()->to('../Home/index');
		} else {
			$_SESSION['id_kelas'] = $param;

			$data = $_SESSION['course'];
			$coursee = [];

			foreach ($data as $d) {
				if ($d['kelas_matkul'] == $param) {
					$coursee = $d;
				}
			}

			$ndd = trim($coursee['dosen_matkul'], " \t<br/>.");
			$nd = explode('-', $ndd);
			$ins = [
				'id' => $coursee['kelas_matkul'],
				'id_matkul' => $coursee['kode_matkul'],
				'nama_matkul' => $coursee['nama__matkul'],
				'nama_dosen' => $nd[1],
				'jml_sks' => $coursee['arcmk_matkul']
			];

			// save course to db
			$this->courseModel->insert($ins);

			$form05 = $this->form05Model->where('id_kelas', $param)->findAll();
			$course = $this->courseModel->where('id', $param)->findAll();
			$pj_nama = $course[0]['nama_pj'];
			$pj_id = $course[0]['id_pj'];

			$data = [
				'title' => "Daftar Pertemuan",
				'course' => $course,
				'form05' => $form05,
				'nama_pj' => $pj_nama,
				'id_pj' => $pj_id
			];

			echo view('pages/meetings', $data);

			if ($_SESSION['mode'] == 9) {
				if ($course[0]['id_pj'] == null) {
					return view('button/pj_button');
				}
			} else {
				if ($course[0]['id_pj'] != null) {
					return view('button/add_button');
				}
			}
		}
	}

	public function mau()				// finish
	{
		$this->courseModel->where('id', $_SESSION['id_kelas'])->set(['id_pj' => $_SESSION['username'], 'nama_pj' => $_SESSION['name']])->update();
		return redirect()->to('../Home/course');
	}

	//--------------------------------------------------------------------

}