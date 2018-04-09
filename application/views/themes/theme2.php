<html lang="en">
	<head>
     
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/themes/default/js/tinymce/tinymce.min.js"></script>            
      
    

    
	
    
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/bootstrap/js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery-ui-1.9.2/js/jquery-ui-1.9.2.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap-fileupload.js"></script>
        
        <link href="<?php echo base_url(); ?>assets/jquery-ui-1.9.2/css/smoothness/jquery-ui-1.9.2.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap-responsive.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap-fileupload.css" rel="stylesheet" />        
        <link href="<?php echo base_url(); ?>assets/css/data_table_jui.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>assets/css/datatable/dt_bootstrap.css" rel="stylesheet" />
		
        
		
		
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script> 
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/knockout-2.2.1.js"></script> 
        <script type="text/javascript"  src="<?php echo base_url(); ?>assets/js/knockout.mapping-latest.js"></script>
        <script type="text/javascript"  src="<?php echo base_url(); ?>assets/js/date.format.js"></script>
        
        
        <script type="text/javascript"  src="<?php echo base_url(); ?>assets/js/ko-dt-bootstrap/cog.js"></script>
        <script type="text/javascript"  src="<?php echo base_url(); ?>assets/js/ko-dt-bootstrap/cog.utils.js"></script>
        <script type="text/javascript"  src="<?php echo base_url(); ?>assets/js/ko-dt-bootstrap/knockout_datatable.js"></script>
        <script type="text/javascript"  src="<?php echo base_url(); ?>assets/js/ko-dt-bootstrap/jquery.dataTables.js"></script>
        <script type="text/javascript"  src="<?php echo base_url(); ?>assets/js/ko-dt-bootstrap/dataTables_bootstrap.js"></script>
		

        
    
        <link href="<?php echo base_url(); ?>assets/themes/default/hero_files/mystyle.css" rel="stylesheet">


<!--Reports scripts-->
<script src="<?php echo base_url(); ?>assets/data_table/data_table.min.js"></script>
<script src="<?php echo base_url(); ?>assets/data_table/dataTables.buttons.js"></script>
<script src="<?php echo base_url(); ?>assets/data_table/jszip.js"></script>
<script src="<?php echo base_url(); ?>assets/data_table/pdfmake.js"></script>
<script src="<?php echo base_url(); ?>assets/data_table/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>assets/data_table/buttons.html5.js"></script>
<script src="<?php echo base_url(); ?>assets/data_table/buttons.flash.js"></script>
<script src="<?php echo base_url(); ?>assets/data_table/buttons.print.js"></script>
<script src="<?php echo base_url(); ?>assets/js/reports.js"></script>
<link href="<?php echo base_url(); ?>assets/data_table/data_table.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/reports.css">
<script src="<?php echo base_url(); ?>assets/datepicker/jquery-ui.min.js"></script>
<!--End Reports scripts-->


<script>var baseURL = "<?php echo base_url();?>";</script>



    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/themes/default/images/tab_icon.png" type="image/x-icon"/>
    <meta property="og:image" content="<?php echo base_url(); ?>assets/themes/default/images/property_icon.png"/>
    <link rel="image_src" href="<?php echo base_url(); ?>assets/themes/default/images/property_icon.png" />
    <style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 0px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body{
		margin: 0 15px 0 15px;
	}

	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container{
		margin: 10px;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	}
	</style>

</head>

<body>

    <nav class="navbar navbar-inverse" role="navigation" style="background-color:#268C3A;border-color:#FDC50D">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">

                    <a class="navbar-brand" href="#"><img src="<?= base_url() ?>assets/image/scb_logo_full_size.png" alt="Smiley face" height="69" width="69"></a>

                    <ul class="nav navbar-nav navbar-left">
                         <li ><a href='<?php echo base_url('/admin_login/dashboard') ?>'><b>Dashboard</b></a></li>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <div class="loginstatus">
                            <p>Welcome <?php echo $this->session->userdata('username'); ?><p>
                            <p>Logged in as <?php echo $this->session->userdata('group') ?><p>
                        </div>
                    </ul>

                </div>

                <ul class="nav navbar-nav navbar-right"> 
                    <a class="navbar-brand" href="#"><img src="<?= base_url() ?>assets/image/cibl.png" alt="Smiley face" height="25" width="175"></a>
                </ul>




                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">


                    <ul class="nav navbar-nav navbar-right">
                        <li ><a href='<?php echo base_url('/admin_login/logout') ?>'><b>Log Out</b></a></li></ul>

<!--Report navigation-->

 <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown dropdown-large" >

                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>Reports</b> <b class="caret"></b></a>

                            <ul class="dropdown-menu dropdown-menu-large row">

                                
                                 <li class="col-sm-6 menu-left">
                                    <ul>


                    <li class="dropdown-header">Apps User Reports</li>
                    <?php if (strpos($this->session->userdata('reportTypeModules'), user_status) > -1) { ?>
                        <li><a href='<?php echo site_url('reports/user_status') ?>'><i
                                    class="fa fa-link"></i> Apps Users' Status </a></li>
                    <?php } ?>

                    <?php if (strpos($this->session->userdata('reportTypeModules'), customer_info) > -1) { ?>
                        <li><a href='<?php echo site_url('reports/customer_info') ?>'><i
                                    class="fa fa-link"></i> Customer Information</a></li>
                    <?php } ?>

                    <?php if (strpos($this->session->userdata('reportTypeModules'), user_login_info) > -1) { ?>
                        <li><a href='<?php echo site_url('reports/user_login_info') ?>'><i
                                    class="fa fa-link"></i> User Last Login Information</a></li>
                    <?php } ?>
                    <li class="divider"></li>

                    <li class="dropdown-header">Transaction Reports</li>
                    <?php if (strpos($this->session->userdata('reportTypeModules'), fund_transfer) > -1) { ?>
                        <li><a href='<?php echo site_url('reports/fund_transfer') ?>'><i
                                    class="fa fa-link"></i> Fund Transfer</a>
                        </li>
                    <?php } ?>

                        <?php if (strpos($this->session->userdata('reportTypeModules'), other_fund_transfer) > -1) { ?>
                        <li><a href='<?php echo site_url('reports/other_fund_transfer') ?>'><i
                                    class="fa fa-link"></i> Other Fund Transfer</a>
                        </li>
                    <?php } ?>

                </ul>
            </li>
            <li class="col-sm-6 menu-left">
                <ul>
                    <li class="dropdown-header">Admin Reports</li>

                    <?php if (strpos($this->session->userdata('reportTypeModules'), user_id_modification) > -1) { ?>
                        <li><a href='<?php echo site_url('reports/id_modification') ?>'><i
                                    class="fa fa-link"></i> Last User ID Modification</a></li>
                    <?php } ?>

                    <li class="divider"></li>

                    <li class="dropdown-header">Billing Report</li>
                    <?php if (strpos($this->session->userdata('reportTypeModules'), billing_info) > -1) { ?>
                        <li><a href='<?php echo site_url('reports/bill_pay') ?>'><i
                                    class="fa fa-link"></i> Billing Information</a>
                        </li>
                    <?php } ?>

                    <li class="divider"></li>
                    <li class="dropdown-header">Request Report</li>
                    <?php if (strpos($this->session->userdata('reportTypeModules'), priority_request) > -1) { ?>
                        <li><a href='<?php echo site_url('reports/priority_request') ?>'><i
                                    class="fa fa-link"></i> Priority Request</a>
                        </li>
                    <?php } ?>
                    <?php if (strpos($this->session->userdata('reportTypeModules'), product_request) > -1) { ?>
                        <li><a href='<?php echo site_url('reports/product_request') ?>'><i
                                    class="fa fa-link"></i> Product Request</a>
                        </li>
                    <?php } ?>
                    <?php if (strpos($this->session->userdata('reportTypeModules'), banking_request) > -1) { ?>
                        <li><a href='<?php echo site_url('reports/banking_request') ?>'><i
                                    class="fa fa-link"></i> Banking Request</a>
                        </li>
                    <?php } ?>


                </ul>
            </li>
        </ul>
    </li>
</ul>


<!--End of report navigation-->





                    <!--Menue bar+++++++++++++++++++++++++++++++++++++++++++-->
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown dropdown-large" >

                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>Admin Setup</b> <b class="caret"></b></a>

                            <ul class="dropdown-menu dropdown-menu-large row">

                                
                                 <li class="col-sm-6">
                                    <ul>

                                        <li class="dropdown-header">Users/Pin</li>

                                        <?php if(strpos($this->session->userdata('moduleCodes'), admin_user_module) > -1 ){ ?>   
                                            <li><a href='<?php echo site_url('admin_users_maker') ?>'>Admin users</a></li>
                                        <?php } ?>

                                        <?php if(strpos($this->session->userdata('moduleCodes'), admin_user_group_module) > -1 ){ ?>   
                                            <li><a href='<?php echo site_url('admin_user_group_maker') ?>'>Admin user group</a></li>
                                        <?php } ?>

                                        <?php if(strpos($this->session->userdata('moduleCodes'), apps_user_module) > -1 ){ ?>   
                                            <li><a href='<?php echo site_url('client_registration/index') ?>'>Apps users</a></li>
                                        <?php } ?>

                                        <?php if(strpos($this->session->userdata('moduleCodes'), pin_module) > -1 ){ ?>   
                                            <li><a href='<?php echo site_url('pin_generation/viewPinByAction') ?>'>Pin</a></li>
                                        <?php } ?>


                                        <li class="divider"></li>

                                        <li class="dropdown-header">Checker</li>

                                        <?php if(strpos($this->session->userdata('authorizationModules'), admin_user_group_authorization) > -1 ){ ?>   
                                            <li><a href='<?php echo site_url('admin_user_group_checker') ?>'>Admin User Group Authorization</a></li>
                                        <?php } ?>

                                        <?php if(strpos($this->session->userdata('authorizationModules'), admin_user_authorization) > -1 ){ ?>   
                                            <li><a href='<?php echo site_url('admin_users_checker') ?>'>Admin Users Authorization</a></li>
                                        <?php } ?>

                                        <?php if(strpos($this->session->userdata('authorizationModules'), limit_package_authorization) > -1 ){ ?>   
                                            <li><a href='<?php echo site_url('transaction_limit_setup_checker') ?>'>Limit Package Authorization</a></li>
                                        <?php } ?>

                                        <?php if(strpos($this->session->userdata('authorizationModules'), apps_users_authorization) > -1 ){ ?>   
                                            <li><a href='<?php echo site_url('client_registration_checker') ?>'>Apps Users Authorization</a></li>
                                        <?php } ?>


                                        <?php if(strpos($this->session->userdata('authorizationModules'), device_authorization) > -1 ){ ?>   
                                            <li><a href='<?php echo site_url('device_info_checker') ?>'>Device Authorization</a></li>
                                        <?php } ?>


                                        <?php if(strpos($this->session->userdata('authorizationModules'), biller_setup_authorization) > -1 ){ ?>   
                                            <li><a href='<?php echo site_url('biller_setup_checker') ?>'>Biller Setup Authorization</a></li>
                                        <?php } ?>


                                        <?php if(strpos($this->session->userdata('authorizationModules'), pin_reset_authorization) > -1 ){ ?>   
                                            <li><a href='<?php echo site_url('pin_generation_checker') ?>'>Pin Reset Authorization</a></li>
                                        <?php } ?>

                                        <?php if(strpos($this->session->userdata('authorizationModules'), pin_create_authorization) > -1 ){ ?>   
                                            <li><a href='<?php echo site_url('pin_create_checker') ?>'>Pin Create Authorization</a></li>
                                        <?php } ?>

						 <?php if (strpos($this->session->userdata('authorizationModules'), password_policy_authorization) > -1) { ?>
                                		<li><a href='<?php echo site_url('password_policy_checker') ?>'>Password
                                        	Authorization</a></li>
                            		<?php } ?>


                                       <?php if(strpos($this->session->userdata('authorizationModules'), apps_user_delete_authorization) > -1 ){ ?>   
                                            <li><a href='<?php echo site_url('apps_user_delete_checker') ?>'>Apps User Delete Authorization</a></li>
                                        <?php } ?>


                                    </ul>
                                </li>


                                <li class="col-sm-6">
                                    <ul>

                                        <li class="dropdown-header">Request Process</li>

                                        <?php if(strpos($this->session->userdata('serviceRequestModules'), priority_sr) > -1 ){ ?>   
                                            <li><a href='<?php echo site_url('priority_request_process/getRequests') ?>'>Priority</a></li>
                                        <?php } ?>

                                        <?php if(strpos($this->session->userdata('serviceRequestModules'), product_sr) > -1 ){ ?>   
                                            <li><a href='<?php echo site_url('product_request_process/index') ?>'>Product</a></li>
                                        <?php } ?>

                                        <?php if(strpos($this->session->userdata('serviceRequestModules'), banking_sr) > -1 ){ ?>   
                                            <li><a href='<?php echo site_url('banking_service_request/getRequests') ?>'>Banking</a></li>
                                        <?php } ?>

                                        <li class="divider"></li>

                                        <li class="dropdown-header">Configuration</li>
                                        
					           		<?php if (strpos($this->session->userdata('moduleCodes'), password_policy_module) > -1) { ?>
                                				<li><a href='<?php echo site_url('validation_setup') ?>'>Password Policy</a></li>
                            				<?php } ?>


                                        <?php if(strpos($this->session->userdata('moduleCodes'), biller_setup_module) > -1 ){ ?>   
                                            <li><a href='<?php echo site_url('biller_setup_maker') ?>'>Biller Setup</a></li>
                                        <?php } ?>

                                        <?php if(strpos($this->session->userdata('moduleCodes'), routing_number_module) > -1 ){ ?>   
                                            <li><a href='<?php echo site_url('routing_number') ?>'>Routing Number Setup</a></li> 
                                        <?php } ?>

                                        <?php if(strpos($this->session->userdata('moduleCodes'), bill_type_setup_module) > -1 ){ ?>   
                                            <li><a href='<?php echo site_url('bill_type_setup') ?>'>Bill Type Setup</a></li>
                                        <?php } ?>

                                        <?php if(strpos($this->session->userdata('moduleCodes'), limit_package_module) > -1 ){ ?>   
                                            <li><a href='<?php echo site_url('transaction_limit_setup_maker') ?>'>Transaction Package Limit</a></li>
                                        <?php } ?> 


                                    </ul>
                                </li>

                            </ul>

                        </li>  
                    </ul>



                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown dropdown-large" >
                            
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>Content Setup</b> <b class="caret"></b></a>

                            <ul class="dropdown-menu dropdown-menu-large row">
                                <li class="col-sm-4">
                                    <ul>

                                        <?php if(strpos($this->session->userdata('contentSetupModules'), product) > -1 ){ ?>   
                                            <li class="dropdown-header">Products Setup</li>
                                            <li><a href='<?php echo site_url('product_setup/index') ?>'>EBL Products</a></li>
                                            <li><a href='<?php echo site_url('product_setup/index/add') ?>'>Add EBL Products</a></li>
                                            
                                        <?php } ?>

                                        <li class="divider"></li>

                                        <?php if(strpos($this->session->userdata('contentSetupModules'), location) > -1 ){ ?>   
                                            <li class="dropdown-header">Location Setup</li>
                                            <li><a href='<?php echo site_url('ebl_locator_setup/index') ?>'>EBL Locations</a></li>
                                            <li><a href='<?php echo site_url('ebl_locator_setup/index/add') ?>'>Add EBL Locations</a></li>
                                        <?php } ?>


                                        <li class="divider"></li>


                                        <?php if(strpos($this->session->userdata('contentSetupModules'), zip) > -1 ){ ?>   
                                            
                                            <li class="dropdown-header">Zip Partners</li>
                                            <li><a href='<?php echo site_url('zip_partners/index') ?>'>EBL Zip Partners</a></li>
                                            <li><a href='<?php echo site_url('zip_partners/index/add') ?>'>Add Zip Partners</a></li>
                                        <?php } ?>


                                    </ul>
                                </li>

                                <li class="col-sm-4">
                                    <ul>


                                        <?php if(strpos($this->session->userdata('contentSetupModules'), priority) > -1 ){ ?>   
                                            
                                            <li class="dropdown-header">Priority Setup</li>
                                            <li><a href='<?php echo site_url('priority_products/index') ?>'>EBl Priority Setup</a></li>
                                            <li><a href='<?php echo site_url('priority_products/index/add') ?>'>Add Priority Setup</a></li>
                                        <?php } ?>


                                        <li class="divider"></li>


                                        <?php if(strpos($this->session->userdata('contentSetupModules'), benifit) > -1 ){ ?>   
                                            
                                            <li class="dropdown-header">Benefits Setup</li>
                                            <li><a href='<?php echo site_url('discount_partners/index') ?>'>EBL Benefits Setup</a></li>
                                            <li><a href='<?php echo site_url('discount_partners/index/add') ?>'>Add Benefits Setup</a></li>
                                        <?php } ?>


                                        <li class="divider"></li>

                                        <?php if(strpos($this->session->userdata('contentSetupModules'), newsEvents) > -1 ){ ?>   
                                            
                                            <li class="dropdown-header">News And Events Setup</li>
                                            <li><a href='<?php echo site_url('news_events/index') ?>'>News And Events</a></li>
                                            <li><a href='<?php echo site_url('news_events/index/add') ?>'>Add News And Events</a></li>
                                        <?php } ?>


                                    </ul>
                                </li> 
                                
                                 <li class="col-sm-4">
                                    <ul>

                                        <?php if(strpos($this->session->userdata('contentSetupModules'), notification) > -1 ){ ?>   
                                            
                                            <li class="dropdown-header">Notification</li>
                                            <li><a href='<?php echo site_url('push_notification') ?>'>Notify to aps user</a></li>
                                        <?php } ?>

                                        <li class="divider"></li>

                                        <?php if(strpos($this->session->userdata('contentSetupModules'), advertisement) > -1 ){ ?>   
                                            
                                            <li class="dropdown-header">Advertisement</li>
                                            <li><a href='<?php echo site_url('advertisement') ?>'>Advertisement</a></li>
                                        <?php } ?>



                                        <li class="divider"></li>

                                        <?php if(strpos($this->session->userdata('contentSetupModules'), help) > -1 ){ ?>   
                                            
                                            <li class="dropdown-header">Help</li>
                                            <li><a href='<?php echo site_url('help_setup') ?>'>Help Setup</a></li>
						  <li><a href='<?php echo site_url('help_setup/complaintInfo') ?>'>Complaint Info</a></li>
                                        <?php } ?>

                                    </ul>
                                </li>

                            </ul>

                        </li>  
                    </ul>




    </div><!-- /.navbar-collapse -->                                                           
</nav>


<?php echo $output;?>

      <hr/>

      <footer>
      	<div class="row">
	        <div class="span6 b10">
				Copyright &copy; <a target="_blank" href="https://www.ebl.com.bd/">Eastern Bank Ltd.</a>
	        </div>
        </div>
      </footer>

    </div>
</body>

</html>