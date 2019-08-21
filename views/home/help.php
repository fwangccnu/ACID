<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <p class="text-strong panel-title">User guide</p>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-12 col-xs-12 scrollspy">
                        <ul class="nav nav-pills nav-stacked affix" data-spy="affix">
                            <li class=""><a href="#brief">Brief Introduction</a></li>
                            <li class=""><a href="#Database">1.Drug & Target Database</a></li>
                            <li class=""><a href="#Docking">2.Consensus Inverse Docking</a></li>
                            <li class=""><a href="#Results">3.Results Browse </a></li>
                            <li class=""><a href="#Notice">4.Notice</a></li>
                            <li class=""><a href="#faq">5.FAQ</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-9 col-sm-12 col-xs-12">
                        <h2 id="brief">Brief Introduction</h2>
                        <p style="text-align: justify; text-indent:2em;">
                            CID (Consensus Inverse Docking) is an efficient computational approach for drug repurposing
                            with high efficiency and low cost advantages. Hence, we developed a web server named Auto
                            <i>in silico</i> Consensus Inverse Docking (ACID) based on CID method. ACID server contains
                            1479 commercial drugs, 809 approved targets and the rank-by-vote consensus inverse docking
                            program. Structure, physicochemistry, and therapeutic information are annotated for drugs.
                            Gene name, protein function, sequence, protein family and 3D structure etc. are recorded for
                            targets. The consensus inverse docking program evaluates the binding affinity between the
                            given active compound and each target in database, outputting top potential targets and
                            corresponding energy terms.
                        </p>

                        <h2 id="Database">1.Drug & Target Database</h2>
                        <p style="text-indent:2em;">
                            All drugs and targets are obtained through text-mining method. Firstly, we extract 1479
                            approved small molecule drugs from internet, and then extract targets of each drug according
                            to corresponding publications. Secondly, we search each target in Uniprot database for
                            further detailed information. Finally, 3D structures of each target were extracted from
                            Protein Data Bank, resulting 809 approved targets with X-ray 3D structures.
                        </p>

                        <div align="center">
                            <img class="img-responsive" alt="Responsive image"
                                 src="<?php echo base_url(array('resource', 'img', 'Fig1.png')) ?>" data-action="zoom">
                            <h4>Fig 1. Drug & target information extracted from internet </h4>
                        </div>
                        <h3>Usage of Drug & target DB:</h3>
                        <p style="text-indent:2em;">As you click the <strong>‘Browse’ </strong>option in navigation bar,
                            you are directed to the potential target DB. You can just browse brief information of the
                            whole database, ten entries for each page. Or you can enter key words at the top of the
                            page, choose your filtration criteria and click search to find the specific ones you are
                            interested in. </p>
                        <div align="center">
                            <img class="img-responsive" alt="Responsive image"
                                 src="<?php echo base_url(array('resource', 'img', 'Fig2.png')) ?>" data-action="zoom">
                            <h4>Fig 2. Browse or search in the Drug & target database </h4>
                        </div>
                        <p> Still, you can click Uniprot ID (pink frame) of one entry to get more detailed information
                            about the target, or click PDB code (cyan frame) to see details of the 3D structure in
                            Protein Data Bank.</p>
                        <h2 id="Docking">2.Consensus Inverse Docking</h2>
                        <p style="text-indent:2em;">CID (Consensus Inverse Docking) method contains three main steps: 1>
                            Conformation search; 2>Rank-By-Vote process; 3>MM/PBSA energy evaluation. </p>
                        <div align="center">
                            <img class="img-responsive" alt="Responsive image"
                                 src="<?php echo base_url(array('resource', 'img', 'Fig3.png')) ?>" data-action="zoom">
                            <h4>Fig 3. Consensus Inverse Docking strategy </h4>
                        </div>
                        <p style="text-indent:2em;">Firstly, in the Conformation search process, five docking programs
                            with five different conformation searching algorithms are used to generate five candidate
                            conformation sets, top ten for each. And Then, RMSDs between all conformations are computed,
                            if there is a conformation that is similar with the recommended one(RMSD < 2), it gets one
                            vote; the very one who get highest vote is selected as final binding mode. Finally scoring,
                            optimization and MM/PBSA energy computation is applied for evaluation of the binding
                            affinity between each protein and the active compound.</p>
                        <div align="center">
                            <img class="img-responsive" alt="Responsive image"
                                 src="<?php echo base_url(array('resource', 'img', 'Fig4.png')) ?>" data-action="zoom">
                            <h4>Fig 4. The Rank-By-Vote algorithm </h4>
                        </div>
                        <h3>Submit Jobs:</h3>
                        <p style="text-indent:2em;">Clicking <strong>‘Submit’</strong> option in the navigation bar, you
                            can submit your job briefly through three steps: 1. Select protein set (needed); 2. Draw a
                            molecule or upload file (needed); 3. Enter additional information (optional). </p>
                        <div align="center">
                            <img class="img-responsive" alt="Responsive image"
                                 src="<?php echo base_url(array('resource', 'img', 'Fig5.png')) ?>" data-action="zoom">
                            <h4>Fig 5. Choose protein sets for ACID </h4>
                        </div>
                        <div align="center">
                            <img class="img-responsive" alt="Responsive image"
                                 src="<?php echo base_url(array('resource', 'img', 'Fig6.png')) ?>" data-action="zoom">
                            <h4>Fig 6. Draw and upload molecule for ACID </h4>
                        </div>
                        <h2 id="Results">3.Results Browse </h2>
                        <p style="text-indent:2em;">Click <strong>‘Jobs’</strong> option, you browse status of your job:<span
                                    style="color: blue;display: inline"> Running</span>, <span
                                    style="color: red;display: inline">Queue</span>, <span
                                    style="color: orange;display: inline">Error</span> or <span
                                    style="color: green;display: inline">Finished</span>. If the job is finished, you
                            can click the ID of your job to redirect to the login page, and then input your job password
                            to check the result of your job.There is an example which can be searched on the
                            ‘Jobs’ webpage with the Job ID 'R600ahjx7978028', or you can click <a
                                    href="<?php echo site_url(array('reversedock', 'login', 'R600ahjx7978028')); ?>">here</a>
                            to see the example result. </p>
                        <h2 id="Notice">4.Notice</h2>
                        <p>IE10 or later on Windows, Firefox , Google Chrome , Apple Safari on Windows and Mac os are
                            recommended to ensure display. </p>
                        <p>Any questions, bug reports or suggestions are welcomed. Please send us an email.</p>
                        <h2 id="faq">5. FAQ</h2>
                        <b>Q:</b> What kind of molecule is needed? <br/>
                        <p style="text-align: justify">
                            <b>A:</b>Actually, an active compound molecule is needed. Briefly, any molecule with lower
                            than 6 heavy atoms are considered non-active. If you choose to upload file, SDF,PDB or mol2
                            file format is required.
                        </p>
                        <b>Q:</b> What is pdb/mol2 file? <br/>
                        <p style="text-align: justify">
                            <b>A:</b><span style="color: red"> pdb:</span> A processible pdb file can be obtained from
                            Protein Data Bank or from docking results
                            which should contain ATOM records for protein atoms, HETATM records for non-standard
                            residues.
                            For each HETATM record or ATOM record, columns 7-11 should be atom serial number, columns
                            13-16 should be atom name, columns 18-20 should be residue name, columns 23-26 should be
                            residue
                            sequence number, columns 31-38 stand for orthogonal coordinates for X in Angstroms, columns
                            39-46 stand for orthogonal coordinates for Y in Angstroms, columns 47-54 stand for
                            orthogonal
                            coordinates for Z in Angstroms, columns 77-78 for Element symbol. Generally, pdb files from
                            the
                            protein data bank are always acceptable by our server, but you may need to check your files
                            if
                            you get the pdb file through other ways (e.g. docking, homology modeling).
                        </p>

                        <b>Here is a pdb file example:</b><br/>


                        Example:<br>

                        <div class="row">
                    <pre>
                             1         2         3         4         5         6         7         8
                    12345678901234567890123456789012345678901234567890123456789012345678901234567890
                    ATOM   3001  N   TRP   378      91.533 115.037 126.730                       N
                    ATOM   3002  CA  TRP   378      90.600 113.899 126.701                       C
                    ATOM   3003  C   TRP   378      89.250 114.198 127.341                       C
                    ATOM   3004  O   TRP   378      89.145 114.768 128.432                       O
                    ATOM   3005  CB  TRP   378      91.230 112.721 127.449                       C
                    ATOM   3006  CG  TRP   378      91.424 111.487 126.659                       C
                    ATOM   3007  CD1 TRP   378      90.925 111.208 125.407                       C
                    ATOM   3008  CD2 TRP   378      92.129 110.319 127.075                       C
                    ATOM   3009  NE1 TRP   378      91.276 109.932 125.027                       N
                    ATOM   3010  CE2 TRP   378      92.025 109.360 126.020                       C
                    ATOM   3011  CE3 TRP   378      92.872 109.981 128.223                       C
                    ATOM   3012  CZ2 TRP   378      92.632 108.084 126.081                       C
                    ATOM   3013  CZ3 TRP   378      93.463 108.696 128.292                       C
                    ATOM   3014  CH2 TRP   378      93.324 107.762 127.223                       C
                    ATOM   3015  OXT TRP   378      88.213 113.721 126.886                       O
                    TER
                    HETATM 3018  CHA HEM     2      88.420  89.817 143.178                       C
                    HETATM 3019  C1A HEM     2      88.328  88.707 144.014                       C
                    HETATM 3020  NA  HEM     2      87.626  88.531 145.174                       N
                    HETATM 3021  C4A HEM     2      87.863  87.326 145.752                       C
                    </pre>
                        </div>
                        <br>
                        <p style="text-align: justify">
                            <span style="color: red">mol2:</span> A Tripos mol2 file is a complete, portable
                            representation of a SYBYL molecule. It is an ASCII file which contains all the information
                            needed to reconstruct a SYBYL molecule. For more information about mol2 file, please see&nbsp;<a
                                    href="http://chemyang.ccnu.edu.cn/ccb/server/AiHO/index.php/home/download/mol2">mol2.pdf</a>&nbsp;for
                            details.&nbsp;
                        </p>
                        <br>
                        <b>Here is a mol2 file for benzene:</b><br>

                        Example:
                        <pre>
                    1    #     Name: benzene
                    2    #     Creating user name: tom
                    3    #     Creation time: Wed Dec 28 00:18:30 1988
                    4
                    5    #     Modifying user name: tom
                    6    #     Modification time: Wed Dec 28 00:18:30 1988
                    7
                    8    @<TRIPOS>MOLECULE
                    9    benzene
                    10   12 12 1 0 0
                    11   SMALL
                    12   NO_CHARGES
                    13
                    14
                    15   @<TRIPOS>ATOM
                    16   1     C1     1.207  2.091  0.000  C.ar  1     BENZENE0.000
                    17   2     C2     2.414  1.394  0.000  C.ar  1     BENZENE0.000
                    18   3     C3     2.414  0.000  0.000  C.ar  1     BENZENE0.000
                    19   4     C4     1.207  -0.697 0.000  C.ar  1     BENZENE0.000
                    20   5     C5     0.000  0.000  0.000  C.ar  1     BENZENE0.000
                    21   6     C6     0.000  1.394  0.000  C.ar  1     BENZENE0.000
                    22   7     H1     1.207  3.175  0.000  H     1     BENZENE0.000
                    23   8     H2     3.353  1.936  0.000  H     1     BENZENE0.000
                    24   9     H3     3.353  -0.542 0.000  H     1     BENZENE0.000
                    25   10    H4     1.207  -1.781 0.000  H     1     BENZENE0.000
                    26   11    H5     -0.939 -0.542 0.000  H     1     BENZENE0.000
                    27   12    H6     -0.939 1.936  0.000  H     1     BENZENE0.000
                    28   @<TRIPOS>BOND
                    29   1     1      2      ar
                    30   2     1      6      ar
                    31   3     2      3      ar
                    32   4     3      4      ar
                    33   5     4      5      ar
                    34   6     5      6      ar
                    35   7     1      7      1
                    36   8     2      8      1
                    37   9     3      9      1
                    38   10    4      10     1
                    39   11    5      11     1
                    40   12    6      12     1
                    41   @<TRIPOS>SUBSTRUCTURE
                    42   1     BENZENE1      PERM  0    ****   ****  0     ROOT
                     </pre>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
