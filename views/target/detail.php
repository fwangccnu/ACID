<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="#">Target </a></li>
            <li class="active">Target Detail</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-lg-8 col-sm-12 col-xs-12">
                    <div class="row" style=" border-bottom: 1px solid #e6e6e6;">
                        <div class="col-lg-6 col-sm-12 col-xs-12 ">
                            <h4 class="_17b55d">Uniprot ID:</h4>
                            <p><?php echo "<a href='http://www.uniprot.org/uniprot/" . $target['protID'] . "' target='_blank'>" . $target['protID'] . "</a>" ?></p>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            <h4 class="_17b55d">Resolution</h4>
                            <p><?php echo $target['resolu']; ?></p>
                        </div>
                    </div>
                    <div class="row" style=" border-bottom: 1px solid #e6e6e6;">
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            <h4 class="_17b55d">PDB code</h4>
                            <p><?php echo "<a href='http://www.rcsb.org/pdb/explore/explore.do?structureId=" . $target['pdbID'] . "' target='_blank'>" . $target['pdbID'] . "</a>" ?></p>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            <h4 class="_17b55d">Chain</h4>
                            <p><?php echo $target['chain']; ?></p>
                        </div>
                    </div>
                    <div class="row" style=" border-bottom: 1px solid #e6e6e6;">
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            <h4 class="_17b55d">Residue number</h4>
                            <p><?php echo $target['resiNum']; ?></p>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            <h4 class="_17b55d">Protein name</h4>
                            <p><?php echo $target['prot_name']; ?></p>
                        </div>
                    </div>
                    <div class="row" style=" border-bottom: 1px solid #e6e6e6;">
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            <h4 class="_17b55d">Classification</h4>
                            <p><?php echo $target['classification']; ?></p>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            <h4 class="_17b55d">Pfam</h4>
                            <p><?php echo $target['pfam']; ?></p>
                        </div>
                    </div>
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
                            <h4 class="_17b55d">Pfam</h4>
                            <p><?php echo $target['pfam']; ?></p>
                        </div>
                    </div>
                    <div class="row" style=" border-bottom: 1px solid #e6e6e6;">
                        <div class="col-lg-12 col-sm-12 col-xs-12">
                            <h4 class="_17b55d">Molecular function</h4>
                            <p style="text-align: justify; word-wrap:break-word">
                                <?php echo $target['mol_func']; ?>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-xs-12">
                            <h4 class="_17b55d">Sequence</h4>
                            <p style="text-align: justify; word-wrap:break-word"><?php echo $target['sequence']; ?></p>
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


