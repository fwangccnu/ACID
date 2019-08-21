<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['view_content'] = 'home/home';
        $this->load->vars($data);
        $this->load->view('public/template_home');
    }

    public function guide()
    {
        $this->load_view('home/guide', $data = array());
    }

    public function help()
    {
        $this->load_view('home/help', $data = array());
    }

    public function contact()
    {
        $this->load_view('home/citation', $data = array());
    }

    public function download() {
        $this->load_view('home/download', $data = array());
    }

    public function suggestion()
    {
        $this->form_validation->set_rules('email', "E-mail", 'required');
        $this->form_validation->set_rules('suggestion', "Suggestions Problems", 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('alert', validation_errors());
            redirect('home/contact');
        } else {
            $email = $this->security->xss_clean($this->input->post('email'));
            $suggestion = $this->security->xss_clean($this->input->post('suggestion'));

            shell_exec('python resource/plugin/sendmail/sendmail.py '.escapeshellarg($email.' : '.$suggestion));
            $this->session->set_flashdata('success', $this->lang->line('success_sendmail'));
            redirect('home/contact', 'refresh');
        }
    }

    public function check_server_keywords(){
        echo "chemyang.ccnu.edu.cn is ok!";
    }

}

