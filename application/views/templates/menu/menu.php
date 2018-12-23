<div class="container">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">
            <img src="<?= asset_url() . "images/logo.png" ?>" class="img-responsive" style="height : 45px;" />
        </a>
    </div>
    <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
            <li class="dropdown">
                <a href="#" id="dropHome" role="button" data-toggle="dropdown">
                    Home
                    <b class="caret"></b>
                </a>
                <ul id="menuHome" class="dropdown-menu" role="menu" aria-labelledby="dropHome">
                    <li>
                        <a href="<?= base_url(); ?>">
                            <i class="glyphicon glyphicon-home"></i> Home
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>">
                            <i class="glyphicon glyphicon-user"></i> Profile
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url() . 'admin_login/logout' ?>">
                            <i class="glyphicon glyphicon-log-out"></i> Log Out
                        </a>
                    </li>
                </ul>
            </li>
            <?php if (ci_check_permission("canViewContentSetupMenu")): ?>
                <li class="dropdown yamm-fw">
                    <a href="#" id="dropModules" role="button" data-toggle="dropdown">Content Setup<b class="caret"></b></a>
                    <ul id="dropModules" class="dropdown-menu" role="menu" aria-labelledby="dropModules">                    
                        <li class="">
                            <div class="yamm-content">
                                <div class="row">                                    
                                    <div class="clearfix mb">
                                        <?php if (ci_check_permission("canViewProductsSetupMenu")): ?>
                                            <ul class="col-md-2 col-xs-12 list-unstyled">
                                                <li><p><b>Product & Services Setup</b></p></li>
                                                <li><a href="<?= base_url() . 'product_setup/index' ?>">Product & Services</a></li>
                                                <li><a href="<?= base_url() . 'product_setup/index/add' ?>">Add Product & Services</a></li>
                                            </ul>
                                        <?php endif; ?>
                                        <?php if (ci_check_permission("canViewLocationSetupMenu")): ?>
                                            <ul class="col-md-2 col-xs-12 list-unstyled">
                                                <li><p><b>ATM & Branch Locator Setup</b></p></li>
                                                <li><a href="<?= base_url() . 'locator_setup/index' ?>">ATM & Branch Locator</a></li>
                                                <li><a href="<?= base_url() . 'locator_setup/index/add' ?>">Add ATM & Branch Locator</a></li>
                                            </ul>
                                        <?php endif; ?>
                                        <?php if (ci_check_permission("canViewZipPartnersSetupMenu")): ?>
                                            <ul class="col-md-2 col-xs-12 list-unstyled">
                                                <li><p><b>Comfort Pay Partners</b></p></li>
                                                <li><a href="<?= base_url() . 'zip_partners/index' ?>">Comfort Pay Partners</a></li>
                                                <li><a href="<?= base_url() . 'zip_partners/index/add' ?>">Add Comfort Pay Partners</a></li>
                                            </ul>
                                        <?php endif; ?>
                                        <?php if (ci_check_permission("canViewPrioritySetupMenu")): ?>
                                            <ul class="col-md-2 col-xs-12 list-unstyled">
                                                <li><p><b>Priority Setup</b></p></li>
                                                <li><a href="<?= base_url() . 'priority_products/index' ?>">Priority Setup</a></li>
                                                <li><a href="<?= base_url() . 'priority_products/index/add' ?>">Add Priority Setup</a></li>
                                            </ul>
                                        <?php endif; ?>
                                        <?php if (ci_check_permission("canViewBenefitsSetupMenu")): ?>
                                            <ul class="col-md-2 col-xs-12 list-unstyled">
                                                <li><p><b>Discount Partners Setup</b></p></li>
                                                <li><a href="<?= base_url() . 'discount_partners/index' ?>">Discount Partners</a></li>
                                                <li><a href="<?= base_url() . 'discount_partners/index/add' ?>">Add Discount Partners</a></li>
                                            </ul>
                                        <?php endif; ?>
                                        <?php if (ci_check_permission("canViewNewsAndEventsSetupMenu")): ?>
                                            <ul class="col-md-2 col-xs-12 list-unstyled">
                                                <li><p><b>News And Events Setup</b></p></li>
                                                <li><a href="<?= base_url() . 'news_events/index' ?>">News And Events</a></li>
                                                <li><a href="<?= base_url() . 'news_events/index/add' ?>">Add News And Events</a></li>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                <div class="row">                                    
                                    <div class="clearfix mb">
                                        <?php if (ci_check_permission("canViewNotificationSetupMenu")): ?>
                                            <ul class="col-md-2 col-xs-12 list-unstyled">
                                                <li><p><b>Notification</b></p></li>
                                                <li><a href="<?= base_url() . 'notification' ?>">Notify to aps user</a></li>
                                            </ul>
                                        <?php endif; ?>
                                        <?php if (ci_check_permission("canViewAdvertisementSetupMenu")): ?>
                                            <ul class="col-md-2 col-xs-12 list-unstyled">
                                                <li><p><b>Advertisement</b></p></li>
                                                <li><a href="<?= base_url() . 'advertisement' ?>">Advertisement</a></li>
                                            </ul>
                                        <?php endif; ?>
                                        <?php if (ci_check_permission("canViewHelpSetupMenu")): ?>
                                            <ul class="col-md-2 col-xs-12 list-unstyled">
                                                <li><p><b>Terms & Conditions</b></p></li>
                                                <li><a href="<?= base_url() . 'help_setup' ?>">App Contents</a></li>
                                                <li><a href="<?= base_url() . 'help_setup/complaintInfo' ?>">Complaint Info</a></li>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </li>    
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (ci_check_permission("canViewAdminSetupMenu")): ?>
                <li class="dropdown yamm-fw">
                    <a href="#" id="dropModules" role="button" data-toggle="dropdown">Admin Setup<b class="caret"></b></a>
                    <ul id="dropModules" class="dropdown-menu" role="menu" aria-labelledby="dropModules">                    
                        <li class="">
                            <div class="yamm-content">
                                <div class="row">                                    
                                    <div class="clearfix mb">
                                        <ul class="col-md-3 col-xs-12 list-unstyled">
                                            <li><p><b>Maker</b></p></li>
                                            <?php if (ci_check_permission("canViewAdminUserCreateMenu")): ?>
                                                <li><a href="<?= base_url() . 'admin_users_maker' ?>">Admin Users</a></li>
                                            <?php endif; ?>

                                            <?php if (ci_check_permission("canViewAdminUserGroupCreateMenu")): ?>
                                                <li><a href="<?= base_url() . 'admin_user_group_maker' ?>">Admin User Group</a></li>
                                            <?php endif; ?>

                                            <?php if (ci_check_permission("canViewAppsUserCreateMenu")): ?>
                                                <li><a href="<?= base_url() . 'client_registration/index' ?>">Apps users</a></li>
                                            <?php endif; ?>

                                            <?php if (ci_check_permission("canViewPinCreateMenu")): ?>
                                                <li><a class="hidden" href="<?= base_url() . 'pin_generation/viewPinByAction' ?>">Pin</a></li>
                                            <?php endif; ?>
                                                <!--
                                            <?php if (ci_check_permission("canViewBillerSetupMenu")): ?>
                                                <li><a href="<?= base_url() . 'biller_setup_maker' ?>">Biller Setup</a></li>
                                            <?php endif; ?>
                                                -->
                                            <?php if (ci_check_permission("canViewLimitPackgaeMenu")): ?>
                                                <li><a href="<?= base_url() . 'transaction_limit_setup_maker' ?>">Transaction Package Limit</a></li>
                                            <?php endif; ?>
                                            <?php if (ci_check_permission("canViewPasswordPolicyMenu")): ?>
                                                <li><a href="<?= base_url() . 'validation_setup' ?>">Password Policy</a></li>
                                            <?php endif; ?>
                                            <?php if (ci_check_permission("canViewPermissionMenu")): ?>
                                                <li class="hide"><a href="<?= base_url() ?>permission">Permission</a></li>
                                            <?php endif; ?>
                                        </ul>
                                        <ul class="col-md-3 col-xs-12 list-unstyled">
                                            <li><p><b>Checker</b></p></li>
                                            <?php if (ci_check_permission("canViewAdminUserGroupAuthorizationMenu")): ?>
                                                <li><a href="<?= base_url() . 'admin_user_group_checker' ?>">Admin User Group Authorization</a></li>
                                            <?php endif; ?>
                                            <?php if (ci_check_permission("canViewAdminUserAuthorizationMenu")): ?>
                                                <li><a href="<?= base_url() . 'admin_users_checker' ?>">Admin User Authorization</a></li>
                                            <?php endif; ?>
                                            <?php if (ci_check_permission("canViewLimitSetupAuthorizationMenu")): ?>
                                                <li><a href="<?= base_url() . 'transaction_limit_setup_checker' ?>">Transaction Package Limit Authorization</a></li>
                                            <?php endif; ?>
                                            <?php if (ci_check_permission("canViewAppsUserAuthorizationMenu")): ?>
                                                <li><a href="<?= base_url() . 'client_registration_checker' ?>">Apps Users Authorization</a></li>
                                            <?php endif; ?>
                                            <?php if (ci_check_permission("canViewDeviceAuthorizationMenu")): ?>
                                                <li><a class="hidden" href="<?= base_url() . 'device_info_checker' ?>">Device Authorization</a></li>
                                            <?php endif; ?>
                                            <!--
                                            <?php if (ci_check_permission("canViewBillerSetupAuthorizationMenu")): ?>
                                                <li><a href="<?= base_url() . 'biller_setup_checker' ?>">Biller Setup Authorization</a></li>
                                            <?php endif; ?>
                                            -->
                                            <?php if (ci_check_permission("canViewPinResetAuthorizationMenu")): ?>
                                                <li><a class="hidden" href="<?= base_url() . 'pin_generation_checker' ?>">Pin Reset Authorization</a></li>
                                            <?php endif; ?>

                                            <?php if (ci_check_permission("canViewPinCreateAuthorizationMenu")): ?>
                                                <li><a class="hidden" href="<?= base_url() . 'pin_create_checker' ?>">Pin Create Authorization</a></li>
                                            <?php endif; ?>

                                            <?php if (ci_check_permission("canViewPasswordPolicyAuthorizationMenu")): ?>
                                                <li><a href="<?= base_url() . 'password_policy_checker' ?>">Password Policy Authorization</a></li>
                                            <?php endif; ?>

                                            <?php if (ci_check_permission("canViewAppsUserDeleteAuthorizationMenu")): ?>
                                                <li><a class="hidden" href="<?= base_url() . 'apps_user_delete_checker' ?>">Apps User Delete Authorization</a></li>
                                            <?php endif; ?>
                                        </ul>
                                        <ul class="col-md-3 col-xs-12 list-unstyled">
                                            <li><p><b>Request Process</b></p></li>

                                            <?php if (ci_check_permission("canViewProductRequestMenu")): ?>
                                                <li><a href="<?= base_url() . 'priority_request_process/getRequests' ?>">Priority</a></li>
                                            <?php endif; ?>

                                            <?php if (ci_check_permission("canViewProductRequestMenu")): ?>
                                                <li><a href="<?= base_url() . 'product_request_process/index' ?>">Product</a></li>
                                            <?php endif; ?>

                                            <?php if (ci_check_permission("canViewBankingRequestMenu")): ?>
                                                <li><a href="<?= base_url() . 'banking_service_request/getRequests' ?>">Banking</a></li>
                                            <?php endif; ?>
                                        </ul>
                                        <ul class="col-md-3 col-xs-12 list-unstyled">
                                            <li><p><b>Configuration</b></p></li>
                                            <?php if (ci_check_permission("canViewPasswordPolicyMenu")): ?>
                                                <li><a href="<?= base_url() . 'password_policy_setup' ?>">Password Policy Setup</a></li>
                                            <?php endif; ?>
                                            <?php if (ci_check_permission("canViewRoutingNumberMenu")): ?>
                                                <li><a href="<?= base_url() . 'routing_number' ?>">Routing Number Setup</a></li>
                                            <?php endif; ?>
                                            <!--
                                            <?php if (ci_check_permission("canViewBillerSetupMenu")): ?>
                                                <li><a href="<?= base_url() . 'bill_type_setup' ?>">Bill Type Setup</a></li>
                                            <?php endif; ?>
                                            -->
                                            <li><a href="<?= base_url() . 'account_type/index' ?>">Account Type Setup</a></li>
                                            <li><a href="<?= base_url() . 'fund_transfer_setup/index' ?>">Fund Transfer Setup</a></li>
                                            <li><a href="<?= base_url() . 'activity_log_type/index' ?>">Activity Log Type Setup</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>    
                    </ul>
                </li>
            <?php endif; ?>
            <!--
            <li class="dropdown yamm-fw">
                <a href="#" id="dropModules" role="button" data-toggle="dropdown">Reports<b class="caret"></b></a>
                <ul id="dropModules" class="dropdown-menu" role="menu" aria-labelledby="dropModules">                    
                    <li class="">
                        <div class="yamm-content">
                            <div class="row">                                    
                                <div class="clearfix mb">
                                    <ul class="col-md-2 col-xs-12 list-unstyled">
                                        <li><p><b>Apps User Reports</b></p></li>
                                        <li><a href="<?= base_url() . 'reports/user_status' ?>">Apps Users' Status</a></li>
                                        <li><a href="<?= base_url() . 'reports/customer_info' ?>">Customer Information</a></li>
                                        <li><a href="<?= base_url() . 'reports/user_login_info' ?>">User Last Login Information</a></li>
                                        <li><a href="<?= base_url() . 'reports/fund_transfer' ?>">Fund Transfer</a></li>
                                        <li><a href="<?= base_url() . 'reports/other_fund_transfer' ?>">Other Fund Transfer</a></li>
                                        <li><a href="<?= base_url() . 'reports/customer_activity_report' ?>">Apps User Activity Log</a></li>
                                    </ul>
                                    <ul class="col-md-2 col-xs-12 list-unstyled">
                                        <li><p><b>Admin Reports</b></p></li>
                                        <li><a href="<?= base_url() . 'reports/id_modification' ?>">Last User ID Modification</a></li>
                                    </ul>
                                    <ul class="col-md-2 col-xs-12 list-unstyled">
                                        <li><p><b>Transaction Reports</b></p></li>
                                        <li><a href="<?= base_url() . 'reports/fund_transfer' ?>">Fund Transfer</a></li>
                                        <li><a href="<?= base_url() . 'reports/other_fund_transfer' ?>">Other Fund Transfer</a></li>
                                    </ul>
                                    <ul class="col-md-2 col-xs-12 list-unstyled">
                                        <li><p><b>Billing Report</b></p></li>
                                        <li><a href="<?= base_url() . 'reports/bill_pay' ?>">Billing Information</a></li>
                                    </ul>
                                    <ul class="col-md-2 col-xs-12 list-unstyled">
                                        <li><p><b>Request Report</b></p></li>
                                        <li><a href="<?= base_url() . 'reports/priority_request' ?>">Priority Request</a></li>
                                        <li><a href="<?= base_url() . 'reports/product_request' ?>">Product Request</a></li>
                                        <li><a href="<?= base_url() . 'reports/banking_request' ?>">Banking Request</a></li>
                                    </ul>
                                   
                                    <ul class="col-md-2 col-xs-12 list-unstyled">
                                        <li><p><b>Card Payment</b></p></li>
                                        <li><a href="<?= base_url() . 'reports/card_payment_report' ?>">Card Payment Report</a></li>
                                        <li><a href="<?= base_url() . 'reports/mobile_topup_card_report' ?>">Bill Payment Report</a></li>   
                                    </ul>
                                    <ul class="col-md-2 col-xs-12 list-unstyled">
                                        <li><p><b>Call Center</b></p></li>
                                        <li><a href="<?= base_url() . 'reports/call_center_report' ?>">Call Center User Report</a></li>                                        
                                    </ul>
                          
                                </div>
                            </div>
                        </div>
                    </li>    
                </ul>
            </li>
           -->
            <li class="dropdown yamm-fw">
                <a href="#" id="dropModules" role="button" data-toggle="dropdown">Call Center<b class="caret"></b></a>
                <ul id="dropModules" class="dropdown-menu" role="menu" aria-labelledby="dropModules">                    
                    <li class="">
                        <div class="yamm-content">
                            <div class="row">                                    
                                <div class="clearfix mb">
                                    <ul class="col-md-2 col-xs-12 list-unstyled">
                                        <li><p><b>Apps User</b></p></li>
                                        <li><a href="<?= base_url() . 'call_center/#/user_list' ?>">User Authorization</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>    
                </ul>
            </li>
           <li class="dropdown yamm-fw">
                <a href="#" id="dropModules" role="button" data-toggle="dropdown">Transaction<b class="caret"></b></a>
                <ul id="dropModules" class="dropdown-menu" role="menu" aria-labelledby="dropModules">                    
                    <li class="">
                        <div class="yamm-content">
                            <div class="row">                                    
                                <div class="clearfix mb">
                                    <ul class="col-md-2 col-xs-12 list-unstyled">
                                        <li><p><b>Bill Payment</b></p></li>
                                        <li><a href="<?= base_url() . 'transaction/#/transaction_list' ?>">Bill Payment</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>    
                </ul>
            </li>
        </ul>
        <ul class="nav navbar-nav pull-right">
            <li class="yamm-fw" style="color: #fff; margin-top: 5px;">User Name : <?php echo $this->my_session->userName ?> <br>User Group : <?php echo $this->my_session->group; ?></li>
        </ul>
    </div><!--/.nav-collapse -->
</div>
