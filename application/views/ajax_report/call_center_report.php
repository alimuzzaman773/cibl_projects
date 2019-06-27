<?php
$this->load->view("ajax_report/render_report_parameters.php", $params);
?>
<div class="table-responsive">
    <table class="table table-bordered table-condensed table-hover table-striped">
        <thead>
            <tr class="bg-primary">
                <th>SL</th>
                <th>Apps ID</th>                
                <th>Apps User ID</th>
                <th>Customer ID</th>
                <th>Customer Name</th>
                <th>User Email</th>
                <th>User Mobile</th>
                <th>Enrollment Date</th>
                <th>Requested Through</th>
                <th>Approved By</th>
                <th>Branch</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            foreach ($result as $r):
                ?>
                <tr>
                    <td><?= ($sl++) ?></td>
                    <td><?= $r->skyId ?></td>
                    <td><?= $r->eblSkyId ?></td>
                    <td><?= $r->cfId ?></td>
                    <td><?= $r->userName ?></td>
                    <td><?= $r->userEmail ?></td>
                    <td><?= $r->userMobNo1 ?></td>
                    <td><?= $r->registrationDate ?></td>
                    <td><?= $r->entityType ?></td>
                    <td><?= $r->adminFullname ?></td>
                    <td><?= $r->branchName ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($result) <= 0): ?>
                <tr>
                    <td colspan="11">No data found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>