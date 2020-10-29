<?php

namespace App\Controllers;

session_start();

class Course extends BaseController
{
    public function index()            // finish
    {
        if ($_SESSION['auth'] == null) {        // user belum login -> base_url() -> controller home
            return redirect()->to('/');
        } else {                                // user sudah login -> halaman pages/course

            // mengambil data krs dari web-services
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

    //--------------------------------------------------------------------

}