<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="uk-width-1-1@s uk-width-4-5@m uk-width-5-6@m uk-margin-auto-left content_wrapper uk-padding">
    <div class="page-container">
        <div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">
            <h3 class="uk-heading-divider uk-text-center">Homepage Popup Details</h3>
            <div class="uk-margin">
                <div id="offer_grid" class="uk-grid uk-grid-medium uk-child-width-1-3@m uk-grid-match">
                    <div class="uk-margin-bottom">
                        <div id="" class="uk-card uk-card-default">
                            <div id="new_offer_img_block" class="uk-card-media-top uk-padding uk-text-center">
                                <div class="js-upload" uk-form-custom>
                                    <input type="file" multiple>
                                    <button uk-icon="icon: plus-circle; ratio: 3.5"
                                            class="uk-button uk-button-default uk-padding" type="button"
                                            tabindex="-1"></button>
                                    
                                </div>
                            </div>
                            <div class="uk-card-body">
                                <select id="redirect_to" class="new_editable_brand uk-select">
                                    <option value="" class="uk-h4">Redirect To</option>
                                    <option value="schedulewash">Schedule Wash Page</option>
                                    <option value="payment">Payment Page</option>

                                </select>
                            </div>
                            <div class="uk-card-body">
                                <h3 class="uk-card-title new_editable" id="new_site_url"
                                    contenteditable="true" value="">Site URL</h3>
                            </div>
                            <div class="uk-card-body" class="default_class">
                                <h3><input type="checkbox" class ="uk-checkbox" id="default">   Set as default</h3>
                            </div>
                            <div class="uk-card-body">
                                <select class="uk-select" id="state">
                                    <option value="" class="uk-h4">Select a state and cities</option>
                                        <?php for($i=0;$i<sizeof($states);$i++){?>
                                            <option value="<?php echo $states[$i]['statecode'];?>" class="uk-h4"><?php echo $states[$i]['statename'];?></option>
                                        <?php } ?>
                                    <option value="all" class="uk-h4">ALL</option>
                                </select>
                            </div>
                            <div class="uk-card-body">
                                <div class="uk-form-controls">
                                    <?php for($i=0;$i<=sizeof($states);$i++){?> 
                                        <div id="<?php echo $i;?>" class="uk-h4">
                                            <?php for($j=0;$j<sizeof($cities[$i]);$j++){?>
                                            <input type="checkbox" class="uk-checkbox" name="cities" id="cities" value="<?php echo $cities[$i][$j]['cityname']; ?>"> <?php echo $cities[$i][$j]['cityname']; ?>
                                            <?php }?>
                                        </div>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="uk-card-body" class="default_class">
                                <h4>ExpiryDate :</h4><input type="date" min="<?= date('Y-m-d'); ?>" name="expiry_date" id="expiry_date"  class="uk-textarea">
                            </div>
                            <div class="uk-card-footer">
                                <button onclick="add_popup()"
                                        id="new_save"
                                        class="uk-button uk-button-default">Add
                                </button>

                            </div>
                        </div>
                    </div>
                    <?php foreach($popups as $p) {
                            if(isset($p)){?>

                        <div class="uk-margin-bottom">
                            <div id="<?php echo $p['id']; ?>_card" class="uk-card uk-card-default">
                                <div class="uk-card-media-top">
                                    <img popup_id="<?php echo $p['id']; ?>"
                                         src="<?php echo $p['url']; ?>" alt="">
                                </div>
                                <div class="uk-card-body">
                                    <h3 class="uk-card-title editable" id="<?php echo $p['id']; ?>_title"
                                        contenteditable="true"><?php echo $p['site_url']; ?></h3>
                                    <h3 class="uk-card-title editable" id="<?php echo $p['id']; ?>_title"
                                        contenteditable="true"><?php if($p['city'] != "NULL"){?><?php echo $p['city']; ?>(<?php echo $p['state']; ?>) <?php }else{ echo $p['state']; }?></h3>
                                    <h3 class="uk-card-title editable" id="<?php echo $p['id']; ?>_title"
                                        contenteditable="true"><?php echo date("d/m/Y", strtotime($p['expiry_date'])); ?></h3>
                                    </div>
                                <div class="uk-card-footer">
                                    <p>
                                        <a onclick="delete_popup(<?php echo $p['id']; ?>)"><span
                                                class="uk-margin-small-right" uk-icon="trash"></span>Delete</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php } }?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/jquery.blockUI.js"></script>
<script>
    <?php for($j=0;$j<=sizeof($states);$j++){?>
      $('#<?php echo $j;?>').hide();
    <?php }?>
    $('#default').click(function(){
            if($(this).prop("checked") == true){
                $('#state').hide();
                $('#state').val("");
                <?php for($j=0;$j< sizeof($states);$j++){?>
                $('#<?php echo $j;?>').hide();
                <?php }?>
            }
            else if($(this).prop("checked") == false){
                $('#state').show();
                if($('#state') != "")
                    $("#state").change();
            }
    });
    $("#state").change(function(){
        $('input[type="checkbox"]').prop("checked", false);
        $('#cities').val("");
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
                        }
                    }, false);
                    return xhr;
                },
                beforeSend: function () {
                },
                complete: function () {
                    $.unblockUI();
                },
                success: function (res) {
                    var row = res.row;
                    <?php $j='';
                    
                    for($j=0;$j<=sizeof($states);$j++){?>
            
                        if(<?php echo $j;?> == row+1){
                            $('#<?php echo $j;?>').show();
                        }else{
                            
                            $('#<?php echo $j;?>').hide();
                        }
                <?php } ?>
                $('#0').hide();
                    
                }
            });
        }else{
            <?php for($j=0;$j< sizeof($states);$j++){?>
                $('#<?php echo $j;?>').hide();
            <?php }?>
        }
    });

    $('.editable').on('blur keyup paste', function () {
        var offer_img = $(this).parent().prev().children()[0];
        var popup_id = $(offer_img).attr('popup_id');
        if ($('#' + popup_id + '_save').hasClass('uk-hidden')) {
            $('#' + popup_id + '_save').removeClass('uk-hidden');
        }

    });

    $('.editable_expiry').on('blur keyup paste', function () {
        var offer_img = $(this).parent().parent().prev().prev().children()[0];
        var popup_id = $(offer_img).attr('popup_id');

        if ($('#' + popup_id + '_save').hasClass('uk-hidden')) {
            $('#' + popup_id + '_save').removeClass('uk-hidden');
        }

    });

    $('.editable_brand').change(function () {
        var offer_img = $(this).parent().parent().prev().prev().children()[0];
        var popup_id = $(offer_img).attr('popup_id');

        if ($('#' + popup_id + '_save').hasClass('uk-hidden')) {
            $('#' + popup_id + '_save').removeClass('uk-hidden');
        }
    })

    function delete_popup(id) {
        UIkit.modal.confirm('Do you want to delete this image?').then(function () {
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/console_controller/delete_popup",
                dataType: 'json',
                data: {
                    popup_id: id
                },
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
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
                            message: 'Successfully deleted',
                            status: 'success',
                            pos: 'bottom-center',
                            timeout: 1000
                        });

                        $('#' + id + '_card').parent().remove();
                    } else {
                        UIkit.notification({
                            message: 'Failed to delete',
                            status: 'danger',
                            pos: 'bottom-center',
                            timeout: 1000
                        });
                    }
                }
            });
        }, function () {

        });


    }

    

    UIkit.upload('.js-upload', {
        url: base_url + 'index.php/console_controller/upload_image',
        multiple: false,
        concurrent: 1,
        completeAll: function (res) {

            var response = JSON.parse(res.response);
            if(response.status =='success'){
                if (response.moved_files.length > 0) {
                    $('#new_offer_img_block').html('');
                    var img = $('<img />').attr({
                        'id': 'new_offer_image',
                        'src': response.moved_files[0].link

                    }).appendTo('#new_offer_img_block');

                } else if (response.existing_files.length > 0) {
                    $('#new_offer_img_block').html('');
                    var img = $('<img />').attr({
                        'id': 'new_offer_image',
                        'src': response.existing_files[0].link

                    }).appendTo('#new_offer_img_block');

                } else {
                    UIkit.notification({
                        message: response.failed_files[0].file + ' upload failed!',
                        status: 'danger',
                        pos: 'top-right',
                        timeout: 5000

                    });
                }
            }else{
                UIkit.notification({
                        message: response.message,
                        status: 'danger',
                        pos: 'top-right',
                        timeout: 5000

                    });
            }
        }
    });

    function add_popup() {
        if($('#new_site_url').text() != "Site URL")
            var site_url = $('#new_site_url').text();
        else
            var site_url = "";

        var page_url = $('#redirect_to').val();
        var popup_image = $('#new_offer_image').attr('src');
        var expiry = $('#expiry_date').val();
        if(page_url != "" && site_url != ""){
            UIkit.notification({
                message: 'Do not add site url and redirect page together',
                status: 'danger',
                pos: 'bottom-center',
                timeout: 1000
            });
            return false;
        }
        if(page_url != "")
            site_url = page_url;
        if(typeof popup_image == 'undefined' ||  expiry == ""){
            UIkit.notification({
                message: 'Image and expiry date are mandatory',
                status: 'danger',
                pos: 'bottom-center',
                timeout: 1000
            });
            return false;
        }
        if($('#default').is(":checked")){
            var state ="";
            var cities ="NULL";
        }else{
            var state = $('#state').val();
            if(state != "all" && state != ""){
                var cities=[];
                $(':checkbox:checked').each(function(i){
                    cities[i] = $(this).val();
                });  
            }else{
                var cities = "NULL";
            }
            if(state == "" || cities == ""){
                UIkit.notification({
                    message: 'Please choose cities',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
                return false;
            }
            
        }
        UIkit.modal.confirm('Do you want to add this image?').then(function () {
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/console_controller/add_popup",
                dataType: 'json',
                data: {
                    popup_image: popup_image,
                    site_url:site_url,
                    state: state,
                    cities: cities,
                    expiry:expiry
                },
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
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
        })   
    }


</script>
<style>
.hide {
  display: none;
}

.js-upload:hover + .hide {
  display: block;
}

</style>