<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kriteria_model extends CI_Model
{

    public function tampil()
    {
        $query = $this->db->get('kriteria');
        return $query->result();
    }

    public function insert($data = [])
    {
        $result = $this->db->insert('kriteria', $data);
        return $result;
    }

    public function show($id_kriteria)
    {
        $this->db->where('id_kriteria', $id_kriteria);
        $query = $this->db->get('kriteria');
        return $query->row();
    }

    public function update($id_kriteria, $data = [])
    {
        $ubah = array(
            'keterangan' => $data['keterangan'],
            'kode_kriteria' => $data['kode_kriteria'],
            'jenis' => $data['jenis'],
            'bobot' => $data['bobot']
        );

        $this->db->where('id_kriteria', $id_kriteria);
        $this->db->update('kriteria', $ubah);
    }

    public function delete($id_kriteria)
    {
        $this->db->where('id_kriteria', $id_kriteria);
        $this->db->delete('kriteria');
    }

    public function getLastKodeKriteria()
    {
        $this->db->select('kode_kriteria');
        $this->db->order_by('kode_kriteria', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('kriteria');

        if ($query->num_rows() > 0) {
            return $query->row()->kode_kriteria;
        }

        return null;
    }

}