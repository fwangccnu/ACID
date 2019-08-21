<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Drug extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {
        $this->load->model('MDrug', '', TRUE);
        $total_rows = $this->MDrug->query_total(array());
        $data = $this->order_pagination(array('drug', 'index'), array(array(3, 'MOLID', array('MOLID', 'NAME1', 'CLOGP', 'CLOGS', 'MW'), 0, array(0, 0, 0, 0, 0, 0))), array(5, 10, $total_rows));
        $list_drug = $this->MDrug->query(array(), array($data['per_page'], $data['offset']), $data['order_model']);
        $data['list_drug'] = $list_drug;
        $data['view_content'] = 'drug/list';
        $this->load->vars($data);
        $this->load->view('template');
    }

    public function select_page()
    {
        $n_page = trim($this->security->xss_clean($this->input->post('n_page')));
        $order = $this->uri->segment(3, 0);
        $order_by = $this->uri->segment(4, 0);
        redirect('drug/index/' . $order . '/' . $order_by . '/' . $n_page);
    }

    public function more()
    {
        $id = $this->uri->segment(3, 0);
        $this->load->model('MDrug', '', TRUE);
        $drug = $this->MDrug->query_item(array(array('MOLID' => $id)));
        if (!is_null($drug)) {
            $condition = array('drugID' => $drug['LINK']);
            $this->load->model('MDrug_target', '', TRUE);
            $total_rows = $this->MDrug_target->query_total(array($condition));
            $data = $this->order_pagination(array('drug', 'more', $id), array(array(4, 'protID', array('protID'), 0, array(0))), array(6, 10, $total_rows), array('', '#targets'));
            $list_drug_target = $this->MDrug_target->query(array($condition), array($data['per_page'], $data['offset']));
            $list_target_id = array();
            foreach ($list_drug_target as $drug_target) {
                $list_target_id[] = $drug_target['protID'];
            }
            $list_target = array();
            if (count($list_target_id)) {
                $this->load->model('MTarget', '', TRUE);
                $list_target = $this->MTarget->query(array(array('protID' => $list_target_id)));
            }
            $data['list_target'] = $list_target;
            $data['drug'] = $drug;
            $data['view_content'] = 'drug/more';
            $this->load->vars($data);
            $this->load->view('template');
        } else {
            $this->session->set_flashdata('alert', $this->lang->line('alert_have_not_right'));
            redirect('drug/index', 'refresh');
        }
    }

    public function search_page()
    {
        $data['view_content'] = 'drug/search_page';
        $this->load->vars($data);
        $this->load->view('template');
    }

    public function search_query()
    {
        $this->form_validation->set_rules('key', 'key', 'required');
        $this->form_validation->set_rules('words', 'words', '');
        $this->form_validation->set_rules('mw_min', 'mw_min', 'numeric');
        $this->form_validation->set_rules('mw_max', 'mw_max', 'numeric');
        $this->form_validation->set_rules('nring_min', 'nring_min', 'numeric');
        $this->form_validation->set_rules('nring_max', 'nring_max', 'numeric');
        $this->form_validation->set_rules('nrb_min', 'nrb_min', 'numeric');
        $this->form_validation->set_rules('nrb_max', 'nrb_max', 'numeric');
        $this->form_validation->set_rules('clogs_min', 'clogs_min', 'numeric');
        $this->form_validation->set_rules('clogs_max', 'clogs_max', 'numeric');
        $this->form_validation->set_rules('clogp_min', 'clogp_min', 'numeric');
        $this->form_validation->set_rules('clogp_max', 'clogp_max', 'numeric');
        $this->form_validation->set_rules('hba_min', 'hba_min', 'numeric');
        $this->form_validation->set_rules('hba_max', 'hba_max', 'numeric');
        $this->form_validation->set_rules('hbd_min', 'hbd_min', 'numeric');
        $this->form_validation->set_rules('hbd_max', 'hbd_max', 'numeric');
        $this->form_validation->set_rules('psa_min', 'psa_min', 'numeric');
        $this->form_validation->set_rules('psa_max', 'psa_max', 'numeric');
        if ($this->form_validation->run() == FALSE) {
            $this->search_page();
        } else {
            $condition = array();
            $key = $this->security->xss_clean($this->input->post('key'));
            $words = $this->security->xss_clean($this->input->post('words'));
            if ($words != '') {
                $condition[$key] = $words;
            }
            $mw_min = $this->security->xss_clean($this->input->post('mw_min'));
            if ($mw_min != '') {
                $condition['MW >'] = $mw_min;
            }
            $mw_max = $this->security->xss_clean($this->input->post('mw_max'));
            if ($mw_max != '') {
                $condition['MW <'] = $mw_max;
            }
            $nring_min = $this->security->xss_clean($this->input->post('nring_min'));
            if ($nring_min != '') {
                $condition['NRING >'] = $nring_min;
            }
            $nring_max = $this->security->xss_clean($this->input->post('nring_max'));
            if ($nring_max != '') {
                $condition['NRING <'] = $nring_max;
            }
            $nrb_min = $this->security->xss_clean($this->input->post('nrb_min'));
            if ($nrb_min != '') {
                $condition['NRB >'] = $nrb_min;
            }
            $nrb_max = $this->security->xss_clean($this->input->post('nrb_max'));
            if ($nrb_max != '') {
                $condition['NRB <'] = $nrb_max;
            }
            $clogs_min = $this->security->xss_clean($this->input->post('clogs_min'));
            if ($clogs_min != '') {
                $condition['CLOGS >'] = $clogs_min;
            }
            $clogs_max = $this->security->xss_clean($this->input->post('clogs_max'));
            if ($clogs_max != '') {
                $condition['CLOGS <'] = $clogs_max;
            }
            $clogp_min = $this->security->xss_clean($this->input->post('clogp_min'));
            if ($clogp_min != '') {
                $condition['CLOGP >'] = $clogp_min;
            }
            $clogp_max = $this->security->xss_clean($this->input->post('clogp_max'));
            if ($clogp_max != '') {
                $condition['CLOGP <'] = $clogp_max;
            }
            $hba_min = $this->security->xss_clean($this->input->post('hba_min'));
            if ($hba_min != '') {
                $condition['HBA >'] = $hba_min;
            }
            $hba_max = $this->security->xss_clean($this->input->post('hba_max'));
            if ($hba_max != '') {
                $condition['HBA <'] = $hba_max;
            }
            $hbd_min = $this->security->xss_clean($this->input->post('hbd_min'));
            if ($hbd_min != '') {
                $condition['HBD >'] = $hbd_min;
            }
            $hbd_max = $this->security->xss_clean($this->input->post('hbd_max'));
            if ($hbd_max != '') {
                $condition['HBD <'] = $hbd_max;
            }
            $psa_min = $this->security->xss_clean($this->input->post('psa_min'));
            if ($psa_min != '') {
                $condition['PSA >'] = $psa_min;
            }
            $psa_max = $this->security->xss_clean($this->input->post('psa_max'));
            if ($psa_max != '') {
                $condition['PSA <'] = $psa_max;
            }
            if (count($condition) > 0) {
                $str_condition = base64_encode(json_encode($condition));
                redirect('drug/search/' . $str_condition, 'refresh');
            } else {
                $this->search_page();
            }
        }
    }

    public function search()
    {
        $str_condition = $this->uri->segment(3, 0);
        $condition = json_decode(base64_decode($str_condition), TRUE);
        if (is_array($condition)) {
            $this->load->model('MDrug', '', TRUE);
            $total_rows = $this->MDrug->query_total(array($condition));
            $data = $this->order_pagination(array('drug', 'search', $str_condition), array(array(4, 'MOLID', array('MOLID', 'NAME1', 'CLOGP', 'CLOGS', 'MW'), 0, array(0, 0, 0, 0, 0, 0))), array(6, 10, $total_rows));
            $list_drug = $this->MDrug->query(array($condition), array($data['per_page'], $data['offset']), $data['order_model']);

            $data['condition'] = $str_condition;
            $data['list_drug'] = $list_drug;
            $data['view_content'] = 'drug/search';
            $this->load->vars($data);
            $this->load->view('template');
        } else {
            redirect('drug/search_query', 'refresh');
        }
    }

    public function search_smi()
    {
        $this->form_validation->set_rules('smi', 'smi', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->search_page();
        } else {
            $smi = $this->security->xss_clean($this->input->post('smi'));
            $argv = array("table" => "drugs", "smi" => $smi);
            $result = shell_exec('sh resource/plugin/calcsmi/calcsmi.sh ' . escapeshellarg(json_encode($argv)));
            $result_data = json_decode($result, TRUE);

            $list_drug = array();
            $this->load->model('MDrug', '', TRUE);
            if (count($result_data)) {
                foreach ($result_data as $calcsmi) {
                    $drug = $this->MDrug->query_item(array(array("MOLID" => $calcsmi[0])));
                    $drug['CALCSMI'] = $calcsmi[1];
                    $list_drug[] = $drug;
                }
            }

            $data['result'] = base64_encode($result);
            $data['list_drug'] = $list_drug;
            $data['view_content'] = 'drug/search_smi';
            $this->load->vars($data);
            $this->load->view('template');
        }
    }

}
