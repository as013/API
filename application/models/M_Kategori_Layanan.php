<?php


class M_Kategori_Layanan extends CI_Model
{

    public function create($data){
        $this->db->insert('kategori_layanan', $data);
        return $this->db->affected_rows();
    }

    public function update($data, $id){
        $this->db->where('id', $id);
        $this->db->update('kategori_layanan', $data);
        return $this->db->affected_rows();
    }

    public function getAll(){
        $this->db->select('*');
        $this->db->from('kategori_layanan');
        $query = $this->db->get();

        return $query->result();
    }

    public function getKategoriByID($id){
        $this->db->select('*');
        $this->db->from('kategori_layanan');
        $this->db->where('id', $id);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $result = $query->row();
            return $result;
        }

        return false;
    }
}