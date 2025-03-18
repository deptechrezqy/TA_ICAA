<?php
class Register extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->load->view('register');
    }

    public function store()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[user.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required');

        if ($this->form_validation->run() != false) {
            $data = [
                'id_user_level' => 2, // Level default
                'nama' => $this->input->post('nama'),
                'email' => $this->input->post('email'),
                'username' => $this->input->post('username'),
                'password' => md5($this->input->post('password'))
            ];

            if ($this->User_model->insert($data)) {
                $this->session->set_flashdata('message', '<div class="alert alert-success">Akun berhasil dibuat! Silakan login.</div>');
                redirect('Login');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Gagal menyimpan data.</div>');
                redirect('Register');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">' . validation_errors() . '</div>');
            redirect('Register');
        }
    }
}
