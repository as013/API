<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . '/libraries/JWT.php';


use \Firebase\JWT\JWT;

class Layanan extends REST_Controller
{

    public function __construct() {
        parent::__construct();
        $this->load->model('M_Layanan');
        $this->load->model('M_Auth_Token');
    }

    public function layanan_create_post(){
        $headers = $this->input->request_headers();
        $auth_token = null;
        if (isset($headers['X-Auth-Token'])){
            $auth_token = $headers['X-Auth-Token'];
        }

        $invalid_token = ['invalid' => 'Token is invalid'];
        if(!$auth_token){
            $this->set_response($invalid_token, REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $userToken = $this->M_Auth_Token->check_auth_token($auth_token);
        if(!$userToken){
            $this->set_response($invalid_token, REST_Controller::HTTP_UNAUTHORIZED);
            return;
        }

        $layanan = $this->post('layanan');
        $deskripsi = $this->post('deskripsi');

        $invalid_parameter = [
            'success' => FALSE,
            'message' => 'Invalid parameter'
        ];

        if(!$layanan && !$deskripsi){
            $this->response($invalid_parameter, REST_Controller::HTTP_NOT_FOUND);
            return;
        }

        // Harus ada juga ini ?
        //if($this->M_Layanan->getLayananByUsername($username)){
        //    $invalid_parameter['message'] = "Username already exist.";
        //    $this->response($invalid_parameter, REST_Controller::HTTP_NOT_FOUND);
        //    return;
        //}

        $data = [
            'layanan'  => $layanan,
            'deskripsi'  => $deskripsi
        ];

        $this->M_Layanan->createLayanan($data);
        $output = [
            'success' => TRUE
        ];
        $this->set_response($output, REST_Controller::HTTP_OK);
    }

    public function layanan_all_get(){
        $headers = $this->input->request_headers();
        $auth_token = null;
        if (isset($headers['X-Auth-Token'])){
            $auth_token = $headers['X-Auth-Token'];
        }

        $invalid_token = ['invalid' => 'Token is invalid'];
        if(!$auth_token){
            $this->set_response($invalid_token, REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $userToken = $this->M_Auth_Token->check_auth_token($auth_token);
        if(!$userToken){
            $this->set_response($invalid_token, REST_Controller::HTTP_UNAUTHORIZED);
            return;
        }

        $allLayanan = $this->M_Layanan->getAllLayanan();
        $output = [
            'id'  => $allLayanan[0]->id,
            'layanan'     => $allLayanan[0]->layanan,
            'deskripsi'     => $allLayanan[0]->deskripsi,
        ];
        $this->set_response($allLayanan, REST_Controller::HTTP_OK);
    }

    public function layanan_info_get(){
        $headers = $this->input->request_headers();
        $auth_token = null;
        if (isset($headers['X-Auth-Token'])){
            $auth_token = $headers['X-Auth-Token'];
        }


        $idLayanan = $this->get('idLayanan');

        $invalid_token = ['invalid' => 'Token is invalid'];
        if(!$auth_token){
            $this->set_response($invalid_token, REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $userToken = $this->M_Auth_Token->check_auth_token($auth_token);
        if(!$userToken){
            $this->set_response($invalid_token, REST_Controller::HTTP_UNAUTHORIZED);
            return;
        }

        $layanan = $this->M_Layanan->getLayananByID($idLayanan);
        $output = [
            'layanan'     => $layanan->layanan,
            'deskripsi'     => $layanan->deskripsi,
        ];
        $this->set_response($output, REST_Controller::HTTP_OK);
    }

}