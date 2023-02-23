<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <meta http-equiv="Pragma" content="no-cache">
    <title>Form</title>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/core/uikit.min.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/uikit.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/uikit-icons.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/angular.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/core/loading-bar.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/core/angular-block-ui.min.css">
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/loading-bar.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/angular-block-ui.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/angular-messages.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/welcome_app.js"></script>

    <!--JQUERY DATEPICKER  THEME ROLLER-->

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/jquery-ui/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/core/jquery-ui/jquery-ui.min.css"/>

    <script>
        var base_url = "<?php echo base_url(); ?>";
    </script>
    <style>
        .block-ui-message {
            display: none;
        }
        [ng\:cloak],[ng-cloak], .ng-cloak{
            display: none;
        }
    </style>
</head>
<body style="height: 100vh" ng-app="welcome_app" ng-cloak>

<div class="uk-flex uk-flex-center" ng-controller="welcome_form">
    <div
        class="uk-card uk-card-default uk-card-body uk-width-1-2@m uk-padding-remove-top uk-margin-top uk-margin-bottom">
        <div class="uk-text-center">
            <img src="<?php echo base_url(); ?>assets/images/fabricspa_logo.png" style="width: 165px;">
        </div>

        <div class="fabricspa_bar"
             style="background-image: url('<?php echo base_url(); ?>assets/images/bar_1.png');width: auto;height: 17px;background-position: right;"></div>

        <?php if ($is_customer_registered) { ?>
        <div id="form_box" class="uk-margin uk-hidden">
            <?php } else { ?>
            <div id="form_box" class="uk-margin">
                <?php } ?>
                <form name="reg_form">
                    <div class="uk-margin" style="border:2px solid #f4f4f4">
                        <div class="uk-grid uk-child-width-1-2@m uk-padding">
                            <div class="box_padding uk-width-1-1">
                                <div class="uk-inline" style="width: 100%">
                                    <span class="uk-form-icon" uk-icon="icon: user"></span>
                                    <input class="uk-input xinput" type="text" placeholder="Name" ng-model="name"
                                           name="name" required>

                                </div>
                                <span ng-show="reg_form.name.$touched && reg_form.name.$invalid"
                                      class="uk-text-warning">The name is required.</span>
                            </div>

                            <div class="box_padding">
                                <div class="uk-inline" style="width: 100%">
                                    <span class="uk-form-icon" uk-icon="icon: receiver"></span>
                                    <?php if ($mobile_number && strlen($mobile_number) == 10) { ?>
                                        <input class="uk-input xinput" type="text" placeholder="Mobile number"
                                               value="<?php echo $mobile_number; ?>" ng-model="mobile_number"
                                               ng-init="mobile_number ='<?php echo $mobile_number; ?>'"
                                               name="mobile_number" limit-chars="10" ng-number
                                               ng-pattern="/^[6789]\d{9}$/" disabled required>
                                    <?php } else { ?>
                                        <input class="uk-input xinput" type="text" ng-model="mobile_number"
                                               placeholder="Mobile number" name="mobile_number" limit-chars="10"
                                               ng-number ng-pattern="/^[6789]\d{9}$/" required>
                                    <?php } ?>
                                </div>
                            <span ng-show="reg_form.mobile_number.$touched && reg_form.mobile_number.$invalid"
                                  class="uk-text-warning">The mobile number is required.</span>
                            <span ng-show="reg_form.mobile_number.$dirty && reg_form.mobile_number.$error.pattern"
                                  class="uk-text-warning">Enter valid mobile number.</span>
                            </div>

                            <div class="box_padding">
                                <div class="uk-inline" style="width: 100%">
                                    <span class="uk-form-icon" uk-icon="icon: mail"></span>
                                    <input class="uk-input xinput" type="text" placeholder="Email" ng-model="email"
                                           name="email" ng-pattern="/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/" required>
                                </div>
                                <span ng-show="reg_form.email.$touched && reg_form.email.$invalid"
                                      class="uk-text-warning">The email is required.</span>
                                <span ng-show="reg_form.email.$dirty && reg_form.email.$error.pattern"
                                      class="uk-text-warning">Enter valid email</span>

                            </div>


                            <div class="uk-flex uk-flex-bottom box_padding" style="height: 52px;">
                                <div style="padding-left: 20px;width: 100%" uk-tooltip="Please select your gender">
                                    <div class="uk-grid-small uk-child-width-auto uk-grid uk-inline" style="padding-left: 10px; border-bottom-style: dashed;width: 100%;border-bottom: 1px dashed #e5e5e5;
									transition: .2s ease-in-out;
									transition-property: color,background-color,border;">
                                        <label style="padding:0;"><input class="uk-radio" type="radio" name="radio2"
                                                                         ng-model="gender" value="male">
                                            Male</label>
                                        <label><input class="uk-radio" type="radio" name="radio2" ng-model="gender"
                                                      value="female"> Female</label>
                                    </div>
                                </div>
                            </div>


                            <div class="box_padding">
                                <div class="uk-inline" style="width: 100%">
                                    <span class="uk-form-icon" uk-icon="icon: star"></span>
                                    <input class="uk-input xinput" type="text" id="dob"
                                           placeholder="Date of birth (DD/MM/YYYY)" ng-model="dob">
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="uk-margin" style="border:2px solid #f4f4f4">
                        <div class="uk-grid uk-child-width-1-2@m uk-padding uk-text-center">
                            <div>
                                <img src="<?php echo base_url(); ?>assets/images/map_1.png" style="width: 260px;">
                            </div>

                            <div>

                                <div class="box_padding">
                                    <div class="uk-inline" style="width: 100%">
                                        <span class="uk-form-icon" uk-icon="icon: location"></span>
                                        <input class="uk-input xinput" type="text" limit-chars="6" ng-number
                                               placeholder="Pincode" ng-change="load_area()" ng-model="pincode"
                                               ng-model-options="{ debounce: 1000 }" name="pincode" required>
                                    </div>
                                <span ng-show="reg_form.pincode.$touched && reg_form.pincode.$invalid"
                                      class="uk-text-warning">The pincode is required.</span>
                                </div>

                                <div class="box_padding">
                                    <div style="width: 100%">
                                        <!--<span class="uk-form-icon" uk-icon="icon: location"></span>-->
                                        <select class="uk-select" placeholder="Area" ng-model="area_code" required>
                                            <option value="" ng-show="false">Select area</option>
                                            <option value="{{area_detail.AreaCode}}"
                                                    ng-repeat="area_detail in area_list">
                                                {{area_detail.Area}}
                                            </option>
                                        </select>
                                    </div>
                                </div>


                                <div class="box_padding">
                                    <div class="uk-inline" style="width: 100%">
                                        <span class="uk-form-icon" uk-icon="icon: location"></span>
                                        <input class="uk-input xinput" type="text" placeholder="House/Building details"
                                               name="house" ng-model="house" required>
                                    </div>
                                <span ng-show="reg_form.house.$touched && reg_form.house.$invalid"
                                      class="uk-text-warning">The house/building details are required.</span>
                                </div>


                                <div class="box_padding">
                                    <div class="uk-inline" style="width: 100%">
                                        <span class="uk-form-icon" uk-icon="icon: location"></span>
                                        <input class="uk-input xinput" type="text" placeholder="Landmark"
                                               ng-model="landmark">
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                    <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                        <label><input class="uk-checkbox" type="checkbox" ng-model="confirmation"> I confirm acceptance
                            of
                            Fabricspa terms and
                            conditions applicable and agree to receive all the offers updates on my mobile</label>

                    </div>

                    <div class="uk-margin uk-text-center">
                        <div>
                            <button class="uk-button uk-button-default" ng-click="register()">REGISTER</button>
                        </div>
                    </div>


                </form>
            </div>

            <?php if ($is_customer_registered) { ?>

            <div id="result_box" class="uk-margin">
                <?php }else { ?>
                <div id="result_box" class="uk-margin uk-hidden">
                    <?php } ?>


                    <div class="uk-text-center">
                    <span class="uk-margin-small-right uk-animation-scale-up" uk-icon="icon: check; ratio: 2.5"
                          id="finish_icon"></span>

                        <p>You have successfully registered with us. Thank you for choosing Fabricspa.</p>
                        <a href="https://fabspa.in/app" type="button" class="uk-button uk-button-default">Schedule a
                            Pickup</a>
                    </div>
                </div>
            </div>
        </div>

</body>

<style type="text/css">
    .xinput {
        border-top: 0;
        border-left: 0;
        border-right: 0;
        border-bottom-style: dashed;
    }

    .box_padding {
        padding-top: 6px;
        padding-bottom: 6px;
    }

    #finish_icon {
        background: rgb(75, 165, 75);
        border-radius: 100%;
        padding: 5px;
        color: white;
    }
</style>
<script type="text/javascript">var dis = 0;</script>
<script type="text/javascript">

    $(function () {
        $("#dob").datepicker({dateFormat: 'dd-mm-yy', changeYear: true, changeMonth: true, yearRange: "1930:2012"});

    });

</script>

</html>