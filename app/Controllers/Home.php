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

	public function index()						// SELESAI //
	{
		if ($_SESSION['auth'] == null) {		// user belum login -> halaman pages/login

			// mengambil captcha di web-services
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
		} else {								// user sudah login -> controller course
			return redirect()->to('/course');
		}
	}

	//--------------------------------------------------------------------

}