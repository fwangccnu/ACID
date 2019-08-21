<?php

class MReversedock extends MY_Model
{
    private $suffix = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function set_suffix($suffix)
    {
        $this->suffix = $suffix;
        $this->set_table($this->get_prefix() . $this->get_suffix());
    }

    public function get_suffix()
    {
        return $this->suffix;
    }
}
