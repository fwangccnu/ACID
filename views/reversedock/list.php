<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <p class="text-strong panel-title">Jobs</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <ol class="breadcrumb">
            <li>
                <h4 style="color: red ;display: inline;"><?php echo $total_queue; ?></h4> <h4 style="display: inline">
                    tasks are</h4> <h4 style="color: red;display: inline">Queue!</h4>
            </li>
            <li>
                <h4 style="color: blue ;display: inline;"><?php echo $total_running; ?></h4> <h4
                        style="display: inline">tasks are</h4> <h4 style="color: blue;display: inline"> Running!</h4>
            </li>
            <li>
                <h4 style="color: green ;display: inline;"><?php echo $total_finished; ?></h4> <h4
                        style="display: inline">tasks are</h4> <h4 style="color: green;display: inline">Finished!</h4>
            </li>
            <li>
                <a class="btn btn-info" href="#" data-container="body"
                   data-toggle="popover" data-placement="right"
                   data-content="If the job is finished, you can click the ID of your jobs to check the result of jobs." style="color: #ffffff">
                    <span class="glyphicon glyphicon-question-sign"></span>
                </a>
            </li>
            <li>
                The first one is an example result
            </li>
            <li>
                <a class="btn btn btn-primary btn_17b55d" href="#" style="color: #ffffff" onClick="window.location.reload()">
                    <span class="glyphicon glyphicon-refresh"></span>
                </a>
            </li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="bg_ecfff4 _17b55d">
                <tr style="font-size: 16px">
                    <th class="text-left" width="5%">Id</th>
                    <th class="text-left" width="10%">Job id</th>
                    <th class="text-left" width="15%">Ligand name</th>
                    <th class="text-left" width="15%">Target sets</th>
                    <th class="text-left" width="15%">E-mail</th>
                    <th class="text-left" width="15%">Submit time</th>
                    <th class="text-left" width="15%">Pred Calc time</th>
                    <th class="text-left" width="10%">Status</th>
                </tr>
                </thead>

                <tbody>
                <?php if (count($list_job)) { ?>
                    <?php
                    $i = 0;
                    foreach ($list_job as $job) {
                        if ($i == 0) {
                    ?>
                <tr class="info">
                    <?php } else { ?>
                <tr>
                    <?php }
                    $i++; ?>
                            <td>
                                <?php if (strpos($job['status'], 'FINISHED') !== FALSE) { ?>
                                    <a href="<?php echo site_url(array('reversedock', 'login', $job['job_id'])); ?>"> <?php echo $job['id'] ?></a>
                                <?php } else { ?>
                                    <?php echo $job['id'] ?>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if (strpos($job['status'], 'FINISHED') !== FALSE) { ?>
                                    <a href="<?php echo site_url(array('reversedock', 'login', $job['job_id'])); ?>"> <?php echo $job['job_id'] ?></a>
                                <?php } else { ?>
                                    <?php echo $job['job_id'] ?>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if (strpos($job['status'], 'FINISHED') !== FALSE) { ?>
                                    <a href="<?php echo site_url(array('reversedock', 'login', $job['job_id'])); ?>"> <?php echo $job['ligand'] ?></a>
                                <?php } else { ?>
                                    <?php echo $job['ligand']; ?>
                                <?php } ?>
                            </td>
                            <td>
                                <?php echo $job['target_sets']; ?>
                            </td>
                            <td>
                                <?php
                                if (strlen($job['email']) > 1) {
                                    $name_ext = explode('@', $job['email']);
                                    $ext = end($name_ext);
                                    echo '******@' . $ext;
                                }
                                ?>
                            </td>
                            <td>
                                <?php echo $job['submit_time']; ?>
                            </td>
                            <td>
                                <?php echo $job['pred_calc_time']; ?>
                                hours
                            </td>
                            <td>
                                <?php if (strpos($job['status'], 'QUEUE') !== FALSE) { ?>
                                    <h4 style="color: red;display: inline">Queue!</h4>
                                <?php } elseif (strpos($job['status'], 'RUNNING') !== FALSE) { ?>
                                    <h4 style="color: blue;display: inline">Running...</h4>
                                <?php } elseif (strpos($job['status'], 'FINISHED') !== FALSE) { ?>
                                    <h4 style="color: green;display: inline">Finished!</h4>
                                <?php } elseif (strpos($job['status'], 'ERROR') !== FALSE) { ?>
                                    <h4 style="color: orange;display: inline">Error!</h4>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <?php echo $link; ?>
    </div>
</div>