<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <p class="text-strong panel-title">
            Show pose Job ID : <?php echo $job['job_id']; ?> Ligand name : <?php echo $job['ligand']; ?>
        </p>
    </div>
</div>

<div class="row">
        <div class="col-lg-12 col-sm-12 col-xs-12" style="text-align: center">
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

                var mol_file = '<?php echo base_url(array('uploads', $job['job_id'], $reversedock['file_path']));?>';
                var LIG = 'LIG';
                var radius = 4.0;
                var size = 700;

                function getScript(radius) {
                    var scrip_ = "select within(group,within(" + radius + ",[" + LIG + "])) and not [" + LIG + "];";
                    scrip_ += "hide ((all and not (within(group,within(" + radius + ",[" + LIG + "])))) or (ligand and not [" + LIG + "]) or (_H and within(1.5,_C) and not [" + LIG + "].Hxx));";
                    scrip_ += "cartoons off;color rasmol;wireframe -0.2;";
                    scrip_ += "select *.CA and within(group,within(" + radius + ",[" + LIG + "])) and not [" + LIG + "];";
                    scrip_ += "label %n%r;color label red;center [" + LIG + "];zoom " + size + ";";
                    scrip_ += "select " + LIG + ";		color carbon yellow;";
                    scrip_ += "select [" + LIG + "].Hxx;	color black;label link point;spacefill 150;";
                    scrip_ += "select *;			calculate hbond;";

                    return scrip_;
                }

                var Info = {
                    width: 800,
                    height: 600,
                    debug: false,
                    color: "0xFFFFFF",
                    addSelectionOptions: false,
                    use: "HTML5",
                    j2sPath: "<?php echo base_url(array('resource', 'plugin', 'jsmol', 'j2s')); ?>", // this needs to point to where the j2s directory is.
                    readyFunction: jmol_isReady,
                    script: "set antialiasDisplay; load " + mol_file + "; " + getScript(radius),

                    disableJ2SLoadMonitor: true,
                    disableInitialConsole: true,
                    allowJavaScript: true
                }


                $(document).ready(function () {
                    $("#appdiv").html(Jmol.getAppletHtml("jmolApplet0", Info))
                })
                var lastPrompt = 0;
            </script>
            <div id="appdiv" style="z-index: 200;text-align: center;"></div>
        </div>
</div>

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <table class="table table-bordered table-striped">
            <thead class="bg_ecfff4 _17b55d">
            <tr style="font-size: 16px">
                <th class="text-left" width="15%">Uniprot ID<span class=""></span></th>
                <th class="text-left" width="15%">PDB code<span class=""></span></th>
                <th class="text-left" width="15%">Site</th>
                <th width="10%">ΔE<sub>MM</sub></th>
                <th width="10%">ΔG<sub>sol</sub></th>
                <th width="10%">ΔE<sub>bind</sub></th>
                <th width="15%">Dock score</th>
                <th class="text-left" width="10%">Download</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <?php echo "<a href='http://www.uniprot.org/uniprot/" . $reversedock['protID'] . "' target='_blank'>" . $reversedock['protID'] . "</a>" ?>
                </td>
                <td>
                    <?php echo "<a href='http://www.rcsb.org/pdb/explore/explore.do?structureId=" . $reversedock['pdb'] . "' target='_blank'>" . $reversedock['pdb'] . "</a>" ?>
                </td>
                <td>
                    <?php echo $reversedock['site']; ?>
                </td>
                <td>
                    <?php echo $reversedock['GAS']; ?>
                </td>
                <td>
                    <?php echo $reversedock['PBSOL']; ?>
                </td>
                <td>
                    <?php echo $reversedock['PBTOT']; ?>
                </td>
                <td>
                    <?php echo $reversedock['dock_score']; ?>
                </td>
                <td>
                    <a href="<?php echo base_url(array('uploads', $job['job_id'], $reversedock['file_path'])); ?>">Download</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-lg-8 col-sm-12 col-xs-12">
                    <div class="row" style=" border-bottom: 1px solid #e6e6e6;">
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            <h4 class="_17b55d">Gene name</h4>
                            <p><?php echo $target['gene_name']; ?></p>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            <h4 class="_17b55d">Protein name</h4>
                            <p><?php echo $target['prot_name']; ?></p>
                        </div>
                    </div>
                    <div class="row" style=" border-bottom: 1px solid #e6e6e6;">
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            <h4 class="_17b55d">Catalytic Activity</h4>
                            <p><?php echo $target['catalytics']; ?></p>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            <h4 class="_17b55d">Molecular function</h4>
                            <p style="text-align: justify; word-wrap:break-word">
                                <?php echo $target['mol_func']; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-xs-12">
                            <h4 class="_17b55d">Biological process</h4>
                            <p style="text-align: justify; word-wrap:break-word">
                                <?php echo $target['bio_process']; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
