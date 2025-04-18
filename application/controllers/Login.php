<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Login_model');
        $this->load->model('User_model');
    }
    public function index()
    {
        if ($this->Login_model->logged_id()) {
            redirect('Login/home');
        } else {
            $this->load->view('login');
        }
    }

    public function login()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $passwordx = md5($password);

        // Gunakan Login_model untuk login (verifikasi = 1)
        $user = $this->Login_model->login($username, $passwordx);

        if ($user) {
            // Login sukses
            $log = [
                'id_user' => $user->id_user,
                'username' => $user->username,
                'id_user_level' => $user->id_user_level,
                'status' => 'Logged'
            ];
            $this->session->set_userdata($log);
            redirect('Login/home');
        } else {
            // Kalau gagal login, cek apakah user belum terverifikasi
            $check_user = $this->db->get_where('user', ['username' => $username])->row();
            if ($check_user && $check_user->verifikasi == 0) {
                $this->session->set_flashdata('login_message', 'Akun Anda belum terverifikasi.');
            } else {
                $this->session->set_flashdata('login_message', 'Username atau Password salah.');
            }
            redirect('login');
        }
    }


    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }

    public function home()
    {
        $data['page'] = "Dashboard";
        $this->load->view('admin/index', $data);
    }
}

/* End of file Login.php */