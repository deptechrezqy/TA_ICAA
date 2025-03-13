<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Isi_data extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->model('Isi_data_model');

        if ($this->session->userdata('id_user_level') != "2") {
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
        $this->load->model('Isi_data_model');

        // Ambil data siswa sesuai user yang sedang login
        $user_id = $this->session->userdata('id_user');
        $siswa = $this->Isi_data_model->get_siswa_by_user($user_id);

        // Perbaiki pengambilan data ENUM
        $data['penghasilan_ortu'] = $this->Isi_data_model->get_enum_penghasilan();
        $data['kepemilikan_rumah'] = $this->Isi_data_model->get_enum_kepemilikan();

        // Jika siswa tidak ada, set sebagai NULL untuk menghindari error
        $data['siswa'] = $siswa ? $siswa : null;

        // **Tambahkan variabel $page untuk menghindari error**
        $data['page'] = "Isi_data";

        // Load view index
        $this->load->view('Isi_data/index', $data);
    }



    //menampilkan view create
    public function create()
    {
        $this->load->model('Isi_data_model');

        $data['penghasilan_ortu'] = $this->Isi_data_model->get_enum_penghasilan();
        $data['kepemilikan_rumah'] = $this->Isi_data_model->get_enum_kepemilikan();

        // Ambil data siswa
        $data['page'] = "Isi_data";
        $data['list'] = $this->Isi_data_model->tampil();

        // Load view utama yang menampilkan semua data
        $this->load->view('Isi_data/create', $data);
    }

    //menambahkan data ke database
    public function store()
    {
        $nisn = $this->input->post('nisn');

        // Cek apakah NISN sudah ada
        if ($this->Isi_data_model->check_nisn_exists($nisn)) {
            $this->session->set_flashdata('message', '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Perhatian!</strong> NISN sudah terdaftar! Silakan gunakan NISN lain.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>');
            redirect('Isi_data/create');
            return;
        }

        // Validasi form
        // Validasi form input
        $this->form_validation->set_rules('nisn', 'NISN', 'required');
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('penghasilan_ortu', 'Penghasilan Orang Tua', 'required');
        $this->form_validation->set_rules('jumlah_tanggungan', 'Jumlah Tanggungan', 'required|integer');
        $this->form_validation->set_rules('kepemilikan_rumah', 'Kepemilikan Rumah', 'required');
        $this->form_validation->set_rules('nilai_rapor', 'Nilai Rata-Rata Rapor', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message', '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Data gagal disimpan, harap periksa kembali inputan Anda.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>');
            redirect('Isi_data/index');
            return;
        }


        // Konfigurasi upload file
        $config['upload_path']   = './public/uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size']      = 2048; // 2MB
        $config['file_name']     = time() . '_' . $_FILES["file_kip"]['name']; // Nama unik

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file_kip')) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('message', '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Upload Gagal!</strong> ' . $error . '
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>');
            redirect('Isi_data/index');
            return;
        }

        $upload_data = $this->upload->data();
        $file_kip = $upload_data['file_name'];

        // Data yang akan disimpan
        $data = [
            'nisn'              => $nisn,
            'nama'              => $this->input->post('nama'),
            'penghasilan_ortu'  => $this->input->post('penghasilan_ortu'),
            'jumlah_tanggungan' => $this->input->post('jumlah_tanggungan'),
            'kepemilikan_rumah' => $this->input->post('kepemilikan_rumah'),
            'nilai_rapor'       => $this->input->post('nilai_rapor'),
            'file_kip'          => $file_kip,
            'user_id'           => $this->session->userdata('id_user') // Pastikan session menyimpan id_user
        ];

        // Simpan data ke database
        if ($this->Isi_data_model->insert($data)) {
            $this->session->set_flashdata('message', '
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data berhasil disimpan.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>');
        } else {
            $this->session->set_flashdata('message', '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Data gagal disimpan.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>');
        }

        redirect('Isi_data');
    }

    public function edit($id_kriteria)
    {
        $data['page'] = "Kriteria";
        $data['kriteria'] = $this->Kriteria_model->show($id_kriteria);
        $this->load->view('kriteria/edit', $data);
    }

    public function update($id_kriteria)
    {
        // TODO: implementasi update data berdasarkan $id_kriteria
        $id_kriteria = $this->input->post('id_kriteria');
        $data = array(
            'keterangan' => $this->input->post('keterangan'),
            'kode_kriteria' => $this->input->post('kode_kriteria'),
            'jenis' => $this->input->post('jenis')
        );

        $this->Kriteria_model->update($id_kriteria, $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diupdate!</div>');
        redirect('Kriteria');
    }

    public function destroy($id_kriteria)
    {
        $this->Kriteria_model->delete($id_kriteria);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
        redirect('Kriteria');
    }
}
