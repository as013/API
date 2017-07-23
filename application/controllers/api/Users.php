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
    }

    public function sign_up_post(){
        $username = $this->post('username');
        $password = $this->post('password');
        $email = $this->post('email');

        $invalid_parameter = [
            'success' => FALSE,
            'message' => 'Invalid parameter'
        ];

        if(!$username && !$password && !$email){
            $this->response($invalid_parameter, REST_Controller::HTTP_NOT_FOUND);
            return;
        }

        if($this->M_Users->getUserByUsername($username)){
            $invalid_parameter['message'] = "Username already exist.";
            $this->response($invalid_parameter, REST_Controller::HTTP_NOT_FOUND);
            return;
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $invalid_parameter['message'] = "Invalid email address";
            $this->response($invalid_parameter, REST_Controller::HTTP_NOT_FOUND);
            return;
        }

        $data = [
            'username'  => $username,
            'password'  => md5(md5($password)),
            'email'     => $email
        ];

        $this->M_Users->createUser($data);
        $output = [
            'success' => TRUE
        ];
        $this->set_response($output, REST_Controller::HTTP_OK);
    }

    public function login_post() {
        $username = $this->post('username');
        $password = $this->post('password');

        $invalidLogin = ['invalid' => $username];
        if(!$username || !$password) $this->response($invalidLogin, REST_Controller::HTTP_NOT_FOUND);

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

                $access_token = JWT::encode($token, "Ca11m3S3kr3tK3y");

                $data = [
                    'user_id'       => $user->id,
                    'auth_token'    => $access_token
                ];

                $this->M_Auth_Token->create_token($data);
            }

            $output = [
                'username'      => $user->username,
                'email'         => $user->email,
                'access_token'  => $access_token
            ];


            $this->set_response($output, REST_Controller::HTTP_OK);
        }else{
            $this->set_response($invalidLogin, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function user_info_get(){
        $headers = $this->input->request_headers();
        $auth_token = $headers['X-Auth-Token'];

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

        $user = $this->M_Users->getUserByID($userToken->user_id);
        $output = [
            'username'  => $user->username,
            'email'     => $user->email
        ];
        $this->set_response($output, REST_Controller::HTTP_OK);
    }

    public function logout_get(){
        $headers = $this->input->request_headers();
        $auth_token = $headers['X-Auth-Token'];

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

        $this->M_Auth_Token->delete_token($userToken->id);
        $output = [
            'success' => TRUE
        ];

        $this->set_response($output, REST_Controller::HTTP_OK);
    }
}