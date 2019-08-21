package PDBParser;
use strict;
my $ELEM_tab='MG|CA|ZN|NA| K|MN|FE|CO|NI|LI|CU|AL|PT|HG|CD|YB|SR|GD|HO|AU|RB|CS|EU|SM|PB|CE|TB|BA';
  $ELEM_tab.='| F|CL|BR|XE|KR|AR';
my $IONS_tab='SO4|SO3|PO4|PO3|IOD|ACT|SCN|AZI|NO3|NO2|CO3|BCT| OH| NO|CYN| CN| TE|PPV|CAC|POP';	#硫酸根，磷酸跟，碘负离子，乙酸根，硫氰酸根,叠氮负离子,硝酸根
  $IONS_tab.='|NH4|FE2|AU3|CU1|TRS|IMD';			#铵离子,二价铁离子
  $IONS_tab.='|NH2|OXY| O2|SO2|CO2|H2S';		#氨气，氧气
  #水，丙三醇，乙二醇,乙醇，DMSO，NH3，SO2,CO2,乙醛，乙酸，甲酸，1,3丁二醇，十四烷，油酸，单油酸甘油酯
my $SOLV_tab='HOH|DOD|SOL|WAT|GOL|EDO|EOH|DMS|ACE|ACN|ACY|FMT|BU2|C14|OLA|OLB|OLC|CIT|2OP| CP|TRD|HTO|POL';
  $SOLV_tab.='|MPD|BME|6PL|6UL|IPE|IPA|OCT|OCA|HEX|HED|PYR|BNZ|SC2';
  $SOLV_tab.='|PEG|PGE|1PE|PGO|PAG|PGA|PG4|P4G|PG6|P6G|15P|MYR|DIO|16A|PFS|LPE|DAO|SPH|DKA|DFP';	
#辅因子
  #===含铁，镍，锰，钴等的无机物===============
my$COFA_tab ='F3S|FCI|FCO|FDC|FEA|FEO|FES|FNE|FSO|FSX|FS2|FS3|FS4|SF3|SF4';
  $COFA_tab.='|CFM|CFN|CFO|CLF|CLP|CN1|CNB|CNF|CUB|CUM|CUN|CUO|HC0|HC1|HF3|HF5|NFS';
  $COFA_tab.='|OFO|OMO|PFC|PHF';
  #===================
  $COFA_tab.='|1CP|1FH|BCB|BCL|BH1|BPB|BPH|CCH|CL1|CL2|CLA|CLN|COB|COH|CP3|DDH|DEU|DHE';
  $COFA_tab.='|FDD|FDE|FEC|FMI|HAS|HCO|HDD|HDM|HE5|HEA|HEB|HEC|HEG|HEM|HEO|HES|HEV|HIF|HNI';
  $COFA_tab.='|MHM|MMP|MNH|MNR|MP1|PC3|PCU|PHO|PNI|POR|PP9|SRM|VER';
  #==================
my$ATP_tab.='|ATP|ACP|ADP|DTP|NAD|NAP|AZT|ANP|NDP|DGT|C6T|N6T| DU|DUP| DC|DCP|FAD|UDP|GDP|TPP|APR';
  #===糖类===============
my$GLU_tab ='|BEM|BMA|DAG|FU4|FUC|FUL|GAL|GLC|LAT|MAN|MAL|NAG|NDG|IHP|IPD|DAN';	#葡萄糖，L-岩藻糖，葡萄糖胺,N-乙酰基葡萄糖胺
  $GLU_tab.='|GU0|GU1|GU2|GU3|GU4|GU5|GU6|GU7|GU8|GU9|DMU';
  #===氨基酸=============
my$AMI_tab='|GLY|ALA|VAL|LEU|LLE|PRO|SER|THR|CYS|ASP|GLU|LYS|MET|ASN|GLN|OGA';
# $COFA_tab.='|BAZ|BH4|BHS|BIO|BOZ|FAD|HBI|HID|HIE|HIP|H4B|HBL|THB'
 $COFA_tab.= $ATP_tab;
 #$COFA_tab.= $GLU_tab;
$COFA_tab.= $AMI_tab;

sub new{
	my ($class,$ID) = @_;
	my %receptor;#{'chainID'=>$rChain1,'chainID'=>$rChain2,...}
				#$rChain=[$rResi1,$rResi2,$rResi3,...]	#残基用残基编号来索引
				#$rResi=[resiName,atom1,atom2,...];		#可能是氨基酸残基名，也可能是'TER'
	my @ligand; #{'resiNo'=>$rlig1,'resiNo'=>$rlig2,...}
				#rlig=[ligName,chain,het1,het2,...]		#第2个元素标记配体属于哪条链
	my @cofactor;
	my %boxs;
	my @alter;	#保存在17号字节上出现非' '标记的残基编号,便于剔除冗余构象
	my @solv;
	my @ion;
	my $this={
		'recep' => \%receptor,
		'lig'	=> \@ligand,
		'cof'	=> \@cofactor,
		'box'	=> \%boxs,
		'alt'	=> \@alter,	
		'solv'	=> \@solv,
		'ion'	=> \@ion,
		'ID'	=> ''
	};
	$this->{ID} = $ID  if defined($ID);
	bless $this,$class;
	return $this;
}

sub clear{
	my $this=shift;
	my %receptor;
	my @ligand;
	my @cofactor;
	my %boxs;
	my @alter;	
	my @solv;
	my @ion;
	$this={
		'recep' => \%receptor,
		'lig'	=> \@ligand,
		'cof'	=> \@cofactor,
		'box'	=> \%boxs,
		'alt'	=> \@alter,	
		'solv'	=> \@solv,
		'ion'	=> \@ion,
		'ID'	=> ''
	};
}

sub parse{
	my ($this,$file)=@_;
	#===检查文件名,并解析ID号=====================#
	if(!$file){
		print STDERR "ERROR: NO input PDB file given!\n";
		return undef;
	}elsif($file !~ /(\w{4})\.pdb$/o){
		print STDERR "ERROR: Only accept PDB file XXXX.pdb!\n";
		return undef;
	}else{	$this->{ID}=$1;	}
	if(!open(PDB,"<$file")){
		print STDERR "ERROR: Failed to open $file!\n";
		return undef ;
	}
	#===截取需要的信息，过滤其他=================
	my (@buf,@TER);#每一条多肽链都以'TER'作为结尾,没有TER结尾的视作与蛋白无关的杂原子
	my $i=0;
	while(<PDB>){
		if(/^ATOM.{73}H/o){
		
		}elsif(/^ATOM/o or /^HETATM/o){		
			push(@{$buf[$i]},$_);
		}
		elsif(/^TER/o){		
			push(@TER,$_);
			$i ++ ;
		}
	} 
	close PDB;
	if(!$TER[0] or !$buf[0]->[0]){	#必须保证有一条多肽链的存在
		print STDERR "ERROR: $file is empty\n";
		return undef;
	}
	#===解析pdb文件=====================#
	my $het_idex = $#TER +1;
	for(my $i=0;$i < $het_idex;$i++){
		$this->parseRecep($buf[$i]);
	}	
	if($buf[$het_idex]){
		$this->parseHET($buf[$het_idex]);
	}else{print STDERR "NOTICE: no heteroatom out of protein in $this->{ID}\n";}
	$this->parseTER(\@TER);
	#===进一步处理文件=================
	$this->deRedundant();
	$this->calBox();
	
	return $this;
}

sub parseRecep{
	my ($this,$atm)=@_;
	my $len=$#{$atm} +1;
	for(my $i=0;$i<$len;$i++){
		if ($atm->[$i] =~ /^.{17}(\w{3}).(\w)( {3}(\d)| {2}(\d{2})| (\d{3})|(\d{4}))/o){
			#print "lcz: ".$atm->[$i];
			my($resiName,$chain)=($1,$2);
			my @Resi=($resiName,$atm->[$i]);	#根据残基名，残基的第一行定义一个列表
			my $resiNo = 0;
			if($4){$resiNo += $4;}
			elsif($5){$resiNo += $5;}
			elsif($6){$resiNo += $6;}
			elsif($7){$resiNo += $7;}
			$this->{recep}->{$chain}->[$resiNo] = \@Resi;	#将$this数据结构获取残基的引用
			my $flag=0;
			$i++ ;					#移动索引，然后
			for(;$i<$len;$i++){		#读完该残基剩下的所有行
				if ($atm->[$i] =~ /^.{16} $resiName/){	#据大多数情况进入此分支
					push(@Resi,$atm->[$i]);
				}elsif($atm->[$i] =~ /^.{16}\w$resiName/){#存在冗余构象时进入此分支
					push(@Resi,$atm->[$i]);
					if(0==$flag){
						push(@{$this->{alt}},\@Resi);
						$flag =1;
					}
				}else{										#残基读完后，进入此分支
					$i--;
					last;	#退出内层循环
				}
			}
		}
	}
}

sub parseHET{
	my ($this,$het)=@_;
	#print @{$het};
	my $len=$#{$het} +1;
	for(my $i=0;$i<$len;$i++){
		if( 	$het->[$i] =~ /^.{17}$SOLV_tab/o){	#solvent
			#print "solv: ".$het->[$i];
			push(@{$this->{solv}}, $het->[$i]);
		}elsif($het->[$i] =~ /^.{12}($ELEM_tab).{4}\1/o){	#金属离子和卤素离子
			#print "elem: ".$het->[$i];
			push(@{$this->{ion}}, $het->[$i]);
		}elsif($het->[$i] =~ /^.{17}$IONS_tab/o){	#SO4,PO4,I负离子和铵离子等
			#print "ion : ".$het->[$i];
			push(@{$this->{ion}}, $het->[$i]);
		}elsif($het->[$i] =~ /^.{17}($COFA_tab)./o){
			my $cofName=$1;
			#print "cof: $cofName\n";
			my @Cofac=($cofName,$het->[$i]);
			push(@{$this->{cof}}, \@Cofac);
			$i++;
			for(;$i<$len;$i++){		#读完该辅因子的所有行
				if ($het->[$i] =~ /^.{17}$cofName/){
					push(@Cofac,$het->[$i]);
				}else{
					$i -- ;
					last;
				}
			}
		}elsif($het->[$i] =~ /^.{17}(.{3}).(\w)/o){
			my($ligName,$chain)=($1,$2);
			#print "lig: $ligName\n";
			my @Lig=($ligName,$chain,$het->[$i]);	#前两个元素都是标记元素，加上第一行以定义列表
			push(@{$this->{lig}},\@Lig);
			my $flag=0;
			$i++ ;					#移动索引，然后
			for(;$i<$len;$i++){		#读完该配体的所有行
				if ($het->[$i] =~ /^HETATM.{10} $ligName.$chain/){
					push(@Lig,$het->[$i]);
				}elsif($het->[$i] =~ /^HETATM.{10}\w$ligName.$chain/){
					push(@Lig,$het->[$i]);
					if(0==$flag){
						push(@{$this->{alt}},\@Lig);
						$flag =1;
					}
				}else{
					$i--;
					last;	#退出内层循环
				}
			}
		}
		else{print "lines fail to match\n".$het->[$i];}
	}
}

sub parseTER{
	my ($this,$ter)=@_;
	my $len=$#{$ter} +1;
	foreach my $line(@{$ter}){
		if($line=~ /^.{21}([A-Z])/o){
			#print $pdb[$i];
			my @termi=('TER',$line);
			push(@{$this->{recep}->{$1}},\@termi);#链的最后一个元素为该链的正常末端
		}
	}	
}
#+++注：一下的函数都建立在充分相信parse函数的基础上+++++++++++++++#
sub checkChain{
	my $this =shift;
}

sub deRedundant{
	my $this =shift;
	foreach my $alt(@{$this->{alt}}){
		my @buf;
		my $conf={};
		foreach my $line(@{$alt}){
			if($line =~/^(.{16})([A-Z])(.*)/o){
				push(@{$conf->{$2}},"$1 $3\n");		#去掉A或B等冗余标号【$2】
			}else{
				push(@buf,$line);
			}
		}
		my @key =sort{$#{$conf->{$a}}<=>$#{$conf->{$b}}} keys %{$conf};
		#print 'buf '."@buf\n";
		#print @{$conf->{$key[$#key]}};
		@{$alt}=(@buf,@{$conf->{$key[$#key]}}) if defined($key[0]);	#修改$alt指向的残基内容,只保留冗余残基中的最后一个
		
		for(my $i=0;$i<$#{$this->{lig}};$i++){	#遍历所有的配体
			#print @{$this->{lig}->[$i]};
			if($#{$this->{lig}->[$i]} < 9){		
				push(@{$this->{solv}},@{$this->{lig}->[$i]});
				$this->{lig}->[$i]=undef ;	#重原子数小于7的扔到溶剂里去 
			}
		}
	}
}

sub calBox{
	my $this = shift;
	foreach my $lig(@{$this->{lig}}){
		my (@x,@y,@z);
		#print @{$lig};
		next if(!$lig);		#若为空则进入下一个
		for(my $i=2;$i<=$#{$lig};$i++){		#前两个元素都是标记元素，没有坐标信息
			if($lig->[$i] =~/^.{30}(.{8})(.{8})(.{8})/o){	
				push(@x,0.0+$1);
				push(@y,0.0+$2);
				push(@z,0.0+$3);
			}else{
				print STDERR "ERROR: missing coordinate information!\nline>$lig->[$i] in $this->{ID} $lig->[0]\n";
				return undef;
			}
		}
		
		@x=sort{$a<=>$b}@x;
		@y=sort{$a<=>$b}@y;
		@z=sort{$a<=>$b}@z;
		my @box=($x[0]-5,$x[$#x]+5,$y[0]-5,$y[$#y]+5,$z[0]-5,$z[$#z]+5);
		#print "$lig->[0] @box";
		$this->{box}->{$lig->[0]}=\@box;
	}
	return $this->{box};
}

sub printRecep{
	my $this = shift;
	open(RECEP,">$this->{ID}_r.pdb");
	foreach my $chain(values %{$this->{recep}}){
		foreach my $resi(@{$chain}){
			for(my $i=1;$i<=$#{$resi};$i++){		#resi->[o] == resiName;不应打印到文件中
				print RECEP $resi->[$i];
			}
		}
	}
	close RECEP;
}

sub printLig{
	my $this = shift;
	my $idex=0;
	foreach my $lig(@{$this->{lig}}){
		#print @{$lig};
		next if(!$lig);		#若为空则进入下一个
		open(LIG,">$this->{ID}_$lig->[0].pdb");
		#===打印盒子信息=============
		my $box = join("\t" ,@{$this->{box}->{$lig->[0]}});
		print LIG "$this->{ID}\t$lig->[0]\t$box\n";
		#===打印配体内容=============
		for(my $i=2;$i<=$#{$lig};$i++){	#前两个元素都是标记元素，不应打印到文件
			print LIG $lig->[$i];
		}
		close LIG;
		$idex ++;
	}
	print STDERR "No Ligand in $this->{ID}\n" if(0 == $idex);
}

sub printCof{
	my $this = shift;
	open(RECEP,">>$this->{ID}_r.pdb");	#辅因子保留在蛋白中
	foreach my $cof(@{$this->{cof}}){
		for(my $i=1;$i <= $#{$cof};$i++){
			print RECEP $cof->[$i];
		}
	}
	close RECEP;
}

sub printSolv{
	my $this = shift;
	if($this->{solv}->[0]){
		open(SOLV,">$this->{ID}_s.pdb");
		print SOLV @{$this->{solv}};
		close SOLV;
	}
}

sub printIon{
	my $this = shift;
	if($this->{ion}->[0]){
		open(ION,">$this->{ID}_i.pdb");
		print ION @{$this->{ion}};
		close ION;
	}
}

sub printBox{
	my $this = shift;
	foreach my $ligname(keys %{$this->{box}}){
		my $box = join("\t" ,@{$this->{box}->{$ligname}});
		print STDERR "$this->{ID}\t$ligname\t$box\n";
	}
}

sub printBy{
	my($this,$stat) = @_;
	$this->printRecep();
	$this->printLig();
	$this->printBox();
	#$this->printCof();
	if($stat){
		if(1==$stat){$this->printIon();}
		elsif(2==$stat){$this->printSolv();}
		elsif(3==$stat){$this->printIon(); $this->printSolv();}
	}
}

1;