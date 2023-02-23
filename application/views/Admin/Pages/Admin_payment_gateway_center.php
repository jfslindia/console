<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script>
    $(document).ready(function () {

        $('#loading').hide();
        $('#user_table_block').hide();
        $('#transaction_info_block').hide();
        $('#transaction_payment_info_block').hide();


    })

</script>

<style>
    #user_table_block, #transaction_info_block, #transaction_payment_info_block {
        margin-top: 20px;
    }
</style>


<div ng-controller="payment_gateway_center" class="uk-width-1-1@s uk-width-4-5@m uk-width-5-6@m uk-margin-auto-left content_wrapper uk-padding">

    <div>
        <div class="uk-card uk-card-default uk-card-hover uk-card-body">

            <h3 class="uk-card-title uk-heading-primary uk-text-center">Payment Gateway</h3>


            <hr class="uk-divider-icon">
            <form class="uk-form-stacked" name="search_form" novalidate>


                <div class="uk-margin">
                    <label class="uk-form-label" for="search_with">Search with</label>

                    <div class="uk-form-controls">
                        <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid" ng-init="search_with='payment_id'">
                            <label><input ng-model="search_with" class="uk-radio" type="radio" name="radio2" value="payment_id" checked="true"> Payment ID</label>
                            <label><input ng-model="search_with" class="uk-radio" type="radio" name="radio2" value="egrn"> EGRN</label>
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
                <th>Name</th>
                <th>Mobile Number</th>
                <th>Email</th>
                <th>Branch Code</th>
                <th>Branch Name</th>
                <th>City Name</th>
            </tr>
            </thead>
            <tbody id="user_table_body">


            </tbody>
        </table>
    </div>

    <div id="transaction_info_block">
        <h4 class="uk-text-center uk-margin-top">Transaction Logs in our DB</h4>
        <table id="transaction_info_table" class="uk-table uk-table-hover uk-table-divider">
            <thead>
            <tr>
                <th>Transaction Date</th>
                <th>Payment ID</th>
                <th>EGRN</th>
                <th>DCN</th>
                <th>Amount</th>
                <th>Invoice No</th>
                <th>Gateway</th>
            </tr>
            </thead>
            <tbody id="transaction_info_table_body">

            </tbody>
        </table>
    </div>


    <div id="transaction_payment_info_block">
        <h4 class="uk-text-center uk-margin-top">Entries in Transaction Payment Info table</h4>
        <table id="transaction_payment_info_table" class="uk-table uk-table-hover uk-table-divider">
            <thead>
            <tr>
                <th>ID</th>
                <th>Created Date</th>
                <th>Transaction ID</th>
                <th>Payment ID</th>
                <th>Amount</th>
                <th>Payment Gateway status</th>
                <th>Payment Gateway description</th>
                <th>Payment Mode</th>
                <th>PgTransId</th>
                <th>Remarks</th>
                <th>Invoice Number</th>
                <th>Settlement Procedure</th>
            </tr>
            </thead>
            <tbody id="transaction_payment_info_table_body">

            </tbody>
        </table>
    </div>

    <div id="unsettled_orders_modal" class="uk-modal-container" uk-modal>
        <div class="uk-modal-dialog uk-modal-body">
            <table class="uk-table uk-table-striped">
                <thead>
                <tr>
                    <th>Unsettled Order Number(EGRN/DCN)</th>
                    <th>DCN</th>
                    <th>Amount</th>
                    <th>Order Date</th>
                    <th>Transaction Date</th>
                    <th>Garments Count</th>

                </tr>
                </thead>
                <tbody id="unsettled_orders_modal_body">

                </tbody>
            </table>
        </div>
    </div>

</div>

