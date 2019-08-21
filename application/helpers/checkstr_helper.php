<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

function is_natural_number($str) {
	$check = FALSE;
	if (is_numeric($str)) {
		if ($str > 0) {
			if (intval($str) - $str == 0) {
				$check = TRUE;
			}
		}
	}
	return $check;
}
