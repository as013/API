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
        $sqlInsertUser = "INSERT INTO users (username, password, email, is_verified, verified_code, no_telp)
                VALUES ('".$data['username']."', '".$data['password']."', '".$data['email']."',".$data['is_verified'].", '".$data['verified_code']."','".$data['no_telp']."')";

        $this->db->trans_start();

        $this->db->query($sqlInsertUser);
        $userId = $this->db->insert_id();

        $sqlInsertUserRole = "INSERT INTO user_role (id_user, id_role) VALUES (".$userId.", ".$data['role'].")";
        $this->db->query($sqlInsertUserRole);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            return false;
        }else{
            return true;
        }
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

    public function getUserByEmail($email){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email', $email);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $result = $query->row();
            return $result;
        }

        return false;
    }
}