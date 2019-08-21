<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <p class="text-strong panel-title">Targets</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12" id="IUPACname">
        <div class="panel panel-default">
            <div class="panel-body form-group">
                <form action="<?PHP echo site_url(array('target', 'search_query')); ?>" method="post">
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            <div class="form-group is-empty">
                                Key words:
                                <input type="text" name="keyword" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12 col-xs-12">
                            Filter by :
                                <select name="select" class="form-control">
                                <option value="ALL" selected="ALL">ALL</option>
                                <option value="protID">Uniprot ID</option>
                                <option value="PdbID">PDB code</option>
                                <option value="gene_name">Gene name</option>
                                <option value="sequence">Sequence</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-sm-12 col-xs-12">
                            Go to:
                            <button type="submit" class="btn btn-primary btn_17b55d form-control">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="bg_ecfff4 _17b55d">
                <tr style="font-size: 16px">
                    <th class="text-left" width="10%">Uniprot ID</th>
                    <th class="text-left" width="10%">PDB code</th>
                    <th class="text-left" width="15%">Resolution</th>
                    <th class="text-left" width="10%">Chain</th>
                    <th class="text-left" width="10%">Classification</th>
                    <th class="text-left" width="35%">Protein name</th>
                    <th class="text-left" width="20%">Gene name</th>
                    <th class="text-left" width="45%">Pfam</th>
                </tr>
                </thead>
                <tbody>

                <?php if (count($list_target)) { ?>
                    <?php foreach ($list_target as $target) { ?>
                        <tr>
                            <td>
                                <a href="<?php echo site_url(array('target', 'target_detail', $target['protID'])) ?>">
                                    <?php echo $target['protID']; ?>                                             </a>
                            </td>
                            <td>
                                <?php echo "<a href='http://www.rcsb.org/pdb/explore/explore.do?structureId=" . $target['pdbID'] . "' target='_blank'>" . $target['pdbID'] . "</a>" ?>
                            </td>
                            <td>
                                <?php echo $target['resolu']; ?>
                            </td>
                            <td>
                                <?php echo $target['chain']; ?>
                            </td>
                            <td>
                                <?php echo $target['classification']; ?>
                            </td>
                            <td>
                                <?php echo $target['prot_name']; ?>
                            </td>
                            <td>
                                <?php echo $target['gene_name']; ?>
                            </td>
                            <td>
                                <?php echo $target['pfam']; ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<form action="<?PHP echo site_url(array('target', 'select_page')); ?>" method="post">
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

<div class="row">
    <div class="col-lg-1 col-sm-12 col-xs-12">
        <?php echo $this->lang->line("mark_total_rows_title"); ?>
        <?php echo $total_rows; ?>
        ,
        <?php echo $this->lang->line("mark_total_page_title"); ?>
        <?php echo $total_page; ?>
    </div>
</div>


