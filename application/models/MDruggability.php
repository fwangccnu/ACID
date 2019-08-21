<?php
class MDruggability extends MY_Model {
    private $suffix = "druggability";

    public function __construct()
    {
        parent::__construct();
        $this -> set_table($this -> get_prefix() . $this -> suffix);
    }

}
