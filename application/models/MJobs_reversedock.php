<?php
class MJobs_reversedock extends MY_Model {
    private $suffix = "jobs_reversedock";

    public function __construct()
    {
        parent::__construct();
        $this -> set_table($this -> get_prefix() . $this -> suffix);
    }

}
