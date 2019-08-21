
			    
				<?php
				if (count($list_drug)){
				?>
				
				<div class="row">
			         <div class="col-lg-12">
			            <a class="btn btn-primary btn-raised pull-right" href="<?php echo site_url(array('drug','download_smi',$result,1));?>" role="button">Mol2</a>
			            <a class="btn btn-success btn-raised pull-right" href="<?php echo site_url(array('drug','download_smi',$result,2));?>" role="button">Pdb</a>
			            <a class="btn btn-info btn-raised pull-right" href="<?php echo site_url(array('drug','download_smi',$result,3));?>" role="button">CSV</a>
			            <a class="btn btn-default pull-right" href="#" role="button">Download</a>
			         </div>
			    </div>
				
				<div class="row">
			         <div class="col-lg-12">
						<div class="table-responsive">
							<table class="table table-striped">
									<thead>
										<tr>
											<th class="text-left" width="10%">ID LINK</th>
											<th class="text-left" width="10%">SIMILARITY</th>
											<th class="text-left" width="15%">NAME</th>
											<th class="text-left" width="15%">STRUCTURE CAS</th>
											<th class="text-left" width="10%">CLOGP</br>CLOGS</th>
											<th class="text-left" width="10%">MW</th>
										</tr>
									</thead>
									<tbody>
								<?php
								foreach ($list_drug as $drug) {
								?>	
										<tr>	
											<td>
												<a href="<?php echo site_url(array("drug", "more", $drug["MOLID"]."#summary")); ?>">
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
                                                                                                <?php echo number_format($drug["CALCSMI"],2); ?>
                                                                                        </td>
											<td>
												<img class="img-responsive" alt="Responsive image" src="<?php echo base_url(array('uploads','png',$drug['MOLID'].'.jpg'));?>" data-action="zoom">
											</td>
											<td>
												<?php echo $drug["CLOGP"]; ?>
												<br />
												<?php echo $drug["CLOGS"]; ?>
											</td>
											<td>
												<?php echo $drug["MW"]; ?>
											</td>
										</tr>
									<?php
									}
									?>
									</tbody>
							</table>
						</div>
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
				
				
				
