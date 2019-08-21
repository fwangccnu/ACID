#/usr/bin/python
class parameter:
	def __init__(self):
		self.complex = 'COM.pdb'
		self.ligand_name = 'LIG'
		self.minimazation = 'YES' 
		self.directory = './snapshot' 
		self.mm_pbsa = './licz4pbsa_packages/mm_pbsa.in' 
		self.entropy = './licz4pbsa_packages/entropy/' 

	def initial(self,para_file):
		F=open(para_file,'r')
		for line in F:
			if line.startswith('complex'):
				self.complex = line.split()[1]
			elif line.startswith('ligand_name'):
				self.ligand_name = line.split()[1]
			elif line.startswith('minimazation'):
				self.minimazation = line.split()[1]
			elif line.startswith('directory'):
				self.directory = line.split()[1]
			elif line.startswith('mm_pbsa'):
				self.mm_pbsa = line.split()[1]
			elif line.startswith('entropy'):
				self.entropy = line.split()[1]
			else:
				pass
		F.close()
