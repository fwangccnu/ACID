<?php
	if(strcasecmp("",validation_errors())!=0){
		echo '<div class="alert alert-warning">'.validation_errors().'</div>';
	}
	if(strcasecmp("",$this->session->flashdata("alert"))!=0){
		echo '<div class="alert alert-danger">'.$this->session->flashdata("alert").'</div>';
	}
	if(strcasecmp("",$this->session->flashdata("warning"))!=0){
		echo '<div class="alert alert-warning">'.$this->session->flashdata("warning").'</div>';
	}
	if(strcasecmp("",$this->session->flashdata("info"))!=0){
		echo '<div class="alert alert-info">'.$this->session->flashdata("info").'</div>';
	}
	if(strcasecmp("",$this->session->flashdata("success"))!=0){
		echo '<div class="alert alert-success">'.$this->session->flashdata("success").'</div>';
	}
