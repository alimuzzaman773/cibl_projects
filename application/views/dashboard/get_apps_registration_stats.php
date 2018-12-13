<div class="col-md-6 col-sm-6 col-xs-6">
    <table class="table table-bordered table-condensed table-striped table-hover">
        <thead>
            <tr class="bg-primary">
                <th colspan="2">Apps Users Registration Stats</th>
            </tr>
        </thead>
        <tr>
            <td style="width: 260px"><b>Total SKB ID:</b></td>
            <td><?=$totalId?></td>
        </tr>
        <?php foreach($registrationType as $k=> $r): ?>
        <tr>
            <td><b><?= ucfirst(str_replace("_", " ", $k))?></b></td>
            <td><?=number_format(($r/$totalId)*100,2)?>%</td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td><b>Password Reset Request:</b></td>
            <td><?=$passwordResetCount?></td>
        </tr>
        <tr>
            <td><b>Password Reset vs Total:</b></td>
            <td><?=($totalId > 0) ? ($passwordResetCount/$totalId)*100 : 0?>%</td>
        </tr>
    </table>    
</div>

<div class="col-md-6 col-sm-6 col-xs-6">
    <table class="table table-bordered table-condensed table-striped table-hover">
        <thead>
            <tr class="bg-primary">
                <th colspan="2">Apps Users Activations</th>
            </tr>
        </thead>
        <tr>
            <td style="width: 350px"><b>SKB ID Waiting Activation:</b></td>
            <td><?=$activationRequest?></td>
        </tr>
        <tr>
            <td><b>Activation Pending More Than 24 Hours:</b></td>
            <td><?=$activationPending24?></td>
        </tr>
        <tr>
            <td><b>Password Reset Request:</b></td>
            <td><?=$passwordResetCount?></td>
        </tr>
        <tr>
            <td><b>Password Reset Pending More Than 24 Hours:</b></td>
            <td><?=$passwordResetPending24?></td>
        </tr>
    </table>    
</div>