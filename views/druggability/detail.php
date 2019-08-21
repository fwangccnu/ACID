<div class="row">

    <div class="col-lg-12 col-sm-12 col-xs-12">
        <p class="text-strong panel-title">
            Druggability job detail / JOB ID : <?php echo $job['job_id'] ?> / Task name
            : <?php echo $job['task_name'] ?>
        </p>
    </div>

    <div class="col-lg-12 col-sm-12 col-xs-12">
        <table class="table table-bordered table-striped">
            <thead class="bg_ecfff4 _17b55d">
            <tr style="font-size: 16px">
                <th class="text-left" width="10%">Protein<span class=""></span></th>
                <th class="text-left" width="10%">Site<span class=""></span></th>
                <th class="text-left" width="10%">Druggable score<span class=""></span></th>
                <th class="text-left" width="10%">Solv. scces. surf. area<span class=""></span></th>
                <th class="text-left" width="10%">Volume<span class=""></span></th>
                <th class="text-left" width="10%">Apolar proportion<span class=""></span></th>
                <th class="text-left" width="10%">Hydrophobicity score<span class=""></span></th>
                <th class="text-left" width="10%">Polarity score<span class=""></span></th>
                <th class="text-left" width="10%">Charge score<span class=""></span></th>
                <th class="text-left" width="5%">Flexibility<span class=""></span></th>
                <th class="text-left" width="5%">Download<span class=""></span></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <?php echo $druggability['pdb']; ?>
                </td>
                <td>
                    <?php echo $druggability['site']; ?>
                </td>
                <td>
                    <?php echo $druggability['druggable_score']; ?>
                </td>
                <td>
                    <?php echo $druggability['total_SASA']; ?>
                </td>
                <td>
                    <?php echo $druggability['volume']; ?>
                </td>
                <td>
                    <?php echo $druggability['apolar_AS_prop']; ?>
                </td>
                <td>
                    <?php echo $druggability['hydropho_score']; ?>
                </td>
                <td>
                    <?php echo $druggability['polarity score']; ?>
                </td>
                <td>
                    <?php echo $druggability['charge_score']; ?>
                </td>
                <td>
                    <?php echo $druggability['flexibility']; ?>
                </td>
                <td>
                    <?php echo $druggability['file_path']; ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>