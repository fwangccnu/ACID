<div class="container-fluid footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-2 col-md-12 col-xs-12 text-left">
                <ul class="list-unstyled">
                    <li><a href="<?php echo site_url(array('home','index')); ?>">Home</a></li>
                    <li><a href="<?php echo site_url(array('target', 'index')); ?>">Browse</a></li>
                    <li><a href="<?php echo site_url(array('reversedock', 'add')); ?>">Submit</a></li>
                    <li><a href="<?php echo site_url(array('reversedock', 'index')); ?>">Jobs</a></li>
                    <li><a href="<?php echo site_url(array('home','help'));?>">Help</a></li>
                    <li><a href="<?php echo site_url(array('home','contact'));?>">Citation&Contact</a></li>
                </ul>
            </div>
            <div class="col-lg-8 col-md-12 col-xs-12 text-center">
                <img src="<?php echo base_url(array('resource','img','footer_logo.png'));?>">
                <p>Key Laboratory of Pesticide & Chemical Biology of Ministry of Education College of Chemistry</p>
                <p>Central China Normal University</p>
                <p>© 2017 Copyright: The Yang Group</p>
            </div>
            <div class="col-lg-2 col-md-12 col-xs-12 text-right">
                <ul class="list-unstyled">
                    <li><a href="http://chemyang.ccnu.edu.cn/">The Yang Group</a></li>
                    <li><a href="http://chemyang.ccnu.edu.cn/Biography/">Biography</a></li>
                    <li><a href="http://chemyang.ccnu.edu.cn/Research/">Research</a></li>
                    <li><a href="http://chemyang.ccnu.edu.cn/Members/">Members</a></li>
                    <li><a href="http://chemyang.ccnu.edu.cn/Laboratory/">Laboratory</a></li>
                    <li><a href="http://chemyang.ccnu.edu.cn/Photos/">Photos</a></li>
                    <li><a href="http://chemyang.ccnu.edu.cn/Publications/">Publications</a></li>
                    <li><a href="http://chemyang.ccnu.edu.cn/Resource/">Resources</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url(array('resource','plugin','jquery','jquery-1.11.3.min.js'));?>"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#btnMoreOption").click(function(){
            $("#moreOption").slideToggle("slow");
            $(this).toggleClass("active");
            return false;
        });
    });
</script>
<script src="<?php echo base_url(array('resource','plugin','jquery','jquery.placeholder.min.js'));?>"></script>
<script src="<?php echo base_url(array('resource','plugin','myMarquee.js'));?>"></script>
<script src="<?php echo base_url(array('resource','plugin','bootstrap-3.3.7','js','bootstrap.js'));?>"></script>
<script>
    $(function () {
        $("[data-toggle='popover']").popover();
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<script src="<?php echo base_url(array('resource','plugin','zoom','js','zoom.js'));?>"></script>
<script src="<?php echo base_url(array('resource','js','flexible.js'));?>"></script>
<script src="<?php echo base_url(array('resource','js','padfrag.js'));?>"></script>
<script>
	// Javascript to enable link to tab
	var url = document.location.toString();
	if (url.match('#')) {
		console.log(url.split('#')[1]);
		$('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
	}//add a suffix

	// Change hash for page-reload
	$('.nav-tabs a').on('shown.bs.tab', function(e) {
		window.location.hash = e.target.hash;
	})
</script>
