#!/usr/bin/perl -w
use strict;
BEGIN{push(@INC,"$ENV{HOME}/bin/")};	#设置perl环境@INC
BEGIN{push(@INC,"/yp_home/licz/bin/")};
use Paralell;
#***********************************************************
my $UPLOAD = '/yp_home/public/ccb/server/ReverseDock/uploads';#各个job_id文件夹的父文件夹
#***********************************************************
my $db="reverse_dock";
my $USAGE = "usage:\n\t $0 <job_id>\n";
defined $ARGV[0] or die $USAGE;
my $job_id = $ARGV[0];
#===进入工作环境============================
mkdir "$UPLOAD/$job_id/" unless stat "$UPLOAD/$job_id";
chdir "$UPLOAD/$job_id/";

Paralell::sqlExec("UPDATE $db.jobs_druggability SET status='QUEUE' WHERE job_id='$job_id'");
my ($file,$pdb_name) = getPdb();
Paralell::sqlExec("UPDATE $db.jobs_druggability SET status='RUNNING' WHERE job_id='$job_id'");
system "/yp_home/licz/bin/fpocket -f $file >/dev/null";
my $desc = getDescrip($pdb_name);
defined $desc or die"ERR:fail to get descriptors\n";

my $len = scalar(@{$desc});
$len = $len > 10 ? 10 : $len;
my $sql = "INSERT INTO $db.druggability VALUES";
my @dataset;
for(my $i=1; $i < $len; ++ $i)
{
	print scalar(@{$desc->[$i]})."\t@{$desc->[$i]}\n";
	my $data = join(',',@{$desc->[$i]});
	$data = "(null,'$job_id','$pdb_name','\@\@$i'," . $data . ",'$file')";
	push(@dataset,$data);
}
$sql .= join(',',@dataset).";";
#print $sql."\n";
Paralell::sqlExec($sql);
Paralell::sqlExec("UPDATE $db.jobs_druggability SET status='FINISHED' WHERE job_id='$job_id'");
exit(0);

sub getPdb{
	my $rs_file = Paralell::sqlQuery("SELECT file_path FROM $db.jobs_druggability WHERE job_id='$job_id'");
	my $rs_pdbID = Paralell::sqlQuery("SELECT pdb FROM $db.jobs_druggability WHERE job_id='$job_id'");
	my ($file,$pdbID);
	$file = $rs_file->[0] ? $rs_file->[0] : undef;
	$pdbID = $rs_pdbID->[0] ? $rs_pdbID->[0] : undef;
	if(!defined $file){
		if(defined $pdbID){
			chomp $pdbID;
			`wget https://files.rcsb.org/download/$pdbID.pdb -O $pdbID.pdb`;
			$file = "$pdbID.pdb";
		}else {die "ERR:No parameters gotten from web front!\n";}
	}
	chomp $file;
	print "$file\n";
	delZero($file) or die "ERR:cannot access $file\n";
	$file =~ /^(\w+)\.pdb/;
	my $pdb_name = $1;
	return($file,$pdb_name);
}

sub getDescrip{
	my ($pdb) = @_;
	stat "$pdb\_out" or die "Directory $pdb\_out cannot find\n";
	delZero("$pdb\_out/$pdb\_info.txt") or print STDERR "No such file $pdb\_out/$pdb\_info.txt\n" and return undef;
	open(INFO,"<$pdb\_out/$pdb\_info.txt") ;
	my @infos = <INFO>;
	close INFO;
	#unlink glob "$pdb\_out/pockets/*";
	my $rs = [];
	my $idx = 0;
	foreach my $line(@infos){
		if($line =~ /^Pocket (\d{1,2})/o){
			$idx = int(0 + $1);
			my @pock;
			$rs->[$idx] = \@pock;
		}elsif($line =~ /^\tScore :\s+?(-?\d+(\.\d+)?)/o){
			push @{$rs->[$idx]},$1;
		}elsif($line =~ /^\tDruggability Score :\s+?(-?\d+(\.\d+)?)/o){
			push @{$rs->[$idx]},$1;
		}elsif($line =~ /^\tNumber of Alpha Spheres :\s+?(\d+(\.\d+)?)/o){
			push @{$rs->[$idx]},$1;
		}elsif($line =~ /^\tTotal SASA :\s+?(\d+(\.\d+)?)/o){
			push @{$rs->[$idx]},$1;
		}elsif($line =~ /^\tPolar SASA :\s+?(\d+(\.\d+)?)/o){
			push @{$rs->[$idx]},$1;
		}elsif($line =~ /^\tApolar SASA :\s+?(\d+(\.\d+)?)/o){
			push @{$rs->[$idx]},$1;
		}elsif($line =~ /^\tVolume :\s+?(\d+(\.\d+)?)/o){
			push @{$rs->[$idx]},$1;
		}elsif($line =~ /^\tMean local hydrophobic density :\s+?(-?\d+(\.\d+)?)/o){
			push @{$rs->[$idx]},$1;
		}elsif($line =~ /^\tMean alpha sphere radius :\s+?(\d+(\.\d+)?)/o){
			push @{$rs->[$idx]},$1;
		}elsif($line =~ /^\tMean alp. sph. solvent access :\s+?(\d+(\.\d+)?)/o){
			push @{$rs->[$idx]},$1;
		}elsif($line =~ /^\tApolar alpha sphere proportion :\s+?(\d+(\.\d+)?)/o){
			push @{$rs->[$idx]},$1;
		}elsif($line =~ /^\tHydrophobicity score:\s+?(-?\d+(\.\d+)?)/o){
			push @{$rs->[$idx]},$1;
		}elsif($line =~ /^\tVolume score:\s+?(-?\d+(\.\d+)?)/o){
			push @{$rs->[$idx]},$1;
		}elsif($line =~ /^\tPolarity score:\s+?(-?\d+(\.\d+)?)/o){
			push @{$rs->[$idx]},$1;
		}elsif($line =~ /^\tCharge score :\s+?(-?\d+(\.\d+)?)/o){
			push @{$rs->[$idx]},$1;
		}elsif($line =~ /^\tProportion of polar atoms:\s+?(\d+(\.\d+)?)/o){
			push @{$rs->[$idx]},$1;
		}elsif($line =~ /^\tAlpha sphere density :\s+?(\d+(\.\d+)?)/o){
			push @{$rs->[$idx]},$1;
		}elsif($line =~ /^\tCent. of mass - Alpha Sphere max dist:\s+?(\d+(\.\d+)?)/o){
				push @{$rs->[$idx]},$1;
		}elsif($line =~ /^\tFlexibility :\s+?(\d+(\.\d+)?)/o){
			push @{$rs->[$idx]},$1;
		}
	}
	return $rs;
}
sub getCord{
	my ($pdb) = @_;
	delZero("$pdb\_out/$pdb\_pockets.pqr") or print STDERR "No sunch file $pdb\_out/$pdb\_pockets.pqr\n" and return undef;
	open(POCK,"<$pdb\_out/$pdb\_pockets.pqr");
	my @pocks = <POCK>;
	close POCK;
	#unlink glob "$pdb\_out/*";
	my $idx = 0;
	my $rs = [];
	foreach my $line(@pocks){
		if($line =~ /^ATOM.{20}( \d|\d{2}).{4}(.{8})(.{8})(.{8})/o){	
			my $jdx = int(0 + $1);
			if($jdx > $idx){
				$idx = $jdx;
				my (@x,@y,@z);
				my $cord = {'x'=>\@x,'y'=>\@y,'z'=>\@z};
				$rs->[$idx] = $cord;
			}
			push(@{$rs->[$idx]->{x} },0.0+$2);
			push(@{$rs->[$idx]->{y} },0.0+$3);
			push(@{$rs->[$idx]->{z} },0.0+$4);
		}
	}
	return $rs;
}

sub calBox{
	my ($rx,$ry,$rz) = @_;
	my @x = @{$rx};
	my @y = @{$ry};
	my @z = @{$rz};
	return undef if(!defined $x[0] );
	@x=sort{$a<=>$b}@x;
	@y=sort{$a<=>$b}@y;
	@z=sort{$a<=>$b}@z;
	#@box=($x[0],$x[$#x],$y[0],$y[$#y],$z[0],$z[$#z]);
	print "$x[0]\t$x[$#x]\t$y[0]\t$y[$#y]\t$z[0]\t$z[$#z]\n";
	#return \@box;
}

sub delZero{
	my ($file) = @_;
	return undef unless stat($file);
	my @info=stat($file);
	unlink $file if(0 == $info[7]);
	return $info[7];
}
