
				<?php
				if (count($list_drug)){
				?>
				<div class="row">
			         <div class="col-lg-12">
			            <a class="btn btn-primary btn-raised pull-right" href="<?php echo site_url(array('drug','download',$condition,1));?>" role="button">Mol2</a>
			            <a class="btn btn-success btn-raised pull-right" href="<?php echo site_url(array('drug','download',$condition,2));?>" role="button">Pdb</a>
			            <a class="btn btn-info btn-raised pull-right" href="<?php echo site_url(array('drug','download',$condition,3));?>" role="button">CSV</a>
			            <a class="btn btn-default pull-right" href="#" role="button">Download</a>
			         </div>
			    </div>
				<div class="row">
			         <div class="col-lg-12">
						<div class="table-responsive">
							<table class="table table-striped">
									<thead>
										<tr>
											<?php function get_order($order_url,$key){
												foreach ($order_url as $o) {
													if(strcasecmp($o[0],$key)==0){
														return $o[1];
													}
												}
											} ?>
											<?php if(get_order($order_url,"MOLID")==0){ ?>
												<th class="text-left" width="10%"><a href="<?php echo site_url(array("drug", "search", $condition, "MOLID", 0)); ?>">ID LINK <span class="glyphicon glyphicon-arrow-down"></span></a></th>
											<?php }else{ ?>
												<th class="text-left" width="10%"><a href="<?php echo site_url(array("drug", "search", $condition, "MOLID", 1)); ?>">ID LINK <span class="glyphicon glyphicon-arrow-up"></span></a></th>
											<?php } ?>
											<?php if(get_order($order_url,"NAME1")==0){ ?>
												<th class="text-left" width="10%"><a href="<?php echo site_url(array("drug", "search", $condition, "NAME1", 0)); ?>">NAME <span class="glyphicon glyphicon-arrow-down"></span></a></th>
											<?php }else{ ?>
												<th class="text-left" width="10%"><a href="<?php echo site_url(array("drug", "search", $condition, "NAME1", 1)); ?>">NAME <span class="glyphicon glyphicon-arrow-up"></span></a></th>
											<?php } ?>
											<th class="text-left" width="20%">STRUCTURE CAS</th>
											<?php if(get_order($order_url,"CLOGP")==0){ ?>
												<th class="text-left" width="8%"><a href="<?php echo site_url(array("drug", "search", $condition, "CLOGP", 0)); ?>">CLOGP <span class="glyphicon glyphicon-arrow-down"></span></a></th>
											<?php }else{ ?>
												<th class="text-left" width="8%"><a href="<?php echo site_url(array("drug", "search", $condition, "CLOGP", 1)); ?>">CLOGP <span class="glyphicon glyphicon-arrow-up"></span></a></th>
											<?php } ?>
											<?php if(get_order($order_url,"CLOGS")==0){ ?>
												<th class="text-left" width="8%"><a href="<?php echo site_url(array("drug", "search", $condition, "CLOGS", 0)); ?>">CLOGS <span class="glyphicon glyphicon-arrow-down"></span></a></th>
											<?php }else{ ?>
												<th class="text-left" width="8%"><a href="<?php echo site_url(array("drug", "search", $condition, "CLOGS", 1)); ?>">CLOGS <span class="glyphicon glyphicon-arrow-up"></span></a></th>
											<?php } ?>
											<?php if(get_order($order_url,"MW")==0){ ?>
												<th class="text-left" width="5%"><a href="<?php echo site_url(array("drug", "search", $condition, "MW", 0)); ?>">MW <span class="glyphicon glyphicon-arrow-down"></span></a></th>
											<?php }else{ ?>
												<th class="text-left" width="5%"><a href="<?php echo site_url(array("drug", "search", $condition, "MW", 1)); ?>">MW <span class="glyphicon glyphicon-arrow-up"></span></a></th>
											<?php } ?>
											<th class="text-left" width="41%">THERAPEUTIC AREA</th>
										</tr>
									</thead>
									<tbody>
								<?php
								$i=$offset+1;
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
                                                    $link="";
                                                    if(strcasecmp(substr($drug["LINK"],0,2),"DB")==0){
                                                            $link="https://www.drugbank.ca/drugs/".$drug["LINK"];
                                                    }else{
                                                            $link="http://www.alanwood.net/pesticides/".strtolower($drug["LINK"]).".html";
                                                    }
                                                    ?>
                                                    <a href="<?php echo $link; ?>" target="_blank">
                                                    <?php echo $drug["NAME1"]; ?>
                                                    </a>
                                            </td>
											<td>
												<img class="img-responsive" alt="Responsive image" src="<?php echo base_url(array('uploads','png',$drug['MOLID'].'.jpg'));?>" data-action="zoom">
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
	                  
	                  
	            <div class="row">
	                <div class="col-lg-12">
						<?php echo $link_pagination;?>
					</div>
	            </div>
				
				<div class="row">
	                <div class="col-lg-12">
							<?php echo $this -> lang -> line("mark_total_rows_title"); ?>
							<?php echo $total_rows; ?>
					        , 
					    	<?php echo $this -> lang -> line("mark_total_page_title"); ?>
							<?php echo $total_page; ?>							
					</div>
	            </div>
	           
	           
				<?php
				}else{
				?>
				<div class="row">
	                	<div class="col-lg-12">
						    <p>
								<?php echo $this -> lang -> line("list_no_drug"); ?>
							</p>
			    	</div>
	            </div>
				<?php
				}
				?>
				
				
				
