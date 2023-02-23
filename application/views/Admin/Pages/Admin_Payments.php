<!-- * Created by PhpStorm.
 * User: targetman
 * Date: 4/5/2017
 * Time: 1:43 PM
 */-->


<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

?>
<!--styles are here-->

<style>
    input {
        text-align: center;
    }


</style>


<!--scripts are here-->

<script>
    var count_payment_details=0;
    var payment_details_load_finished=false;
    var total_rows=0;
</script>

<script xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">


    $(document).ready(function () {
        $('#payment_id_block').hide();

        $('#file_link_block').hide();

        $(function () {
            $("#datepicker-1").datepicker({maxDate: new Date(), dateFormat: 'yy-mm-dd'});
            $("#datepicker-2").datepicker({maxDate: new Date(), dateFormat: 'yy-mm-dd'});
        });

        $('input[type=radio][name=specific_date]').change(function () {
            if ($(this).val() == 'true') {
                $('#specific_date_block').show();
            } else {
                $('#specific_date_block').hide();
                $("#datepicker-1").val('');
                $("#datepicker-2").val('');
            }
        })

        $('input[type=radio][name=search_criteria]').change(function () {
            if ($(this).val() == 'customer_code') {
                $('#customer_code_block').show();
                $('#payment_id_block').hide();
            } else if($(this).val() == 'payment_id') {
                $('#payment_id_block').show();
                $('#customer_code_block').hide();

            }else{
                $('#payment_id_block').hide();
                $('#customer_code_block').hide();
            }
        })


        // $('#loading').hide();
        $('#progressElem').hide();


        $("#query_button").click(function () {

                $('#file_link_block').hide();

                total_rows=0;

                var specific_date = $("input[type=radio][name='specific_date']:checked").val();

                if (specific_date == 'true') {

                    var start_date = $("#datepicker-1").val() + ' ' + $('#start_date_time').val() + ':00:00' + '.000';
                    var end_date = $("#datepicker-2").val() + ' ' + $('#end_date_time').val() + ':00:00' + '.000';
                } else {
                    var start_date = null;
                    var end_date = null;
                }




                if (specific_date == 'true' && start_date == ' 00:00:00.000' || end_date == ' 00:00:00.000') {
                    UIkit.notification({
                        message: 'Please provide the specific dates',
                        status: 'warning',
                        pos: 'bottom-center',
                        timeout: 1000
                    });

                } else {


                    event.preventDefault();
                    //console.log('success'+start_date);
                    load_payment_details(0);


                }
            }
        )
        ;
    });

    function load_payment_details(count){

        var specific_date = $("input[type=radio][name='specific_date']:checked").val();

        if (specific_date == 'true') {

            var start_date = $("#datepicker-1").val() + ' ' + $('#start_date_time').val() + ':00:00' + '.000';
            var end_date = $("#datepicker-2").val() + ' ' + $('#end_date_time').val() + ':00:00' + '.000';
        } else {
            var start_date = null;
            var end_date = null;
        }


        var customer_code = '';
        var payment_id = '';

        if ($('input[type=radio][name=search_criteria]:checked').val() == 'customer_code') {
            customer_code = $('#customer_code').val();
            payment_id = '';

        } else if($('input[type=radio][name=search_criteria]:checked').val() == 'payment_id') {
            customer_code = '';
            payment_id = $('#payment_id').val();

        }else{
            customer_code = '';
            payment_id = '';
        }

        if($('#generate_report').is(":checked")){
            var generate_report=1;
        }else{
            var generate_report=0;
        }

        jQuery.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>" + "index.php/admin_controller/get_payment_details",
            dataType: 'json',
            data: {
                start_date: start_date,
                end_date: end_date,
                customer_code: customer_code,
                payment_id: payment_id,
                count:count,
                generate_report:generate_report
            },
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                //Download progress
                xhr.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        progressElem.value += Math.round(percentComplete * 100);
                    }
                }, false);
                return xhr;
            },
            beforeSend: function () {
                // $('#loading').show();
                $('#progressElem').show();
            },
            complete: function () {
                // $("#loading").hide();
                $('#progressElem').hide();
            },
            success: function (res) {
                if (res.status == "success") {




                    if ($('#filter_search').hasClass('uk-hidden')) {
                        $('#filter_search').removeClass('uk-hidden');
                    }


                    // Show Entered Value
                    jQuery("div#res").addClass("uk-animation-slide-right").hide();
                    jQuery("div#res").addClass("uk-animation-slide-right").show();

                    $('#generate_excel_button').removeClass('uk-hidden');
                    $('#generate_excel_button').addClass('uk-animation-scale-up');
                    $('#generate_excel_button').addClass('uk-transform-origin-bottom-center');


                    UIkit.notification({
                        message: 'Done',
                        status: 'success',
                        pos: 'bottom-center',
                        timeout: 1000
                    });


                    if ($('#result-table').hide()) {
                        $('#result-table').show();
                    }
                    //$('#result-table-body').html('');


                    if(res.payment_details.length>0) {

                        if(res.report_file){
                            $('#file_link_block').html('<p class="uk-margin-top uk-text-center"><a href="'+res.report_file+'" uk-icon="download">Download the Report</a></p>')
                            $('#file_link_block').show();
                        }

                        $('#load_more_details').show();
                        $('#filter_search').show();

                        $('#clear_button_block').removeClass('uk-hidden');



                            count_payment_details = count_payment_details + 1;



                        for (var i = 0; i < res.payment_details.length; i++) {

                           total_rows++;

                            if (res.payment_details[i]['PaymentAmount'] != null) {
                                $('#result-table-body').append('<tr>' +
                                    '<td>' + total_rows + ' </td>' +
                                    '<td>' + res.payment_details[i]['TransactionDate'] + ' </td>' +
                                    '<td>' + res.payment_details[i]['CustomerCode'] + ' </td>' +
                                    
                                    '<td>' + res.payment_details[i]['EGRNNo'] + ' </td>' +
                                    '<td>' + res.payment_details[i]['DCNo'] + ' </td>' +
                                    '<td>' + res.payment_details[i]['PaymentID'] + ' </td>' +
                                    '<td>' + res.payment_details[i]['PaymentMode'] + ' </td>' +
                                    '<td>' + res.payment_details[i]['CouponCode'] + ' </td>' +
                                    '<td>' + res.payment_details[i]['PaymentAmount'] + ' </td>' +
                                    '<td>' + res.payment_details[i]['PaymentGatewayStatusDescription'] + ' </td>' +
                                    '<td>' + res.payment_details[i]['InvoiceNo'] + ' </td>' +
                                    '<td>' + res.payment_details[i]['BranchCode'] + ' </td>' +
                                    '<td>' + res.payment_details[i]['Remarks'] + ' </td>' +

                                    '</tr>'
                                )
                            } else {
                                $('#result-table-body').append('<tr>' +
                                    '<td>' + total_rows + ' </td>' +
                                    
                                    '<td>' + res.payment_details[i]['TransactionDate'] + ' </td>' +
                                    '<td>' + res.payment_details[i]['CustomerCode'] + ' </td>' +
                                    '<td>' + res.payment_details[i]['EGRNNo'] + ' </td>' +
                                    '<td>' + res.payment_details[i]['DCNo'] + ' </td>' +
                                    '<td>' + res.payment_details[i]['PaymentID'] + ' </td>' +
                                    '<td>' + res.payment_details[i]['PaymentMode'] + ' </td>' +
                                    '<td>' + res.payment_details[i]['CouponCode'] + ' </td>' +
                                    '<td>' + res.payment_details[i]['PaymentAmount'] + ' </td>' +
                                    '<td>' + res.payment_details[i]['PaymentGatewayStatusDescription'] + ' </td>' +
                                    '<td>' + res.payment_details[i]['InvoiceNo'] + ' </td>' +
                                    '<td>' + res.payment_details[i]['BranchCode'] + ' </td>' +
                                    '<td>' + 'User cancelled the transaction' + ' </td>' +

                                    '</tr>'
                                )
                            }
                        }
                    }else{
                        payment_details_load_finished=true;
                        $('#load_more_details').hide();
                    }

                }
                else if (res.status == 'failed') {

                    if ($('#filter_search').hasClass('uk-hidden')) {

                    } else {
                        $('#filter_search').addClass('uk-hidden');
                    }



                    UIkit.notification({
                        message: 'No more results available',
                        status: 'warning',
                        pos: 'bottom-center',
                        timeout: 1000
                    });

                    $('#load_more_details').hide();
                }
            }
        });
    }

/*
    $(document).scroll(function(e){


        var scrollHeight = $(document).height();
        var scrollPosition = Math.round($(window).height() + $(window).scrollTop());

        if ((scrollHeight - scrollPosition) / scrollHeight == 0) {

            *//*if($(window).scrollTop() + $(window).height() >= $(document).height()) {*//*

                if(payment_details_load_finished==false){


                    load_payment_details(count_payment_details);
                }else{

                }



        }
    });*/


    function confirm_clear(){
        UIkit.modal.confirm('Do you want clear the current result?').then(function () {
            $('#result-table-body').html('');
            $('#res').hide();
            $('#filter_search').hide();
            $('#clear_button_block').addClass('uk-hidden');
            $('#file_link_block').hide();
            total_rows=0;
            count_payment_details=0;
        }, function () {
            $('#clear_button_block').addClass('uk-hidden');
        });
    }

</script>

<div class="uk-width-1-1@s uk-width-4-5@m uk-margin-auto-left content_wrapper uk-margin-top uk-padding uk-padding-remove-top">

<div id="page-1-container">

    <div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">

        <!--uk-width-1-2@l uk-width-1-2@m uk-width-1-2@s-->
        <h3 class="uk-heading-divider uk-text-center">Payment Transaction Details</h3>

        <div class="uk-grid">

            <form class="uk-form-horizontal uk-margin-large uk-align-center">

                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Date</label>

                    <div class="uk-form-controlls">
                        <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                            <label><input class="uk-radio" type="radio" name="specific_date" value="true" checked>
                                Specific Date</label>
                            <label><input class="uk-radio" type="radio" name="specific_date" value="false"> No Specific
                                Date</label>
                        </div>
                    </div>
                </div>

                <div id="specific_date_block">
                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-horizontal-text">Start Date</label>

                        <div class="uk-form-controls">
                            <div class="uk-flex-inline">
                                <input type="text" class="uk-input" id="datepicker-1">
                                <select class="uk-select" id="start_date_time">
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

                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-horizontal-text">End Date</label>

                        <div class="uk-form-controls">
                            <div class="uk-flex-inline">

                                <input type="text" class="uk-input" id="datepicker-2">
                                <select class="uk-select" id="end_date_time">
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

                </div>

                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Search Criteria</label>

                    <div class="uk-form-controlls">
                        <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                            <label><input class="uk-radio" type="radio" name="search_criteria" value="customer_code"
                                          checked>
                                Search with Customer Code</label>
                            <label><input class="uk-radio" type="radio" name="search_criteria" value="payment_id">
                                Search with Payment ID</label>
                            <label><input class="uk-radio" type="radio" name="search_criteria" value="all">
                                Search all</label>
                        </div>
                    </div>
                </div>


                <div id="criteria_block">
                    <div id="customer_code_block" class="uk-margin">
                        <div class="uk-form-label">Customer Code</div>
                        <div class="uk-form-controls uk-form-controls-text">
                            <input class="uk-input" type="text" placeholder="Enter the customer code..."
                                   id="customer_code">
                        </div>
                    </div>
                    <div id="payment_id_block" class="uk-margin">
                        <div class="uk-form-label">Payment ID</div>
                        <div class="uk-form-controls uk-form-controls-text">
                            <input class="uk-input" type="text" placeholder="Enter the Payment ID..."
                                   id="payment_id">
                        </div>
                    </div>
                </div>


                <div class="uk-margin">
                    <div class="uk-form-label">Generate Report</div>
                    <div class="uk-form-controls uk-form-controls-text">
                        <label><input class="uk-checkbox" type="checkbox" id="generate_report"> Generate Excel</label>
                    </div>

                </div>


                <div class="uk-margin">


                    <button id="query_button"
                            class="uk-button uk-width-1-1 uk-button-primary uk-display-block uk-margin-small-top uk-float-right">
                        Get Details
                    </button>
                </div>

                <div id="clear_button_block" class="uk-margin uk-hidden">


                    <button id="clear_result" onclick="confirm_clear()"
                            class="uk-button uk-width-1-1 uk-button-primary uk-display-block uk-margin-small-top uk-float-right">
                        Clear the result
                    </button>
                </div>

                <div id="file_link_block">

                </div>

                <div class="uk-clearfix"></div>


                <!-- <div uk-spinner id="loading"></div>  -->
                <progress class="uk-progress" value="0" max="100" id="progressElem"></progress>

            </form>
        </div>
    </div>
    <div>
        <input type="text" id="filter_search" class="uk-input uk-hidden"
               placeholder="Type any text to filter the result...">
    </div>
</div>

<div id="res" class="uk-grid uk-overflow-auto uk-align-center"
     style="display: none">

    <table class="uk-table uk-table-hover" id="result-table">
        <thead>
        <tr>
            <th>#</th>
            <th id="head-1">Date</th>
            <th>Customer Code</th>
            <th>EGRN</th>
            <th>DCN</th>
            <th>Payment ID</th>
            <th>Payment Mode</th>
            <th>Coupon Code</th>
            <th>Payment Amount</th>
            <th>Payment Gateway Status</th>
            <th>Invoice Number</th>
            <th>BranchCode</th>
            <th>Remarks</th>
        </tr>
        </thead>
        <tbody id="result-table-body">

        <!--content-->

        </tbody>
    </table>

    <div>
        <button id="load_more_details" class="uk-width-1-1 uk-button uk-button-default" onclick="load_payment_details(count_payment_details)">Load More</button>
    </div>
</div>


</div>


<script>
    $("#filter_search").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#result-table-body tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
</script>

