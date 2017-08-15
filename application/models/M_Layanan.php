<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 13/08/2017
 * Time: 22.13
 */
class M_Layanan extends CI_Model
{
    public function create($data){
        $this->db->insert('layanan', $data);
        return $this->db->affected_rows();
    }

    public function update($data, $id){
        $this->db->where('id', $id);
        $this->db->update('layanan', $data);
        return $this->db->affected_rows();
    }

    public function getAllByKategori($id_kategori){
        $this->db->select('*');
        $this->db->from('layanan');
        $this->db->where('id_kategori_layanan', $id_kategori);
        $query = $this->db->get();

        return $query->result();
    }

    public function getByID($id){
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