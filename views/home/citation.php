<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <p class="text-strong panel-title">
            Citation
        </p>
        <div class="panel panel-default">
            <div class="panel-body">
                <h4> Hao, G.-F.; Jiang, W.; Ye, Y.-N.; Wu, F.-X.; Zhu, X.-L.; Guo, F.-B.; Yang, G.-F., ACFIS: a web
                    server for fragment-based drug discovery. Nucleic Acids Res 2016, 44 (W1), W550-W556.
                    <a href="http://academic.oup.com/nar/article/44/W1/W550/2499367">URL</a></h4>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <p class="text-strong panel-title">
            Contact
        </p>
        <div class="panel panel-default">
            <div class="panel-body">
                <?php echo form_open('home/suggestion'); ?>
                <div class="row">
                    <div class="col-lg-4 col-sm-12 col-xs-12">
                        <label>Your E-mail:</label>
                    </div>
                    <div class="col-lg-4 col-sm-12 col-xs-12">
                        <div class="form-group is-empty">
                            <input class="form-control" id="email" name="email" placeholder="Enter your e-mail"
                                   type="text">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-sm-12 col-xs-12">
                        <label>Suggestions or Problems:</label>
                    </div>
                    <div class="col-lg-6 col-sm-12 col-xs-12">
                        <div class="form-group is-empty">
                            <textarea class="form-control" rows="6" id="suggestion" name="suggestion"
                                      placeholder="Enter Suggestions or Problems"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-xs-2 col-xs-offset-4 text-center">
                    <button type="submit" class="btn btn-primary btn_17b55d"
                            style="margin: 20px 0;padding: 10px 30px;display: block;width: 100%">
                        Submit
                    </button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
