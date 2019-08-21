		<ul class="nav nav-tabs">
	    	<li class="active"><a href="#summary" data-toggle="tab">Summary</a></li>
	    	<li><a href="#structure" data-toggle="tab">Structure</a></li>
	    	<li><a href="#physicochemistry" data-toggle="tab">Physicochemistry</a></li>
	    	<li><a href="#targets" data-toggle="tab">Targets</a></li>
	    </ul>
	    <div class="tab-content">
	    	<div class="tab-pane fade in active" id="summary">
				<div class="row">
					<div class="col-lg-4">
						<img class="img-responsive" alt="Responsive image" src="<?php echo base_url(array('uploads','png',$drug['MOLID'].'.jpg'));?>" data-action="zoom">
					</div>
					<div class="col-lg-8">
						<div class="row">
							<div class="col-lg-6">
								<h4 class="text-primary">Common name</h4>
								<hr>
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
							</div>
							<div class="col-lg-6">
								<h4 class="text-primary">IUPAC name</h4>
								<hr>
								<p><?php echo $drug['NAME2'];?></p>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<h4 class="text-primary">SMILES</h4>
								<hr>
								<p><?php echo $drug['SMILE'];?></p>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<h4 class="text-primary">Compound class</h4>
								<hr>
								<p><?php echo $drug['CLASS'];?></p>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<h4 class="text-primary">Therapeutic area</h4>
								<hr>
								<p><?php echo $drug['THERAPENTICAREA'];?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
	    	<div class="tab-pane fade" id="structure">
				<div class="row">
					<div class="col-lg-4">
						<script type="text/javascript" src="<?php echo base_url(array('resource','plugin','jsmol','JSmol.min.js')); ?>"></script>
						<script type="text/javascript">
							var jmolApplet0;
							// set up in HTML table, below
							var s = document.location.search;
							Jmol._debugCode = (s.indexOf("debugcode") >= 0);
							jmol_isReady = function(applet) {
								document.title = (applet._id + " - Jmol " + ___JmolVersion)
								Jmol._getElement(applet, "appletdiv").style.border = "2px solid blue"
							}
							var Info = {
								width : 350,
								height : 350,
								debug : false,
								color : "0xFFFFFF",
								use : "HTML5", // JAVA HTML5 WEBGL are all options
								j2sPath : "<?php echo base_url(array('resource','plugin','jsmol','j2s')); ?>", // this needs to point to where the j2s directory is.
								jarPath : "<?php echo base_url(array('resource','plugin','jsmol','java')); ?>", // this needs to point to where the java directory is.
								jarFile : "JmolAppletSigned.jar",
								isSigned : true,
								script : "set zoomlarge false;set antialiasDisplay;load '<?php echo base_url(array('uploads','mol2',$drug['MOLID'].'.mol2'));?>';select *",
								readyFunction : jmol_isReady,
								disableJ2SLoadMonitor : true,
								disableInitialConsole : true,
								allowJavaScript : true
							}
							$(document).ready(function() {
								$("#appdiv").html(Jmol.getAppletHtml("jmolApplet0", Info))
							})
							var lastPrompt = 0;
						</script>
						<div id="appdiv" style="z-index: 200;"></div>
						<div style="text-align: center;">
							<a href='<?php echo base_url(array('uploads','mol2',$drug['MOLID'].'.mol2'));?>' title ='download mol2 file!'>[download mol2 file]</a>
						</div>
					</div>
					<div class="col-lg-8">
						<div class="row">
							<div class="col-lg-6">
								<h4 class="text-primary">Common name</h4>
								<hr>
								<p><?php echo $drug['NAME1'];?></p>
							</div>
							<div class="col-lg-6">
								<h4 class="text-primary">IUPAC name</h4>
								<hr>
								<p><?php echo $drug['NAME2'];?></p>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<h4 class="text-primary">SMILES</h4>
								<hr>
								<p><?php echo $drug['SMILE'];?></p>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<h4 class="text-primary">INCHI</h4>
								<hr>
								<p><?php echo $drug['INCHI'];?></p>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<h4 class="text-primary">FORMULA</h4>
								<hr>
								<p><?php echo $drug['FORMULA'];?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="physicochemistry">
				<div class="row">
					<div class="col-lg-4">
						<img class="img-responsive" alt="Responsive image" src="<?php echo base_url(array('uploads','png',$drug['MOLID'].'.jpg'));?>" data-action="zoom">
					</div>
					<div class="col-lg-8">
						<div class="row">
							<div class="col-lg-6">
								<h4 class="text-primary">Common name</h4>
								<hr>
								<p><?php echo $drug['NAME1'];?></p>
							</div>
							<div class="col-lg-6">
								<h4 class="text-primary">IUPAC name</h4>
								<hr>
								<p><?php echo $drug['NAME2'];?></p>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-4">
								<h4 class="text-primary">Molecular weight</h4>
								<hr>
								<p><?php echo $drug['MW'];?></p>
							</div>
							<div class="col-lg-4">
								<h4 class="text-primary">clogP</h4>
								<hr>
								<p><?php echo $drug['CLOGP'];?></p>
							</div>
							<div class="col-lg-4">
								<h4 class="text-primary">clogS</h4>
								<hr>
								<p><?php echo $drug['CLOGS'];?></p>
							</div>
							
						</div>
						<div class="row">
							<div class="col-lg-2">
								<h4 class="text-primary">HBond Acceptor</h4>
								<hr>
								<p><?php echo $drug['HBA'];?></p>
							</div>
							<div class="col-lg-2">
								<h4 class="text-primary">HBond Donor</h4>
								<hr>
								<p><?php echo $drug['HBD'];?></p>
							</div>
							<div class="col-lg-3">
								<h4 class="text-primary">Total Polar </br> Surface Area</h4>
								<hr>
								<p><?php echo $drug['PSA'];?></p>
							</div>
							<div class="col-lg-2">
								<h4 class="text-primary">Number of Rings</h4>
								<hr>
								<p><?php echo $drug['NRING'];?></p>
							</div>
							<div class="col-lg-2">
								<h4 class="text-primary">Rotatable Bond</h4>
								<hr>
								<p><?php echo $drug['NRB'];?></p>
							</div>
							
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="targets">
					            
				<?php
				if (count($list_target)){
				?>
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
								<?php echo $this -> lang -> line("list_no_target"); ?>
							</p>
			    	</div>
	            </div>
				<?php
				}
				?>
				
			</div>
    	</div>
