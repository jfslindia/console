<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script>
    $(document).ready(function () {

        $('#loading').hide();
        $('#user_table_block').hide();
        $('#orders_table_block').hide();

    });


</script>

<style>
    #user_table_block, #orders_table_block {
        margin-top: 20px;
    }
</style>


<div ng-controller="search_panel"
     class="uk-width-1-1@s uk-width-4-5@m uk-width-5-6@m uk-margin-auto-left content_wrapper uk-padding">

    <div>
        <div class="uk-card uk-card-default uk-card-hover uk-card-body">

            <h3 class="uk-card-title uk-heading-primary uk-text-center">Search Panel</h3>


            <hr class="uk-divider-icon">
            <form class="uk-form-stacked" name="search_form" novalidate>


                <div class="uk-margin">
                    <label class="uk-form-label" for="search_with">Search with</label>

                    <div class="uk-form-controls">
                        <select ng-model="search_with" class="uk-select" name="search_with"
                                required>
                            <option value="" selected>Choose</option>
                            <option value="name">Name</option>
                            <option value="mobile_number">Mobile Number</option>
                            <option value="customer_id">Customer ID</option>
                            <option value="email">Email</option>
                            <option value="order_id">Mobile Order ID</option>
                            <option value="booking_id">Booking ID</option>
                        </select>


                        <div ng-show="search_form.search_with.$dirty && search_form.search_with.$invalid">

                            <span class="uk-text-warning" ng-show="search_form.search_with.$error.required">Search with field is required.</span>
                        </div>


                    </div>
                </div>


                <div class="uk-margin">
                    <label class="uk-form-label" for="search_text">Search Text</label>

                    <div class="uk-form-controls">
                        <input class="uk-input" type="text"
                               placeholder="Search something here..." name="search_text" ng-model="search_text"
                               ng-enter="search(search_form)"
                               ng-minlength="3" required>


                        <div ng-show="search_form.search_text.$dirty && search_form.search_text.$invalid">
                            <span class="uk-text-warning" ng-show="search_form.search_text.$error.minlength">Minimum 3 characters required</span>
                            <span class="uk-text-warning" ng-show="search_form.search_text.$error.required">Search text field is required.</span>
                        </div>


                    </div>
                </div>


                </p>

                <div class="uk-margin uk-flex uk-flex-center">
                    <button class="uk-button uk-button-primary " name="search_button" ng-click="search(search_form)"
                            ng-show="search_form.search_text.$valid">
                        Search
                    </button>
                    <div uk-spinner id="loading"></div>
                </div>


            </form>
        </div>
    </div>

    <div id="user_table_block">
        <h4 class="uk-text-center">Customer Details</h4>
        <table id="user_table" class="uk-table uk-table-hover uk-table-divider">
            <thead>
            <tr>
                <th>Customer Code</th>
                <th>Registered Date</th>
                <th>Name</th>
                <th>Mobile Number</th>
                <th>Email</th>
                <th>Edit</th>
            </tr>
            </thead>
            <tbody id="user_table_body">


            </tbody>
        </table>
    </div>

    <div id="orders_table_block">
        <h4 class="uk-text-center uk-margin-top">Pickups Details</h4>
        <table id="orders_table" class="uk-table uk-table-hover uk-table-divider">
            <thead>
            <tr>
                <th>Pickup Created Date</th>
                <th>Pickup Date</th>
                <th>Customer Code</th>
                <th>Booking ID</th>
                <th>Reference Number</th>
                <th>Order Status</th>
            </tr>
            </thead>
            <tbody id="orders_table_body">

            </tbody>
        </table>
    </div>

</div>


<div id="user_edit" uk-modal="bg-close:false">
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title">Edit customer data</h2>
        </div>
        <div class="uk-modal-body">

            <form name="edit_user_form" novalidate>

                <div class="uk-margin">
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon" uk-icon="icon: user"></span>
                        <input class="uk-input" type="text" placeholder="Customer name" name="customer_name" id="customer_name" ng-model="customer_name"
                               required ng-minlength="3">

                        <div ng-show="edit_user_form.customer_name.$dirty && edit_user_form.customer_name.$invalid">
                            <span class="uk-text-warning" ng-show="edit_user_form.customer_name.$error.minlength">Minimum 3 characters required</span>
                            <span class="uk-text-warning" ng-show="edit_user_form.customer_name.$error.required">Name field is required.</span>
                        </div>
                    </div>
                </div>


                <div class="uk-margin">
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon" uk-icon="icon: receiver"></span>
                        <input class="uk-input" type="text" placeholder="Customer mobile" id="customer_mobile" name="customer_mobile" ng-model="customer_mobile"
                               required ng-minlength="10" ng-maxlength="10" ng-pattern="/^[0-9]{10,10}$/" ng-number disabled>

                        <div ng-show="edit_user_form.customer_mobile.$dirty && edit_user_form.customer_mobile.$invalid">
                            <span class="uk-text-warning" ng-show="edit_user_form.customer_mobile.$error.minlength">Minimum 10 characters required</span>
                            <span class="uk-text-warning" ng-show="edit_user_form.customer_mobile.$error.maxlength">Maximum 10 characters are allowed</span>
                            <span class="uk-text-warning" ng-show="edit_user_form.customer_mobile.$error.required">Mobile number field is required.</span>
                            <span class="uk-text-warning" ng-show="edit_user_form.customer_mobile.$error.pattern">Only mobile numbers are allowed</span>
                        </div>
                    </div>
                </div>

                <div class="uk-margin">
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon" uk-icon="icon: mail"></span>
                        <input class="uk-input" type="email" placeholder="Customer email" id="customer_email" name="customer_email" ng-model="customer_email"
                               required ng-pattern="/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/">

                        <div ng-show="edit_user_form.customer_email.$dirty && edit_user_form.customer_email.$invalid">

                            <span class="uk-text-warning" ng-show="edit_user_form.customer_email.$error.required">Email field is required.</span>
                            <span class="uk-text-warning" ng-show="edit_user_form.customer_email.$error.pattern">Please check the email format</span>
                        </div>
                    </div>
                </div>

            </form>

        </div>
        <div id="edit_user_footer" class="uk-modal-footer uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>

        </div>
    </div>
</div>

<!--<div id="user_edit2" uk-modal="bg-close:false">
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title">Confirm changes</h2>
        </div>
        <div class="uk-modal-body">
            <p>Are you sure?</p>
            <p id="verify_box"></p>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <a href="#user_edit1" class="uk-button uk-button-primary" uk-toggle>Previous</a>
            <button class="uk-button uk-button-default uk-modal-close" id="save_user" type="button">Save</button>
        </div>
    </div>
</div>-->

<style>
    .uk-table th {
        text-align: left !important;
    }
</style>