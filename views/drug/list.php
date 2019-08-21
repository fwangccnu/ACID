<?php
if (count($list_drug)) {
    ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <?php function get_order($order_url, $key)
                        {
                            foreach ($order_url as $o) {
                                if (strcasecmp($o[0], $key) == 0) {
                                    return $o[1];
                                }
                            }
                        } ?>
                        <?php if (get_order($order_url, "MOLID") == 0) { ?>
                            <th class="text-left" width="10%"><a
                                        href="<?php echo site_url(array("drug", "index", "MOLID", 0)); ?>">ID LINK <span
                                            class="glyphicon glyphicon-arrow-down"></span></a></th>
                        <?php } else { ?>
                            <th class="text-left" width="10%"><a
                                        href="<?php echo site_url(array("drug", "index", "MOLID", 1)); ?>">ID LINK <span
                                            class="glyphicon glyphicon-arrow-up"></span></a></th>
                        <?php } ?>
                        <?php if (get_order($order_url, "NAME1") == 0) { ?>
                            <th class="text-left" width="10%"><a
                                        href="<?php echo site_url(array("drug", "index", "NAME1", 0)); ?>">NAME <span
                                            class="glyphicon glyphicon-arrow-down"></span></a></th>
                        <?php } else { ?>
                            <th class="text-left" width="10%"><a
                                        href="<?php echo site_url(array("drug", "index", "NAME1", 1)); ?>">NAME <span
                                            class="glyphicon glyphicon-arrow-up"></span></a></th>
                        <?php } ?>
                        <th class="text-left" width="20%">STRUCTURE CAS</th>
                        <?php if (get_order($order_url, "CLOGP") == 0) { ?>
                            <th class="text-left" width="8%"><a
                                        href="<?php echo site_url(array("drug", "index", "CLOGP", 0)); ?>">CLOGP <span
                                            class="glyphicon glyphicon-arrow-down"></span></a></th>
                        <?php } else { ?>
                            <th class="text-left" width="8%"><a
                                        href="<?php echo site_url(array("drug", "index", "CLOGP", 1)); ?>">CLOGP <span
                                            class="glyphicon glyphicon-arrow-up"></span></a></th>
                        <?php } ?>
                        <?php if (get_order($order_url, "CLOGS") == 0) { ?>
                            <th class="text-left" width="8%"><a
                                        href="<?php echo site_url(array("drug", "index", "CLOGS", 0)); ?>">CLOGS <span
                                            class="glyphicon glyphicon-arrow-down"></span></a></th>
                        <?php } else { ?>
                            <th class="text-left" width="8%"><a
                                        href="<?php echo site_url(array("drug", "index", "CLOGS", 1)); ?>">CLOGS <span
                                            class="glyphicon glyphicon-arrow-up"></span></a></th>
                        <?php } ?>
                        <?php if (get_order($order_url, "MW") == 0) { ?>
                            <th class="text-left" width="5%"><a
                                        href="<?php echo site_url(array("drug", "index", "MW", 0)); ?>">MW <span
                                            class="glyphicon glyphicon-arrow-down"></span></a></th>
                        <?php } else { ?>
                            <th class="text-left" width="5%"><a
                                        href="<?php echo site_url(array("drug", "index", "MW", 1)); ?>">MW <span
                                            class="glyphicon glyphicon-arrow-up"></span></a></th>
                        <?php } ?>
                        <th class="text-left" width="41%">THERAPEUTIC AREA</th>
                        <th class="text-left" width="41%">DrugRepurposing</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = $offset + 1;
                    foreach ($list_drug as $drug) {
                        ?>
                        <tr>
                            <td>
                                <a href="<?php echo site_url(array("drug", "more", $drug["MOLID"])); ?>">
                                    <?php echo $drug["MOLID"]; ?>
                                </a>
                            </td>
                            <td>
                                <?php
                                $link = "";
                                if (strcasecmp(substr($drug["LINK"], 0, 2), "DB") == 0) {
                                    $link = "https://www.drugbank.ca/drugs/" . $drug["LINK"];
                                } else {
                                    $link = "http://www.alanwood.net/pesticides/" . strtolower($drug["LINK"]) . ".html";
                                }
                                ?>
                                <a href="<?php echo $link; ?>" target="_blank">
                                    <?php echo $drug["NAME1"]; ?>
                                </a>
                            </td>
                            <td>
                                <img class="img-responsive" alt="Responsive image"
                                     src="<?php echo base_url(array('uploads', 'png', $drug['MOLID'] . '.jpg')); ?>"
                                     data-action="zoom"><br><?php echo $drug["CAS"]; ?>
                            </td>
                            <td>
                                <?php echo $drug["CLOGP"]; ?>
                            </td>
                            <td><?php echo $drug["CLOGS"]; ?>
                            </td>
                            <td>
                                <?php echo $drug["MW"]; ?>
                            </td>
                            <td>
                                <?php echo $drug["THERAPENTICAREA"]; ?>
                            </td>
                            <td>
                                <a href="<?php echo site_url(array('reversedock', 'add', $drug["MOLID"])); ?>"
                                   class="btn btn-primary btn_17b55d form-control" style="color: #ffffff">Submit</a>
                            </td>
                        </tr>
                        <?php
                        $i = $i + 1;
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <form action="<?PHP echo site_url(array('drug', 'select_page')); ?>" method="post">
        <div class="row">
            <div class="col-lg-9 col-sm-12 col-xs-12">
                <?php echo $link_pagination; ?>
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
        <div class="col-lg-12">
            <?php echo $this->lang->line("mark_total_rows_title"); ?>
            <?php echo $total_rows; ?>
            ,
            <?php echo $this->lang->line("mark_total_page_title"); ?>
            <?php echo $total_page; ?>
        </div>
    </div>


    <?php
} else {
    ?>
    <div class="row">
        <div class="col-lg-12">
            <p>
                <?php echo $this->lang->line("list_no_drug"); ?>
            </p>
        </div>
    </div>
    <?php
}
?>
				
				
				
