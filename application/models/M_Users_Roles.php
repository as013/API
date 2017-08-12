<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 06/08/2017
 * Time: 14.50
 */
class M_Users_Roles extends CI_Model
{
    public function createUserRole($data){
        $this->db->insert('user_role', $data);
    }

    public function getUserRoleByUser($user_id){
        $this->db->select('*');
        $this->db->from('user_role');
        $this->db->join('roles', 'roles.id = user_role.id_role');
        $this->db->where('user_role.id_user', $user_id);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $result = $query->row();
            return $result;
        }

        return false;
    }
}