<title>Apps User</title>
<div class="breadcrum">Edit Apps User</div>



<div class="container" style="margin-top:50px">

	<div class="alert alert-success"><?php echo $message ?></div>

		<form method="post" style="" id="cfidForm" name="editUser" action="<?php echo base_url(); ?>apps_users/pullFromCbsEdit">

			<input hidden class="textbox" type="text" name="selectedActionName" id="selectedActionName" value="<?= $selectedActionName ?>">
			<input hidden class="textbox" type="text" name="skyId" id="skyId" value="<?= $skyId ?>">


			<h4>ESB ID</h4>
			<input type="text" name="eblSkyId" value="<?= $eblSkyId ?>" readonly>
			<br><br>

			<h4>CFID</h4>
			<input type="number" name="cfId" value="<?= $cfId ?>" readonly>
			<br><br>

			<h4>Client ID</h4>
			<input name="clientId" value="<?= $clientId ?>">
			<br><br>

			<button type="submit" class="btn btn-success">Next</button>
			<a href="<?php echo base_url(); ?>client_registration" class="btn btn-success"><i class="icon-plus icon-white"></i><span>Cancel</span></a>	

		</form>

</div>
