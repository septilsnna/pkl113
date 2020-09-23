<?php

namespace App\Controllers;

session_start();

use App\Models\courseModel;
use App\Models\Form05Model;
use App\Models\UsersModel;

class Home extends BaseController
{
	protected $courseModel;
	protected $form05Model;
	protected $user;

	public function __construct()
	{
		$this->courseModel = new CourseModel();
		$this->form05Model = new Form05Model();
		$this->users = new UsersModel();
	}

	public function index()
	{
		if ($_SESSION['username'] == null) {
			$data = ['title' => 'Silahkan Login'];
			return view('pages/login', $data);
		} else {
			return redirect()->to('../Home/course');
		}
	}


	public function login()
	{
		// dapatkan username and password dari input
		$username = $this->request->getVar('username');
		$password = $this->request->getVar('password');

		// check user dan pass ada di db
		$check = $this->users->where(array('username' => $username, 'password' => $password))->findAll();

		if ($check != null) {
			// kalo ada set session, arahin ke halaman course
			$_SESSION['username'] = $username;
			$_SESSION['nama_user'] = $check[0]['nama_user'];
			$_SESSION['role'] = $check[0]['role'];
			return redirect()->to('../Home/course');
		} else {
			echo "username/password tidak sesuai";
			return redirect()->to('../Home/index');
		}
	}

	public function logout()
	{
		session_unset();
		session_destroy();
		return redirect()->to('../Home/index');
	}

	public function course()
	{
		if ($_SESSION['username'] == null) {
			return redirect()->to('../Home/index');
		}

		$id_user = $_SESSION['username'];

		// read data course yang diambil oleh user $id_user
		$userss = $this->users->where('username', $id_user)->findAll();
		$user = $userss[0];

		// memecah string dengan delimeter ", " pada variable $user
		$arr_id = explode(", ", $user['course']);

		// declare array untuk menampung data course
		$course = array();

		foreach ($arr_id as $aid) :
			$course[] = $this->courseModel->where('id', $aid)->findAll();
		endforeach;

		$data = [
			'title' => "Daftar Mata Kuliah",
			'user' => $user,
			'course' => $course
		];

		if ($_SESSION['role'] == 1) {
			return view('pages/course_dosen', $data);
		} else {
			return view('pages/course', $data);
		}
	}

	public function meetings($param)
	{
		if ($_SESSION['username'] == null) {
			return redirect()->to('../Home/index');
		}

		$_SESSION['id_kelas'] = $param;

		$form05 = $this->form05Model->where('id_kelas', $param)->findAll();
		$course = $this->courseModel->where('id', $param)->findAll();
		$pj = $this->users->where('username', $course[0]['id_pj'])->findAll();
		//var_dump($pj[0]['nama_user']);

		$data = [
			'title' => "Daftar Pertemuan",
			'course' => $course,
			'form05' => $form05,
			'nama_pj' => $pj[0]['nama_user'],
			'id_pj' => $pj[0]['username']
		];

		echo view('pages/meetings', $data);

		if ($_SESSION['role'] == "2") {
			if ($course[0]['id_pj'] == null) {
				return view('button/pj_button');
			}
		} else {
			if ($course[0]['id_pj'] != null) {
				return view('button/add_button');
			}
		}
	}

	public function mau()
	{
		$this->courseModel->where('id', $_SESSION['id_kelas'])->set(['id_pj' => $_SESSION['username']])->update();
		return redirect()->to('../Home/course');
	}

	public function coba()
	{
		/* create & initialize a curl session
		$curl = curl_init();

		// set our url with curl_setopt()
		curl_setopt($curl, CURLOPT_URL, 'http://103.8.12.212:36880/siakad_api/api/as400/captcha');

		// return the transfer as a string, also with setopt()
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		// curl_exec() executes the started curl session
		// $output contains the output string
		$output = curl_exec($curl);
		$result = json_decode($output);
		var_dump($result);

		// close curl resource to free up system resources
		// (deletes the variable made by curl_init)
		curl_close($curl);*/

		//return view('pages/coba');

		/* API URL */
		$url = 'http://103.8.12.212:36880/siakad_api/api/as400/login';

		/* Init cURL resource */
		$ch = curl_init($url);

		/* Array Parameter Data */
		$data = [
			'username' => '1313617028',
			'password' => '123456789',
			'captcha_id' => '1',
			'securid' => '3'
		];

		/* pass encoded JSON string to the POST fields */
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		/* set the content type json */
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type:application/json',
			'App-Key: 123456',
			'App-Secret: 1233'
		));

		/* set return type json */
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		/* execute request */
		$result = curl_exec($ch);
		var_dump($result);

		/* close cURL resource */
		curl_close($ch);
	}

	//--------------------------------------------------------------------

}