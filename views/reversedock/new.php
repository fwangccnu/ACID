<form action="<?php echo site_url(array('reversedock', 'save')); ?>" method="post" enctype="multipart/form-data"
      id="formJobs">
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-xs-12">
                    <h3 style="text-align: center">
                        Consensus Inverse Docking for Drug Repurposing
                    </h3>
                </div>
            </div>
            <p class="text-strong panel-title"><b>1.Select Target Sets</b> (which your active compound most likely to
                interact with)</p>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-lg-3 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-lg-9 col-sm-12 col-xs-12 control-label text-right">Oxidoreductase</label>
                                <div class="col-lg-3 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="target_sets[]" value="OXIDOREDUCTASE"
                                           class="checkboxTargetsets">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-lg-9 col-sm-12 col-xs-12 control-label text-right">Transferase</label>
                                <div class="col-lg-3 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="target_sets[]" value="TRANSFERASE"
                                           class="checkboxTargetsets">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-lg-9 col-sm-12 col-xs-12 control-label text-right">Hydrolase</label>
                                <div class="col-lg-3 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="target_sets[]" value="HYDROLASE"
                                           class="checkboxTargetsets">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-lg-9 col-sm-12 col-xs-12 control-label text-right">Others</label>
                                <div class="col-lg-3 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="target_sets[]" value="OTHERS"
                                           class="checkboxTargetsets">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-lg-9 col-sm-12 col-xs-12 control-label text-right">Lyase</label>
                                <div class="col-lg-3 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="target_sets[]" value="LYASE"
                                           class="checkboxTargetsets">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-lg-9 col-sm-12 col-xs-12 control-label text-right">Transport
                                    protein</label>
                                <div class="col-lg-3 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="target_sets[]" value="TRANSPORT_PROT"
                                           class="checkboxTargetsets">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-lg-9 col-sm-12 col-xs-12 control-label text-right">Signaling
                                    protein</label>
                                <div class="col-lg-3 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="target_sets[]" value="SIGNALING_PROT"
                                           class="checkboxTargetsets">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-lg-9 col-sm-12 col-xs-12 control-label text-right">Ligase</label>
                                <div class="col-lg-3 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="target_sets[]" value="LIGASE"
                                           class="checkboxTargetsets">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-lg-9 col-sm-12 col-xs-12 control-label text-right">Isomerase</label>
                                <div class="col-lg-3 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="target_sets[]" value="ISOMERASE"
                                           class="checkboxTargetsets">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-lg-9 col-sm-12 col-xs-12 control-label text-right">Transcription</label>
                                <div class="col-lg-3 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="target_sets[]" value="TRANSCRIPTION"
                                           class="checkboxTargetsets">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-lg-9 col-sm-12 col-xs-12 control-label text-right">Immune
                                    protein</label>
                                <div class="col-lg-3 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="target_sets[]" value="IMMUNE_SYS_PROT"
                                           class="checkboxTargetsets">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-lg-9 col-sm-12 col-xs-12 control-label text-right">Membrane
                                    protein</label>
                                <div class="col-lg-3 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="target_sets[]" value="MEMBRANE_PROT"
                                           class="checkboxTargetsets">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-lg-9 col-sm-12 col-xs-12 control-label text-right">Metal
                                    binding</label>
                                <div class="col-lg-3 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="target_sets[]" value="METAL_BINDING"
                                           class="checkboxTargetsets">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-lg-9 col-sm-12 col-xs-12 control-label text-right">Protein
                                    binding</label>
                                <div class="col-lg-3 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="target_sets[]" value="PROTEIN_BINDING"
                                           class="checkboxTargetsets">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-lg-9 col-sm-12 col-xs-12 control-label text-right">Cell
                                    adhesion</label>
                                <div class="col-lg-3 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="target_sets[]" value="CELL_ADHESION"
                                           class="checkboxTargetsets">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-lg-9 col-sm-12 col-xs-12 control-label text-right">Hormone</label>
                                <div class="col-lg-3 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="target_sets[]" value="HORMONE"
                                           class="checkboxTargetsets">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-lg-9 col-sm-12 col-xs-12 control-label text-right">ALL</label>
                                <div class="col-lg-3 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="target_sets[]" value="ALL" class="checkboxTargetsets"
                                           id="allExample">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-sm-12 col-xs-12">
                        <script src="<?php echo base_url(array('resource', 'plugin', 'Chart.js', 'Chart.bundle.min.js')); ?>"></script>
                        <script src="<?php echo base_url(array('resource', 'plugin', 'Chart.js', 'utils.js')); ?>"></script>
                        <div id="container" style="width: 100%;">
                            <canvas id="canvas"></canvas>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12 col-sm-12 col-xs-12">
            <p class="text-strong panel-title"><b>2.Provide active compound</b></p>
            <ul id="myTab" class="nav nav-tabs">
                <li class="active">
                    <a href="#byChemicalStructure" data-toggle="tab"
                       title="Draw your active compound in the window below">
                        Draw compound
                    </a>
                </li>
                <li><a href="#byFile" data-toggle="tab" title="upload molecule file of your active compound">Or upload
                        compound</a></li>
            </ul>
            <div id="myTabContent" class="tab-content">
                <div class="tab-pane fade in active" id="byChemicalStructure">
                    <div class="panel panel-default">
                        <div class="panel-body form-group">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 col-xs-12">

                                    <?php if(isset($drug)){ ?>
                                        <img src="<?php echo base_url(array('uploads', 'png', $drug['MOLID'] . '.jpg')); ?>" class="img-responsive center-block">
                                    <?php }else{ ?>
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-xs-12">
                                            <div id="appletContainer"></div>
                                            <script type="text/javascript"
                                                    src="<?php echo base_url(array('resource', 'plugin', 'jsmol', 'jsme', 'jsme', 'jsme.nocache.js')); ?>"></script>
                                            <script type="text/javascript">
                                                var jsmeInstance;

                                                //Init JSME
                                                function jsmeOnLoad() {
                                                    jsmeInstance = new JSApplet.JSME("appletContainer", "480px", "480px", {
                                                        "options": "query,hydrogens"
                                                    });
                                                }

                                                function setSDF() {
                                                    param = jsmeInstance.molFile();
                                                    document.getElementById("SDF_textarea").value = param;
                                                }
                                            </script>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12 col-xs-12" style="margin-top: 20px">
                                            <button type="button" class="btn btn-primary btn_17b55d" onclick="setSDF();"
                                                    title="Click to get molecule">Get SDF
                                            </button>
                                        </div>
                                    </div>
                                    <?php } ?>

                                </div>
                                <div class="col-lg-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <h4>Your molecule:</h4>
                                        <?php if(isset($drug)){ ?>
                                            <textarea class="form-control" rows="18" name="sdf" id="SDF_textarea"><?php echo $drug['SDF'];?></textarea>
                                        <?php }else{ ?>
                                        <textarea class="form-control" rows="18" name="sdf" id="SDF_textarea"
                                                  placeholder="Your molecule in SDF format (you can copy the content and store in a SDF file)"></textarea>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="byFile">
                    <div class="panel panel-default">
                        <div class="panel-body form-group">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">upload your active molecule<br>( SDF|PDB|MOL2
                                    format):</label>
                                <div class="col-sm-9">
                                    <input type="file" name="userfile" class="">
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
            <p class="text-strong panel-title"><b>3.Your JOB information</b></p>
            <div class="panel panel-default">
                <div class="panel-body form-group">
                    <div class="panel-body form-group">
                        <div class="row">
                            <div class="col-lg-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="col-lg-4 col-sm-12 col-xs-12 control-label"></label>
                                    <div class="col-lg-4 col-sm-12 col-xs-12">
                                        <button type="button" class="btn btn-info form-control" id="btnMoreOption">
                                            Advanced Options >>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="display: none;" id="moreOption">
                            <div class="row">
                                <div class="col-lg-4 col-sm-12 col-xs-12">
                                    <label>Ligand name:</label>
                                </div>
                                <div class="col-lg-4 col-sm-12 col-xs-12">
                                    <?php if(isset($drug)){ ?>
                                        <input type="text" class="form-control" name="ligand" value="<?php echo $drug['NAME1'];?>">
                                    <?php }else{ ?>
                                        <input type="text" class="form-control" name="ligand"
                                               placeholder="molecule name of your active compound(optional)">
                                    <?php } ?>
                                </div>
                                <div class="col-lg-1 col-sm-12 col-xs-12">
                                    <button type="button" class="btn btn-info form-control" data-container="body"
                                            data-toggle="popover" data-placement="right"
                                            data-content="molecule name of your active compound(optional)">
                                        <span class="glyphicon glyphicon-question-sign"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-sm-12 col-xs-12">
                                    <label>E-mail：</label>
                                </div>
                                <div class="col-lg-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control"
                                               placeholder="E-mail to keep you informed(optional)">
                                    </div>
                                </div>
                                <div class="col-lg-1 col-sm-12 col-xs-12">
                                    <button type="button" class="btn btn-info form-control" data-container="body"
                                            data-toggle="popover" data-placement="right"
                                            data-content="E-mail to keep you informed(optional)">
                                        <span class="glyphicon glyphicon-question-sign"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-sm-12 col-xs-12">
                                    <label>Password:</label>
                                </div>
                                <div class="col-lg-4 col-sm-12 col-xs-12">
                                    <div class="form-group is-empty">
                                        <input type="password" name="password" class="form-control"
                                               placeholder="Password to check results of your Job(optional)">
                                    </div>
                                </div>
                                <div class="col-lg-1 col-sm-12 col-xs-12">
                                    <button type="button" class="btn btn-info form-control" data-container="body"
                                            data-toggle="popover" data-placement="right"
                                            data-content="Password to check results of your Job(optional)">
                                        <span class="glyphicon glyphicon-question-sign"></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 col-sm-12 col-xs-12"></div>
                            <div class="col-lg-4 col-sm-12 col-xs-12">
                                <button class="btn btn-primary btn_17b55d form-control" type="submit">
                                    Submit
                                </button>
                            </div>
                            <div class="col-lg-2 col-sm-12 col-xs-12">
                                <input type="hidden" name="example" value="0" id="example">
                                <button type="button" class="btn btn-info form-control" id="btnExample">Run an example
                                </button>
                                <div class="modal fade" id="loading" tabindex="-1" role="dialog"
                                     aria-labelledby="myModalLabel" data-backdrop='static'>
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background-color: #5bc0de;color: #ffffff;">
                                                <h3 class="modal-title" id="myModalLabel">Run an example</h3>
                                            </div>
                                            <div class="modal-body">
                                                Please wait 3 seconds......
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
			    <div class="col-lg-2 col-sm-12 col-xs-12">
                                <a href="<?php echo base_url(array('resource','plugin','example','ACID_Example.sdf')); ?>" class="btn btn-primary btn_17b55d form-control">
                                    Or download example
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>

    <script>
        var backgroundColor = [];
        for (var i = 0; i < 16; i++) {
            backgroundColor.push('rgb(192, 192, 192)')
        }
        var barChartData = {
            labels: ['Oxidoreductase', 'Transferase', 'Hydrolase', 'Others', 'Lyase', 'Tranport', 'Signaling', 'Ligase', 'Isomerase', 'Transcription', 'Immune protein', 'membrane protein', 'Metal binding', 'Protein binding', 'Cell adhesion', 'Hormone'],
            datasets: [{
                backgroundColor: backgroundColor,
                data: [21.6, 21, 17.8, 8.3, 4.4, 3.9, 3.8, 3.7, 3, 2.7, 2.2, 2.1, 1.7, 1.6, 1.5, 1]
            }]
        };

        window.onload = function () {
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'horizontalBar',
                data: barChartData,
                options: {
                    responsive: true,
                    legend: {
                        display: false,
                    },
                    title: {
                        display: true,
                        text: 'Target sets (%)',
                    }
                }
            });

        };

        var checkboxID = ['OXIDOREDUCTASE', 'TRANSFERASE', 'HYDROLASE', 'OTHERS', 'LYASE', 'TRANSPORT_PROT', 'SIGNALING_PROT', 'LIGASE', 'ISOMERASE', 'TRANSCRIPTION', 'IMMUNE_SYS_PROT', 'MEMBRANE_PROT', 'METAL_BINDING', 'PROTEIN_BINDING', 'CELL_ADHESION', 'HORMONE'];
        var checkboxTargetsets = document.getElementsByClassName('checkboxTargetsets');
        for (var i = 0; i < checkboxTargetsets.length; i++) {
            checkboxTargetsets[i].addEventListener('click', function () {
                if ('ALL'.indexOf(this.value) > -1) {
                    var checkboxTargetsets = document.getElementsByClassName('checkboxTargetsets');
                    if (this.checked == true) {
                        for (var i = 0; i < checkboxTargetsets.length; i++) {
                            checkboxTargetsets[i].checked = true;
                            if (i < checkboxID.length) {
                                barChartData.datasets[0].backgroundColor[i] = 'rgb(0, 189, 91)';
                            }
                        }
                    } else {
                        for (var i = 0; i < checkboxTargetsets.length; i++) {
                            checkboxTargetsets[i].checked = false;
                            if (i < checkboxID.length) {
                                barChartData.datasets[0].backgroundColor[i] = 'rgb(192, 192, 192)';
                            }
                        }
                    }
                } else {
                    var targetSet = null;
                    for (var j = 0; j < checkboxID.length; j++) {
                        if (checkboxID[j].indexOf(this.value) > -1) {
                            targetSet = j;
                        }
                    }
                    if (this.checked == true) {
                        barChartData.datasets[0].backgroundColor[targetSet] = 'rgb(0, 189, 91)';
                    } else {
                        barChartData.datasets[0].backgroundColor[targetSet] = 'rgb(192, 192, 192)';
                    }
                }
                window.myBar.update();
            });
        }


        var btnExample = document.getElementById('btnExample');
        btnExample.addEventListener('click', function () {
            var SDF_textarea = document.getElementById('SDF_textarea');
            SDF_textarea.value = 'CN(C)CCC=C2c1ccccc1CCc3ccccc23\n' +
                'JME 2015-12-06 Wed Oct 27 15:52:00 GMT+800 2017\n' +
                ' \n' +
                ' 21 23  0  0  0  0  0  0  0  0999 V2000\n' +
                '    6.1957    3.9933    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\n' +
                '    7.0686    5.0879    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\n' +
                '    6.7570    6.4528    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\n' +
                '    5.4957    7.0602    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\n' +
                '    4.2343    6.4528    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\n' +
                '    3.9228    5.0879    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\n' +
                '    4.7957    3.9933    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\n' +
                '    3.2081    7.4050    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\n' +
                '    1.8703    6.9924    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\n' +
                '    1.5587    5.6275    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\n' +
                '    2.5850    4.6752    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\n' +
                '    6.7072    2.6901    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\n' +
                '    8.0915    2.4814    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\n' +
                '    8.9644    3.5760    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\n' +
                '    8.4529    4.8792    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\n' +
                '    4.1883    2.7320    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\n' +
                '    2.7922    2.6273    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\n' +
                '    2.1847    1.3660    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\n' +
                '    0.7886    1.2614    0.0000 N   0  0  0  0  0  0  0  0  0  0  0  0\n' +
                '    0.1812    0.0000    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\n' +
                '    0.0000    2.4181    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\n' +
                '  1  2  1  0  0  0  0\n' +
                '  2  3  1  0  0  0  0\n' +
                '  3  4  1  0  0  0  0\n' +
                '  4  5  1  0  0  0  0\n' +
                '  5  6  1  0  0  0  0\n' +
                '  6  7  1  0  0  0  0\n' +
                '  7  1  1  0  0  0  0\n' +
                '  8  9  1  0  0  0  0\n' +
                '  9 10  2  0  0  0  0\n' +
                ' 10 11  1  0  0  0  0\n' +
                '  6 11  2  0  0  0  0\n' +
                '  5  8  2  0  0  0  0\n' +
                ' 12 13  1  0  0  0  0\n' +
                ' 13 14  2  0  0  0  0\n' +
                ' 14 15  1  0  0  0  0\n' +
                '  2 15  2  0  0  0  0\n' +
                '  1 12  2  0  0  0  0\n' +
                '  7 16  2  0  0  0  0\n' +
                ' 16 17  1  0  0  0  0\n' +
                ' 17 18  1  0  0  0  0\n' +
                ' 18 19  1  0  0  0  0\n' +
                ' 19 20  1  0  0  0  0\n' +
                ' 19 21  1  0  0  0  0\n' +
                'M  END\n';

            var checkboxTargetsets = document.getElementsByClassName('checkboxTargetsets');

            for (var i = 0; i < checkboxTargetsets.length; i++) {
                if (i == 11 || i == 15) {
                    checkboxTargetsets[i].checked = true;
                    if (i < checkboxID.length) {
                        barChartData.datasets[0].backgroundColor[i] = 'rgb(0, 189, 91)';
                    }
                }
            }
            window.myBar.update();

            $("#example").val(1);
            $('#loading').modal('show');
            setTimeout(function () {
                $('#loading').modal('hide');
            }, 3000);
            $('html,body').animate({scrollTop: 0}, 1000);
            setTimeout(function () {
                $("#formJobs").submit();
            }, 1000);

        });

    </script>
