import os
import fnmatch
from multiprocessing.dummy import Pool as ThreadPool
def entropy_cal(file_dir,work_dir):
	os.system('cp {file_dir}/* {work_dir}'.format(file_dir=file_dir,work_dir=work_dir))
	os.system('cp {file_dir}/ASH.prep  ./'.format(file_dir=file_dir))
	os.system('cp {file_dir}/GLH.prep  ./'.format(file_dir=file_dir))
	os.system('cp {file_dir}/h2o.prep  ./'.format(file_dir=file_dir))
####################################################################################################################################################################################################
	path=os.getcwd()
	f17=open(work_dir+'/files.in','w+')
	f17.write('  AMBER FILES  FOR CONFORMATIONAL ENTROPY\n')
	f17.write('ProteinNT     all_aminont94.in\n')
	f17.write('Proteinct     all_aminoct94.in\n')                               #Define the function
	f17.write('Protein       all_amino94.in\n')
	f17.write('DNA           all_nuc02.in\n')
	f17.write('Ligand        {path}/ligand.prep\n'.format(path=path))
	f17.write('Nonstandard   {path}/ASH.prep\n'.format(path=path))
	f17.write('Nonstandard   {path}/GLH.prep\n'.format(path=path))
	f17.write('Nonstandard   {path}/h2o.prep\n'.format(path=path))
	if os.path.isdir(r'./confactors_para'):
        	directory=os.listdir(r'./confactors_para')
        	for file in directory:
        		if fnmatch.fnmatch('./confactors_para/{file}'.format(file=file),'*prep'):
                        	f17.write("Nonstandard   {path}/confactors_para/{confactor_prep}\n".format(path=path,confactor_prep=file))
                	else:
                        	continue
	f17.write('Bondindex     bondindex.h\n')
	f17.close()
	os.chdir(work_dir)
	try:
		os.system('./nmode_S')
        	os.system('./average_single')
	except:
		os.chdir(path)
	os.chdir(path)	
##################################################################################################################################################################################
	
