<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MDrug_target extends MY_Model {

    private $suffix = "drug_targ";

    public function __construct() {
        parent::__construct();
        $this -> set_table($this -> get_prefix() . $this -> suffix);
    }

}
