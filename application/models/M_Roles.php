<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 20/07/2017
 * Time: 00.23
 */
class M_Roles extends CI_Model
{
    public function getRolesByRole($role){
        $this->db->select('*');
        $this->db->from('roles');
        $this->db->where('role', $role);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $result = $query->row();
            return $result;
        }

        return false;
    }

    public function getRolesByID($id){
        $this->db->select('*');
        $this->db->from('roles');
        $this->db->where('id', $id);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $result = $query->row();
            return $result;
        }

        return false;
    }
}