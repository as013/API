<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 20/07/2017
 * Time: 00.23
 */
class M_Users extends CI_Model
{
    public function login($username, $password){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username', $username);
        $this->db->where('password', md5(md5($password)));
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $result = $query->row();
            return $result;
        }

        return false;
    }

    public function createUser($data){
        $this->db->insert('users', $data);
    }

    public function getUserByID($id){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id', $id);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $result = $query->row();
            return $result;
        }

        return false;
    }

    public function getUserByUsername($username){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username', $username);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $result = $query->row();
            return $result;
        }

        return false;
    }
}