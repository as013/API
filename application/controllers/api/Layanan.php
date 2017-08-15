<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . '/libraries/JWT.php';


use \Firebase\JWT\JWT;

class Layanan extends REST_Controller
{

    public function __construct() {
        parent::__construct();
        $this->load->model('M_Kategori_Layanan');
        $this->load->model('M_Auth_Token');
        $this->load->model('M_Users');
        $this->load->model('M_Users_ROles');
        $this->load->model('M_Layanan');
    }

    public function create_kategori_post(){
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

        $layanan = $this->post('nama_kategori');
        $deskripsi = $this->post('deskripsi');

        if(!$layanan){
            $status = false;
            $msg = "Missing parameter." . $layanan;
            $this->response(restData($status, $msg, ''), REST_Controller::HTTP_NOT_FOUND);
        }

        $data = [
            'kategori_layanan'  => $layanan,
            'deskripsi'  => $deskripsi
        ];

        $create = $this->M_Kategori_Layanan->create($data);
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

    public function update_kategori_post(){
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

        $layanan = $this->post('nama_kategori');
        $deskripsi = $this->post('deskripsi');
        $id = $this->post('id');

        if(!$layanan && !$id){
            $status = false;
            $msg = "Missing parameter.";
            $this->response(restData($status, $msg, ''), REST_Controller::HTTP_NOT_FOUND);
        }

        $data = [
            'kategori_layanan'  => $layanan,
            'deskripsi'  => $deskripsi
        ];

        $create = $this->M_Kategori_Layanan->update($data, $id);
        if($create > 0){
            $status = true;
            $msg = "Successfully update.";

            $this->set_response(restData($status, $msg, ''), REST_Controller::HTTP_OK);
        }else{
            $status = false;
            $msg = "Error update.";

            $this->set_response(restData($status, $msg, ''), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function getAllKategori_get(){
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

        $allLayanan = $this->M_Kategori_Layanan->getAll();
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
        $kategori_layanan = $this->post('kategori_layanan');
        $harga = $this->post('harga');
        $deskripsi = $this->post('deskripsi');

        if(!$layanan){
            $status = false;
            $msg = "Missing parameter." . $layanan;
            $this->response(restData($status, $msg, ''), REST_Controller::HTTP_NOT_FOUND);
        }

        $data = [
            'layanan'  => $layanan,
            'id_kategori_layanan'  => $kategori_layanan,
            'harga'  => $harga,
            'deskripsi'  => $deskripsi
        ];

        $create = $this->M_Layanan->create($data);
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

    public function update_post(){
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
        $kategori_layanan = $this->post('kategori_layanan');
        $harga = $this->post('harga');
        $deskripsi = $this->post('deskripsi');
        $id = $this->post('id');

        if(!$layanan && !$id){
            $status = false;
            $msg = "Missing parameter.";
            $this->response(restData($status, $msg, ''), REST_Controller::HTTP_NOT_FOUND);
        }

        $data = [
            'layanan'  => $layanan,
            'id_kategori_layanan'  => $kategori_layanan,
            'harga'  => $harga,
            'deskripsi'  => $deskripsi
        ];

        $create = $this->M_Layanan->update($data, $id);
        if($create > 0){
            $status = true;
            $msg = "Successfully update.";

            $this->set_response(restData($status, $msg, ''), REST_Controller::HTTP_OK);
        }else{
            $status = false;
            $msg = "Error update.";

            $this->set_response(restData($status, $msg, ''), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function layanan_by_kategori_get(){
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

        $cat_id = $this->get('cat_id');
        if(!$cat_id){
            $status = false;
            $msg = "Missing Parameter.";
            $this->response(restData($status, $msg, ''), REST_Controller::HTTP_NOT_FOUND);
        }

        $kategori = $this->M_Kategori_Layanan->getKategoriByID($cat_id);
        if(!$kategori){
            $status = false;
            $msg = "Missing Parameter.";
            $this->response(restData($status, $msg, ''), REST_Controller::HTTP_NOT_FOUND);
        }

        $allLayanan = $this->M_Layanan->getAllByKategori($cat_id);
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

}