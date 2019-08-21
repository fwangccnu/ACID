<div class="col-lg-12 col-sm-12 col-xs-12">
    <p class="text-strong panel-title">Druggable</p>
</div>
<?php echo form_open_multipart('druggability/save', 'method="post"'); ?>
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <ul id="myTab" class="nav nav-tabs">
            <li class="active"><a href="#byFile" data-toggle="tab"><h4>Upload file</h4></a></li>
            <li><a href="#byID" data-toggle="tab"><h4>Or input ID</h4></a></li>
        </ul>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade in active" id="byFile">
                <div class="panel panel-default">
                    <div class="panel-body form-group">
                        <div class="row">
                            <div class="col-lg-2 col-sm-12 col-xs-12">
                                <label><h4>Upload File:</h4></label>
                            </div>
                            <div class="col-lg-4 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <input class="form-control" type="file" id="userfile" name="userfile">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="byID">
                <div class="panel panel-default">
                    <div class="panel-body form-group">
                        <div class="row">
                            <div class="col-lg-2 col-sm-12 col-xs-12">
                                <label><h4> Input ID:</h4></label>
                            </div>
                            <div class="col-lg-4 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <input class="form-control" name="input_id" type="text" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <p class="text-strong panel-title"> Your JOB information</p>
        <div class="panel panel-default">
            <div class="panel-body form-group">
                <div class="panel-body form-group">
                    <div class="row">
                        <div class="col-lg-2 col-sm-12 col-xs-12">
                            <label><h4>Task name:</h4></label>
                        </div>
                        <div class="col-lg-4 col-sm-12 col-xs-12">
                            <div class="form-group is-empty">
                                <input class="form-control" type="text" name="task_name" id="task">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-sm-12 col-xs-12">
                            <label><h4>E-mail：</h4></label>
                        </div>
                        <div class="col-lg-4 col-sm-12 col-xs-12">
                            <div class="form-group is-empty">
                                <input type="text" name="email" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-sm-12 col-xs-12">
                            <label><h4>Password:</h4></label>
                        </div>
                        <div class="col-lg-4 col-sm-12 col-xs-12">
                            <div class="form-group is-empty">
                                <input type="password" name="password" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-sm-12 col-xs-12"></div>
                        <div class="col-lg-4 col-sm-12 col-xs-12">
                            <button class="btn btn-primary btn_17b55d" type="submit">Submit
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
