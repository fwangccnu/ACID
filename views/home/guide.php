<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <p class="text-strong panel-title">User guide</p>
        <div class="panel panel-info">
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-3 scrollspy">
                        <ul class="nav nav-pills nav-stacked affix" data-spy="affix">
                            <li class=""><a href="#brief">Brief Introduction</a></li>
                            <li class=""><a href="#browser">Browser Recommendation</a></li>
                            <li class=""><a href="#support">Free Support</a></li>
                            <li class=""><a href="#index">Index</a></li>
                            <li class=""><a href="#generation">1.Fragment Generation Protocol</a></li>
                            <li class=""><a href="#mapping">2. Fragment Mapping method</a></li>
                            <li class=""><a href="#browse">3. Browse PADFrag</a></li>
                        </ul>
                    </div>
                    <div class="col-xs-9">
                        <h2 id="brief">Brief Introduction</h2>
                        <p>
                            PADFrag (Pesticide&amp;Drug Fragments) database is a special molecular database for
                            biological-functional molecular fragments. It contains 1652 FDA approved drugs and 1259
                            approved agricultural chemicals and 5919 molecular fragments generated from them. A special
                            link-point marked fragment database is created along with common database, which will be
                            useful for fragment-linking project. The 3D structure, physicochemical properties, target
                            information for these molecules are recorded in PADFrag. All fragments are mapped into
                            pdbbind database based on molecular similarity and interaction energy with binding sites.
                        </p>
                        <h2 id="browser">Browser Recommendation</h2>
                        <!--<p class="t3">This site is designed for use with <a href="http://www.mozilla.org/en-US/firefox/new/">Firefox</a>,<a href="https://www.google.com/chrome/browser/desktop/"> Google Chrome</a>, <a href="http://support.apple.com/downloads/#safari">Apple Safari</a> and <a href="http://windows.microsoft.com/en-US/internet-explorer/download-ie">IE10 or later </a>as it makes use of special features available only in these browsers. Some tools may be unavailable or not run as expected with other browsers.</p>
                        -->
                        <p class="t3">We tested our database using different browsers on different systems (<a
                                    href="http://windows.microsoft.com/en-US/internet-explorer/download-ie">IE10 or
                                later </a> on Windows, <a href="http://www.mozilla.org/en-US/firefox/new/">Firefox</a>
                            on Windows, Mac OS and Linux,<a href="https://www.google.com/chrome/browser/desktop/">
                                Google Chrome</a> on Windows, Mac OS, and Linux, <a
                                    href="http://support.apple.com/downloads/#safari">Apple Safari</a> on Windows and
                            Mac os) to assure the normal display. The testing results showed good compatibility.</p>

                        <h2 id="support">Free Support</h2>
                        <p>
                            If you have any questions, bug reports, or suggestions on how we could make PADFrag more
                            intuitive, powerful, or useful. please <a href="mailto:computchembio_group@aliyun.com"> send
                                us an email.</a>
                        </p>
                        <h2 id="index">Index</h2>
                        <p>
                        </p>
                        <ul>
                            <li>1.Fragment Generation Protocol</li>
                            <li>2.Fragment Mapping Method</li>
                            <li>3.Browse PADFrag</li>
                        </ul>
                        <p></p>

                        <h2 id="generation">1.Fragment Generation Protocol</h2>
                        <p>
                            All Fragments in PADFrag are generated from FDA approved drugs and commercial pesticides, to
                            improve the diversity of our fragment database, a cut and recombine protocol is used, All
                            fragments are filtered by physicochemical rules and only fragments with proper
                            physicochemical properties are retained, as link-points are always important for
                            Fragment-linking, the original link-points for all these fragments are then marked.
                        </p>
                        <div align="center">
                            <img class="img-responsive" alt="Responsive image"
                                 src="http://chemyang.ccnu.edu.cn/ccb/database/PADFrag/uploads/help/fraggen.png"
                                 data-action="zoom">
                            <h4>Fig 1. Fragment Generation Protocol Used in PADFrag</h4>
                        </div>
                        <h2 id="mapping">2. Fragment Mapping method</h2>
                        <p>
                            Fragments in PADFrag are mapped with approved drugs and commercial pesticides, you can
                            easily browse fragments contained in a specific drug or pesticide and drugs contain your
                            interested fragment. Meanwhile, to shed lights to the biological function of a certain
                            fragment, all fragments in PADFrag are mapped into 13283 protein-ligand complexes in
                            pdbbind-cn database based on molecular similarity and interaction energy with protein, more
                            than 100 pdb entries are recorded for each fragment.
                        </p>
                        <div align="center">
                            <img class="img-responsive" alt="Responsive image"
                                 src="http://chemyang.ccnu.edu.cn/ccb/database/PADFrag/uploads/help/fragmap.png"
                                 data-action="zoom">
                            <h4>Fig 2. Fragment Mapping Method</h4>
                        </div>
                        <p>
                            Take benzene as an example, benzene is the most common fragments in drugs and pesticides
                            with a frequency higher than 0.28, Benzene can be mapped to 511 drugs and 311 pesticides.
                            Mapping of benzene to pdbbind result in 5256 entries in total. Lysozyme (4I7J) is detected
                            as the most ideal binding target for benzene, for benzene is nearly full packaged by
                            hydrophobic residues in the binding site. 5 residues, Ala99/Leu84/Leu118/Tyr88/Val87 are
                            demonstrated as key residues for the binding of benzene. In fact, the bioactivity of benzene
                            towards 4I7J are record in pdbbind as 480 uM.
                        </p>
                        <div align="center">
                            <img class="img-responsive" alt="Responsive image"
                                 src="http://chemyang.ccnu.edu.cn/ccb/database/PADFrag/uploads/help/mapexam.png"
                                 data-action="zoom">
                            <h4>Fig 3. Fragment Mapping Example</h4>
                        </div>

                        <h2 id="browse">3. Browse PADFrag</h2>
                        <p>
                            PADFrag can be displayed in two modes. In drug2fragment mode, information about the drugs or
                            pesticides is displayed, together with all fragments contained in them. In fragment2drug
                            mode, information about the fragment is displayed, together with a list for all drugs and
                            pesticides containing this fragment, Each Fragments are mapped into pdb database based on
                            similarity search and interaction energy between fragments and targets.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
