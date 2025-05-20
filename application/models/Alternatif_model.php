<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Alternatif_model extends CI_Model
{

    public function tampil()
    {
        $query = $this->db->get('alternatif');
        return $query->result();
    }

    public function insert($data = [])
    {
        $this->db->insert('alternatif', $data);
        return $this->db->insert_id(); // ini akan mengembalikan id terakhir yang dimasukkan
    }
    public function get_by_id($id)
    {
        return $this->db->get_where('alternatif', ['id_alternatif' => $id])->row();
    }

    public function show($id_alternatif)
    {
        $this->db->where('id_alternatif', $id_alternatif);
        $query = $this->db->get('alternatif');
        return $query->row();
    }

    public function update($id_alternatif, $data = [])
    {
        $ubah = array(
            'nama' => $data['nama']
        );

        $this->db->where('id_alternatif', $id_alternatif);
        $this->db->update('alternatif', $ubah);
    }


    public function delete($id_alternatif)
    {
        $this->db->where('id_alternatif', $id_alternatif);
        $this->db->delete('alternatif');
    }
}