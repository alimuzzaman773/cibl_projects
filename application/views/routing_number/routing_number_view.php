
<div>
		<?php
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes); 
        ?>
        <?php foreach($moduleCodes as $index => $value){
            if($moduleCodes[$index] == routing_number_module){
                $moduleWiseActionCodes = $actionCodes[$index];
                if(strpos($moduleWiseActionCodes, "importCsv") > -1){ ?>
                	<a href="<?php echo base_url(); ?>csv_import" class="btn btn-success"><i class="icon-plus icon-white"></i><span>Import CSV File</span></a>
          <?php } ?>
      <?php } ?>
  <?php } ?>


	<div style='height:20px;'></div>  
    <div>
		<?php echo $output; ?>
    </div>
</div>
