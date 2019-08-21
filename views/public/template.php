<!DOCTYPE html>
<html>
<?php $this -> load -> view("public/head"); ?>
<body data-spy="scroll" data-target=".scrollspy">
<?php $this -> load -> view("public/menu"); ?>
<div class="container main-content">
    <div class="row">
        <div class="col-lg-12">
            <?php $this -> load -> view('public/message'); ?>
        </div>
    </div>
    <div class="row">
<!--        <div class="col-lg-12">-->
            <?php $this -> load -> view($view_content); ?>
<!--        </div>-->
    </div>
</div>
<?php $this -> load -> view('public/foot'); ?>
</body>
</html>
