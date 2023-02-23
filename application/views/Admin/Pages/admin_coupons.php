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
<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/jquery.blockUI.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="http://malsup.github.io/jquery.blockUI.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<div id="page-1-container"
     class="uk-width-1-1@s uk-width-4-5@m uk-width-5-6@m uk-margin-auto-left content_wrapper uk-padding">

    <div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">

        <!--uk-width-1-2@l uk-width-1-2@m uk-width-1-2@s-->

        <h3 class="uk-heading-divider uk-text-center">New Coupons</h3>
        <div class="uk-grid" id="grid">

            <form class="uk-form-horizontal uk-width-expand">
                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">State:</label>

                    <div class="uk-form-controls">
                        <select class="uk-select" id="state">
                            <option value="">Select a state and cities</option>
                        <?php for($i=0;$i<sizeof($states);$i++){?>
                            <option value="<?php echo $states[$i]['statecode'];?>"><?php echo $states[$i]['statename'];?></option>
                        <?php } ?>
                        <option value="all">All</option>
                        </select>
                    </div>
                </div>

                <div class="uk-margin" id="cities">
                <!-- <label class="uk-form-label" for="form-horizontal-text">Cities:</label> -->
                    <div class="uk-form-controls">
                        <?php for($i=0;$i<sizeof($states);$i++){?>
                            
                            <div id="<?php echo $i;?>">
                           <?php for($j=0;$j<sizeof($cities[$i]);$j++){?>
                                <input type="checkbox" class="uk-checkbox" name="cities" id="cities" value="<?php echo $cities[$i][$j]['cityname']; ?>" id="cities"> <?php echo $cities[$i][$j]['cityname']; ?>
                            <?php }?>
                            </div>
                        <?php }?>
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">PromoCode:</label>

                    <div class="uk-form-controls">
                        <input type="text" id="promocode" class="uk-input">
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">DiscountCode:</label>
                    <div class="uk-form-controls">
                        <input type="text" id="discountcode" class="uk-input">
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">App Remarks:</label>
                    <div class="uk-form-controls">
                    <textarea class="uk-textarea" id="app_remarks" rows="3"></textarea></
                    </div>
                </div>
                <div class="uk-margin" id="schedule_block">
                    <label class="uk-form-label" for="form-horizontal-text">ExpiryDate:</label>
                    <div class="uk-form-controls">
                        <input type="date" min="<?= date('Y-m-d'); ?>" name="expiry_date" id="expiry_date"  class="uk-textarea">
                    </div>
                </div>
                <div class="uk-margin" id="add_coupon">
                    <button id="add_coupon" type="button"
                            class="uk-button uk-button-primary uk-width-1-1">
                        ADD COUPON
                    </button>
                </div>                                 
            </form>
        </div>
        
    </div>
    <h3 class="uk-heading-divider uk-text-center">Coupon Details</h3>
        <div class="uk-grid" id="grid">
                <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">PromoCode</th>
                        <th scope="col">DiscountCode</th>
                        <th scope="col">AppRemarks</th>
                        <th scope="col">ExpiryDate</th>
                        <th scope="col">City</th>
                        <th scope="col">State</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $n = 1;
                    for ($i = 0; $i < sizeof($coupons); $i++) { 
                    $coupons[$i]['ExpiryDate'] = date("d/m/Y", strtotime($coupons[$i]['ExpiryDate'])); 
                    ?>
                    <tr>
                        <td scope="row"><?php echo $n; ?></td>
                        <td><?php echo $coupons[$i]['PromoCode']; ?></td>
                        <td><?php echo $coupons[$i]['DiscountCode']; ?></td>
                        <td><?php echo $coupons[$i]['AppRemarks']; ?></td>
                        <td><?php echo $coupons[$i]['ExpiryDate']; ?></td>
                        <?php if($coupons[$i]['city'] == "NULL"){?>
                            <td></td>
                        <?php } else{?>
                            <td><?php echo $coupons[$i]['city']; ?></td>
                        <?php } ?>
                        <td><?php echo $coupons[$i]['state']; ?></td>
                        <td><a href="<?php echo site_url('Console_Controller/delete_coupon/'.$coupons[$i]['Id']);?>"onclick="return confirm('Do you want to delete this coupon?')">Delete</a></td>
                    </tr>
                <?php $n++; } ?>
                </tbody>
            </table>
              
        </div>
</div>



<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/jquery.blockUI.js"></script>
<script>
    <?php for($j=0;$j< sizeof($states);$j++){?>
      $('#<?php echo $j;?>').hide();
    <?php }?>
    $("#state").change(function(){
        var statecode = $('#state').val();
        if(statecode != "all"){
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/console_controller/get_state_cities_sp",
                dataType: 'json',
                data: {
                    statecode: statecode
                },
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    //Download progress
                    xhr.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            //progressElem.value+=Math.round(percentComplete * 100);
                        }
                    }, false);
                    return xhr;
                },
                beforeSend: function () {
                    // $.blockUI({

                    //     message: '<h1>Please wait...</h1>'

                    // });
                },
                complete: function () {
                    $.unblockUI();
                },
                success: function (res) {
                    var row = res.row;
                    <?php $j='';
                    for($j=0;$j<sizeof($states);$j++){?>
                        if(<?php echo $j;?> == row){
                            $('#<?php echo $j;?>').show();
                        }else{
                            $('#<?php echo $j;?>').hide();
                        }
                <?php } ?>
                    
                }
            });
        }else{
            <?php for($j=0;$j< sizeof($states);$j++){?>
                $('#<?php echo $j;?>').hide();
            <?php }?>
        }
    });



    $('#add_coupon').click(function () {
            var state = $('#state').val();
            if(state != "all"){
                var cities = [];
                $(':checkbox:checked').each(function(i){
                cities[i] = $(this).val();
                });
            }else{
                var cities = "NULL";
            }
            var promo_code = $('#promocode').val();
            var discount_code = $('#discountcode').val();
            var app_remarks = $('#app_remarks').val();
            var expiry_date = $('#expiry_date').val();
            if ( state!= '' && cities != '' && promo_code != '' && expiry_date != '') {
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>" + "index.php/console_controller/add_coupon",
                    dataType: 'json',
                    data: {

                        state: state,
                        cities: cities,
                        promo_code: promo_code,
                        discount_code: discount_code,
                        app_remarks: app_remarks,
                        expiry_date: expiry_date
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
                                message: 'Failed to save',
                                status: 'danger',
                                pos: 'bottom-center',
                                timeout: 1000
                            });
                        }


                    }
                });
            } else {
                if(state == "all"){
                    UIkit.notification({
                        message: 'Expirydate and PromoCode are mandatory...',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });
                }else{     
                    UIkit.notification({
                        message: 'State,City,Expirydate and PromoCode are mandatory...',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });

                }
            }
       

    });


</script>
<style>
    table {
  /* border-collapse: collapse; */
  width: 100%;
  
}

tr:nth-child(even){background-color: #f2f2f2}
th, td {
  text-align: left;
  padding: 16px;
}
</style>