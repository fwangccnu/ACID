#!/usr/bin/python
import os
import sys
import fnmatch
import linecache
def clean(complex,ligand_name):
	print 'Clean the input pdb file to make it processible by our protocol!'
	amber_res=['HIS','ALA','GLY','SER','THR','LEU','ILE','VAL','ASN','GLN','ARG','HID','HIE','HIP','TRP','PHE','TYR','GLU','ASP','LYS','LYN','PRO','CYS','CYM','CYX','MET','ACE','NME','ASH','GLH','2HO']                    #Constrcut the list that includes residues which can be identify by AMBER 
	f1=open(complex,'r')
	f2=open(r'./com_1.pdb','w+')
	for line in f1.readlines():                                             #Keep ATOM HETATM TER informations and remove others
		if line.startswith('ATOM') or line.startswith('HETATM') or line.startswith('TER'):
			f2.write(line)                                          #Get the com_1.pdb that just include ATOM HETATM and TER information 
	f2.close()
	f3=open(r'./com_1.pdb','r')
	f4=open(r'./com_2.pdb','w+')
	for line in f3.readlines():                                             #Remove waters and solvents
		if line[17:20]=='HOH' or line[17:20]=='WAT' or line[17:20]=='SOL':
			pass
		else:
			f4.write(line)						#Get the com_2.pdb that does not include waters and other solvents
	f4.close()
	######################################################################################################################################	
	directory=os.listdir(r'./')
	COFACTORS=[]
	LIGAND=[]
	for prepfile in directory:                                              #Add fragment and cofactors names to amber_res
		if fnmatch.fnmatch(prepfile,'*.zip'):
			if os.path.isdir(r'./confactors_para'):
	        		pass
			else:
	        		os.mkdir(r'./confactors_para')
			os.system('unzip -d ./confactors_para {zipfile}'.format(zipfile=prepfile))
			os.system('cp $(find ./confactors_para | grep .prep) ./confactors_para/ >/dev/null 2>&1')
			os.system('cp $(find ./confactors_para | grep .mod) ./confactors_para/ >/dev/null 2>&1')
	
			directory2=os.listdir(r'./confactors_para')
			for file in directory2:
				if fnmatch.fnmatch('./confactors_para/{file}'.format(file=file),'*prep'):
					name_line=linecache.getline('./confactors_para/{file}'.format(file=file),5)
					amber_res.append(name_line[0:3])
					COFACTORS.append(name_line[0:3])
					print 'COFACTORS',name_line[0:3] 
		else:
			pass
	LIGAND.append(ligand_name)
	amber_res.append(ligand_name)
	COF_FRG= COFACTORS + LIGAND
	#######################################################################################################################################
	f5=open(r'./com_2.pdb','r')
	f6=open(r'./com_3.pdb','w+')
	unidenti_res={}
	for line in f5.readlines():	                                         #Delete the unidentified resduies or cofactors
		if line.startswith('TER'):
	 		f6.write(line)
		else:
			y=line[17:20]
			if y in amber_res:
				f6.write(line)
			else:
				unidenti_res[line[22:26]]=line[17:20]
	f6.close()
	if unidenti_res!={}:
		print 'There exits unidentified residues or cofactors',unidenti_res
	else:
		pass
	#####################################################################################################################################
	f7=open(r'./com_3.pdb','r')
	for line in f7.readlines():                                 #Only keep one ligand and remove others with the same ligand name
		if line[17:20]==ligand_name:
			ligand_name_acc=line[16:20]                            
			chain_name=line[21:22]
			ligand_num=line[22:26]
			print 'The ligand',ligand_name_acc,'with residue number',ligand_num,'has been found, other models will be removed.'
			break
		else:
			continue
	f7.close()
	f7=open(r'./com_3.pdb','r')
	f8=open(r'./com_4.pdb','w+')
	fL=open(r'./L.pdb','w+')
	for line in f7.readlines():
		if line.startswith('TER'):
			f8.write(line)
		elif line[16:20]==ligand_name_acc and line[21:22]==chain_name and line[22:26]==ligand_num:
			fL.write(line)
		elif line[17:20]==ligand_name and (line[16:20]!=ligand_name_acc or line[21:22]!=chain_name or line[22:26]!=ligand_num):
			pass
		else:
			f8.write(line)
	f7.close()
	f8.close()
	fL.close()
	fL=open(r'./L.pdb','r')
	f8=open(r'./com_4.pdb','a')
	for line in fL.readlines():
		f8.write(line)
	f8.close()
	fL.close()
	###################################################################################################################################
	f9=open(r'./com_4.pdb','r')
	f10=open(r'./com_5.pdb','w+')
	a=0
	c=[]
	for line in f9.readlines():
		a+=1
		if line.startswith('TER'):
			gapline=linecache.getline('./com_4.pdb',a-1)             #Get the gap line of the protein and remember it
			z=gapline[30:54]
			if z not in c and z!='                        ':
				c.append(z)
			else:
				pass
		else:
			f10.write(line)
	f10.close()
	###########################################################################################################################
	f11=open(r'./com_5.pdb','r')                                              #Add TER
	f12=open(r'./com_6.pdb','w+')
	d=2
	for line in f11.readlines():
		if linecache.getline('./com_5.pdb',d):
			xxx=linecache.getline('./com_5.pdb',d)
	                res_num2=int(xxx[22:26])
			res_num1=int(line[22:26])
			res_cord1=line[30:54]
			res_name2=xxx[17:20]
			res_name1=line[17:20]
	                if not (res_num2 - res_num1 == 1 or res_num2 - res_num1 == 0):     #Add TER between the gap residues
				f12.write(line)
	                        f12.write('TER\n')
				print "we will add 'TER' tag between" ,res_num1, 'and' ,res_num2
	                elif res_name2 in COF_FRG and res_name1 != res_name2:              #Add TER for cofactors or fragment
				f12.write(line)
				f12.write('TER\n')
			elif res_name1 in COF_FRG and res_name1 != res_name2:              #Add TER for cofactors or fragemnt
				f12.write(line)
				f12.write('TER\n')
			elif res_cord1 in c:                                                  #Add TER at the gap line
				f12.write(line)
				f12.write('TER\n')
			elif res_name1 in COF_FRG and res_name2 in COF_FRG and res_num2 - res_num1 != 0:
				f12.write(line)
				f12.write('TER\n')
			else:	                  
				f12.write(line)
		else:
			f12.write(line)
			f12.write('TER\n')
			f12.write('END')
		d+=1
	f12.close()
	#############################################################################################################################
	f13=open(r'./com_6.pdb','r')
	f14=open(r'./complex.pdb','w+')
	f15=open(r'./lig.pdb','w+')
	e=1
	for line in f13.readlines():                                           #Rewrite the ligand's atom name
		if line[17:20]==ligand_name:
			line=line[:14]+'%-2s'%(e)+line[16:]
			e+=1
			if line[12:14]=='CL' or line[12:14]=='cl':
				line=line.replace(line[12:14],'Cl')
				f14.write(line)
				f15.write(line)
			elif line[12:14]=='BR' or line[12:14]=='br':
				line=line.replace(line[12:14],'Br')
				f14.write(line)
				f15.write(line)
			else:
				f14.write(line)
				f15.write(line)
		else:
			f14.write(line)
	f15.write('TER\n')
	f15.write('END')		
	f14.close()
	f15.close() 		
	os.system('babel -h -ipdb ./lig.pdb -omol2 ligand.mol2 -p 7 >/dev/null 2>&1')
	os.system('dos2unix complex.pdb >/dev/null 2>&1')
	os.system('dos2unix ligand.mol2 >/dev/null 2>&1')
	#f16=open(r'./LIG.pdb','r')
	#f17=open(r'./ligand.pdb','w+')
	#e=1
	#for line in f16.readlines():
	#	if line.startswith('ATOM') or line.startswith('HETATM'):
	#		line=line[:14]+'%-2s'%(e)+line[16:]
	#		e+=1
	#		f17.write(line)
	#	elif line.startswith('TER') or line.startswith('END'):
	#		f17.write(line)
	#
	#	else:
	#		pass
	
	#f17.close()
	########################################################################################################################
	os.remove('./com_1.pdb')
	os.remove('./com_2.pdb')
	os.remove('./com_3.pdb')                                                 #Remove the tmp files
	os.remove('./com_4.pdb')
	os.remove('./com_5.pdb')
	os.remove('./com_6.pdb')
	os.remove('./lig.pdb')
	os.remove('./L.pdb')
	#os.remove('./LIG.pdb')

