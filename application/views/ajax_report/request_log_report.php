<?php
$this->load->view("ajax_report/render_report_parameters.php", $params);
?>
<div class="table-responsive">
    <table class="table table-bordered table-condensed table-hover table-striped">
        <thead>
            <tr class="bg-primary">
                <th>SL</th>
                <th>Date</th>
                <th>Source</th>                
                <th>URL</th>
                <th>Method Name</th>
                <th>Request XML</th>
                <th>Response XML</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            foreach ($result as $r):
                ?>
                <tr>
                    <td><?= ($sl++) ?></td>
                    <td><?= $r->created ?></td>          
                    <td><?= $r->source ?></td>
                    <td><?= $r->url ?></td>
                    <td><?= $r->method_name ?></td>
                    <td><?= $r->requestXml ?></td>
                    <td><?= $r->responseXml ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($result) <= 0): ?>
                <tr>
                    <td colspan="7">No data found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>