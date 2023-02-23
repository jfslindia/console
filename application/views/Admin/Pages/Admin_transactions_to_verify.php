<!-- * Created by PhpStorm.
 * User: targetman
 * Date: 4/5/2017
 * Time: 1:43 PM
 */-->


<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

?>

<div class="uk-width-1-1@s uk-width-4-5@m uk-width-5-6@m uk-margin-auto-left content_wrapper uk-padding"
     ng-controller="payments" block-ui="main">

    <div id="page-1-container">

        <div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">

            <!--uk-width-1-2@l uk-width-1-2@m uk-width-1-2@s-->
            <h3 class="uk-heading-divider uk-text-center">Transactions to verify</h3>


            <div class="uk-grid uk-child-width-1-4@m">
                <div></div>
                <input type="text" class="uk-input" placeholder="Select a starting date" id="from_date">
                <button class="uk-button uk-button-default" ng-click="get_transactions()">Get transactions</button>
                <div></div>
            </div>
            <p id="date_info" class="uk-text-center uk-text-warning">Last 100 transactions</p>
            <hr class="uk-divider-icon">

            <div class="uk-overflow-auto">
                <table id="transactions" class="display uk-table uk-table-hover" style="width:100%">
                    <thead>
                    <tr>
                        <th>Transaction Date</th>
                        <th>Customer Code</th>
                        <th>EGRN</th>
                        <th>DCN</th>
                        <th>Verified</th>


                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Transaction Date</th>
                        <th>Customer Code</th>
                        <th>EGRN</th>
                        <th>DCN</th>
                        <th>Verified</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div>
            <input type="text" id="filter_search" class="uk-input uk-hidden"
                   placeholder="Type any text to filter the result...">
        </div>
    </div>

</div>

<div id="transaction_details_modal" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div id="transaction_details_modal_content">
            <div id="transaction_details_modal_content_table"></div>
            <div id="transactions_details_check_pg"></div>
            <div id="mobk_response" class="uk-text-center uk-text-primary" style="font-weight: 600;"></div>
        </div>
    </div>
</div>


