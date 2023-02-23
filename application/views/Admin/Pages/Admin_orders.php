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

<script xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">


    $(document).ready(function () {

        $('#download_link').hide();
        $('#generate_excel_button').addClass('uk-hidden');

         $(function () {
        $("#datepicker-1").datepicker({maxDate: new Date(), dateFormat: 'yy-mm-dd'});
        $("#datepicker-2").datepicker({maxDate: new Date(), dateFormat: 'yy-mm-dd'});
    });


        // $('#loading').hide();
        $('#progressElem').hide();



        $("#query_button").click(function () {

            $('#download_link').hide();
            $('#generate_excel_button').addClass('uk-hidden');
            $('#generate_excel_button').removeClass('uk-animation-scale-down');
            $('#generate_excel_button').removeClass('uk-animation-reverse');
            $('#generate_excel_button').addClass('uk-animation-scale-up');
            $('#generate_excel_button').addClass('uk-transform-origin-bottom-center');

            var start_date = $("#datepicker-1").val()+' '+$('#start_date_time').val()+':00:00'+'.000';
            var end_date = $("#datepicker-2").val()+' '+$('#end_date_time').val()+':00:00'+'.000';

            var locationValue = $("#location_option").val();
            var brandValue = $("#brand_option").val();
            var coupon_details = $('#coupon_details').val();
            var order_details = $('#order_details').val();
            event.preventDefault();
            //console.log('success'+start_date);
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/console_controller/get_orders_details",
                dataType: 'json',
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    location_value: locationValue,
                    brand_value: brandValue,
                    coupon_details: coupon_details,
                    order_details: order_details
                },
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    //Download progress
                    xhr.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                    var percentComplete = evt.loaded / evt.total;
                    progressElem.value+=Math.round(percentComplete * 100);
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
                        

                        // Show Entered Value
                        jQuery("div#res").addClass("uk-animation-slide-right").hide();
                        jQuery("div#res").addClass("uk-animation-slide-right").show();

                        $('#generate_excel_button').removeClass('uk-hidden');
                        $('#generate_excel_button').addClass('uk-animation-scale-up');
                        $('#generate_excel_button').addClass('uk-transform-origin-bottom-center');

                        //Generate Excel Ajax Call
                        $('#generate_excel_button').click(function(){

                            event.preventDefault();

                            jQuery.ajax({
                            type: "POST",
                            url: "<?php echo base_url(); ?>" + "index.php/console_controller/get_orders_details_excel",
                            dataType: 'json',
                            data: {
                                res : res.result
                            },
                            xhr: function () {
                                var xhr = new window.XMLHttpRequest();
                                //Download progress
                                xhr.addEventListener("progress", function (evt) {
                                if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                progressElem.value+=Math.round(percentComplete * 100);
                                
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
                            success: function (result) {
                                
                                      
                                    $('#generate_excel_button').removeClass('uk-animation-scale-up');
                                    $('#generate_excel_button').removeClass('uk-transform-origin-bottom-center');
                                    $('#generate_excel_button').addClass('uk-animation-scale-down');
                                    $('#generate_excel_button').addClass('uk-animation-reverse');
                                    

                                    $('#download_link').attr('href','<?php echo base_url(); ?>'+result.link);
                                    $('#download_link').show();
                                                           

                            },error: function(res){
                                //console.log(res);
                            }

                            });


                        });

                         UIkit.notification({
						    message: 'Done',
						    status: 'success',
						    pos: 'bottom-center',
						    timeout: 1000
						});

                        


                         if($('#result-table').hide()){
                            $('#result-table').show();
                        }
                        $('#result-table-body').html('');

                        var coupon_code = [];

                        for (var i = 0; i < res.result.length; i++){

                            if(res.result[i]['coupon']==""){
                                 coupon_code[i] = "NILL";
                            }else{
                                 coupon_code[i] = res.result[i]['coupon'];
                            }

                        }

                        for (var i = 0; i < res.result.length; i++) {

                        	var start=0;
                        	if(i==0)
                        		start = i+1;
                        	else
                        		start = i+1;

                            $('#result-table-body').append('<tr>' +
                                '<td>' + start  + ' </td>' +
                                '<td>' + res.result[i]['date'] + ' </td>' +
                                '<td>' + res.result[i]['id'] + ' </td>' +
                                '<td>' + res.result[i]['user_id'] + ' </td>' +
                                '<td>' + res.result[i]['name'] + ' </td>' +
                                '<td>' + res.result[i]['mobile_number'] + ' </td>' +
                                '<td>' + res.result[i]['email'] + ' </td>' +
                                '<td>' + res.result[i]['BrandCode'] + ' </td>' +
                                '<td>' + res.result[i]['service_type'] + ' </td>' +
                                '<td>' + res.result[i]['status'] + ' </td>' +
                                '<td>' + res.result[i]['wash_type'] + ' </td>' +
                                '<td>' + res.result[i]['pick_up_date'] + ' </td>' +
                                '<td>' + res.result[i]['pick_up_address'] + ' </td>' +
                                '<td>' + res.result[i]['pincode'] + ' </td>' +
                                '<td>' + res.result[i]['location'] + ' </td>' +
                                '<td>' + coupon_code[i] + ' </td>' +
                                '<td>' + res.result[i]['pick_up_source'] + ' </td>' +
                                '<td>' + res.result[i]['Area'] + ' </td>' +
                                '<td>' + res.result[i]['RouteCode'] + ' </td>' +
                                '<td>' + res.result[i]['remakrs'] + ' </td>' +
                                '<td>' + res.result[i]['campaign_info'] + ' </td>'+
                           
                                '</tr>'
                            )
                        }

                       }
                    else if (res.status == 'failed') {
                        
                       $('#result-table').hide();
                       $('#result-table-body').html('');
                       UIkit.notification({
						    message: 'No such result',
						    status: 'warning',
						    pos: 'bottom-center',
						    timeout: 1000
						});
                    }
                }
            });


        });


    });


</script>


<div id="page-1-container" class="uk-width-1-1@s uk-width-4-5@m uk-width-5-6@m uk-margin-auto-left content_wrapper uk-padding">

    <div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">

        <!--uk-width-1-2@l uk-width-1-2@m uk-width-1-2@s-->
        <h3 class="uk-heading-divider uk-text-center">Orders Details</h3>

        <div class="uk-grid">

            <form class="uk-form-horizontal uk-margin-large uk-align-center">

                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Start Date</label>

                    <div class="uk-form-controls">
                        <div class="uk-flex-inline">
                        <input type="text" class="uk-input" id="datepicker-1">
                        <select class="uk-select" id="start_date_time">
                            <?php for($i=0;$i<=9;$i++) { ?>
                                <option value="<?php echo '0'.$i; ?>"><?php echo '0'.$i.':00'; ?></option>
                            <?php } ?>
                            <?php for($i=10;$i<=23;$i++) { ?>
                                <option value="<?php echo $i; ?>"><?php echo $i.':00'; ?></option>
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
                                <?php for($i=0;$i<=9;$i++) { ?>
                                    <option value="<?php echo '0'.$i; ?>"><?php echo '0'.$i.':00'; ?></option>
                                <?php } ?>
                                <?php for($i=10;$i<=23;$i++) { ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i.':00'; ?></option>
                                <?php } ?>
                            </select>

                        </div>
                    </div>
                </div>

                <div class="uk-margin">
                    <div class="uk-form-label">Brand</div>
                    <div class="uk-form-controls uk-form-controls-text">
                        <select class="uk-select" id="brand_option">
		                	<option value="all">All</option>
		                    <option value="Fabricspa">Fabricspa</option>
		                    <option value="Click2Wash">Click2Wash</option>
                    	</select>
                    </div>
                </div>

                <div class="uk-margin">
                <div class="uk-form-label">Location</div>
                    <div class="uk-form-controls uk-form-controls-text">
                        <select class="uk-select" id="location_option">
                			<option value="all">All</option>
                    		<option value="Bangalore">Bangalore</option>
                    		<option value="Mumbai">Mumbai</option>
                    	</select>
                    </div>
                </div>

                 <div class="uk-margin">
                 <div class="uk-form-label">Coupon Details</div>
                <select class="uk-select" id="coupon_details">
                    <option value="all_coupons">All Coupons</option>
                    <option value="with_coupon">With Coupons</option>
                    <option value="without_coupon">Without Coupons</option>
                </select>
                </div>

                 <div class="uk-margin">
                 <div class="uk-form-label">Order Status</div>
                <select class="uk-select" id="order_details">
                    <option value="all_orders">All Orders</option>
                    <option value="active_orders">Active Orders</option>
                    <option value="cancelled_orders">Cancelled Orders</option>
                </select>
                </div>

                <div class="uk-margin">

                    <button id="reset_button"
                            class="uk-button uk-button-primary uk-display-block uk-margin-small-top uk-float-left">
                        Reset
                    </button>

                    <button id="query_button"
                            class="uk-button uk-button-primary uk-display-block uk-margin-small-top uk-float-right">
                        Get Details
                    </button>
                </div>
                <div class="uk-clearfix"></div>

                <div id="generate_excel_button_div" class="uk-width-1-1">
                         <button id="generate_excel_button"
                            class="uk-button uk-button-primary uk-display-block uk-margin-small-top uk-width-1-1">
                        Generate Excel
                    </button> 
                    <a id="download_link" style="display: block; text-align: center;">-->Download The File<--</a>
                </div>

                 <!-- <div uk-spinner id="loading"></div>  -->
                <progress class="uk-progress" value="0" max="100" id="progressElem"></progress>

            </form>
        </div>
    </div>


<div id="res" class="uk-grid uk-overflow-auto uk-align-center uk-width-1-2\@s uk-width-1-3\@m uk-width-1-4\@l" style="display: none; max-height: 700px;">

    <table class="uk-table uk-table-hover" id="result-table">
        <thead>
        <tr>
            <th>#</th>
            <th id="head-1">Date</th>
            <th>Booking ID</th>
            <th>User ID</th>
            <th>Name</th>
            <th>Mobile Number</th>
            <th>Email</th>
            <th>BrandCode</th>
            <th>Service Type</th>
            <th>Status</th>
            <th>Washtype</th>
            <th>Pickup Date</th>
            <th>Pickup Address</th>
            <th>Pincode</th>
            <th>Location</th>
            <th>Coupon</th>
            <th>Pickup_Source</th>
            <th>Area</th>
            <th>RouteCode</th>
            <th>Remarks</th>
            <th>Campaign Info</th>
        </tr>
        </thead>
        <tbody id="result-table-body">

        <!--content-->

        </tbody>
    </table>
</div>

</div>



