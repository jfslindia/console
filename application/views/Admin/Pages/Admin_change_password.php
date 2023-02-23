<!-- * Created by PhpStorm.
 * User: targetman
 * Date: 4/5/2017
 * Time: 1:43 PM
 */-->


<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

?>
<!--styles are here-->


<div ng-controller="change_passwd" class="uk-width-1-1@s uk-width-4-5@m uk-width-5-6@m uk-margin-auto-left content_wrapper uk-padding">

    <div id="page-1-container">

        <div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">

            <!--uk-width-1-2@l uk-width-1-2@m uk-width-1-2@s-->
            <h3 class="uk-heading-divider uk-text-center">Change Password</h3>

            <div class="uk-grid" id="grid">

                <form class="uk-form-horizontal uk-margin-large uk-align-center">

                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-horizontal-text">Old Password</label>

                        <div class="uk-form-controls">
                            <input type="password" ng-model="old_password" class="uk-input">
                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-horizontal-text">New Password</label>

                        <div class="uk-form-controls">
                            <input type="password" ng-model="new_password" class="uk-input">
                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-horizontal-text">Re-enter Password</label>

                        <div class="uk-form-controls">
                            <input type="password" ng-model="re_enter_password" class="uk-input" ng-enter="change_password()">
                        </div>
                    </div>


                    <div class="uk-margin">

                        <button id="change_password_button" type="button"
                                class="uk-button uk-button-primary uk-display-block uk-margin-small-top uk-float-right" ng-click="change_password()">
                            Change Password
                        </button>
                        <!--<div uk-spinner id="loading" class="uk-flex uk-flex-center"></div>-->
                    </div>
                    <div class="uk-clearfix"></div>

                    <!-- <progress class="uk-progress" value="0" max="100" id="progressElem"></progress> -->
                    <div id="resfield" style="display: block; width: 100%; margin:auto;"></div>
                </form>

            </div>

        </div>

    </div>


    <div id="res" class="uk-grid uk-align-center uk-width-1-2\@s uk-width-1-3\@m uk-width-1-4\@l" style="display: none">

        <table class="uk-table uk-table-hover" id="result-table">
            <thead>
            <tr>
                <th>#</th>
                <th id="head-1">Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>

            </tr>
            </thead>
            <tbody id="result-table-body">

            <!--content-->

            </tbody>
        </table>
    </div>

</div>




