<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Druggability extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('MJobs_druggability', 'MDruggability'));
    }


    public function index()
    {
        $condition = array();
        $this->load->model('MJobs_druggability', '', true);
        $total_rows = $this->MJobs_druggability->query_total($condition);
        $data = $this->order_pagination(array('druggability', 'index'), array(array(3, 'submit_time', array('submit_time'), 1, array(1))), array(5, 10, $total_rows));
        $list_job = $this->MJobs_druggability->query($condition, array($data['per_page'], $data['offset']), $data['order_model']);

        $data['total_queue'] = $this->MJobs_druggability->query_total(array(array('status' => 'QUEUE')));
        $data['total_running'] = $this->MJobs_druggability->query_total(array(array('status' => 'RUNNING')));
        $data['total_finished'] = $this->MJobs_druggability->query_total(array(array('status' => 'FINISHED')));

        $data['link'] = $data['link_pagination'];
        $data['list_job'] = $list_job;
        $this->load_view('druggability/list', $data);
    }


    public function add()
    {
        $this->load_view('druggability/new');
    }

    public function save()
    {
        $this->form_validation->set_rules('input_id', $this->lang->line('mark_'), '');
        $this->form_validation->set_rules('task_name', $this->lang->line('mark_'), 'required');
        $this->form_validation->set_rules('email', $this->lang->line('mark_'), '');
        $this->form_validation->set_rules('password', $this->lang->line('mark_'), '');
        $this->form_validation->set_message('required', $this->lang->line('alert_required'));
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('warning', 'Someting is wrong!');
            redirect('druggability/add', 'refresh');
        } else {
            $input_id = $this->security->xss_clean($this->input->post('input_id'));
            $task_name = $this->security->xss_clean($this->input->post('task_name'));
            $email = $this->security->xss_clean($this->input->post('email'));
            $password = $this->security->xss_clean($this->input->post('password'));

            $this->load->helper('randomstr');
            $job_id = random_str('D', 6);
            $path_dir = './uploads/' . $job_id;
            mkdir(iconv("UTF-8", "GBK", $path_dir), 0777, true);

            $file_path = '';
            $file_name = '';
            if (strlen($input_id) > 2) {
                //???
            } else {
                $config['upload_path'] = $path_dir;
                $config['allowed_types'] = 'pdb|mol2';
                $config['max_size'] = '20480';
                $config['overwrite'] = TRUE;
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('userfile')) {
                    $this->session->set_flashdata('alert', 'Upload Errors!');
                    redirect('druggability/add', 'refresh');
                } else {
                    $file_data = $this->upload->data();
                    $file_path = $file_data["raw_name"] . $file_data["file_ext"];
                    $file_name = $file_data["orig_name"];
                }
            }

            $data = array(
                'job_id' => $job_id,
                'file_path' => $file_path,
                'task_name' => $task_name,
                'email' => $email,
                'password' => $password,
                'input_file' => $file_name,
                'submit_time' => date('Y-m-d H:i:s', time())
            );

            $this->MJobs_druggability->insert($data);
            $argv = array("job_id" => '$job_id');
            $result = shell_exec('perl ???' . escapeshellarg(json_encode($argv)));
            $result_data = json_decode($result, TRUE);
            //@@@

            $this->session->set_userdata(array('job_d' => $job_id));
            redirect('druggability/wait/' . $job_id);
        }
    }


    public function wait()
    {
        $job_id = $this->session->userdata('job_d');
        $data['job'] = $this->MJobs_druggability->query_item(array(array('job_id' => $job_id)));
        $this->load_view('druggability/wait', $data);
    }


    public function detail()
    {
        $job_id = $this->session->userdata('job_d');
        $data['job'] = $this->MJobs_druggability->query_item(array(array('job_id' => $job_id)));
        $data['druggability'] = $this->MDruggability->query_item(array(array('job_id' => $job_id)));
        $this->load_view('druggability/detail', $data);
    }

    public function login()
    {
        $job_id = $this->uri->segment(3, 0);
        $job = $this->MJobs_druggability->query_item(array(array('job_id' => $job_id)));
        if (!is_null($job)) {
            if (strcasecmp('' . $this->session->userdata('job_d'), '' . $job_id) == 0) {
                redirect('druggability/detail/');
            } else {
                if (strlen($job['email']) > 0) {
                    $this->load_view('druggability/login', array('job' => $job));
                } else {
                    $this->session->set_userdata(array('job_d' => $job_id));
                    redirect('druggability/detail/');
                }
            }
        } else {
            $this->session->set_flashdata('alert', $this->lang->line('login_job_not_exist'));
        }
    }

    public function check()
    {
        $this->form_validation->set_rules('job_id', "job id", 'required');
        $this->form_validation->set_rules('password', "Password", 'required|callback_check_password');
        $this->form_validation->set_message('check_password', 'Password Errors!');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('alert', validation_errors());
            $job_id = $this->security->xss_clean($this->input->post('job_id'));
            redirect('druggability/login/' . $job_id);
        } else {
            $job_id = $this->security->xss_clean($this->input->post('job_id'));
            $this->session->set_userdata(array('job_d' => $job_id));
            redirect('druggability/detail/');
        }
    }

    public function check_password()
    {
        $check = FALSE;
        $job = addslashes($this->security->xss_clean($this->input->post('job_id')));
        $password = addslashes($this->security->xss_clean($this->input->post('password')));
        $data = $this->MJobs_druggability->query_item(array(array('job_id' => $job, 'password' => $password)));
        if (count($data)) {
            $check = TRUE;
        }
        return $check;
    }

}

