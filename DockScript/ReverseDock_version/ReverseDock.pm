package ReverseDock;
#! /usr/bin/perl -w
use strict;
my $POOL_PATH="$ENV{ACID}/FishPool";
my $PATH = "$ENV{ACID}/DockScript/";
my $PWD = $ENV{PBS_O_WORKDIR} ? $ENV{PBS_O_WORKDIR} : $ENV{PWD};
#****************************************************************************************************
sub leDock{
	my ($h,$idx) = @_;
	
	my $prot = "$POOL_PATH/$h->{pdb}/$h->{pdb}";
	my $out = "$h->{pdb}\_$h->{site}\_$h->{lig}\_le";
	my $score_file = "$h->{lig}\_$idx.le";
	my $score = 'null';
	
	return if stat "$out.pdb";#避免重复计算
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
	$content .= "Number of binding poses\n10\n";
	$content .= "Ligands list\n$out.list\nEND\n";
	open(CONF,">$out.conf");
	print CONF $content;
	close CONF;
	open(LIST,">$out.list");
	print LIST "$out/$h->{lig}.mol2";		#Ledock×Ô¶¯Ê¹ÓÃÅäÌåËùÔÚµÄÂ·¾¶×÷Îª¹¤×÷Â·¾¶
	close LIST;
	#======================================================
	my $set_env="LD_LIBRARY_PATH=$PATH/Ledock/libc";
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
		#print PDB @{$_} foreach @{$conf};
		print PDB @{$conf->[1]};
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
	
	#return if stat "$out.mol2";

	`babel $name\_le.pdb $out.mol2`;	

	goto END_ unless checkProt("$prot\_r.pdb",25000);	#¼ì²éµ°°×´óÐ¡
	goto END_ unless checkOrganic("$out.mol2",600);

	my $s = `$PATH/xscore/xscore $PATH/xscore/parameter/ $prot\_r.pdb $out.mol2 2>>$out.err|awk '/^Predicted binding energy/{print \$5}'`;
	chomp $s;
	print STDERR "Error occured during the XSCORE of $name!\n$!\n" if!(defined $s and $s ne '');
	
END_:
	delZero("$out.mol2");
	delZero("$out.err");
	$s = defined $s ? $s : 'null';
	open(VOTE,">>$score_file");
	print VOTE "$h->{pdb} $h->{site} $s\n";
	close VOTE;
}

sub pbsa{
	my ($h,$idx) = @_;
	my $out = "$h->{pdb}\_$h->{site}\_$h->{lig}\_pbsa";
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
		if($prot_info[7] < 35000){			#×îÐ¡µ°°×ÎÄ¼þ³ß´çÔÝ¶¨35000
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
	$year += 1900; # $yearÊÇ´Ó1900¿ªÊ¼¼ÆÊýµÄ£¬ËùÒÔ$yearÐèÒª¼ÓÉÏ1900
	$mon ++; # $monÊÇ´Ó0¿ªÊ¼¼ÆÊýµÄ£¬ËùÒÔ$monÐèÒª¼ÓÉÏ1
	return sprintf("%d-%02d-%02d %02d:%02d:%02d",$year,$mon,$mday,$hour,$min,$sec) ;
}
