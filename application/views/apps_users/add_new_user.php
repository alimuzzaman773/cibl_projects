<title>Apps User</title>
<div class="breadcrum">Add Apps User</div>



<div class="container" style="margin-top:50px">


	<div class="alert alert-success"><?php echo $message ?></div>

		<form method="post" style="" id="cfidForm" name="AddUser" action="<?php echo base_url(); ?>apps_users/pullFromCbs">

			<input hidden class="textbox" type="text" name="selectedActionName" id="selectedActionName" value="<?= $selectedActionName ?>">


			<h4>Apps ID</h4>
			<input type="text" name="esbId" style="text-transform:uppercase" required>
			<br><br>

			<h4>CIF ID</h4>
			<input type="number" name="cfId" required>
			<br><br>

			<h4>Client ID</h4>
			<input type="text" name="clientId">
			<br><br>

			<button type="submit" class="btn btn-success">Next</button>
			<a href="<?php echo base_url(); ?>client_registration" class="btn btn-success"><i class="icon-plus icon-white"></i><span>Cancel</span></a>	

		</form>

</div>
