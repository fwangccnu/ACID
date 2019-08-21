<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reversedock extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('MJobs_reversedock', 'MReversedock'));
    }


    public function index()
    {
        $condition = array();
        $this->load->model('MJobs_reversedock', '', true);
        $total_rows = $this->MJobs_reversedock->query_total($condition);
        $data = $this->order_pagination(array('reversedock', 'index'), array(array(3, 'submit_time', array('submit_time'), 1, array(1))), array(5, 10, $total_rows));
        $list_job = $this->MJobs_reversedock->query($condition, array($data['per_page'], $data['offset']), $data['order_model']);

        $example_job = $this->MJobs_reversedock->query(array(array('job_id' => array('R600ahjx7978028'))));
        $new_list_job = array_merge($example_job, $list_job);

        $data['total_queue'] = $this->MJobs_reversedock->query_total(array(array('status' => 'QUEUE')));
        $data['total_running'] = $this->MJobs_reversedock->query_total(array(array('status' => 'RUNNING')));
        $data['total_finished'] = $this->MJobs_reversedock->query_total(array(array('status' => 'FINISHED')));

        $data['link'] = $data['link_pagination'];
        $data['list_job'] = $new_list_job;
        $this->load_view('reversedock/list', $data);
    }

    /**
     * public function update_pred_calc_time()
     * {
     * $condition = array();
     * $this->load->model('MJobs_reversedock', '', true);
     * $list_job = $this->MJobs_reversedock->query();
     * foreach ($list_job as $job){
     * $this->MJobs_reversedock->update($job['id'],array('pred_calc_time'=>rand(18,30)));
     * }
     * }
     **/

    public function add()
    {
        $id = $this->uri->segment(3, '');
        $this->load->model('MDrug', '', TRUE);
        $drug = $this->MDrug->query_item(array(array('MOLID' => $id)));
        $data = array();
        if (!is_null($drug)) {
            $data['drug'] = $drug;
        }
        $this->load_view('reversedock/new', $data);
    }

    public function add2()
    {
        $id = $this->uri->segment(3, '');
        $this->load->model('MDrug', '', TRUE);
        $drug = $this->MDrug->query_item(array(array('MOLID' => $id)));
        $data = array();
        if (!is_null($drug)) {
            $data['drug'] = $drug;
        }
        print_r($drug);
        //$this->load_view('reversedock/new',$data);
    }

    public function save()
    {
        $this->form_validation->set_rules('target_sets[]', $this->lang->line('mark_target_sets'), 'required', array('required' => $this->lang->line('error_required_target_sets')));
        $this->form_validation->set_rules('sdf', $this->lang->line('mark_sdf'), 'min_length[680]', array('min_length' => $this->lang->line('error_min_length_sdf')));
        $this->form_validation->set_rules('ligand', $this->lang->line('mark_ligand'), '');
        $this->form_validation->set_rules('email', $this->lang->line('mark_email'), 'valid_email');
        $this->form_validation->set_rules('password', $this->lang->line('mark_password'), '');
        $this->form_validation->set_rules('example', $this->lang->line('mark_example'), '');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('alert', validation_errors());
            redirect('reversedock/add', 'refresh');
        } else {
            $example = $this->security->xss_clean($this->input->post('example'));
            if ($example == 1) {
                $target_sets = $this->security->xss_clean($this->input->post('target_sets[]'));
                $sdf = $this->security->xss_clean($this->input->post('sdf'));

                $this->load->helper('randomstr');
                $job_id = random_str('R', 6);
                $path_dir = './uploads/' . $job_id;
                mkdir(iconv("UTF-8", "GBK", $path_dir), 0777, true);

                $this->load->helper('file');
                $file_path = random_str('', 8) . '.sdf';
                if (!write_file($path_dir . '/' . $file_path, $sdf)) {
                    $this->session->set_flashdata('alert', $this->lang->line('alert_writing_sdf_error'));
                    redirect('reversedock/add', 'refresh');
                }

                $target_sets = implode(" ", $target_sets);

                $data = array(
                    'job_id' => $job_id,
                    'target_sets' => $target_sets,
                    'file_path' => $file_path,
                    'submit_time' => date('Y-m-d H:i:s', time()),
                    'pred_calc_time' => rand(18, 30),
                    'ip' => $this->input->ip_address()
                );

                $this->MJobs_reversedock->insert($data);
                $result = shell_exec("qsubInvDock.pl $job_id");

                $this->session->set_userdata(array('job_r' => $job_id));
                redirect('reversedock/wait/' . $job_id);


            } else {
                $target_sets = $this->security->xss_clean($this->input->post('target_sets[]'));
                $sdf = $this->security->xss_clean($this->input->post('sdf'));
                $ligand = $this->security->xss_clean($this->input->post('ligand'));
                $email = $this->security->xss_clean($this->input->post('email'));
                $password = $this->security->xss_clean($this->input->post('password'));

                $this->load->helper('randomstr');
                $job_id = random_str('R', 6);
                $path_dir = './uploads/' . $job_id;
                mkdir(iconv("UTF-8", "GBK", $path_dir), 0777, true);

                $file_path = '';
                if (strlen($sdf) > 48) {
                    $this->load->helper('file');
                    $file_path = random_str('', 8) . '.sdf';
                    if (!write_file($path_dir . '/' . $file_path, $sdf)) {
                        $this->session->set_flashdata('alert', $this->lang->line('alert_writing_sdf_error'));
                        redirect('reversedock/add', 'refresh');
                    }
                } else {
                    $config['upload_path'] = $path_dir;
                    $config['allowed_types'] = 'pdb|mol2|sdf';
                    $config['max_size'] = '20480';
                    $config['overwrite'] = TRUE;
                    $config['encrypt_name'] = TRUE;
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('userfile')) {
                        $this->session->set_flashdata('alert', $this->lang->line('alert_upload_file'));
                        redirect('reversedock/add', 'refresh');
                    } else {
                        $file_data = $this->upload->data();
                        $file_path = $file_data["raw_name"] . $file_data["file_ext"];
                    }
                }

                if (in_array("ALL", $target_sets)) {
                    $target_sets = 'ALL';
                } else {
                    $target_sets = implode(" ", $target_sets);
                }

                $data = array(
                    'job_id' => $job_id,
                    'target_sets' => $target_sets,
                    'file_path' => $file_path,
                    'ligand' => $ligand,
                    'email' => $email,
                    'password' => $password,
                    'submit_time' => date('Y-m-d H:i:s', time()),
                    'pred_calc_time' => rand(18, 30),
                    'ip' => $this->input->ip_address()
                );

                $this->MJobs_reversedock->insert($data);
                $result = shell_exec("qsubInvDock.pl $job_id");

                $this->session->set_userdata(array('job_r' => $job_id));
                redirect('reversedock/wait/' . $job_id);
            }
        }
    }


    public function wait()
    {
        $job_id = $this->session->userdata('job_r');
        if (strlen($job_id) > 1) {
            $data['job'] = $this->MJobs_reversedock->query_item(array(array('job_id' => $job_id)));
            $this->load_view('reversedock/wait', $data);
        } else {
            $this->session->set_flashdata('alert', $this->lang->line('alert_can_not_find_job_id'));
            redirect('reversedock/index', 'refresh');
        }
    }

    public function select_page()
    {
        $n_page = trim($this->security->xss_clean($this->input->post('n_page')));
        $order = $this->uri->segment(3, 0);
        $order_by = $this->uri->segment(4, 0);
        redirect('reversedock/detail/' . $order . '/' . $order_by . '/' . $n_page);
    }

    public function detail()
    {
        $job_id = $this->session->userdata('job_r');
        if (strlen($job_id) > 1) {
            $this->MReversedock->set_suffix('reversedock_' . $job_id);
            $total_rows = $this->MReversedock->query_total(array());
            $data = $this->order_pagination(array('reversedock', 'detail'), array(array(3, 'dock_score', array('GAS', 'PBSOL', 'PBTOT', 'dock_score'), 1, array(0, 0, 0, 0, 0, 1))), array(5, 50, $total_rows));
            $data['reversedock'] = $this->MReversedock->query(array(), array($data['per_page'], $data['offset']), $data['order_model']);
            $data['job'] = $this->MJobs_reversedock->query_item(array(array('job_id' => $job_id)));
            $data['link'] = $data['link_pagination'];
            $this->load_view('reversedock/detail', $data);
        } else {
            $this->session->set_flashdata('alert', $this->lang->line('alert_can_not_find_job_id'));
            redirect('reversedock/index', 'refresh');
        }
    }

    public function show_pose()
    {
        $job_id = $this->session->userdata('job_r');
        if (strlen($job_id) > 1) {
            $protID = $this->uri->segment(3, 0);
            $data['job'] = $this->MJobs_reversedock->query_item(array(array('job_id' => $job_id)));
            $this->MReversedock->set_suffix('reversedock_' . $job_id);
            $data['reversedock'] = $this->MReversedock->query_item(array(array('protID' => $protID)));
            $this->load->model(array('MTarget'));
            $data['target'] = $this->MTarget->query_item(array(array('protID' => $protID)));
            $this->load_view('reversedock/show_pose', $data);
        } else {
            $this->session->set_flashdata('alert', $this->lang->line('alert_can_not_find_job_id'));
            redirect('reversedock/index', 'refresh');
        }
    }

    public function login()
    {
        $job_id = $this->uri->segment(3, 0);
        $job = $this->MJobs_reversedock->query_item(array(array('job_id' => $job_id)));
        //print_r($job);
        //die;
        if (!is_null($job)) {
            if (strcasecmp('' . $this->session->userdata('job_r'), '' . $job_id) == 0) {
                redirect('reversedock/detail/');
            } else {
                if (strlen($job['password']) > 0) {
                    $this->load_view('reversedock/login', array('job' => $job));
                } else {
                    $this->session->set_userdata(array('job_r' => $job_id));
                    redirect('reversedock/detail/');
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
            redirect('reversedock/login/' . $job_id);
        } else {
            $job_id = $this->security->xss_clean($this->input->post('job_id'));
            $this->session->set_userdata(array('job_r' => $job_id));
            redirect('reversedock/detail/');
        }
    }

    public function check_password()
    {
        $check = FALSE;
        $job = addslashes($this->security->xss_clean($this->input->post('job_id')));
        $password = addslashes($this->security->xss_clean($this->input->post('password')));
        $data = $this->MJobs_reversedock->query_item(array(array('job_id' => $job, 'password' => $password)));
        if (count($data)) {
            $check = TRUE;
        }
        return $check;
    }

    public function check_dir()
    {
        $this->load->model('MJobs_reversedock', '', true);
        $list_job = $this->MJobs_reversedock->query();
        foreach ($list_job as $job) {
            if (file_exists('uploads/' . $job['job_id'])) {
                echo $job['job_id'];
                //$this->MJobs_reversedock->query_update(array(array('job_id' => $job['job_id'])),array('password'=>''));
                //echo "ok";
                echo "<br>";
            } else {
                //echo $job['job_id'];
                //$this->MJobs_reversedock->query_update(array(array('job_id' => $job['job_id'])),array('password'=>'1a2b3c4d5e'));
                //echo "no";
            }

        }
    }

}

