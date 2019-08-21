package InverseDock;
#! /usr/bin/perl -w
use strict;
my $POOL_PATH="$ENV{ACID}/FishPool";
my $PATH = "$ENV{ACID}/DockScript/";
my $PWD = $ENV{PBS_O_WORKDIR} ? $ENV{PBS_O_WORKDIR} : $ENV{PWD};
my $PLT_NUM=10;
my $LE_NUM=10;
sub mcDock{
	my ($h,$idx) = @_;
	
	my $prot = "$POOL_PATH/$h->{pdb}/$h->{pdb}";
	my $out = "$h->{pdb}\_$h->{site}\_$h->{lig}\_mc";
	my $score_file = "$h->{lig}\_$idx.mc";

	return if stat "$out.pdbqt";

	my $Lenx = $h->{Lenx} < 25 ? $h->{Lenx} : 25 ;
	my $Leny = $h->{Leny} < 25 ? $h->{Leny} : 25 ;
	my $Lenz = $h->{Lenz} < 25 ? $h->{Lenz} : 25 ;
	my $cmd = "$PATH/Vina/mc_vina";
	$cmd .= " --receptor $prot.pdbqt --ligand $h->{lig}.pdbqt";	#receptor format PDBQT
	$cmd .= " --center_x $h->{Cx} --center_y $h->{Cy} --center_z $h->{Cz}";
	$cmd .=	" --size_x $Lenx --size_y $Leny --size_z $Lenz";
	$cmd .=	" --out $out.pdbqt";

	if(!open MC,'-|',"$cmd 2>$out.err"){
		print STDERR "Error occured during the MC docking of $out\n";
		return undef;
	}
	my @bindEnergy;
	while(<MC>){
		if(/^   \d/o){
			my @split = split;
			$split[1] = 0 + $split[1];
			push(@bindEnergy, $split[1]);
		}
	}
	close MC;
	
	delZero("$out.err");
	my $score = defined $bindEnergy[0] ? $bindEnergy[0] : 'null';
	open(PDBQT,">>$score_file");
	print PDBQT "$h->{pdb} $h->{site} $score\n";
	close PDBQT;
}

sub psoDock{
	my ($h,$idx) = @_;
	
	my $prot = "$POOL_PATH/$h->{pdb}/$h->{pdb}";
	my $out = "$h->{pdb}\_$h->{site}\_$h->{lig}\_pso";
	my $score_file = "$h->{lig}\_$idx.pso";

	return if stat "$out.pdbqt";#靠靠靠

	my $Lenx = $h->{Lenx} < 25 ? $h->{Lenx} : 25 ;
	my $Leny = $h->{Leny} < 25 ? $h->{Leny} : 25 ;
	my $Lenz = $h->{Lenz} < 25 ? $h->{Lenz} : 25 ;
	my $cmd = "$PATH/Vina/pso_vina";
	$cmd .= " --receptor $prot.pdbqt --ligand $h->{lig}.pdbqt"; #receptor format PDBQT
	$cmd .= " --center_x $h->{Cx} --center_y $h->{Cy} --center_z $h->{Cz}";
	$cmd .=	" --size_x $Lenx --size_y $Leny --size_z $Lenz";
	$cmd .=	" --out $out.pdbqt";
	
	if(!open PSO,'-|',"$cmd 2>$out.err"){
		print STDERR "Error occured during the PSO docking of $out\n";
		return undef;
	}
	my @bindEnergy;
	while(<PSO>){
		if(/^   \d/o){
			my @split = split;
			$split[1] = 0 + $split[1];
			push(@bindEnergy, $split[1]);
		}
	}
	close PSO;
	
	delZero("$out.err");
	my $score = defined $bindEnergy[0] ? $bindEnergy[0] : 'null';
	open(PDBQT,">>$score_file");
	print PDBQT "$h->{pdb} $h->{site} $score\n";
	close PDBQT;
}

#****************************************************************************************************
sub pltDock{
	my ($h,$idx) = @_;
	
	my $prot = "$POOL_PATH/$h->{pdb}/$h->{pdb}";
	my $out = "$h->{pdb}\_$h->{site}\_$h->{lig}\_plt";
	my $score_file = "$h->{lig}\_$idx.plt";

	return if stat "$out.mol2";#靠靠靠

	my $score = 'null';
	my $RADIUS = $h->{R} < 25 ? $h->{R} : 25;
	#===prepare Config file================================
	unlink glob "$out/*" and rmdir $out if stat $out;
	my $content="bindingsite_center $h->{Cx} $h->{Cy} $h->{Cz}\n";
	$content .= "bindingsite_radius $RADIUS\n";
	$content .= "scoring_function chemplp\n";
	$content .= "cluster_structures $PLT_NUM\n";
	$content .= "protein_file $prot.mol2\n";		#absolute path,receptor format MOL2
	$content .= "ligand_file $PWD/$h->{lig}.mol2\n";		#lig也是用绝对路径指定
	$content .= "output_dir $out\n";	#同时指定一个工作路径，保护输出结果
	$content .= "write_protein_conformations 0\n";	
	$content .= "write_protein_bindingsite 0\n";
	open(CONF,">$out.conf");
	print CONF $content;
	close CONF;
	#======================================================
	my $stat=system "$PATH/Plants/plants --mode screen $out.conf >/dev/null 2>$out.err";
	print STDERR "Error occured during the PLT docking of $out!\n$!\n" and
	goto _END_ if $stat;
	
	$score = cluster($h,$out);
	
_END_:
	delZero("$out.err");
	unlink "$out.conf";
	unlink glob "PLANTS*.pid";
	unlink glob "$out/*";
	rmdir $out;
	$score = defined $score ? $score : 'null';
	open(PLT,">>$score_file");
	print PLT "$h->{pdb} $h->{site} $score\n";
	close PLT;
}

sub cluster{
	my ($h,$out) = @_;	
	
	return undef unless checkOrganic("$out/docked_ligands.mol2",100);	##保护外部程序的输入文件安全
	#=====正常运行的cluster，若成功返回0;任何非零值表示失败===============
	my $stat=system "$PATH/cluster $out/docked_ligands.mol2 2.0 >$out.mol2"; 
	print STDERR "Error occured during cluster $out/docked_ligands.mol2\n$!\n" and 
	unlink "$out.mol2" and rename("$out/docked_ligands.mol2","$out\_ori.mol2") if $stat;
	#==================================================================
	
	
	print STDERR "Not score file $out/ranking.csv generated!\n" and 
	return undef unless open(SCORE,"<$out/ranking.csv");	#plants基于CHEMPLP打分函数
	<SCORE>;
	my $line=<SCORE>;
	close SCORE;
	my @arr=split /,/,$line;
	print STDERR "No CHEMPLP score generated for $out\n" and
	return undef unless $arr[1];
	return $arr[1];
}
#****************************************************************************************************

#****************************************************************************************************
sub leDock{
	my ($h,$idx) = @_;
	
	my $prot = "$POOL_PATH/$h->{pdb}/$h->{pdb}";
	my $out = "$h->{pdb}\_$h->{site}\_$h->{lig}\_le";
	my $score_file = "$h->{lig}\_$idx.le";
	my $score = 'null';
	
	return if stat "$out.pdb";#靠靠靠
	#===prepare Config file================================
	mkdir $out unless stat $out;
	unlink glob "$out/*";
	print STDERR "Fail to create symlink $h->{lig}.mol2" and 
	goto _END unless link "$PWD/$h->{lig}.mol2","$out/$h->{lig}.mol2";
	my $content="Receptor\n$prot.pdb\n";	#receptor format PDB
	$content .= "RMSD\n2.0\n";			#suitable for highthroughput virtual screening
	$content .= "Binding pocket\n";
	$content .= "$h->{x1} $h->{x2}\n";
	$content .= "$h->{y1} $h->{y2}\n";
	$content .= "$h->{z1} $h->{z2}\n";
	$content .= "Number of binding poses\n$LE_NUM\n";
	$content .= "Ligands list\n$out.list\nEND\n";
	open(CONF,">$out.conf");
	print CONF $content;
	close CONF;
	open(LIST,">$out.list");
	print LIST "$out/$h->{lig}.mol2";		#Ledock自动使用配体所在的路径作为工作路径
	close LIST;
	#======================================================
	
	my $stat=system "$PATH/Ledock/ledock $out.conf 2>$out.err";
	print STDERR "Error occured during the LE docking of $out!\n$!\n" and 
	goto _END if $stat;
	
	$score = deal_dok($h,$out);
	
_END:
	unlink "$out.conf","$out.list";
	unlink glob "$out/*";
	rmdir $out;
	delZero("$out.err");
	$score = defined $score ? $score : 'null';
	open(_LE,">>$score_file");
	print _LE "$h->{pdb} $h->{site} $score\n";
	close _LE;
}

sub deal_dok{
	my ($h,$out) =@_;
	my $conf=[[]];
	my $idex=1;
	my @score;

	print STDERR "Fail to open original $out/$h->{lig}.dok!\n" and 
	return undef unless open(DOK,"<$out/$h->{lig}.dok");
	while(<DOK>){
		#chomp;
		if(/^REMARK.{26}Score: (.+)kcal\/mol/o){
			push(@score,$1);
			push @{$conf->[$idex]},"MODEL $idex\nCOMPND    $idex\nAUTHOR    LCZ\n";
			push @{$conf->[$idex]},$_;
		}elsif(/^(ATOM.{8})(.{4})(.+)/o){
			my($ATM,$atm)=fix_atm($2);
			my $line;
			if(defined $ATM and defined $atm){
				$line="$1$ATM$3  1.00  0.00          $atm  \n";
			}else{
				$line=$_;
			}
			push @{$conf->[$idex]},$line;
		}elsif(/^ATOM.{8} ([A-Z]) /o){
			chomp $_;
			my $line="$_  1.00  0.00           $1  \n";
			push @{$conf->[$idex]},$line;
		}elsif(/^END/o){
			push @{$conf->[$idex]},$_;
			push @{$conf->[$idex]},"ENDMDL\n";
			$idex ++;
		}
	}
	close DOK;
	
	open(PDB,">$out.pdb");
		print PDB @{$_} foreach @{$conf};
	close PDB;
	
	print STDERR "No score generated for $out\n" and
	return undef unless $score[0];
	
	return $score[0];
}

sub fix_atm{
	my $dok =shift;
	my $ATM;
	my $atm;
	if($dok =~/^ ?(CL|BR|SI|AS|SE)/o){
		$ATM="$1  ";
		$atm=substr($1,0,1).lc(substr($1,1,1))."  ";
	}elsif($dok =~/^ ?(C|H|O|N|S|P|F|I|B)/){
		$ATM=" $1  ";
		$atm=" $1  ";
	}
	return ($ATM,$atm);
}
#****************************************************************************************************

sub vote{
	my ($h,$idx) = @_;
	
	my $prot = "$POOL_PATH/$h->{pdb}/$h->{pdb}";
	my $name = "$h->{pdb}\_$h->{site}\_$h->{lig}";
	my $out = "$h->{pdb}\_$h->{site}\_$h->{lig}\_vote";
	my $score_file = "$h->{lig}\_$idx.vote";
	my ($i,$v,$s)=('null','null','null');

	return if stat "$out.mol2";#靠靠靠

	my $method_num=0;
	my $in=' ';
	($in .="$name\_mc.pdbqt ",$method_num++)if stat "$name\_mc.pdbqt";
	($in .="$name\_pso.pdbqt ",$method_num++)if stat "$name\_pso.pdbqt";
	($in .="$name\_plt.mol2 ",$method_num++)if stat "$name\_plt.mol2";
	($in .="$name\_le.pdb ",$method_num++)if stat "$name\_le.pdb";
	if($method_num ==0){
		`echo "No conformations generated" >$out.err`;
		return ; 
	}elsif($method_num==1){
		#print "$in\n";
		my $stat = system "rankbyvote $in $in >$out.mol2 2>/dev/null";
		print STDERR "Error occured during the VOTE of $name!\n$!\n" and
                goto END_ if $stat;
	}else{
		print STDERR "$in\n";
		my $stat = system "rankbyvote $in >$out.mol2  2>/dev/null";
		print STDERR "Error occured during the VOTE of $name!\n$!\n" and
		goto END_ if $stat;
	}
	
	print STDERR "No vote file generated for $out\n" and
	goto END_ unless open(VOTE,"<$out.mol2");
       	<VOTE>;
        my $line=<VOTE>;
        close VOTE;
        chomp $line;
        my @arr =split /\s+/,$line;
        print STDERR "No vote found in $out.mol2\n" and
        goto END_ unless($arr[1]);
        $i = $arr[0];
        $v = $arr[1];
	
	goto END_ unless checkProt("$prot\_r.pdb",25000);	#检查蛋白大小
	goto END_ unless checkOrganic("$out.mol2",600);
	#xscore要求去掉杂原子的纯受体文件
	$s = `$PATH/xscore/xscore $PATH/xscore/parameter/ $prot\_r.pdb $out.mol2 2>>$out.err|awk '/^Predicted binding energy/{print \$5}'`;
	chomp $s;
	print STDERR "Error occured during the XSCORE of $name!\n$!\n" if!(defined $s and $s ne '');
	
END_:
	delZero("$out.err");
	delZero("$out.mol2");
	$s = defined $s ? $s : 'null';
	open(VOTE,">>$score_file");
	print VOTE "$h->{pdb} $h->{site} $s $v $i\n";
	close VOTE;
}

sub pbsa{
	my ($h,$idx) = @_;
	my $out = "$h->{pdb}\_$h->{site}\_$h->{lig}\_pbsa";

	return if stat "$out.pdb";#靠靠靠	
	return if !stat "$h->{pdb}\_$h->{site}\_$h->{lig}\_vote.mol2";
	#print getDateTime().$out."\n";
	`mmPBSA.pl $h->{lig} $h->{pdb} $h->{site} $idx 2>$out.err`;
	#print getDateTime()."\n";
	delZero("$out.err");
	stat "$out.err" or `rm -r $out`;
}

1;
#===utils===========================================================

sub delZero{
	my ($file) = @_;
	return undef unless stat($file);
	my @info=stat($file);
	unlink $file if(0 == $info[7]);
}

sub checkProt{
	my($prot,$siz)=@_;

	if(!stat($prot)){		
		print STDERR "No such file or cannot access prot: $prot!\n";
		return undef;
	}
	
	my @prot_info=stat($prot);
	if(defined $siz){
		if($prot_info[7] < $siz){			
			print STDERR "Residue num of $prot is too small,keep it greater than 100!\n";
			return undef;
		}
	}else{
		if($prot_info[7] < 35000){			#最小蛋白文件尺寸暂定35000
			print STDERR "Residue num of $prot is too small,keep it greater than 100!\n";
			return undef;
		}
	}
	return $prot_info[7];
}

sub checkOrganic{
	my($organic,$siz)=@_;
	
	print STDERR "No such file or cannot access: $organic!\n" and 
	return undef unless stat($organic);

	my @info=stat($organic);
	
	if(defined $siz){
		if($info[7] < $siz){
			print STDERR "HeavAtom num of $organic is too small,keep it greater than 6!\n";
			return undef;
		}
	}else{
		if($info[7] < 500){
			print STDERR "HeavAtom num of $organic is too small,keep it greater than 6!\n";
			return undef;
		}
	}
	
	return $info[7];
}

sub getDateTime{
	my ($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime(time());
	$year += 1900; # $year是从1900开始计数的，所以$year需要加上1900
	$mon ++; # $mon是从0开始计数的，所以$mon需要加上1
	return sprintf("%d-%02d-%02d %02d:%02d:%02d",$year,$mon,$mday,$hour,$min,$sec) ;
}
