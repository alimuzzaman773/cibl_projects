<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex,nofollow" />
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="<?= asset_url() ?>favicon.ico">

        <title><?= isset($pageTitle) ? $pageTitle : 'NoTitle' ?> | SkyBank</title>

        <!-- Bootstrap core CSS -->
        <link href="<?= theme_path() ?>bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap theme -->
        <link href="<?= theme_path() ?>bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
        <!-- FontAwesome -->
        <link href="<?= theme_path() ?>font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <!-- yamm mega menu -->
        <link href="<?= theme_path() ?>yamm.css" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="<?= theme_path() ?>style.css" rel="stylesheet">

        <!-- custom css -->

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <?php
        /** grocery css * */
        if (isset($css_files)):
            foreach ($css_files as $file):
                ?>
                <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
                <?php
            endforeach;
        endif;
        ?>

        <script>
            window.app = window.app || {};
            app.baseUrl = "<?= base_url() ?>";
            app.assetUrl = "<?= asset_url() ?>";
            app.logged_in = '<?= $this->my_session->logged_in ?>';
            app.config = {
                dateFormat: 'dd/mm/yy'
            };
            app.angularModules = [];
            app.addModules = function (moduleName, domId) {
                window.app.angularModules.push({name: moduleName, domId: domId});
            }
            app.showModal = function () {
                $(".containermodal").show();
            };
            app.hideModal = function (delayTime) {
                if (delayTime != undefined) {
                    $(".containermodal").show().delay(delayTime).hide();
                    return false;
                }
                $(".containermodal").hide();
            };
            app.parseInt = function (val, defaultval) {
                return !isNaN(parseInt(val)) ? parseInt(val) : (defaultval == undefined ? 0 : defaultval);
            };
            app.parseFloat = function (val, defaultval) {
                return !isNaN(parseFloat(val)) ? parseFloat(val) : (defaultval == undefined ? 0.00 : defaultval);
            };
        </script>  
        <script src="<?= asset_url() ?>jquery/jquery-1.11.3.min.js"></script>
        <script src="<?= asset_url() ?>jquery/jquery-migrate-1.2.1.min.js"></script>

        <style>
            .container {width:100%}
            .container-fluid .starter-template {padding-top: 50px;margin-right: 0px; margin-left: 0px}
            .flexigrid div.form-div input[type=text], .flexigrid div.form-div input[type=password]
            {               
                height:auto;                
            }
            .title-underlined{border-bottom: 1px solid #ccc}        
        </style>    


        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/knockout-2.2.1.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/knockout.mapping-latest.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ko-dt-bootstrap/cog.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ko-dt-bootstrap/cog.utils.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ko-dt-bootstrap/knockout_datatable.js"></script>
        <script src="<?php echo base_url(); ?>assets/data_table/data_table.min.js"></script>
       

    </head>

    <body>
        <?php if ($this->my_session->logged_in): ?>
            <div class="navbar yamm navbar-edotco navbar-fixed-top" id="navbar-edotco" role="navigation">
                <?php
                $this->load->view(TEMPLATES_FOLDER . 'menu/menu.php');
                ?>
            </div>
        <?php endif; ?>    

        <div class="container-fluid">
            <div class="starter-template row">
                <?php
                $this->load->view($body_template);
                ?>
            </div>
            <div class="containermodal">
                Loading Please Wait...
            </div>
        </div><!-- /.container -->

        <script src="<?= asset_url() ?>angularjs/angular.min.js"></script>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->        
        <script src="<?= theme_path() ?>bootstrap/js/bootstrap.min.js"></script>

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="<?= theme_path() ?>bootstrap/js/ie10-viewport-bug-workaround.js"></script>

        <?php
        /** grocery js * */
        if (isset($js_files)):
            foreach ($js_files as $file):
                ?>
                <script src="<?php echo $file; ?>"></script>
                <?php
            endforeach;
        endif;
        ?> 

        <!-- custom css -->        
        <?= render_css() ?>        
        <!-- custom js -->
        <?= render_js() ?>

        <script>
            app.checkUserLoginStatus = function () {
                $.ajax({
                    url: app.baseUrl + "api/user/check_login_status",
                    type: "get",
                    dataType: 'json',
                    success: function (data) {
                        if (data.success == false) {
                            alert(data.msg);
                            window.location = app.baseUrl + "home/login"
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
                return false;
            };
            $(function () {
                $(document).on('click', '.yamm .dropdown-menu', function (e) {
                    e.stopPropagation()
                });
            });
            angular.element(document).ready(function () {
                var modules = [];
                angular.forEach(app.angularModules, function (v, i) {
                    modules.push(v.name);
                    angular.bootstrap(document.getElementById(v.domId), [v.name]);
                });
            });

            if (typeof String.prototype.trim != "function") {
                String.prototype.trim = function () {
                    return this.replace(/^\s+|\s+$/g, '');
                };
            }

            if (!Array.prototype.indexOf) {
                Array.prototype.indexOf = function (elem, startFrom) {
                    var startFrom = startFrom || 0;
                    if (startFrom > this.length)
                        return -1;

                    for (var i = 0; i < this.length; i++) {
                        if (this[i] == elem && startFrom <= i) {
                            return i;
                        } else if (this[i] == elem && startFrom > i) {
                            return -1;
                        }
                    }
                    return -1;
                }
            }
        </script>    
        <footer>
            <div class="container">
                <hr />
                <p class="text-center">&copy; PREMIER BANK</p>
            </div>
        </footer>
    </body>
</html>
