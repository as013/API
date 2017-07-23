<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 20/07/2017
 * Time: 00.24
 */
class M_Auth_Token extends CI_Model
{
    public function create_token($data){
        $this->db->insert('auth_token', $data);
    }

    public function check_auth_token($auth_token){
        $this->db->select('*');
        $this->db->from('auth_token');
        $this->db->where('auth_token', $auth_token);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $result = $query->row();
            return $result;
        }

        return false;
    }

    public function delete_token($id){
        $this->db->where('id', $id);
        $this->db->delete('auth_token');
    }

    public function is_user_login($id){
        $this->db->select('*');
        $this->db->from('auth_token');
        $this->db->where('user_id', $id);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $result = $query->row();
            return $result;
        }

        return false;
    }
}