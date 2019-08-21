<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand" href="#">
				<img src="<?php echo base_url(array('resource','img','logo_new111.png'));?>" style="margin-top: 16px;">
			</a>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li><a href="<?php echo site_url(array('home','index')); ?>">Home</a></li>
                <!--<li><a href="<?php echo site_url(array('target', 'index')); ?>">Browse</a></li>-->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Browse <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo site_url(array('target', 'index')); ?>">Approved Target</a></li>
                        <li><a href="<?php echo site_url(array('drug', 'index')); ?>">Commercial Drugs</a></li>
                    </ul>
                </li>

                <li><a href="<?php echo site_url(array('reversedock', 'add')); ?>">Submit</a></li>
                <li><a href="<?php echo site_url(array('reversedock', 'index')); ?>">Jobs</a></li>
                <!--
				<li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Browse <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo site_url(array('target', 'index')); ?>">Target</a></li>
                        <li><a href="<?php echo site_url(array('target', 'search')); ?>">Search</a></li>
                    </ul>
                </li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Submit <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo site_url(array('reversedock', 'add')); ?>">ReverseDock</a></li>
						<li><a href="<?php echo site_url(array('druggability', 'add')); ?>">Druggability</a></li>
					</ul>
				</li>
				<li>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Jobs<span class="caret"></span></a>
                    <ul class="dropdown-menu">
						<li><a href="<?php echo site_url(array('reversedock', 'index')); ?>">ReverseDock</a></li>
						<li><a href="<?php echo site_url(array('druggability', 'index')); ?>">Druggability</a></li>
					</ul>
				</li>
                -->
                <li><a href="<?php echo site_url(array('home','download')); ?>">Download</a></li>
				<li><a href="<?php echo site_url(array('home','help')); ?>">Help</a></li>
				<li><a href="<?php echo site_url(array('home','contact')); ?>">Citation&Contact</a></li>
			</ul>
		</div>
	</div>
</nav>