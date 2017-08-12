<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . '/libraries/JWT.php';
/**
 * Created by PhpStorm.
 * User: User
 * Date: 20/07/2017
 * Time: 00.37
 */
use \Firebase\JWT\JWT;

class Users extends REST_Controller
{

    public function __construct() {
        parent::__construct();
        $this->load->model('M_Users');
        $this->load->model('M_Auth_Token');
        $this->load->model('M_Roles');
    }

    public function sign_up_post(){
        $headers = $this->input->request_headers();

        $api_key = null;
        if (isset($headers['X-API-KEY'])){
            $api_key = $headers['X-API-KEY'];
        }

        if($api_key != $this->config->item('API_KEY')){
            $status = false;
            $msg = "Invalid API KEY.";
            $this->response(restData($status, $msg, ''), REST_Controller::HTTP_NOT_FOUND);
        }

        $username = $this->post('username');
        $password = $this->post('password');
        $email = $this->post('email');
        $no_tlp = $this->post('no_telp');

        if(!$username && !$password && !$email && !$no_tlp){
            $status = false;
            $msg = "Missing parameter.";
            $this->response(restData($status, $msg, ''), REST_Controller::HTTP_NOT_FOUND);
        }

        if($this->M_Users->getUserByUsername($username)){
            $status = false;
            $msg = "Username already exist.";
            $this->response(restData($status, $msg, ''), REST_Controller::HTTP_NOT_FOUND);
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $status = false;
            $msg = "Invalid email address";
            $this->response(restData($status, $msg, ''), REST_Controller::HTTP_NOT_FOUND);
        }

        if($this->M_Users->getUserByEmail($email)){
            $status = false;
            $msg = "Email already exist.";
            $this->response(restData($status, $msg, ''), REST_Controller::HTTP_NOT_FOUND);
        }

        $roles = $this->M_Roles->getRolesByRole('ROLE_USER');

        $verfied_code = $this->encrypt->encode($username."_".$email, $this->config->item('SECRET_KEY'));

        $data = array(
            'username'  => $username,
            'password'  => md5(md5($password)),
            'email'     => $email,
            'no_telp'   => $no_tlp,
            'is_verified'   => 0,
            'verified_code' => $verfied_code,
            'role'      => $roles->id
        );

        $res = $this->M_Users->createUser($data);
        $status = true;
        $message = "User created succesfully.";
        $this->set_response(restData($status, $message, ''), REST_Controller::HTTP_OK);
    }

    public function login_post() {
        $headers = $this->input->request_headers();

        $api_key = null;
        if (isset($headers['X-API-KEY'])){
            $api_key = $headers['X-API-KEY'];
        }

        if($api_key != $this->config->item('API_KEY')){
            $status = false;
            $msg = "Invalid API KEY.";
            $this->response(restData($status, $msg, ''), REST_Controller::HTTP_NOT_FOUND);
        }

        $username = $this->post('username');
        $password = $this->post('password');

        if(!$username || !$password){
            $status = false;
            $msg = "Invalid parameter.";
            $this->response(restData($status, $msg, ''), REST_Controller::HTTP_NOT_FOUND);
        }

        $user = $this->M_Users->login($username,$password);

        if($user) {
            $is_login = $this->M_Auth_Token->is_user_login($user->id);
            $access_token = $is_login->auth_token;

            if(!$is_login){
                $date = new DateTime();
                $token = [
                    'id'        => $user->id,
                    'username'  => $user->username,
                    'email'     => $user->email,
                    'iat'       => $date->getTimestamp(),
                    'exp'       => $date->getTimestamp() + 60*60*5
                ];

                $access_token = JWT::encode($token, $this->config->item('SECRET_KEY'));

                $data = [
                    'user_id'       => $user->id,
                    'auth_token'    => $access_token
                ];

                $this->M_Auth_Token->create_token($data);
            }

            $status = true;
            $data = [
                'username'      => $user->username,
                'email'         => $user->email,
                'access_token'  => $access_token
            ];

            $this->set_response(restData($status, '', $data), REST_Controller::HTTP_OK);
        }else{
            $status = false;
            $msg = "Invalid parameter.";
            $this->set_response(restData($status, $msg, ''), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function user_info_get(){
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

        $user = $this->M_Users->getUserByID($userToken->user_id);
        $status = true;
        $data = [
            'username'  => $user->username,
            'email'     => $user->email,
            'no_telp'   => $user->no_telp
        ];

        $this->set_response(restData($status, '', $data), REST_Controller::HTTP_OK);
    }

    public function logout_get(){
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

        $this->M_Auth_Token->delete_token($userToken->id);
        $status = true;
        $msg = "Logout successfully.";

        $this->set_response(restData($status, $msg, ''), REST_Controller::HTTP_OK);
    }
}