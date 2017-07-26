<?php


class M_Layanan extends CI_Model
{

    public function createLayanan($data){
        $this->db->insert('layanan', $data);
    }

    public function getAllLayanan(){
        $this->db->select('*');
        $this->db->from('layanan');
        $query = $this->db->get();

        return $query->result();

        return false;
    }

    public function getLayananByID($id){
        $this->db->select('*');
        $this->db->from('layanan');
        $this->db->where('id', $id);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $result = $query->row();
            return $result;
        }

        return false;
    }
}