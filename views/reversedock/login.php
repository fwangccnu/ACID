
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <form action="<?php echo site_url(array('reversedock','check'))?>" method="post" accept-charset="utf-8">
            <div class="row" style="margin-top: 20px">
                <div class="col-lg-6 col-md-12 col-xs-12 text-left">
                    <img src="<?php echo base_url(array('resource','img','login.png')); ?>">
                </div>
                <div class="col-lg-6 col-md-12 col-xs-12 login-form">
                    <div class="panel  panel-default">
                        <div class="panel-body">
                            <h4 class="media-heading">CRD Login</h4>

                            <div class="form-group">
                                <label class="active"></label>
                                <input class="form-control validate" name="job_id" value="<?php echo $job['job_id']?>" readonly="readonly">
                            </div>
                            <div class="form-group is-empty">
                                <label class="active"></label>
                                <input type="password" class="form-control" name="password" placeholder="Password" value="">
                            </div>
                            <div class="text-center">
                                <button type="submit" id="loginBtn" class="btn btn-info button-padfrag btn_17b55d">Login</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
