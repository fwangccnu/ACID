<div class="row">
    <div class="col-xs-12">
        <p class="text-strong panel-title">Jobs</p>
    </div>

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
        </ol>
    </div>

    <div class="col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="bg_ecfff4 _17b55d">
                <tr style="font-size: 16px">
                    <th class="text-left" width="20%">Job ID</th>
                    <th class="text-left" width="30%">Task name</th>
                    <th class="text-left" width="25%">E-mail</th>
                    <th class="text-left" width="15%">Submit time</th>
                    <th class="text-left" width="10%">Status</th>
                </tr>
                </thead>

                <tbody>
                <?php if (count($list_job)) { ?>
                    <?php foreach ($list_job as $job) { ?>
                        <tr>
                            <td>
                                <?php if (strpos($job['status'], 'FINISHED') !== FALSE) { ?>
                                    <a href="<?php echo site_url(array('druggability', 'login', $job['job_id'])); ?>"> <?php echo $job['id'] ?></a>
                                <?php } else { ?>
                                    <?php echo $job['id'] ?>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if (strpos($job['status'], 'FINISHED') !== FALSE) { ?>
                                    <a href="<?php echo site_url(array('reversedock', 'login', $job['job_id'])); ?>"> <?php echo $job['id'] ?></a>
                                <?php } else { ?>
                                    <?php echo $job['task_name']; ?>
                                <?php } ?>
                            </td>
                            <td>
                                <?php echo $job['email']; ?>
                            </td>
                            <td>
                                <?php echo $job['submit_time']; ?>
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
    <div class="col-xs-12 text-center">
        <?php echo $link; ?>
    </div>
</div>
