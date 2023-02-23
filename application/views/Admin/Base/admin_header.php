<?php
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 4/5/2017
 * Time: 2:10 PM
 */

?>
<!--

ADMIN CONSOLE
(c) JYOTHY LABORATORIES

-->

<html lang="en-US">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>

    <title>JFSL ADMIN</title>


    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom/admin.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/core/uikit.min.css"/>


    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/angular.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/angular-messages.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/admin_app.min.js"></script>


    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/uikit.min.js"></script>
    <!--<script type="text/javascript" src="<?php /*echo base_url(); */ ?>assets/js/core/jquery.blockUI.js"></script>-->
    <script> var base_url = "<?php echo base_url(); ?>"; </script>
    <!--JQUERY DATEPICKER  THEME ROLLER-->

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/jquery-ui/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/core/jquery-ui/jquery-ui.min.css"/>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/fastselect.standalone.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/core/fastselect.min.css"/>


    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/uikit-icons.min.js"></script>

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/core/loading-bar.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/loading-bar.js"></script>


    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/core/angular-block-ui.min.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/angular-block-ui.min.js"></script>


    <!--<link rel="stylesheet" href="<?php /*echo base_url(); */ ?>assets/css/core/dataTables.uikit.min.css"/>-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/core/jquery.dataTables.min.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/angular-datatables.min.js"></script>


    <!-- <script type="text/javascript">
         $(window).load(function () {
             $('.blockUI').remove()
         });

     </script>-->

    <style>
        .active_page {
            font-weight: bold;
        }
    </style>

</head>


<body class="uk-visible" ng-app="jfsl_admin" ng-cloak>
<?php /*if (uri_string() != 'console/dcr') { */ ?><!--
    <div
        style="z-index: 10000; border: medium none; margin: 0pt; padding: 0pt; width: 100%; height: 100%; top: 0pt; left: 0pt; background-color: rgb(0, 0, 0); opacity: 0.6; cursor: wait; position: fixed;"
        class="blockUI blockOverlay"></div>
    <div
        style="z-index: 10001; position: fixed; padding: 0px; margin: 0px; width: 30%; top: 40%; left: 35%; text-align: center; color: #8c3313; border: 3px solid rgb(170, 170, 170); background-color: rgb(255, 255, 255); cursor: wait;"
        class="blockUI blockMsg blockPage"><h1>Please wait...</h1></div>
--><?php /*} */ ?>
<?php

$pages_array = unserialize(PAGES);

?>
<div class="console">

    <div class="uk-hidden@m">
        <button class="uk-button uk-button-default uk-margin-top uk-margin-left" type="button"
                uk-toggle="target: #offcanvas-overlay">Menu
        </button>

        <div id="offcanvas-overlay" uk-offcanvas="overlay: true">
            <div class="uk-offcanvas-bar">

                <button class="uk-offcanvas-close" type="button" uk-close></button>

                <div class="uk-card uk-card-secondary uk-card-body  uk-position-fixed" style="overflow: auto">
                    <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>
                        <li class="uk-active"><a href="<?php echo base_url(); ?>console/home"><span
                                    uk-icon="icon: home; ratio: 1"></span> HOME</a></li>
                        <li class="uk-parent">
                            <a href="#"><span uk-icon="icon: user; ratio: 1"></span> Profile</a>
                            <ul class="uk-nav-sub">
                                <li><a href="<?php echo base_url(); ?>console/change_password"><span
                                            uk-icon="icon: lock; ratio: 1"></span> CHANGE PASSWORD</a></li>
                            </ul>
                        </li>

                        <?php
                        if (ADMIN_ACCESSIBLE_PAGES_STRING || ADMIN_PREVILIGE == 'root') {
                            ?>
                            <li class="uk-nav-header">CONSOLE</li>
                            <li class="uk-nav-divider"></li>
                        <?php } ?>
                        <?php if (ADMIN_PREVILIGE == 'root') {


                            foreach ($pages_array as $page) {


                                if ($page['SUB_MENU'] == TRUE) {


                                    ?>

                                    <li class="uk-parent">
                                        <a href="#"> <span
                                                uk-icon="icon: <?php echo $page['ICON']; ?>; ratio: 1"></span> <?php echo $page['NAME']; ?>
                                        </a>
                                        <ul class="uk-nav-sub">
                                            <?php for ($i = 0; $i < sizeof($page['SUB_MENU_ARRAY']); $i++) { ?>

                                                <?php if ($i > 0) { ?>
                                                    <li class="uk-nav-divider"></li>
                                                <?php } ?>

                                                <li>
                                                    <a href="<?php echo $page['SUB_MENU_ARRAY'][$i]['SUB_MENU_LINK']; ?>">
                                                        <span
                                                            uk-icon="icon: <?php echo $page['SUB_MENU_ARRAY'][$i]['SUB_MENU_ICON']; ?>; ratio: 1"></span>
                                                        <?php echo $page['SUB_MENU_ARRAY'][$i]['SUB_MENU']; ?>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>

                                <?php } else { ?>

                                    <li>
                                        <a href="<?php echo $page['LINK']; ?>"><span
                                                uk-icon="icon: <?php echo $page['ICON']; ?>; ratio: 1"></span>
                                            <?php echo $page['NAME']; ?>
                                        </a>
                                    </li>
                                <?php
                                }

                            }
                            ?>

                        <?php
                        } else {

                            if (ADMIN_ACCESSIBLE_PAGES_STRING) {

                                foreach (unserialize(ADMIN_ACCESSIBLE_PAGES) as $accessible_page) {

                                    if ($pages_array[$accessible_page]['SUB_MENU'] == TRUE) {
                                        ?>

                                        <li class="uk-parent">
                                            <a href="#"><span
                                                    uk-icon="icon: <?php echo $pages_array[$accessible_page]['ICON']; ?>; ratio: 1"></span> <?php echo $pages_array[$accessible_page]['NAME']; ?>
                                            </a>
                                            <ul class="uk-nav-sub">
                                                <?php for ($i = 0; $i < sizeof($pages_array[$accessible_page]['SUB_MENU_ARRAY']); $i++) { ?>

                                                    <?php if ($i > 0) { ?>
                                                        <li class="uk-nav-divider"></li>
                                                    <?php } ?>

                                                    <li>
                                                        <a href="<?php echo $pages_array[$accessible_page]['SUB_MENU_ARRAY'][$i]['SUB_MENU_LINK']; ?>">
                                                            <span
                                                                uk-icon="icon: <?php echo $pages_array[$accessible_page]['SUB_MENU_ARRAY'][$i]['SUB_MENU_ICON']; ?>; ratio: 1"></span>
                                                            <?php echo $pages_array[$accessible_page]['SUB_MENU_ARRAY'][$i]['SUB_MENU']; ?>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </li>

                                    <?php
                                    } else {
                                        ?>
                                        <li class="uk-nav-divider"></li>
                                        <li><a href="<?php echo $pages_array[$accessible_page]['LINK']; ?>"><span
                                                    uk-icon="icon: <?php echo $pages_array[$accessible_page]['ICON']; ?>; ratio: 1"></span> <?php echo $pages_array[$accessible_page]['NAME']; ?>
                                            </a></li>

                                    <?php
                                    }

                                }
                            }
                        }?>
                        <li class="uk-nav-divider"></li>
                        <li><a href="<?php echo base_url(); ?>console/logout"><span
                                    uk-icon="icon: sign-out; ratio: 1"></span>
                                Logout</a></li>
                    </ul>
                </div>

            </div>
        </div>

    </div>


    <div
        class="uk-card uk-card-default uk-card-body uk-width-1-5@s uk-width-1-6@l uk-visible@m uk-position-fixed uk-height-1-1 uk-position-top"
        style="overflow: auto">
        <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>


            <?php
            /*if (ADMIN_ACCESSIBLE_PAGES_STRING || ADMIN_PREVILIGE == 'root') {*/
            ?>
            <li><a href="<?php echo base_url(); ?>console/home"><span
                        uk-icon="icon: home; ratio: 1"></span> HOME</a></li>
            <li class="uk-nav-divider"></li>
            <li><a href="<?php echo base_url(); ?>console/change_password"><span
                        uk-icon="icon: lock; ratio: 1"></span> CHANGE PASSWORD</a></li>

            <?php /*} */ ?>

            <?php if (ADMIN_PREVILIGE == 'root') {

                foreach ($pages_array as $page) {

                    if ($page['SUB_MENU'] == TRUE) {

                        ?>

                        <li class="uk-nav-divider"></li>
                        <li class="uk-parent">
                            <a href="#"><span
                                    uk-icon="icon: <?php echo $page['ICON']; ?>; ratio: 1"></span> <?php echo $page['NAME']; ?>
                            </a>
                            <ul class="uk-nav-sub">
                                <?php for ($i = 0; $i < sizeof($page['SUB_MENU_ARRAY']); $i++) { ?>


                                    <?php if ($i > 0) { ?>
                                        <li class="uk-nav-divider"></li>
                                    <?php } ?>
                                    <li><a href="<?php echo $page['SUB_MENU_ARRAY'][$i]['SUB_MENU_LINK']; ?>">
                                            <span
                                                uk-icon="icon: <?php echo $page['SUB_MENU_ARRAY'][$i]['SUB_MENU_ICON']; ?>; ratio: 1"></span>
                                            <?php echo $page['SUB_MENU_ARRAY'][$i]['SUB_MENU']; ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>

                    <?php } else { 
                    if($page['NAME'] != 'FABHOME ORDERS'){?>
                        <li class="uk-nav-divider"></li>
                        <li>
                            <a href="<?php echo $page['LINK']; ?>"><span
                                    uk-icon="icon: <?php echo $page['ICON']; ?>; ratio: 1"></span> <?php echo $page['NAME']; ?>
                            </a>
                        </li>
                    <?php }?>
                    <?php
                    }

                }
                ?>

            <?php
            } else {


                if (ADMIN_ACCESSIBLE_PAGES_STRING) {

                    foreach (unserialize(ADMIN_ACCESSIBLE_PAGES) as $accessible_page) {


                        if ($pages_array[$accessible_page]['SUB_MENU'] == TRUE) {
                            ?>

                            <li class="uk-nav-divider"></li>
                            <li class="uk-parent">
                                <a href="#"><span
                                        uk-icon="icon: <?php echo $pages_array[$accessible_page]['ICON']; ?>; ratio: 1"></span> <?php echo $pages_array[$accessible_page]['NAME']; ?>
                                </a>
                                <ul class="uk-nav-sub">

                                    <?php for ($i = 0; $i < sizeof($pages_array[$accessible_page]['SUB_MENU_ARRAY']); $i++) { ?>

                                        <?php if ($i > 0) { ?>
                                            <li class="uk-nav-divider"></li>
                                        <?php } ?>

                                        <li>
                                            <a href="<?php echo $pages_array[$accessible_page]['SUB_MENU_ARRAY'][$i]['SUB_MENU_LINK']; ?>">
                                                <span
                                                    uk-icon="icon: <?php echo $pages_array[$accessible_page]['SUB_MENU_ARRAY'][$i]['SUB_MENU_ICON']; ?>; ratio: 1"></span>
                                                <?php echo $pages_array[$accessible_page]['SUB_MENU_ARRAY'][$i]['SUB_MENU']; ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>


                        <?php } else { ?>

                            <li class="uk-nav-divider"></li>
                            <li>
                                <a href="<?php echo $pages_array[$accessible_page]['LINK']; ?>"><span
                                        uk-icon="icon: <?php echo $pages_array[$accessible_page]['ICON']; ?>; ratio: 1"></span> <?php echo $pages_array[$accessible_page]['NAME']; ?>
                                </a>
                            </li>

                        <?php
                        }

                    }
                }
            }?>
            <li class="uk-nav-divider"></li>
            <li><a href="<?php echo base_url(); ?>console/logout"><span uk-icon="icon: sign-out; ratio: 1"></span>
                    LOGOUT</a></li>
        </ul>
    </div>