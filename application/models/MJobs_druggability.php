<?php
class MJobs_druggability extends MY_Model {
    private $suffix = "jobs_druggability";

    public function __construct()
    {
        parent::__construct();
        $this -> set_table($this -> get_prefix() . $this -> suffix);
    }

}
