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
<?php


?>

<div class="uk-width-1-1@s uk-width-4-5@m uk-margin-auto-left content_wrapper uk-padding">
    <div class="page-container">

        <?php if(sizeof($logs)>0) { ?>

        <div ng-controller="qc_logs">

            <!--<div id="chart_card" class="uk-card uk-card-default uk-card-small uk-card-hover uk-width-auto">
                <div class="uk-card-header">
                    <div class="uk-grid uk-grid-small">
                        <div class="uk-width-auto"><h4>QC Logs</h4></div>
                        <div class="uk-width-expand uk-text-right panel-icons">

                            <a ng-click="qc_logs_close_chart()" href="#" class="uk-icon-link" title="Close"
                               data-uk-tooltip data-uk-icon="icon: close"></a>
                        </div>
                    </div>
                </div>
                <div class="uk-card-body">
                    <div class="chart-container">
                        <canvas id="qc_logs_pie_chart" width="800" height="150"></canvas>
                    </div>
                </div>
            </div>-->


            <div class="uk-card uk-card-body uk-card-default">
                <div class="uk-overflow-auto" style="max-height: 765px;">
                    <table class="uk-table uk-table-hover uk-table-divider" >
                        <div class="uk-margin uk-text-left">

                            <div class="uk-inline">
                                <a class="uk-form-icon" href="#" uk-icon="icon: search"></a>
                                <input class="uk-input " placeholder="Search Tag No" type="text" ng-model="search"
                                       ng-enter="search_tag_id()">
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
                            <th>Customer Code</th>
                            <th>Tag No</th>
                            <th>EGRN</th>

<!--
                            <?php /*for ($i = 0; $i < sizeof($reasons); $i++) { */?>

                                <th><?php /*echo $reasons[$i]['Reason']; */?></th>

                            --><?php /*} */?>


                            <th>Reasons</th>

                            <th>Status</th>

                            <th>More Details</th>



                        </tr>
                        </thead>
                        <tbody id="table_body">


                        <?php  for ($i = 0; $i < sizeof($logs); $i++) {

                            ?>
                            <tr id="table_row">
                                <td><?php echo $logs[$i]['CreatedDate']; ?></td>
                                <td><?php echo $logs[$i]['CustomerCode']; ?></td>
                                <td><?php echo $logs[$i]['TagNo']; ?></td>
                                <td><?php echo $logs[$i]['EGRNNo']; ?></td>

                               <!-- <?php /*for ($j = 0; $j < sizeof($reasons); $j++) { */?>
                                    <td><?php /*echo $logs[$i][$reasons[$j]['Reason']]; */?></td>

                                --><?php /*} */?>

                                <td><?php echo $logs[$i]['Reason'];?></td>


                                <td><?php echo $logs[$i]['Status']; ?></td>

                                <td><a href="<?php echo base_url(); ?>console/qc_log/<?php echo $logs[$i]['TagNo']; ?>">More Details</a></td>
                            </tr>
                        <?php } ?>



                        </tbody>
                    </table>
                </div>

                <div class="uk-margin-top">
                    <!--<button class="uk-button uk-button-default uk-margin-small-right" type="button"
                        uk-toggle="target: #report_modal">Generate Report
                </button>-->
                </div>

                <?php if(isset($pagination)) { ?>

                    <div class="pagination_div uk-margin-top uk-text-right"><?php echo $pagination['links']; ?></div>
                <?php } ?>
            </div>


            <div id="search_modal" uk-modal>
                <div class="uk-modal-dialog uk-modal-body" uk-overflow-auto>
                    <h2 class="uk-modal-title uk-text-center">Search result</h2>

                    <div id="search_result_block">

                    </div>
                    <p class="uk-text-right">
                        <button class="uk-button uk-button-default uk-modal-close" type="button">Close</button>
                    </p>
                </div>
            </div>


            <div id="report_modal" uk-modal>
                <div class="uk-modal-dialog uk-modal-body uk-animation-scale-up " style="width: max-content;">
                    <button class="uk-modal-close-default" type="button" uk-close></button>
                    <h2 class="uk-modal-title">Generate Excel Report</h2>

                    <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                        <label><input class="uk-radio" type="radio" name="radio2" checked ng-model="time_period"
                                      ng-value="true"> Specific time period</label>
                        <label><input class="uk-radio" type="radio" name="radio2" ng-model="time_period"
                                      ng-value="false"> No specific time period</label>

                    </div>
                    <div ng-show="time_period">
                        <div class="uk-margin">
                            <div class="uk-flex-inline">
                                <span style="margin: auto;padding-right: 10px;">Start Date </span>
                                <input type="text" ng-model="start_date" class="uk-input" id="datepicker-1">
                                <span style="margin: auto;padding-right: 10px;">Start Time </span>
                                <select class="uk-select" ng-model="start_time" id="start_date_time"
                                        style="margin-right: 10px;">

                                    <?php for ($i = 0; $i <= 9; $i++) { ?>
                                        <option value="<?php echo '0' . $i; ?>"><?php echo '0' . $i . ':00'; ?></option>
                                    <?php } ?>

                                    <?php for ($i = 10; $i <= 23; $i++) { ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i . ':00'; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="uk-margin">
                            <div class="uk-flex-inline">
                                <span style="margin: auto;padding-right: 10px;">End Date </span>
                                <input type="text" class="uk-input" ng-model="end_date" id="datepicker-2">
                                <span style="margin: auto;padding-right: 10px;">End Time </span>
                                <select class="uk-select" ng-model="end_time" id="end_date_time">
                                    <?php for ($i = 0; $i <= 9; $i++) { ?>
                                        <option value="<?php echo '0' . $i; ?>"><?php echo '0' . $i . ':00'; ?></option>
                                    <?php } ?>
                                    <?php for ($i = 10; $i <= 23; $i++) { ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i . ':00'; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <p class="uk-text-right">
                        <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                        <button class="uk-button uk-button-primary" ng-click="generate_report()"
                                type="button">Generate
                        </button>
                    </p>
                </div>
            </div>


        </div>
        <?php } else { echo 'No logs found'; } ?>
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

    #qc_user_modal > div > div.uk-modal-body.uk-overflow-auto > form > div:nth-child(8) > div > div > div:nth-child(3) > div {
        width: -webkit-fill-available;
    }

    .uk-table th, .uk-table td {
        text-align: center !important;
    }
</style>
<!--<div id="spinner" style="z-index: 999999" class="loading-spiner-holder uk-flex uk-flex-middle" data-loading>
    <div class="loading-spiner"><img src="<?php /*echo base_url(); */ ?>assets/images/loading.gif"/></div>
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

    UIkit.util.on('#report_modal', 'hide', function () {
        $('#report_modal').css('display', 'none');
    });


    $(function () {
        $("#datepicker-1").datepicker({maxDate: new Date(), dateFormat: 'yy-mm-dd'});
        $("#datepicker-2").datepicker({maxDate: new Date(), dateFormat: 'yy-mm-dd'});

    });
</script>
