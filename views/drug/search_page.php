<ul class="nav nav-tabs">
	<li class="active">
		<a href="#text" data-toggle="tab">By text</a>
	</li>
	<li>
		<a href="#structure" data-toggle="tab">By chemical structure</a>
	</li>
</ul>
<div class="tab-content">
	<div class="tab-pane fade in active" id="text">

		<?php echo form_open('drug/search_query'); ?>
		<div class="row">
			<div class="col-lg-4 col-sm-12 col-xs-12">
				<p class="text-strong panel-title">
					1.Set KeyWords
				</p>
				<div class="panel panel-info">
					<div class="panel-body">
						<div class="form-group">
							<select name="key" class="form-control">
								<option value="MOLID" selected="MOLID">MOLID</option>
								<option value="NAME1">COMMAN NAME</option>
								<option value="NAME2">IUPAC NAME</option>
								<option value="CLASS">CLASS</option>
								<option value="CAS">CAS</option>
								<option value="FORMULA">FORMULA</option>
								<option value="LINK">DRUGBANK/ALANWOOD ID</option>
								<option value="SMILE">SMILE</option>
							</select>
						</div>
						<div class="form-group">
							<input type="text" name="words" class="form-control">
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-8 col-sm-12 col-xs-12">
				<p class="text-strong panel-title">
					2.Set Pysicochemical Threshhold
				</p>
				<div class="panel panel-info">
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-6 col-sm-12 col-xs-12">
								<div class="form-group">
									<div class="row">
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<input name="mw_min" type="text" class="form-control" placeholder="0">
										</div>
										<div class="col-lg-4 col-sm-12 col-xs-12">
										<= MW <=
										</div>
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<input name="mw_max" type="text" class="form-control" placeholder="500">
										</div>
									</div>
									<div class="row">
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<input type="text" name="nring_min" class="form-control" placeholder="0">
										</div>
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<=  NRING <=
										</div>
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<input type="text" name="nring_max" class="form-control" placeholder="10">
										</div>
									</div>
									<div class="row">
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<input type="text" name="nrb_min" class="form-control" placeholder="0">
										</div>
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<= NRB <=
										</div>
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<input type="text" name="nrb_max" class="form-control" placeholder="10">
										</div>
									</div>
									<div class="row">
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<input type="text" name="psa_min" class="form-control" placeholder="0">
										</div>
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<= PSA <=
										</div>
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<input type="text" name="psa_max" class="form-control" placeholder="200">
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-sm-12 col-xs-12">
								<div class="form-group">
									<div class="row">
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<input type="text" name="clogs_min" class="form-control" placeholder="-10">
										</div>
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<= CLOGS <=
										</div>
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<input type="text" name="clogs_max" class="form-control" placeholder="5">
										</div>
									</div>
									<div class="row">
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<input type="text" name="clogp_min" class="form-control" placeholder="-10">
										</div>
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<= CLOGP <=
										</div>
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<input type="text" name="clogp_max" class="form-control" placeholder="10">
										</div>
									</div>
									<div class="row">
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<input type="text" name="hba_min" class="form-control" placeholder="0">
										</div>
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<= HBA <=
										</div>
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<input type="text" name="hba_max" class="form-control" placeholder="10">
										</div>
									</div>
									<div class="row">
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<input type="text" name="hbd_min" class="form-control" placeholder="0">
										</div>
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<= HBD <=
										</div>
										<div class="col-lg-4 col-sm-12 col-xs-12">
											<input type="text" name="hbd_max" class="form-control" placeholder="5">
										</div>
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
				<div class="pull-right">
					<button class="btn btn-primary btn-raised" type="submit">
						Search
					</button>
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
	<div class="tab-pane fade in" id="structure">
		<?php echo form_open('drug/search_smi'); ?>
		<div class="row">
			<div class="col-lg-6 col-sm-12 col-xs-12">
					<p class="text-strong panel-title">
						1.Draw A Mol
					</p>
					<div class="panel panel-info">
						<div class="panel-body" align="center">
					
							<div id="appletContainer"></div>
							<button type="button" class="btn btn-primary btn-raised" id="search" onclick="setSMI();">get SMI</button>
							
								<script type="text/javascript" src="<?php echo base_url(array('resource','plugin','jsmol','jsme','jsme','jsme.nocache.js')); ?>"></script>
								<script type="text/javascript">
								var jsmeInstance;
								//Init JSME
								function jsmeOnLoad() {
									jsmeInstance = new JSApplet.JSME("appletContainer", "380px", "380px", {
										"options" : "query,hydrogens"
									});
								}
								
								function setSMI() {
									param = jsmeInstance.smiles();
									//param1 = jsmeInstance.molFile();
									//document.getElementById("SMI").innerHTML=param;
									document.getElementById("SMI_input").value=param;
									//document.getElementById("SMI2").innerHTML=param1;
									//document.getElementById("SMI2_input").value=param1;
								}
								</script>
						</div>
					</div>
			</div>
			
			<div class="col-lg-6 col-sm-12 col-xs-12">
				<p class="text-strong panel-title">
					2.Check SMI and submit
				</p>
				<div class="panel panel-info">
					<div class="panel-body" align="center">
						
						<h4>You are Searching your molecue with SMI:</h4>
						<!--<p id="SMI"></p>-->
						<input type="text" id="SMI_input" name="smi" value=""/>
						
						<button type="submit" class="btn btn-primary btn-raised"> Search </button></div>
				
					</div>
				</div>
			</div>
			
			<?php echo form_close(); ?>
	</div>
</div>	


