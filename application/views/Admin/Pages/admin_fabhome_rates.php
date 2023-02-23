<?php
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 11/22/2018
 * Time: 10:06 AM
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');


?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/ratchet/2.0.2/css/ratchet.css" rel="stylesheet"/>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/jquery.blockUI.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.js"></script>
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://malsup.github.io/jquery.blockUI.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<div id="page-1-container"
     class="uk-width-1-1@s uk-width-4-5@m uk-width-4-5@m uk-margin-auto-left content_wrapper uk-padding">

    <div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">

        <!--uk-width-1-2@l uk-width-1-2@m uk-width-1-2@s-->
        <button id="download_options" type="button" class = "w3-button w3-sand" onclick="window.location='<?php echo site_url('console/fabhome_orders');?>'">VIEW CART</button>
        <h3 class="uk-heading-divider uk-text-center">New Rates</h3>
        <div class="uk-grid" id="grid">

            <form class="uk-form-horizontal uk-width-expand">
                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Service Type:</label>
                    <div class="uk-form-controls">
                        <select class="uk-select" id="service_type">
                            <option value="Deep Cleaning">Deep Cleaning</option>
                            <option value="Office Cleaning">Office Cleaning</option>
                            <option value="Home Cleaning">Home Cleaning</option>
                        </select>
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Service:</label>

                    <div class="uk-form-controls">
                        <input type="text" id="service" class="uk-input">
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Category:</label>
                    <div class="uk-form-controls">
                        <input type="text" id="category" class="uk-input">
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Input UOM:</label>
                    <div class="uk-form-controls">
                        <select class="uk-select" id="input_uom">
                            <option value="NOS">NOS</option>
                            <option value="SQFT">SQFT</option>
                        </select>
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Rate Per UOM:</label>
                    <div class="uk-form-controls">
                        <input type="number" id="rate_per_uom" class="uk-input"min="1">
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Discount  In Percentage:</label>
                    <div class="uk-form-controls">
                        <input type="number" id="discount_percentage" class="uk-input" min="1" max="100">
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Discount In Rupees:</label>
                    <div class="uk-form-controls">
                        <input type="number" id="discount_value" class="uk-input" min="1">
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Tax  In Percentage:</label>
                    <div class="uk-form-controls">
                        <input type="number" id="tax_percentage" class="uk-input"min="1" max="100">
                    </div>
                </div>
                
                <div class="uk-margin" >
                    <label class="uk-form-label" for="form-horizontal-text">Expiry:</label>
                    <div class="uk-form-controls">
                        <input type="date" min="<?= date('Y-m-d'); ?>" name="expiry_date" id="expiry_date"  class="uk-input">
                    </div>
                </div>            
                <div class="uk-margin" id="add_rate">
                    <button id="add_rate" type="button"
                            class="uk-button uk-button-primary uk-width-1-1">
                        ADD 
                    </button>
                </div> 
                <button id="download_options" type="button" class = "w3-button w3-sand" onclick="window.location='<?php echo site_url('console/show_fabhome_rates');?>'">SHOW RATES</button>                                
            </form>
		   
        </div>
        
    </div>
    
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/jquery.blockUI.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#office_cleaning_rates').hide();
        $('#home_cleaning_rates').hide(); 
        $("#discount_value").keypress(function(e){
            var keyCode = e.which;
            /*
            8 - (backspace)
            32 - (space)
            48-57 - (0-9)Numbers
            */
            if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) { 
            return false;
            }
        });
        $("#rate_per_uom").keypress(function(e){
            var keyCode = e.which;
            if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) { 
            return false;
            }
        });
        $("#discount_percentage").keypress(function(e){
            var keyCode = e.which;
            if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) { 
            return false;
            }
        });
        $("#tax_percentage").keypress(function(e){
            var keyCode = e.which;
            if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) { 
            return false;
            }
        });
    });
    function editable(id){
        if ($('#' + id + '_save').hasClass('uk-hidden')) {
            $('#' + id + '_save').removeClass('uk-hidden');
        }
    }
    $('#add_rate').click(function () {
            var service_type = $('#service_type').val();
            var service = $('#service').val();
            var category = $('#category').val();
            var input_uom = $('#input_uom').val();
            var rate_per_uom = $('#rate_per_uom').val();
            var discount_perc = $('#discount_percentage').val();
            var discount = $('#discount_value').val();
            var tax = $('#tax_percentage').val();
            var expiry_date = $('#expiry_date').val();           
            if(discount_perc != "" && discount != ""){
                UIkit.notification({
                    message: 'Do not add discount in percentage or discount as amount together ',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
                return false;
            } 
            if (service!= ''  && input_uom != '' && rate_per_uom != '' &&  expiry_date != '') {
                if(rate_per_uom == 0){
                    UIkit.notification({
                        message: 'Do not add Rate per UOM  as zero ',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });
                    return false;
                }
                if(discount_perc == ""){
                    discount_perc = 0;
                }else if(discount_perc == "0"){
                    UIkit.notification({
                        message: 'Do not add discount percentage  as zero ',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });
                    return false;
                }
                if(discount == ""){
                    discount = 0;
                }else if(discount == "0"){
                    UIkit.notification({
                        message: 'Do not add discount  zero ',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });
                    return false; 
                }
                if(tax == ""){
                    tax = 0;
                }else if(tax == "0"){
                    UIkit.notification({
                        message: 'Do not add tax  percentage as zero ',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });
                    return false;
                }
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>" + "index.php/console_controller/add_rates",
                    dataType: 'json',
                    data: {
                        service_type: service_type,
                        service: service,
                        category: category,
                        input_uom: input_uom,
                        rate_per_uom: rate_per_uom,
                        discount_perc: discount_perc,
                        discount: discount,
                        tax: tax,
                        expiry_date: expiry_date
                    },
                    xhr: function () {
                        var xhr = new window.XMLHttpRequest();
                        //Download progress
                        xhr.addEventListener("progress", function (evt) {
                        }, false);
                        return xhr;
                    },
                    beforeSend: function () {
                        $.blockUI({

                            message: '<h1>Please wait...</h1>'

                        });
                    },
                    complete: function () {
                        $.unblockUI();
                    },
                    success: function (res) {
                        if (res.status == 'success') {
                            UIkit.notification({
                                message: 'Successfully saved',
                                status: 'success',
                                pos: 'bottom-center',
                                timeout: 1000
                            });

                            setTimeout(function () {
                                location.reload();
                            }, 1500);

                        } else {
                            UIkit.notification({
                                message: res.message,
                                status: 'danger',
                                pos: 'bottom-center',
                                timeout: 1000
                            });
                        }


                    }
                });
            } else {     
                    UIkit.notification({
                        message: 'Please add a service type ,service,input UOM, rate per UOM, and expity date',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });
            }
       

    });
    $("#search_data").change(function(){
        var service_type = $('#search_data').val();
        if(service_type == "Deep Cleaning"){
            $('#deep_cleaning_rates').show();
            $('#office_cleaning_rates').hide();
            $('#home_cleaning_rates').hide();  
        }else if(service_type == "Office Cleaning"){
            $('#deep_cleaning_rates').hide();
            $('#office_cleaning_rates').show();
            $('#home_cleaning_rates').hide();
        }else{
            $('#deep_cleaning_rates').hide();
            $('#office_cleaning_rates').hide();
            $('#home_cleaning_rates').show();
        }
    });
</script>
<style>
 table {
  /* border-collapse: collapse; */
  width: 100%;
  
}
th {
  background-color: skyblue;
  color: white;
}
tr:nth-child(even){background-color: #f2f2f2}
th, td {
  text-align: left;
  padding: 16px;
}
</style>