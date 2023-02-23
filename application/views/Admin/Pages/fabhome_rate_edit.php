<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/ratchet/2.0.2/css/ratchet.css" rel="stylesheet"/>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/jquery.blockUI.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://malsup.github.io/jquery.blockUI.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<div id="page-1-container" class="uk-width-1-1@s uk-width-4-5@m uk-width-5-6@m uk-margin-auto-left content_wrapper uk-padding">
<a href="<?php echo base_url('console/show_fabhome_rates');?>">Back</a><br><br>
<div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">
    <h3 class="uk-heading-divider uk-text-center">Update Rate Details</h3>
    <div class="uk-grid" id="grid">
        <form class="uk-form-horizontal uk-width-expand">
            <div class="uk-margin">
                <label class="uk-form-label" for="form-horizontal-text">Service Type:</label>
                <div class="uk-form-controls">
                    <select class="uk-select" id="service_type">
                        <option value="Deep Cleaning" <?php if(strtoupper($rates[0]['type']) == "DEEP CLEANING"): ?> selected<?php endif; ?>>Deep Cleaning</option>
                        <option value="Office Cleaning" <?php if(strtoupper($rates[0]['type']) == "OFFICE CLEANING"): ?> selected<?php endif; ?>>Office Cleaning</option>
                        <option value="Home Cleaning" <?php if(strtoupper($rates[0]['type']) == "HOME CLEANING"): ?> selected<?php endif; ?>>Home Cleaning</option>
                    </select>
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="form-horizontal-text">Service:</label>

                <div class="uk-form-controls">
                    <input type="text" id="service" class="uk-input" value="<?php echo $rates[0]['service'];?>">
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="form-horizontal-text">Category:</label>
                <div class="uk-form-controls">
                    <input type="text" id="category" class="uk-input" value="<?php echo $rates[0]['category'];?>">
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="form-horizontal-text">Input UOM:</label>
                <div class="uk-form-controls">
                    <select class="uk-select" id="input_uom">
                        <option value="NOS" <?php if($rates[0]['input_uom'] == "NOS"): ?> selected<?php endif; ?>>NOS</option>
                        <option value="SQFT" <?php if($rates[0]['input_uom'] == "SQFT"): ?> selected<?php endif; ?>>SQFT</option>
                    </select>
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="form-horizontal-text">Rate Per UOM:</label>
                <div class="uk-form-controls">
                    <input type="number" id="rate_per_uom" class="uk-input" min="1" value="<?php echo $rates[0]['rate_per_uom'];?>">
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="form-horizontal-text">Discount  In Percentage:</label>
                <div class="uk-form-controls">
                    <input type="number" id="discount_percentage" class="uk-input" min="1" max="100"  value="<?php if ($rates[0]['discount_percentage'] != 0) {echo $rates[0]['discount_percentage'];}?>">
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="form-horizontal-text">Discount In Rupees:</label>
                <div class="uk-form-controls">
                    <input type="number" id="discount_value" class="uk-input" min="1" value="<?php if($rates[0]['discount_value'] != 0)echo $rates[0]['discount_value'];?>">
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="form-horizontal-text">Tax  In Percentage:</label>
                <div class="uk-form-controls">
                    <input type="number" id="tax_percentage" class="uk-input" min="1" max="100" value="<?php if ($rates[0]['tax_percentage'] != 0) {echo $rates[0]['tax_percentage'];}?>">
                </div>
            </div>
            <div class="uk-margin" >
                <label class="uk-form-label" for="form-horizontal-text">ExpiryDate:</label>
                <div class="uk-form-controls">
                    <input type="date" min="<?= date('Y-m-d'); ?>" name="expiry_date" id="expiry_date"  class="uk-textarea" value="<?php echo $rates[0]['expiry'];?>">
                </div>
            </div>
        <div class="uk-card-footer">
        <p>
                <button onclick="update_rate(<?php echo $rates[0]['Id'];?>)"
                        id="save"
                        class="uk-button uk-button-default">Save changes
                </button>
            </p>
        </div>
        </div>
    </div>
    </div>
</div>
<style>
    img{
        width:500px;

    }
</style>
<script>
     $(document).ready(function() { 
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
            if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48  || keyCode > 57)) { 
            return false;
            }
        });
    });
    function update_rate(id) {
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
                    url: "<?php echo base_url(); ?>" + "index.php/console_controller/update_rate",
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
                        expiry_date: expiry_date,
                        Id: id
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
                                message: 'Successfully updated',
                                status: 'success',
                                pos: 'bottom-center',
                                timeout: 1000
                            });
                            

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
       

    }
</script>