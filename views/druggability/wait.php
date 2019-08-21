<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <p class="text-strong panel-title">Your job for druggability submit success!</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-6 col-sm-12 col-xs-12">
                        <h4 class="_17b55d">Job ID</h4>
                        <p><?php echo $job['job_id']; ?></p>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-xs-12">
                        <h4 class="_17b55d">Task name</h4>
                        <p><?php echo $job['task_name']; ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-sm-12 col-xs-12">
                        <h4 class="_17b55d">E-mail</h4>
                        <p><?php echo $job['email']; ?></p>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-xs-12">
                        <h4 class="_17b55d">Submit time</h4>
                        <p><?php echo $job['submit_time']; ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-12 col-xs-12"></div>
                    <div class="col-lg-6 col-sm-12 col-xs-12">
                        <a  href="<?php echo site_url(array('druggability','index'));?>" class="btn btn-primary btn_17b55d" type="submit">Go to job list of druggability >></a>
                    </div>
                    <div class="col-lg-3 col-sm-12 col-xs-12"></div>
                </div>
            </div>
        </div>
    </div>
</div>
