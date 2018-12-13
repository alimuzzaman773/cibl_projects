<div class="col-sm-12">
    <table class="no-border" style="width: 100%;margin-top: 0px">
        <tr>
            <td style="text-align: center">
                <h4 style="margin-top: 0px; margin-bottom: 5px;font-size: 16px;font-weight: bold">EBL SKYBANK</h4>
                <h4 style="font-size: 13px"><?= $reportHeader ?></h4>
            </td>
        </tr>
        <tr>
            <td style="font-size: 0.9em;font-weight: bold">
            <?php
            if(isset($params["reportParams"])):
                foreach ($params["reportParams"] as $paramName => $paramValue):
                    ?>            
                    <?= ucfirst($paramName) ?> : <?= $paramValue ?><br />
                    <?php
                endforeach;            
            endif;
            ?>        
            </td>
        </tr>
    </table>
</div>