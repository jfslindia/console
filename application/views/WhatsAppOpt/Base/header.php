<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>

  <title>JFSL</title>


  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/core/uikit.min.css"/>


  <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/jquery-1.12.4.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/angular.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/angular-messages.min.js"></script>



  <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/uikit.min.js"></script>

  <script> var base_url = "<?php echo base_url(); ?>"; </script>
  <!--JQUERY DATEPICKER  THEME ROLLER-->


  <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/uikit-icons.min.js"></script>

  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/core/loading-bar.css"/>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/loading-bar.js"></script>


  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/core/angular-block-ui.min.css"/>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/angular-block-ui.min.js"></script>


    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/Opt.js"></script>

<link href="<?php echo base_url() ?>assets/css/custom/fabricspa.css?n=<?php echo date('G'); ?>" rel="stylesheet"
          media="screen">


</head>
<body>




   <script>
    $(document).ready(function () {
        var header_height = $('#main-header').height();
        $('#offcanvas-side-menu').css('top', header_height);
    });


</script>


<script>
    $(document).ready(function () {
        $('.header_menu').hover(function () {

            if ($(this).hasClass('animated')) {
                $(this).removeClass('animated');
                $(this).removeClass('pulse');

            } else {

                $(this).addClass('animated');
                $(this).addClass('pulse');
            }


        })
    })
</script>



<?php /*Desktop navbar*/ ?>
<div class="uk-visible@m" style="height: 92px;">
    <div uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky; bottom: #transparent-sticky-navbar">
        <nav class="uk-navbar-container" uk-navbar="mode: click"
        style="padding-top:10px;padding-bottom:15px;height: 65px;background-color: white !important;">
        <div class="uk-navbar-left">
            <a class="uk-navbar-item uk-logo new_logo" href="https://fabricspa.com"><img
                src="<?php echo base_url(); ?>assets/images/newlogo.jpg"></a>


            </div>
            <div class="uk-navbar-right">


                <ul class="uk-navbar-nav">

                    <li><a style="font-size:14px !important;" class="header_menu flex_end uk-padding-remove-right" href="https://fabricspa.com/">HOME</a></li>


                    <?php

                    if ($this->uri->segment(1) == 'essentials') {

                        ?>

                        <li><a style="font-size:14px !important;" class="header_menu flex_end active_page" href="https://fabricspa.com/essentials">ESSENTIALS</a>
                        </li>

                    <?php } else { ?>
                        <li><a style="font-size:14px !important;" class="header_menu flex_end" href="https://fabricspa.com/essentials">ESSENTIALS</a></li>
                    <?php } ?>


                    <?php

                    if ($this->uri->segment(1) == 'our_stores') {

                        ?>

                        <li><a style="font-size:14px !important;" class="header_menu flex_end active_page"
                         href="https://fabricspa.com/our_stores">STORES</a></li>

                     <?php } else { ?>
                        <li><a style="font-size:14px !important;" class="header_menu flex_end" href="https://fabricspa.com/our_stores">STORES</a></li>
                    <?php } ?>




                    <?php



                    if ($this->uri->segment(1) == 'partner_with_us') {

                        ?>

                        <li><a style="font-size:14px !important;" class="header_menu flex_end active_page" href="https://fabricspa.com/partner_with_us">PARTNER
                        WITH US</a></li>

                    <?php } else { ?>
                        <li><a style="font-size:14px !important;" class="header_menu flex_end" href="https://fabricspa.com/partner_with_us">PARTNER WITH
                        US</a></li>

                    <?php } ?>


                    <?php

                    if ($this->uri->segment(1) == 'contact_us') {

                        ?>

                        <li><a style="font-size:14px !important;" class="header_menu flex_end active_page"
                         href="https://fabricspa.com/contact_us">CONTACT</a></li>

                     <?php } else { ?>

                        <li><a style="font-size:14px !important;" class="header_menu flex_end" href="https://fabricspa.com/contact_us">CONTACT</a></li>

                    <?php } ?>


                    <?php




                    if ($this->uri->segment(1) == 'blog') {

                        ?>

                        <li><a style="font-size:14px !important;" class="header_menu flex_end active_page" href="https://fabricspa.com/blog">BLOG</a></li>

                    <?php } else { ?>
                        <li><a style="font-size:14px !important;" class="header_menu flex_end" href="https://fabricspa.com/blog">BLOG</a></li>
                    <?php } ?>

                    <?php

                    if ($this->uri->segment(1) == 'fabricspa_story') {

                        ?>

                        <li><a style="font-size:14px !important;" class="header_menu active_page flex_end" href="https://fabricspa.com/fabricspa_story">ABOUT
                        US</a></li>


                    <?php } else { ?>

                        <li><a style="font-size:14px !important;" class="header_menu flex_end" href="https://fabricspa.com/fabricspa_story">ABOUT US</a>
                        </li>
                    <?php } ?>


                </ul>
            </div>

        </nav>

        <div class="fabricspa_bar"
        style="background-image: url('<?php echo base_url(); ?>assets/images/bar_1.png');"></div>
    </div>
</div>


<?php /*Mobile navbar*/ ?>
<div id="m_header" class="uk-hidden@m">
    <div uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky; bottom: #transparent-sticky-navbar">
        <nav class="uk-navbar-container pbottom20px ptop10px" uk-navbar="mode: click"
        style="height: 65px;background-color: white !important;">
        <div class="uk-navbar-left">


            <a class="uk-navbar-item uk-logo new_logo" href="https://fabricspa.com" style="height: auto;"><img
                src="<?php echo base_url(); ?>assets/images/newlogo.jpg"></a>


            </div>
            <div class="uk-navbar-right">


                <ul class="uk-navbar-nav">
                    <li>
                        <a class="uk-navbar-toggle" href="#" style="height: 60px;">
                            <span uk-icon="icon: menu; ratio: 2"></span> <span class="uk-margin-small-left"></span>
                        </a>

                        <div class="uk-navbar-dropdown">
                            <ul class="uk-nav uk-navbar-dropdown-nav">


                                <li><a style="font-size:14px !important;" class="header_menu flex_end" href="https://fabricspa.com/">HOME</a></li>



                                <?php

                                if ($this->uri->segment(1) == 'essentials') {

                                    ?>

                                    <li><a style="font-size:14px !important;" class="header_menu active_page" href="https://fabricspa.com/essentials">ESSENTIALS</a>
                                    </li>

                                <?php } else { ?>

                                    <li><a style="font-size:14px !important;" class="header_menu " href="https://fabricspa.com/essentials">ESSENTIALS</a>
                                    </li>

                                <?php } ?>

                                <?php

                                if ($this->uri->segment(1) == 'our_stores') {

                                    ?>

                                    <li><a style="font-size:14px !important;" class="header_menu active_page" href="https://fabricspa.com/our_stores">STORES</a>
                                    </li>

                                <?php } else { ?>

                                    <li><a style="font-size:14px !important;" class="header_menu " href="https://fabricspa.com/our_stores">STORES</a></li>

                                <?php } ?>

                                <?php


                                if ($this->uri->segment(1) == 'partner_with_us') {

                                    ?>


                                    <li><a style="font-size:14px !important;" class="header_menu active_page" href="https://fabricspa.com/partner_with_us">PARTNER
                                    WITH US</a></li>

                                <?php } else { ?>

                                    <li><a style="font-size:14px !important;" class="header_menu " href="https://fabricspa.com/partner_with_us">PARTNER WITH
                                    US</a></li>

                                <?php } ?>


                                <?php

                                if ($this->uri->segment(1) == 'contact_us') {

                                    ?>

                                    <li><a style="font-size:14px !important;" class="header_menu active_page" href="https://fabricspa.com/contact_us">CONTACT</a>
                                    </li>

                                <?php } else { ?>

                                    <li><a style="font-size:14px !important;" class="header_menu " href="https://fabricspa.com/contact_us">CONTACT</a></li>

                                <?php } ?>

                                <?php

                                if ($this->uri->segment(1) == 'blog') {

                                    ?>

                                    <li><a style="font-size:14px !important;" class="header_menu active_page" href="https://fabricspa.com/blog">BLOG</a>
                                    </li>


                                <?php } else { ?>

                                    <li><a style="font-size:14px !important;" class="header_menu " href="https://fabricspa.com/blog">BLOG</a></li>
                                <?php } ?>


                                <?php

                                if ($this->uri->segment(1) == 'fabricspa_story') {

                                    ?>

                                    <li><a style="font-size:14px !important;" class="header_menu active_page flex_end"
                                     href="https://fabricspa.com/fabricspa_story">ABOUT US</a></li>


                                 <?php } else { ?>

                                    <li><a style="font-size:14px !important;" class="header_menu flex_end" href="https://fabricspa.com/fabricspa_story">ABOUT
                                    US</a></li>
                                <?php } ?>




                            </ul>
                        </div>
                    </li>
                </ul>
            </div>

        </nav>

        <div class="fabricspa_bar"
        style="background-image: url('<?php echo base_url(); ?>assets/images/bar_1.png');"></div>
    </div>
</div>
