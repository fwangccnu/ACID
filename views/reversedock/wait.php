<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <p class="text-strong panel-title">Your job for ReverseDock submit success!</p>
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
                        <h4 class="_17b55d">Ligand name</h4>
                        <p><?php echo $job['ligand']; ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-xs-12">
                        <h4 class="_17b55d">Target sets</h4>
                        <p><?php echo $job['target_sets']; ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-sm-12 col-xs-12">
                        <h4 class="_17b55d">E-mail</h4>
                        <p><?php
                            if (strlen($job['email']) > 1) {
                                $name_ext = explode('@', $job['email']);
                                $ext = end($name_ext);
                                echo '******@' . $ext;
                            }
                            ?></p>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-xs-12">
                        <h4 class="_17b55d">Submit time</h4>
                        <p><?php echo $job['submit_time']; ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-12 col-xs-12"></div>
                    <div class="col-lg-6 col-sm-12 col-xs-12">
                        <a href="<?php echo site_url(array('reversedock', 'index')); ?>"
                           class="btn btn-primary btn_17b55d" type="submit">Go to job list of ReverseDock >></a>
                    </div>
                    <div class="col-lg-3 col-sm-12 col-xs-12"></div>
                </div>
            </div>
        </div>
    </div>
</div>
