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
        $this->load->model('M_Users');
        $this->load->model('M_Users_ROles');
    }

    public function create_post(){
        $headers = $this->input->request_headers();
        $auth_token = null;
        if (isset($headers['X-Auth-Token'])){
            $auth_token = $headers['X-Auth-Token'];
        }

        if(!$auth_token){
            $status = false;
            $msg = "Token is invalid";
            $this->response(restData($status, $msg, ''), REST_Controller::HTTP_NOT_FOUND);
        }

        $userToken = $this->M_Auth_Token->check_auth_token($auth_token);
        if(!$userToken){
            $status = false;
            $msg = "Token is invalid";
            $this->response(restData($status, $msg, ''), REST_Controller::HTTP_NOT_FOUND);
        }

        $checkUserRole = $this->M_Users_ROles->getUserRoleByUser($userToken->user_id);
        if($checkUserRole->role != $this->config->item('ROLE_ADMIN')){
            $status = false;
            $msg = "Unauthorized.";
            $this->response(restData($status, $msg, $checkUserRole), REST_Controller::HTTP_UNAUTHORIZED);
        }

        $layanan = $this->post('layanan');
        $deskripsi = $this->post('deskripsi');

        if(!$layanan){
            $status = false;
            $msg = "Missing parameter." . $layanan;
            $this->response(restData($status, $msg, ''), REST_Controller::HTTP_NOT_FOUND);
        }

        $data = [
            'layanan'  => $layanan,
            'deskripsi'  => $deskripsi
        ];

        $create = $this->M_Layanan->createLayanan($data);
        if($create > 0){
            $status = true;
            $msg = "Successfully saved.";

            $this->set_response(restData($status, $msg, ''), REST_Controller::HTTP_OK);
        }else{
            $status = false;
            $msg = "Error saving.";

            $this->set_response(restData($status, $msg, ''), REST_Controller::HTTP_NOT_FOUND);
        }

    }

    public function getAll_get(){
        $headers = $this->input->request_headers();
        $auth_token = null;
        if (isset($headers['X-Auth-Token'])){
            $auth_token = $headers['X-Auth-Token'];
        }

        if(!$auth_token){
            $status = false;
            $msg = "Token is invalid";
            $this->response(restData($status, $msg, ''), REST_Controller::HTTP_NOT_FOUND);
        }

        $userToken = $this->M_Auth_Token->check_auth_token($auth_token);
        if(!$userToken){
            $status = false;
            $msg = "Token is invalid";
            $this->response(restData($status, $msg, ''), REST_Controller::HTTP_NOT_FOUND);
        }

        $allLayanan = $this->M_Layanan->getAllLayanan();
        if($allLayanan){
            $status = true;
            $msg = "";

            $this->set_response(restData($status, $msg, $allLayanan), REST_Controller::HTTP_OK);
        }else{
            $status = true;
            $msg = "No data.";

            $this->set_response(restData($status, $msg, ''), REST_Controller::HTTP_OK);
        }
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