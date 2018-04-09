

<div class="container" style="margin-top:50px">    

            <h3>Import CSV File</h3>

            <hr>
                <form method="post" action="<?php echo base_url() ?>csv_import/importcsv" enctype="multipart/form-data">
                    <input type="file" name="userfile" ><br><br>
                    <input type="submit" name="submit" value="UPLOAD" class="btn btn-primary">
                </form>

            <a href="<?php echo base_url(); ?>routing_number" class="btn btn-success"><i class="icon-plus icon-white"></i><span>Back to List</span></a>
            <hr>

 
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('success') == TRUE): ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>

</div>

