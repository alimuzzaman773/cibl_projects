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
                        <a href="<?= base_url() . 'home' ?>">
                            <i class="glyphicon glyphicon-home"></i> Home
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url() . 'profile' ?>">
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
                                                <li><p><b>Products Setup</b></p></li>
                                                <li><a href="<?= base_url() . 'product_setup/index' ?>">Products</a></li>
                                                <li><a href="<?= base_url() . 'product_setup/index/add' ?>">Add Products</a></li>
                                            </ul>
                                        <?php endif; ?>
                                        <?php if (ci_check_permission("canViewLocationSetupMenu")): ?>
                                            <ul class="col-md-2 col-xs-12 list-unstyled">
                                                <li><p><b>Location Setup</b></p></li>
                                                <li><a href="<?= base_url() . 'locator_setup/index' ?>">Location</a></li>
                                                <li><a href="<?= base_url() . 'locator_setup/index/add' ?>">Add Location</a></li>
                                            </ul>
                                        <?php endif; ?>
                                        <?php if (ci_check_permission("canViewZipPartnersSetupMenu")): ?>
                                            <ul class="col-md-2 col-xs-12 list-unstyled">
                                                <li><p><b>Zip Partners</b></p></li>
                                                <li><a href="<?= base_url() . 'zip_partners/index' ?>">Zip Partners</a></li>
                                                <li><a href="<?= base_url() . 'zip_partners/index/add' ?>">Add Zip Partners</a></li>
                                            </ul>
                                        <?php endif; ?>
                                        <?php if (ci_check_permission("canViewPrioritySetupMenu")): ?>
                                            <ul class="col-md-2 col-xs-12 list-unstyled">
                                                <li><p><b>Priority Setup</b></p></li>
                                                <li><a href="<?= base_url() . 'priority_products/index' ?>">Priority</a></li>
                                                <li><a href="<?= base_url() . 'priority_products/index/add' ?>">Add Priority</a></li>
                                            </ul>
                                        <?php endif; ?>
                                        <?php if (ci_check_permission("canViewBenefitsSetupMenu")): ?>
                                            <ul class="col-md-2 col-xs-12 list-unstyled">
                                                <li><p><b>Benefits Setup</b></p></li>
                                                <li><a href="<?= base_url() . 'discount_partners/index' ?>">Benefits</a></li>
                                                <li><a href="<?= base_url() . 'discount_partners/index/add' ?>">Add Benefits</a></li>
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
                                                <li><a href="<?= base_url() . 'push_notification' ?>">Notify to aps user</a></li>
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
                                                <li><p><b>Help</b></p></li>
                                                <li><a href="<?= base_url() . 'help_setup' ?>">Help</a></li>
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
                                        <ul class="col-md-2 col-xs-12 list-unstyled">
                                            <li><p><b>Users/Pin</b></p></li>
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
                                                <li><a href="<?= base_url() . 'pin_generation/viewPinByAction' ?>">Pin</a></li>
                                            <?php endif; ?>
                                        </ul>
                                        <ul class="col-md-2 col-xs-12 list-unstyled">
                                            <li><p><b>Checker</b></p></li>
                                            <?php if (ci_check_permission("canViewAdminUserGroupAuthorizationMenu")): ?>
                                                <li><a href="<?= base_url() . 'admin_user_group_checker' ?>">Admin User Group Authorization</a></li>
                                            <?php endif; ?>

                                            <?php if (ci_check_permission("canViewAdminUserAuthorizationMenu")): ?>
                                                <li><a href="<?= base_url() . 'admin_users_checker' ?>">Admin User Authorization</a></li>
                                            <?php endif; ?>

                                            <?php if (ci_check_permission("canViewLimitSetupAuthorizationMenu")): ?>
                                                <li><a href="<?= base_url() . 'transaction_limit_setup_checker' ?>">Limit Package Authorization</a></li>
                                            <?php endif; ?>

                                            <?php if (ci_check_permission("canViewAppsUserAuthorizationMenu")): ?>
                                                <li><a href="<?= base_url() . 'client_registration_checker' ?>">Apps Users Authorization</a></li>
                                            <?php endif; ?>

                                            <?php if (ci_check_permission("canViewDeviceAuthorizationMenu")): ?>
                                                <li><a href="<?= base_url() . 'device_info_checker' ?>">Device Authorization</a></li>
                                            <?php endif; ?>

                                            <?php if (ci_check_permission("canViewBillerSetupAuthorizationMenu")): ?>
                                                <li><a href="<?= base_url() . 'biller_setup_checker' ?>">Biller Setup Authorization</a></li>
                                            <?php endif; ?>

                                            <?php if (ci_check_permission("canViewPinResetAuthorizationMenu")): ?>
                                                <li><a href="<?= base_url() . 'pin_generation_checker' ?>">Pin Reset Authorization</a></li>
                                            <?php endif; ?>

                                            <?php if (ci_check_permission("canViewPinCreateAuthorizationMenu")): ?>
                                                <li><a href="<?= base_url() . 'pin_create_checker' ?>">Pin Create Authorization</a></li>
                                            <?php endif; ?>

                                            <?php if (ci_check_permission("canViewPasswordPolicyAuthorizationMenu")): ?>
                                                <li><a href="<?= base_url() . 'password_policy_checker' ?>">Password</a></li>
                                            <?php endif; ?>

                                            <?php if (ci_check_permission("canViewAppsUserDeleteAuthorizationMenu")): ?>
                                                <li><a href="<?= base_url() . 'apps_user_delete_checker' ?>">Apps User Delete Authorization</a></li>
                                            <?php endif; ?>
                                        </ul>
                                        <ul class="col-md-2 col-xs-12 list-unstyled">
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
                                        <ul class="col-md-2 col-xs-12 list-unstyled">
                                            <li><p><b>Configuration</b></p></li>
                                            <?php if (ci_check_permission("canViewPasswordPolicyMenu")): ?>
                                                <li><a href="<?= base_url() . 'validation_setup' ?>">Password Policy</a></li>
                                            <?php endif; ?>

                                            <?php if (ci_check_permission("canViewBillerSetupMenu")): ?>
                                                <li><a href="<?= base_url() . 'biller_setup_maker' ?>">Biller Setup</a></li>
                                            <?php endif; ?>

                                            <?php if (ci_check_permission("canViewRoutingNumberMenu")): ?>
                                                <li><a href="<?= base_url() . 'routing_number' ?>">Routing Number Setup</a></li>
                                            <?php endif; ?>

                                            <?php if (ci_check_permission("canViewBillerSetupMenu")): ?>
                                                <li><a href="<?= base_url() . 'bill_type_setup' ?>">Bill Type Setup</a></li>
                                            <?php endif; ?>

                                            <?php if (ci_check_permission("canViewLimitPackgaeMenu")): ?>
                                                <li><a href="<?= base_url() . 'transaction_limit_setup_maker' ?>">Transaction Package Limit</a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>    
                    </ul>
                </li>
            <?php endif; ?>
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
                                </div>
                            </div>
                        </div>
                    </li>    
                </ul>
            </li>
        </ul>
    </div><!--/.nav-collapse -->
</div>
