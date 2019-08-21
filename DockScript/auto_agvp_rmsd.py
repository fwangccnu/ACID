#! /usr/bin/python
import re,os,numpy,fcntl
def rmsd(V, W):
  """ Calculate Root-mean-square deviation from two sets of vectors V and W.
  """
  D = len(V.values()[0])
  N = len(V.keys())
  rmsd = 0.0
  for i in V.keys():
    if i not in W.keys() or re.search('H',i):
      pass
    else:
      for j in range(D):
         rmsd += sum([(V[i][j]-W[i][j])**2.0])
  return  numpy.sqrt(rmsd/N)

def get_coordinates(filename):
  f = open(filename, 'r').readlines()
  DICT={}
  for line in f[f.index('@<TRIPOS>ATOM\n')+1:f.index('@<TRIPOS>BOND\n')]:
    numbers = line[17:46].split()
    atom_name=line.split()[1]
    numbers = [float(number) for number in numbers]
    DICT[atom_name]=numbers
  return DICT

def multiple_replace(text, adict):
     rx = re.compile('|'.join(map(re.escape, adict)))
     def one_xlat(match):
           return adict[match.group(0)]
     return rx.sub(one_xlat, text)


def f(file):
    n_start=1
    while n_start>=1:
      if os.path.exists(pathfirst+'/lig.txt'):
        try:
          fo=open(pathfirst+'/'+file,'w')
          fcntl.flock(fo,fcntl.LOCK_EX)
          fi=open(pathfirst+'/lig.txt').readlines()
          n_start=len(fi)
          if len(fi)==1:
            if len(fi[0].split('.'))==2:
              ligand=fi[0].split('.')[0]
              fo1=open(pathfirst+'/lig.txt','w')
              fo1.close()
              fo.close()
            else:
              fo.close()
              n_start=0
          elif len(fi)>1:
            ligand=fi[0].split('.')[0]
            fo1=open(pathfirst+'/lig.txt','w')
            for line in fi[1:]:
              fo1.write(line)
            fo1.close()
          fo.close()
        except:
          pass
      else:
        n_start=0
      if n_start>=1:
        try:
          os.system('echo '+ligand+' >>'+pathfirst+'/out.txt')
          if os.path.exists(pathsecond+'//'+ligand):
            os.popen('rm -r '+pathsecond+'//'+ligand)
          os.makedirs(pathsecond+'//'+ligand)
          os.chdir(pathsecond+'//'+ligand)
          os.popen('cp -r '+pathfirst+'//template//* '+pathsecond+'//'+ligand+'//')
          os.popen('cp '+pathfirst+'//database//'+ligand+'.mol2 '+pathsecond+'//'+ligand+'//')
          os.chdir(pathsecond+'//'+ligand+'//')
          os.popen('python auto_autodock.py')
          os.popen('python auto_vina.py')
          os.popen('python auto_gold.py')
          os.popen('python auto_plants.py')
          pwd1=os.listdir('gold_cluster')
          pwd2=os.listdir('autodock_cluster')
          pwd3=os.listdir('vina_cluster')
          pwd4=os.listdir('plants_cluster')
          if os.path.exists(pathsecond+'//'+ligand+'//cluster'):
            os.system('rm -r '+pathsecond+'//'+ligand+'//cluster')
          os.makedirs(pathsecond+'//'+ligand+'//cluster')
          os.system('cp *_cluster/* '+pathsecond+'//'+ligand+'//cluster')
          list_ligand=os.listdir(pathsecond+'//'+ligand+'//cluster')
          fo=open('rmsd.out','w+')
          for i in list_ligand:
            fo.write(i+'  ')
          fo.write('\n')
          fo=open('rmsd.out','a')
          for i in list_ligand:
            a=get_coordinates(pathsecond+'//'+ligand+'//cluster/'+i)
            fo.write(i+'  ')
            for j in list_ligand:
              b=get_coordinates(pathsecond+'//'+ligand+'//cluster/'+j)
              rmsd1=rmsd(a,b)
              fo.write(str(rmsd1)+'  ')
            fo.write('\n')
          fo.close()
          fi=open('rmsd.out').readlines()
          list_rmsd=[]
          m=-1
          for line in fi[1:]:
            m+=1
            n=-1
            for i in line.split()[1:]:
              n+=1
              if 0<float(i)<=2:
                list_rmsd.append([list_ligand[m],list_ligand[n]])
          n=len(list_rmsd)
          fo=open('rmsd_reslut.out','w')
          m=0
          inter=[]
          while m<n:
            a=0
            for j in list_rmsd[m+1:]:
              if len(list(set(list_rmsd[m]).intersection(set(j))))==1:
                list_start=list(set(list_rmsd[m]).intersection(set(j)))
                new=list_start[0]
                inter.append(new)
                a+=1
              elif len(list(set(list_rmsd[m]).intersection(set(j))))==2:
                a+=1
            if a==0:          
              for b in list_rmsd[m]:
                fo.write(b+'  ')
              fo.write('\n')
            m+=1
          if len(inter)!=0:
            inter=list(set(inter))
            list_rmsd_1=[]
            for i in inter:
              list_s=[]
              for j in list_rmsd:
                if i in j:
                  for m in j:
                    list_s.append(m)
              list_s=list(set(list_s))
              list_rmsd_1.append(list_s)
            list_rmsd_end=[]
            for i in list_rmsd_1:
              for j in list_rmsd_1:
                a=0
                if i!=j and len(list(set(i).intersection(set(j))))>=1:  
                  list_s=list(set(i).union(set(j)))
                  list_rmsd_end.append(list_s)
                  a+=1
                if a==0:
                  list_rmsd_end.append(i)
            for i in list_rmsd_end:
              for j in i:
                fo.write(j+' ')
              fo.write('\n')
          fo.close()
          if not os.path.exists(pathfirst+'//selected'):
            os.makedirs(pathfirst+'//selected')
          fi=open('rmsd_reslut.out').readlines()
          m=100
          linedock={}
          final=[]
          start=0
          for line in fi:
            list_dock=[]
            for i in line.split():
              sorts=re.findall(r"\D+",i)[0]
              if sorts not in list_dock:
                list_dock.append(sorts)
            n=len(list_dock)
            linedock[start]=n
            start+=1
          print linedock
          highest = max(linedock.values())
          new=list(k for k,v in linedock.items() if v == highest)
          if len(new)==1:
            final=fi[new[0]].split()
          else:
            D={}
            for i in  new:
              nums=0
              D[i]=0
              list5=fi[i].split()
              for w in list5:
                p,q=os.path.splitext(w)
                D[i]+=float(p.split('_')[1])
            list6=D.values()
            list6.sort(reverse=True)
            list6_len=len(list6)
            listfinal=[]
            if list6[0]/list6[1]>1.5:
              for key,value in D.iteritems():
                if value==list6[0]:
                  final=fi[key].split()
            else:
              r=1
              while r+1<list6_len-1:
                if list6[r]/list6[r+1]>1.5:
                  w=0
                  while w<r+1:
                    for key,value in D.iteritems():
                      if value==list6[w]:
                        listfinal.append(value)
                    w+=1  
                r+=1
              if len(listfinal)==0:
                listfinal=list6
              dict1={}
              for i in listfinal:
                  for key,value in D.iteritems():
                    if value==i:
                      list7=fi[key].split()
                      for x in list7:
                        sorts=re.findall("\D+",x.split('_')[0])[0]
                        if sorts not in dict1.keys():
                          oder=re.search('(\d+)\.(\d*)',x.split('_')[0])
                          oder1=oder.group(0)
                          dict1[sorts]=float(oder1)
                        else:
                          oder=re.search('(\d+)\.(\d*)',x.split('_')[0])
                          oder1=oder.group(0)
                          if float(oder1)<dict1[sorts]:
                            dict1[sorts]=float(oder1)
                      for y in dict1.values():
                        nums+=y
                      j=nums/highest
                      if j<m:
                        m=j
                        final=list7
          for i in final:
            a=re.findall("\D+",i)[0]
            oder_end_test=100
            for j in final:
              if re.findall("\D+",j)[0]==a:
                oder_end=re.search('(\d+)\.(\d*)',j.split('_')[0])
                oder_end1=oder_end.group(0)
                if float(oder_end1)<oder_end_test:
                  oder_end_test=float(oder_end1)
                  name=j
            fi1=open(pathsecond+'//'+ligand+'//'+a+'_cluster/'+name).readlines()
            fo=open(pathfirst+'//selected//'+ligand+'.mol2','w')
            for line in fi1:
              text=line
              adict ={
                      'BR':'Br',
                      'CL':'Cl',
                     }
              fo.write(multiple_replace(text, adict))
            break
            fo.close()
          os.chdir(pathfirst)
          os.popen('rm '+pathfirst+'//database//'+ligand+'.mol2')
          #os.popen('rm -r '+pathsecond+'//'+ligand)
        except:
          continue
    return n_start 

if __name__ == "__main__":
  pathfirst=os.getcwd()
  mainpath=os.path.dirname(pathfirst)
  if not os.path.exists('template'):
    os.makedirs('template')
    fi=open(pathfirst+'//example//receptor.txt').readlines()
    for line in fi:
      if line.startswith('receptor'):
        receptor=line.split()[1]
      elif line.startswith('center'):
        center=(line.split()[1]+' '+line.split()[2]+' '+line.split()[3])  
      elif line.startswith('ligand'):
        if len(line.split())==2:
          ligand_t=line.split()[1]
        else:
          ligand_t=0
      elif line.startswith('autodock_size'):
        box_size=(line.split()[1]+' '+line.split()[2]+' '+line.split()[3])
      elif line.startswith('autodock_number'):
        autodock_number=line.split()[1]
      elif line.startswith('gold_number'):
        gold_number=line.split()[1]
      elif line.startswith('vina_number'):
        vina_number=line.split()[1]
      elif line.startswith('plants_number'):
        plants_number=line.split()[1]
    fi=open(pathfirst+'//example//clean_pdb_bak.py').readlines()
    fo=open(pathfirst+'//example//clean_pdb.py','w') 
    for line in fi:
      if re.search('pfvs_com.pdb',line):
        fo.write(line.replace('pfvs_com.pdb',receptor+'.pdb'))
      else:
        fo.write(line)
    fo.close()  
    os.chdir(pathfirst+'/example/')
    os.system('./prepare_receptor4.py -r '+receptor+'.pdb')
    os.system('python clean_pdb.py')
    list_dir=os.listdir(pathfirst+'/example/')
    prep_list=[]
    for prep in list_dir:
      p,q=os.path.splitext(prep) 
      if q=='.prep':
        prep_list.append(p)
    fo=open(pathfirst+'/example/parameter','w')
    fi1=open(mainpath+'//pbsa//screen_pbsa//parameter1.bak').readlines()
    fo1=open(mainpath+'//pbsa//screen_pbsa//parameter1.bak','w')
    fi2=open(mainpath+'//pbsa//screen_pbsa//parameter2.bak').readlines()
    fo2=open(mainpath+'//pbsa//screen_pbsa//parameter2.bak','w')
    fi3=open(mainpath+'//pbsa//screen_pbsa//files.in.bak').readlines()
    fo3=open(mainpath+'//pbsa//screen_pbsa//files.in.bak','w')
    for line in fi3[:6]:
      fo3.write(line) 
    fo2.write(fi2[0])
    fo2.write(fi2[1])
    fo2.write(fi2[2])
    fo2.write(fi2[3])
    fo1.write(fi1[0])
    fo1.write(fi1[1])
    fo1.write(fi1[2])
    fo1.write(fi1[3])   
    fo.write('source leaprc.ff99SB\n')
    fo.write('source leaprc.gaff\n')
    for prep in prep_list:
       fo.write('loadamberparams '+prep+'.mod\n')
       fo.write('loadamberprep '+prep+'.prep\n')
       fo1.write('loadamberparams '+prep+'.mod\n')
       fo1.write('loadamberprep '+prep+'.prep\n')
       fo2.write('loadamberparams '+prep+'.mod\n')
       fo2.write('loadamberprep '+prep+'.prep\n')
       fo3.write('Nonstandard   '+prep+'.prep\n')
    fo3.write('Bondindex     bondindex.h\n')
    fo3.close()
    fo.write('complex=loadpdb '+receptor+'.pdb\n')
    fo.write('saveamberparm complex complex.top complex.crd\n')
    fo.write('quit\n')
    fo.close()
    for line in fi1[4:]:
      fo1.write(line)
    fo1.close()
    for line in fi2[4:]:
      fo2.write(line)
    fo2.close()
    os.system('tleap -f parameter')
    os.system('ambpdb -p complex.top <complex.crd> '+receptor+'.mol2 -mol2')
    os.system('ambpdb -p complex.top <complex.crd> '+receptor+'.pdb -pqr')
    fi=open(receptor+'.pdb').readlines()
    fo=open(receptor+'.pdb','w')
    for line in fi[:-1]:
      fo.write(line.replace('END','TER'))
    fo.close()
    os.system('cp '+receptor+'.pdb '+mainpath+'//pbsa//screen_pbsa//')
    os.system('cp * '+pathfirst+'//template//')
    fi=open(receptor+'.pdb').readlines()
    residue_number=fi[-2].split()[4]
    atom_number=fi[-2].split()[1]
    fi=open(mainpath+'//pbsa//screen_pbsa//screening_run.bak').readlines()
    fo=open(mainpath+'//pbsa//screen_pbsa//screening_run','w')
    for line in fi:
      if re.search('PYR1.pdb',line):
        fo.write('cp '+receptor+'.pdb complex.pdb\n')
      elif re.search('7881',line):
        fo.write(line.replace('7881',atom_number))
      else:
        fo.write(line)
    fo.close()
    fi=open(mainpath+'//pbsa//screen_pbsa//min2.in.bak').readlines()
    fo=open(mainpath+'//pbsa//screen_pbsa//min2.in','w')
    for line in fi:
      if line.startswith('restraintmask'):
        fo.write(line.replace('474',residue_number))
      else:
        fo.write(line)
    fo.close()
    fi=open(mainpath+'//pbsa//screen_pbsa//min3.in.bak').readlines()
    fo=open(mainpath+'//pbsa//screen_pbsa//min3.in','w')
    for line in fi:
      if line.startswith('restraintmask'):
        fo.write(line.replace('474',residue_number))
      else:
        fo.write(line)
    fo.close()
    fi=open(mainpath+'//pbsa//screen_pbsa//min4.in.bak').readlines()
    fo=open(mainpath+'//pbsa//screen_pbsa//min4.in','w')
    for line in fi:
      if line.startswith('restraintmask'):
        fo.write(line.replace('475',str(int(residue_number)+1)))
      else:
        fo.write(line)
    fo.close()
    fi1=open(pathfirst+'//example//ligand').readlines()
    fo=open(pathfirst+'//template//ligand','w')
    for line in fi1:
      if line.startswith('receptor'):
        fo.write('receptor ='+receptor+'.pdbqt\n')
      elif line.startswith('center_x'):
        fo.write('center_x='+center.split()[0]+'\n')
      elif line.startswith('center_y'):
        fo.write('center_y='+center.split()[1]+'\n')
      elif line.startswith('center_z'):
        fo.write('center_z='+center.split()[2]+'\n')
      elif line.startswith('size_x'):
        fo.write('size_x='+str(float(box_size.split()[0])*0.375)+'\n')
      elif line.startswith('size_y'):
        fo.write('size_y='+str(float(box_size.split()[1])*0.375)+'\n')
      elif line.startswith('center_z'):
        fo.write('size_z='+str(float(box_size.split()[2])*0.375)+'\n')
      elif line.startswith('num_modes'):
        fo.write('num_modes='+str(vina_number)+'\n')
      else:
        fo.write(line)
    fo.close()
    if ligand_t==0:
      fi1=open(pathfirst+'//example//gold.conf').readlines()
      fo=open(pathfirst+'//template//gold.conf','w')
      for line in fi1:
        if line.startswith('protein_datafile'):
          fo.write('protein_datafile='+receptor+'.pdb\n')
        elif line.startswith('origin = '):
          fo.write('origin = '+center+'\n')
        elif line.startswith('ligand_data_file'):
          fo.write('ligand_data_file x.mol2 '+str(gold_number)+'\n')
        elif line.startswith('radius'):
          R=max(float(i) for i in box_size.split())
          fo.write('radius = '+str(R*0.375/2)+'\n')
        else:
          fo.write(line)
      fo.close()
    else:   
      fi1=open(pathfirst+'//example//gold_ligand.conf').readlines()
      fo=open(pathfirst+'//template//gold.conf','w')
      for line in fi1:
        if line.startswith('protein_datafile'):
          fo.write('protein_datafile='+receptor+'.pdb\n')
        elif line.startswith('ligand_data_file'):
          fo.write('ligand_data_file x.mol2 '+str(gold_number)+'\n')
        elif line.startswith('cavity_file'):
          fo.write('cavity_file = '+pathfirst+'//example//'+ligand_t+'.pdb\n')
        elif line.startswith('radius'):
          R=max(float(i) for i in box_size.split())  
          fo.write('radius = '+str(((R*0.375)**2+(R*0.375)**2+(R*0.375)**2)**(1/2)/2)+'\n')
        else:
          fo.write(line)
      fo.close()
    fi1=open(pathfirst+'//example//plants.in').readlines()
    fo=open(pathfirst+'//template//plants.in','w')
    for line in fi1:
      if line.startswith('protein_file'):
        fo.write('protein_file '+receptor+'.mol2\n')
      elif line.startswith('cluster_structures'):
        fo.write('cluster_structures '+str(plants_number)+'\n')
      elif line.startswith('bindingsite_radius'):
        fo.write('bindingsite_radius '+str(((R*0.375)**2+(R*0.375)**2+(R*0.375)**2)**(1/2)/2)+'\n')
      elif line.startswith('bindingsite_center'):
        fo.write('bindingsite_center '+center+'\n')
      else:
        fo.write(line)
    fo.close()
    os.system('cp '+pathfirst+'//example//*.prep '+mainpath+'//pbsa//screen_pbsa//')
    os.system('cp '+pathfirst+'//example//*.mod '+mainpath+'//pbsa//screen_pbsa//')
  if not os.path.exists(pathfirst+'//conformation'):
    os.makedirs(pathfirst+'//conformation')
  pathsecond=pathfirst+'//conformation'
  lig=os.listdir(pathfirst+'//database')
  os.chdir(pathfirst)
  if not os.path.exists(pathfirst+'//lig.txt'):
    fo=open(pathfirst+'//lig.txt','w')
    for i in lig:
      p,q=os.path.splitext(i)
      if q=='.mol2':
        fo.write(i+'\n')
    fo.close()
  while 1:
    if f('./temp')==0:
      break
