<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <p class="text-strong panel-title">
            ReverseDock job detail / JOB ID : <?php echo $job['job_id'] ?> / Ligand name : <?php echo $job['ligand'] ?>
        </p>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div>
                    <a href="<?php echo base_url(array('uploads', $job['job_id'], 'LIG.jpg')); ?>" download="pic">
                        <img src="<?php echo base_url(array('uploads', $job['job_id'], 'LIG.jpg')); ?>" alt="pic">
                    </a>
                    <p style="text-align: center;">Active compound name: <span style="color: red"><?php echo $job['ligand'] ?></span></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div>
                    <p class="text-strong panel-title">
                        <script type="text/javascript"
                                src="<?php echo base_url(array('resource', 'plugin', 'jsmol', 'JSmol.min.js')); ?>"></script>
                        <script type="text/javascript">
                            var jmolApplet0;
                            // set up in HTML table, below
                            var s = document.location.search;
                            Jmol._debugCode = (s.indexOf("debugcode") >= 0);
                            jmol_isReady = function (applet) {
                                document.title = (applet._id + " - Jmol " + ___JmolVersion)
                                Jmol._getElement(applet, "appletdiv").style.border = "2px solid blue"
                            }
                            var Info = {
                                width: 500,
                                height: 490,
                                debug: false,
                                color: "0xFFFFFF",
                                use: "HTML5", // JAVA HTML5 WEBGL are all options
                                j2sPath: "<?php echo base_url(array('resource', 'plugin', 'jsmol', 'j2s')); ?>", // this needs to point to where the j2s directory is.
                                jarPath: "<?php echo base_url(array('resource', 'plugin', 'jsmol', 'java')); ?>", // this needs to point to where the java directory is.
                                jarFile: "JmolAppletSigned.jar",
                                isSigned: true,
                                script: "set zoomlarge false;set antialiasDisplay;load '<?php echo base_url(array('uploads',$job['job_id'], 'LIG.pdb'));?>';select *",
                                readyFunction: jmol_isReady,
                                disableJ2SLoadMonitor: true,
                                disableInitialConsole: true,
                                allowJavaScript: true
                            }
                            $(document).ready(function () {
                                $("#appdiv").html(Jmol.getAppletHtml("jmolApplet0", Info))
                            })
                            var lastPrompt = 0;
                        </script>
                    <div id="appdiv" style="z-index: 200;"></div>
                    <div style="text-align: center;">
                        <a href='<?php echo base_url(array('uploads', $job['job_id'], 'LIG.pdb')); ?>'
                           title='download file!'>[Download File]</a>
                    </div>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <table class="table table-bordered table-striped">
            <thead class="bg_ecfff4 _17b55d">
            <tr style="font-size: 16px">
                <?php function get_order($order_url, $key)
                {
                    foreach ($order_url as $o) {
                        if (strcasecmp($o[0], $key) == 0) {
                            return $o[1];
                        }
                    }
                } ?>
                <th class="text-left" width="10%">Uniprot ID<span class=""></span></th>
                <th class="text-left" width="15%">PDB code<span class=""></span></th>
                <th class="text-left" width="10%"><a href="#" data-toggle="tooltip" data-placement="top"
                                                     title="position where ligand bind with protein">Site</a></th>
                <?php if (get_order($order_url, "GAS") == 0) { ?>
                    <th width="10%"><a href="<?php echo site_url(array('reversedock', 'detail', 'GAS', 0)) ?>"
                                       data-toggle="tooltip" data-placement="top"
                                       title="electrostatic + vdW energy">ΔE<sub>MM</sub>
                            <span class="glyphicon glyphicon-arrow-down"></span></a></th>
                <?php } else { ?>
                    <th width="10%"><a href="<?php echo site_url(array('reversedock', 'detail', 'GAS', 1)) ?>"
                                       data-toggle="tooltip" data-placement="top"
                                       title="electrostatic + vdW energy">ΔE<sub>MM</sub>
                            <span class="glyphicon glyphicon-arrow-up"></span></a></th>
                <?php } ?>
                <?php if (get_order($order_url, "PBSOL") == 0) { ?>
                    <th width="10%"><a href="<?php echo site_url(array('reversedock', 'detail', 'PBSOL', 0)) ?>"
                                       data-toggle="tooltip" data-placement="top"
                                       title="solvation energy">ΔG<sub>sol</sub>
                            <span class="glyphicon glyphicon-arrow-down"></span></a></th>
                <?php } else { ?>
                    <th width="10%"><a href="<?php echo site_url(array('reversedock', 'detail', 'PBSOL', 1)) ?>"
                                       data-toggle="tooltip" data-placement="top"
                                       title="solvation energy">ΔG<sub>sol</sub>
                            <span class="glyphicon glyphicon-arrow-up"></span></a></th>
                <?php } ?>
                <?php if (get_order($order_url, "PBTOT") == 0) { ?>
                    <th width="10%"><a href="<?php echo site_url(array('reversedock', 'detail', 'PBTOT', 0)) ?>"
                                       data-toggle="tooltip" data-placement="top"
                                       title="binding energy">ΔE<sub>bind</sub>
                            <span class="glyphicon glyphicon-arrow-down"></span></a></th>
                <?php } else { ?>
                    <th width="10%"><a href="<?php echo site_url(array('reversedock', 'detail', 'PBTOT', 1)) ?>"
                                       data-toggle="tooltip" data-placement="top"
                                       title="binding energy">ΔE<sub>bind</sub>
                            <span class="glyphicon glyphicon-arrow-up"></span></a></th>
                <?php } ?>
                <?php if (get_order($order_url, "dock_score") == 0) { ?>
                    <th width="15%"><a href="<?php echo site_url(array('reversedock', 'detail', 'dock_score', 0)) ?>">Dock
                            score
                            <span class="glyphicon glyphicon-arrow-down"></span></a></th>
                <?php } else { ?>
                    <th width="15%"><a href="<?php echo site_url(array('reversedock', 'detail', 'dock_score', 1)) ?>">Dock
                            score
                            <span class="glyphicon glyphicon-arrow-up"></span></a></th>
                <?php } ?>
                <th class="text-left" width="10%">Show Pose<span class=""></span></th>
                <th class="text-left" width="10%">Download</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($reversedock as $item) { ?>
                <tr>
                    <td>
                        <?php echo "<a href='http://www.uniprot.org/uniprot/" . $item['protID'] . "' target='_blank'>" . $item['protID'] . "</a>" ?>
                    </td>
                    <td>
                        <?php echo "<a href='http://www.rcsb.org/pdb/explore/explore.do?structureId=" . $item['pdb'] . "' target='_blank'>" . $item['pdb'] . "</a>" ?>
                    </td>
                    <td>
                        <?php echo $item['site']; ?>
                    </td>
                    <td>
                        <?php echo $item['GAS']; ?>
                    </td>
                    <td>
                        <?php echo $item['PBSOL']; ?>
                    </td>
                    <td>
                        <?php echo $item['PBTOT']; ?>
                    </td>
                    <td>
                        <?php echo $item['dock_score']; ?>
                    </td>
                    <td>
                        <a href="<?php echo site_url(array('reversedock', 'show_pose', $item['protID'])); ?>">Show</a>
                    </td>
                    <td>
                        <a href="<?php echo base_url(array('uploads', $job['job_id'], $item['file_path'])); ?>">Download</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php if($total_page>1){ ?>
<form action="<?PHP echo site_url(array('reversedock', 'select_page')); ?>" method="post">
    <div class="row">
        <div class="col-lg-9 col-sm-12 col-xs-12">
            <?php echo $link; ?>
        </div>
        <div class="col-lg-2 col-sm-12 col-xs-12">
            <div class="row">
                <div class="form-group">
                    <label class="col-lg-6 col-sm-12 col-xs-12 control-label"><h4>Page:</h4></label>
                    <div class="col-lg-6 col-sm-12 col-xs-12">
                        <input type="text" name="n_page" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-1 col-sm-12 col-xs-12">
            <button type="submit" class="btn btn-primary btn_17b55d form-control">Go >></button>
        </div>
    </div>
</form>
<?php } ?>

<div class="row">
    <div class="col-lg-1 col-sm-12 col-xs-12">
        <?php echo $this->lang->line("mark_total_rows_title"); ?>
        <?php echo $total_rows; ?>
        ,
        <?php echo $this->lang->line("mark_total_page_title"); ?>
        <?php echo $total_page; ?>
    </div>
</div>

