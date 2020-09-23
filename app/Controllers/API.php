<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class API extends ResourceController
{
    protected $format       = 'json';
    protected $modelName    = 'App\Models\Users';

    public function index()
    {
        return $this->respond($this->model->findAll(), 200);
    }

    public function show($id = NULL)
    {
        $get = $this->model->getCategory($id);
        if ($get) {
            $code = 200;
            $response = [
                'status' => $code,
                'error' => false,
                'data' => $get,
            ];
        } else {
            $code = 401;
            $msg = ['message' => 'Not Found'];
            $response = [
                'status' => $code,
                'error' => true,
                'data' => $msg,
            ];
        }
        return $this->respond($response, $code);
    }
}