<?php
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 8/3/2018
 * Time: 9:08 AM
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');


?>
<script>

    window.onscroll = function () {
        changeMenu()
    }

    function changeMenu() {
        var scrollBarPosition = window.pageYOffset | document.body.scrollTop;

        if (scrollBarPosition >= 1000) {
            document.getElementById('to-top').style.display = "block";
        }
        else {
            document.getElementById('to-top').style.display = "none";
        }
    }

</script>


<div class="uk-width-1-1@s uk-width-4-5@m uk-margin-auto-left content_wrapper uk-padding">
    <div class="page-container">

        <div ng-controller="qa_logs">
            <div class="uk-card uk-card-body uk-card-default">
                <table class="uk-table uk-table-hover uk-table-divider">
                    <div class="uk-margin uk-text-right">

                        <div class="uk-inline">
                            <a class="uk-form-icon" href="#" uk-icon="icon: search"></a>
                            <input class="uk-input " placeholder="Search here..." type="text" ng-model="search">
                        </div>
                    </div>

                    <thead>
                    <tr>
                        <!--<th>Select</th>
                        <th>#</th>-->
                        <th>
                            <a href="#" ng-click="orderByField='name'; reverseSort = !reverseSort">
                                Date <span ng-show="orderByField == 'name'"><span
                                        ng-show="!reverseSort"></span><span ng-show="reverseSort"></span></span>

                        </th>
                        <th>Tag ID</th>


                        <th>Reason</th>

                        <th>Finished By</th>


                    </tr>
                    </thead>
                    <tbody>
                    <!--<tr  ng-repeat="qa_user in qa_users | filter:search">-->
                    <tr ng-repeat="qa_log in results.qa_logs |orderBy:orderByField:reverseSort | filter:search">
                        <!--      <tr  ng-repeat="qa_user in qa_users | filter:search">-->
                       <!-- <td><input type="checkbox" name="qa_log_row" class="uk-checkbox" value="{{qa_log.Id}}">
                        </td>
                        <td>{{serial_number = $index + 1}}</td>-->
                        <td>{{qa_log.CreatedDate}}</td>
                        <td>{{qa_log.TagNo}}</td>
                        <td>{{qa_log.Reason}}</td>
                        <td>{{qa_log.FinishedBy}}</td>

                    </tr>
                    </tbody>
                </table>
            </div>



            <div >
                <canvas id="qa_logs_pie_chart"   width="800" height="150"></canvas>
            </div>

        </div>


    </div>
</div>

<!--</div>-->


<script>
    $('.multipleSelect').fastselect();
</script>

<style>
    .fstElement {
        font-size: 12px;
    }

    .fstMultipleMode .fstControls {
        width: 100%;
    }

    #qa_user_modal > div > div.uk-modal-body.uk-overflow-auto > form > div:nth-child(8) > div > div > div:nth-child(3) > div {
        width: -webkit-fill-available;
    }
    .uk-table th{
        text-align: left !important;
    }
</style>
<!--<div id="spinner" style="z-index: 999999" class="loading-spiner-holder uk-flex uk-flex-middle" data-loading>
    <div class="loading-spiner"><img src="<?php /*echo base_url(); */?>assets/images/loading.gif"/></div>
</div>-->
<a id="to-top" href="#" uk-totop="ratio: 2" uk-scroll="" class="uk-totop uk-icon" style="display: block;">
    <svg width="36" height="20" viewBox="0 0 18 10" xmlns="http://www.w3.org/2000/svg" ratio="2">
        <polyline fill="none" stroke="#000" stroke-width="1.2" points="1 9 9 1 17 9 "></polyline>
    </svg>
</a>


<script>
    $("#filter_text").on("keyup", function () {
        var value = $(this).val().toLowerCase();

        $("#stores_block > label").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
</script>
