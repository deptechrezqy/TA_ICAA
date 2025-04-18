<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Isi_data_model extends CI_Model
{

    public function tampil()
    {
        $query = $this->db->get('siswa');
        return $query->result();
    }

    public function get_siswa_by_user($user_id)
    {
        return $this->db->get_where('siswa', ['user_id' => $user_id])->row();
    }
    public function get_by_id($id)
    {
        return $this->db->get_where('siswa', ['id' => $id])->row();
    }
    public function insert($data = [])
    {
        return $this->db->insert('siswa', $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('siswa', $data);
    }
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('siswa');
    }

    // Tambahkan fungsi ini untuk mengambil nilai ENUM penghasilan_orang_tua
    public function get_enum_penghasilan()
    {
        $query = $this->db->query("SHOW COLUMNS FROM siswa LIKE 'penghasilan_ortu'");
        $row = $query->row();
        preg_match('/^enum\((.*)\)$/', $row->Type, $matches);
        return str_getcsv($matches[1], ",", "'"); // Mengubah ENUM ke array
    }


    // Tambahkan fungsi ini untuk mengambil nilai ENUM Kepemilikan rumah
    public function get_enum_kepemilikan()
    {
        $query = $this->db->query("SHOW COLUMNS FROM siswa LIKE 'kepemilikan_rumah'");
        $row = $query->row();
        preg_match('/^enum\((.*)\)$/', $row->Type, $matches);
        return str_getcsv($matches[1], ",", "'"); // Mengubah ENUM ke array
    }

    public function check_nisn_exists($nisn)
    {
        $query = $this->db->get_where('siswa', ['nisn' => $nisn]);
        return $query->num_rows() > 0;
    }

    //cek user udah isi data atau belum
    public function check_user_has_data($user_id)
    {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('siswa'); // Sesuaikan dengan nama tabel siswa
        return $query->num_rows() > 0;
    }
}