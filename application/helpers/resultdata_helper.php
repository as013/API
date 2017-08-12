<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/08/2017
 * Time: 13.48
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('restData'))
{
    function restData($status, $msg, $data)
    {
        $stat = "Error";
        $success = false;

        if($status){
            $stat = "Success";
            $success = true;
        }

        $message = '';
        if($msg){
            $message = $msg;
        }

        $dt = '';
        if($data){
            $dt = $data;
        }

        $res = [
            'status'    => $stat,
            'success'   => $success,
            'message'   => $message,
            'data'      => $dt
        ];

        return $res;
    }
}