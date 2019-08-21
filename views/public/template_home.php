<!DOCTYPE html>
<html>
<?php $this->load->view("public/head"); ?>
<body data-spy="scroll" data-target=".scrollspy">
<?php $this->load->view("public/menu"); ?>
<?php $this->load->view('public/message'); ?>
<?php $this->load->view($view_content); ?>
<?php $this->load->view('public/foot'); ?>
</body>
</html>