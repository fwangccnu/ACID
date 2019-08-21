<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

function get_config_pagination($page, $base_url, $fix = array()) {
	$config['per_page'] = $page[1];
	$config['full_tag_open'] = '<ul class="pagination">';
	$config['full_tag_close'] = '</ul>';
	$config['num_links'] = 5;
	$config['use_page_numbers'] = TRUE;
	$config['uri_segment'] = $page[0];
	$config['first_link'] = '&laquo; First';
	$config['first_tag_open'] = '<li class="page">';
	$config['first_tag_close'] = '</li>';
	$config['prev_link'] = '&larr; Previous......';
	$config['prev_tag_open'] = '<li class="page">';
	$config['prev_tag_close'] = '</li>';
	$config['next_link'] = '......Next &rarr;';
	$config['next_tag_open'] = '<li class="next page">';
	$config['next_tag_close'] = '</li>';
	$config['last_link'] = 'Last &raquo;';
	$config['last_tag_open'] = '<li class="next page">';
	$config['last_tag_close'] = '</li>';
	$config['cur_tag_open'] = '<li class="active"><a href="#">';
	$config['cur_tag_close'] = '</a></li>';
	$config['num_tag_open'] = '<li class="page">';
	$config['num_tag_close'] = '</li>';
	$config['base_url'] = $base_url;
	$config['total_rows'] = $page[2];
	if (count($fix)) {
		$config['prefix'] = $fix[0];
		$config['suffix'] = $fix[1];
		
	}

	return $config;
}
