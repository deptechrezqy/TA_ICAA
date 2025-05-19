<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Siswa extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->model('Isi_data_model');
        $this->load->model('Alternatif_model');
        $this->load->model('Penilaian_model');

        if ($this->session->userdata('id_user_level') != "1") {
            ?>
<script type="text/javascript">
alert('Anda tidak berhak mengakses halaman ini!');
window.location = '<?php echo base_url("Login/home"); ?>'
</script>
<?php
        }
    }

    public function index()
    {
        $data = [
            'page' => "Siswa",
            'list' => $this->Isi_data_model->tampil(),
        ];
        $this->load->view('siswa/index', $data);
    }
    public function valid($nisn)
    {
        // Cek apakah siswa ada
        $user = $this->db->get_where('siswa', ['nisn' => $nisn])->row();

        if (!$user) {
            $this->session->set_flashdata('siswa_message', 'Siswa tidak ditemukan.');
            return redirect('siswa');
        }

        // Update status verifikasi
        $this->db->update('siswa', ['verifikasi_file' => 1], ['nisn' => $nisn]);
        // Insert ke tabel alternatif
        $id_alternatif = $this->Alternatif_model->insert([
            'nama' => $user->nama,
            'siswa_id' => $user->id,

        ]);
        // Mapping kriteria ke field siswa
        $kriteria_map = [
            'C1' => isset($user->file_kip) ? 'Ada' : 'Tidak Ada',
            'C2' => $user->penghasilan_ortu,
            'C3' => $user->jumlah_tanggungan,
            'C4' => $user->kepemilikan_rumah,
            'C5' => $user->nilai_rapor,
        ];
        $kriteria = $this->db->get_where('kriteria', ['kode_kriteria' => 'C2'])->row();


        foreach ($kriteria_map as $kode => $deskripsi) {
            $kriteria = $this->db->get_where('kriteria', ['kode_kriteria' => $kode])->row();
            if (!$kriteria) {
                log_message('error', "Kriteria dengan kode $kode tidak ditemukan.");
                continue;
            }

            $subkriteria = null;
            $subkriteria_list = $this->db->get_where('sub_kriteria', [
                'id_kriteria' => $kriteria->id_kriteria
            ])->result();

            switch ($kode) {
                case 'C5':
                    // Nilai rapor dalam bentuk angka, cocokkan dengan rentang di deskripsi
                    $nilai = (int) $deskripsi;
                    foreach ($subkriteria_list as $sk) {
                        if (preg_match('/(\d+)\s*-\s*(\d+)/', $sk->deskripsi, $matches)) {
                            $min = (int) $matches[1];
                            $max = (int) $matches[2];
                            if ($nilai >= $min && $nilai <= $max) {
                                $subkriteria = $sk;
                                break;
                            }
                        }
                    }
                    break;

                case 'C3':
                    // Jumlah tanggungan (angka atau >N)
                    $jumlah = (int) $deskripsi;
                    foreach ($subkriteria_list as $sk) {
                        if (preg_match('/^>\s*(\d+)/', $sk->deskripsi, $matches)) {
                            if ($jumlah > (int) $matches[1]) {
                                $subkriteria = $sk;
                                break;
                            }
                        } elseif (preg_match('/(\d+)\s*-\s*(\d+)/', $sk->deskripsi, $matches)) {
                            $min = (int) $matches[1];
                            $max = (int) $matches[2];
                            if ($jumlah >= $min && $jumlah <= $max) {
                                $subkriteria = $sk;
                                break;
                            }
                        }
                    }
                    break;

                default:
                    // Kriteria lainnya: cocokan berdasarkan deskripsi string langsung
                    foreach ($subkriteria_list as $sk) {
                        if (trim(strtolower($sk->deskripsi)) === trim(strtolower($deskripsi))) {
                            $subkriteria = $sk;
                            break;
                        }
                    }
                    break;
            }

            if ($subkriteria) {
                $this->Penilaian_model->tambah_penilaian(
                    $id_alternatif,
                    $kriteria->id_kriteria,
                    $subkriteria->id_sub_kriteria
                );
            } else {
                log_message('error', "Subkriteria tidak ditemukan untuk kriteria '$kode' dengan deskripsi '$deskripsi'");
            }
        }

        $this->session->set_flashdata('siswa_message', 'Siswa berhasil diverifikasi.');
        redirect('siswa');
    }

    public function tidakvalid($nisn)
    {
        // Pastikan user ada
        $user = $this->db->get_where('siswa', ['nisn' => $nisn])->row();

        if ($user) {
            // Update kolom verifikasi
            $this->db->where('nisn', $nisn);
            $this->db->update('siswa', ['verifikasi_file' => 2]);

            $this->session->set_flashdata('siswa_message', 'siswa berhasil diverifikasi (Tidak Valid).');
        } else {
            $this->session->set_flashdata('siswa_message', 'siswa tidak ditemukan.');
        }

        redirect('siswa');
    }
}

/* End of file Kategori.php */