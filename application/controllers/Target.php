<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Target extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('MTarget'));
    }

    public function index()
    {
        $condition = array();
        $total_rows = $this->MTarget->query_total($condition);
        $data = $this->order_pagination(array('Target', 'index'), array(array(3, 'protID', array('protID'), 0, array(0, 1))), array(5, 10, $total_rows));
        $list_target = $this->MTarget->query($condition, array($data['per_page'], $data['offset']), $data['order_model']);
        $data['link'] = $data['link_pagination'];
        $data['list_target'] = $list_target;
        $this->load_view('target/list', $data);
    }

    public function select_page()
    {
        $n_page = trim($this->security->xss_clean($this->input->post('n_page')));
        $order = $this->uri->segment(3, 0);
        $order_by = $this->uri->segment(4, 0);
        redirect('target/index/' . $order . '/' . $order_by . '/' . $n_page);
    }

    public function target_detail()
    {
        $protID = $this->uri->segment(3, 0);
        $data['target'] = $this->MTarget->query_item(array(array('protID' => $protID)));
        $this->load_view('target/detail', $data);
    }

    public function search_query()
    {
        $keyword = trim($this->security->xss_clean($this->input->post('keyword')));
        $select = $this->security->xss_clean($this->input->post('select'));
        if (empty($keyword)) {
            $this->session->set_flashdata('warning', 'Please enter the content first！');
            redirect('target/search', 'refresh');
        } else {
            if ($select == "ALL") {
                $condition = array(array('%' => array($keyword, array('protID', 'pdbID', '	hasLig', 'resolu', 'chain', 'resiNum', 'classification', 'class', 'prot_name', 'gene_name', 'catalytics', 'mol_func', 'bio_process', 'pfam', 'sequence'))));
            } else {
                $condition = array(array('%' => array($keyword, array($select))));
            }
            $total_rows = $this->MTarget->query_total($condition);
            $data = $this->order_pagination(array('target', 'search_query'), array(array(3, '	protID', array('protID'), 1, array(1, 0))), array(5, 10, $total_rows));
            $list_target = $this->MTarget->query($condition, array($data['per_page'], $data['offset']), $data['order_model']);
            $data['list_target'] = $list_target;
            $data['link'] = $data['link_pagination'];
            $this->load_view('target/list', $data);
        }
    }

}

