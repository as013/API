<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 20/07/2017
 * Time: 00.23
 */
class M_Talent extends CI_Model
{

    public function createTalent($data){
        $this->db->insert('talent', $data);
    }

    public function getTalentByID($id){
        $this->db->select('*');
        $this->db->from('talent');
        $this->db->where('id', $id);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $result = $query->row();
            return $result;
        }

        return false;
    }

    public function getTalentByUsername($username){
        $this->db->select('*');
        $this->db->from('talent');
        $this->db->where('talent', $username);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $result = $query->row();
            return $result;
        }

        return false;
    }
}