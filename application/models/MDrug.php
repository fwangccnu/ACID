<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class MDrug extends MY_Model {

	private $suffix = "drugs";

	public function __construct() {
		parent::__construct();
		$this -> set_table($this -> get_prefix() . $this -> suffix);
	}

}
