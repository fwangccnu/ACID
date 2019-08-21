<?php
class MTarget extends MY_Model {
    private $suffix = "targets";

    public function __construct()
    {
        parent::__construct();
        $this -> set_table($this -> get_prefix() . $this -> suffix);
    }

}
