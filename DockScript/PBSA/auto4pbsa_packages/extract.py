#!/usr/bin/python
def extract(com_file,lig_name,path):
	F = open(com_file,'r')
	F1 = open(path+'/rec.pdb','w+')
	F2 = open(path+'/lig.pdb','w+')
	for line in F:
		if line.startswith('ATOM') or line.startswith('HETATM'):
                	if line[17:20]==lig_name:
                        	F2.write(line)
                        else:
                                F1.write(line)
                elif line.startswith('TER'):
                        F1.write(line)
                else:
                        pass
        F1.write('END')
        F2.write('END')
        F1.close()
        F2.close()

