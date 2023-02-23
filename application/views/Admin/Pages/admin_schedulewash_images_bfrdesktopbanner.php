<?php
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 11/22/2018
 * Time: 10:06 AM
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');


?>
<div class="uk-width-1-1@s uk-width-4-5@m uk-width-5-6@m uk-margin-auto-left content_wrapper uk-padding">
    <div class="page-container">


        <div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">

            <!--uk-width-1-2@l uk-width-1-2@m uk-width-1-2@s-->
            <h3 class="uk-heading-divider uk-text-center">ScheduleWash Images</h3>


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
                                <div class="hide">Height: 391px<br>Width: 672px</div>
                            </div>
                            <div class="uk-card-body">
                                <h3 class="uk-card-title new_editable" id="new_site_url"
                                    contenteditable="true">Site URL </h3>
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
                                            <input type="checkbox" class="uk-checkbox" name="cities" id="cities" value="<?php echo $cities[$i][$j]['cityname']; ?>" id="cities"> <?php 

echo $cities[$i][$j]['cityname']; ?>
                                            <?php }?>
                                        </div>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="uk-card-body">
                                <select id="new_brand" class="new_editable_brand uk-select">
                                    <option value="PCT0000001" selected>Fabricspa</option>
                                    <option value="PCT0000014">Click2Wash</option>

                                </select>
                            </div>
                            <div class="uk-card-footer">
                                <button onclick="add_wash_image()"
                                        id="new_save"
                                        class="uk-button uk-button-default">Add
                                </button>
                                
                            </div>
                        </div>
                    </div>
                    <?php if($wash_image != ""){ 
                         foreach($wash_image as $img) {
                            if(isset($img)){?>
                            <div class="uk-margin-bottom">
                                <div id="<?php echo $img['id']; ?>_card" class="uk-card uk-card-default">
                                    <div class="uk-card-media-top">
                                        <img popup_id="<?php echo $img['id']; ?>"
                                            src="<?php echo $img['url']; ?>" alt="">
                                            <h3 class="uk-card-title editable" id="<?php echo $img['id']; ?>_title"
                                        contenteditable="true"><?php echo $img['site_url']; ?></h3>
                                              <h3 class="uk-card-title editable" id="<?php echo $img['id']; ?>_title"
                                                contenteditable="true"><?php if($img['city'] != "NULL"){?><?php echo $img['city']; ?>(<?php echo $img['state'];?>)<?php }else{?><?php echo 
$img['state']; }?></h3>
                                            <?php if ($img['brandcode'] == 'PCT0000001') { ?>
                                                <select id="<?php echo $img['id']; ?>_brand"
                                                    class="new_editable_brand uk-select">
                                                    <option value="PCT0000001" selected>Fabricspa</option>
                                                    <option value="PCT0000014">Click2Wash</option>
                                                </select>
                                            <?php } else if ($img['brandcode'] == 'PCT0000014'){ ?>
                                                <select id="<?php echo $img['id']; ?>_brand"
                                                    class="new_editable_brand uk-select">
                                                    <option value="PCT0000001">Fabricspa</option>
                                                    <option value="PCT0000014" selected>Click2Wash</option>
                                                </select>
                                            <?php } else{}?>
                                    </div>
                                    <div class="uk-card-footer">
                                        <p>
                                            <a onclick="delete_wash_image(<?php echo $img['id']; ?>)"><span
                                                    class="uk-margin-small-right" uk-icon="trash"></span>Delete</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                    <?php }
                        }
                    }?>
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

    function delete_wash_image(id) {


        UIkit.modal.confirm('Do you want to delete this banner?').then(function () {
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/console_controller/delete_wash_image",
                dataType: 'json',
                data: {
                    wash_id: id
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
        beforeSend: function () {
            ////console.log('beforeSend', arguments);
        },
        beforeAll: function () {
            ////console.log('beforeAll', arguments);
        },
        load: function () {
            ////console.log('load', arguments);
        },
        error: function () {
            //console.log('error', arguments);
        },
        complete: function () {
            ////console.log('complete', arguments);
        },

        loadStart: function (e) {
            ////console.log('loadStart', arguments);
            /*
             bar.removeAttribute('hidden');
             bar.max = e.total;
             bar.value = e.loaded;*/
        },

        progress: function (e) {
            ////console.log('progress', arguments);

            /* bar.max = e.total;
             bar.value = e.loaded;*/
        },

        loadEnd: function (e) {
            ////console.log('loadEnd', arguments);

            /* bar.max = e.total;
             bar.value = e.loaded;*/
        },

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

    function add_wash_image() {           
            var wash_image = $('#new_offer_image').attr('src');
            if(typeof wash_image == 'undefined'){
                UIkit.notification({
                    message: 'Please upload a banner',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
                return false;
            }
            var brand = $('#new_brand').val();
            var state = $('#state').val();
            var site_url = $('#new_site_url').text();
            if(site_url == 'Site URL ')
                site_url = "";
            if($('#default').is(":checked")){
                var state ="";
                var cities ="NULL";
            }else{
                var state = $('#state').val();
                if(state != "all" && state != ""){
                    var cities = [];
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
            UIkit.modal.confirm('Do you want to add this banner?').then(function () { 
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>" + "index.php/console_controller/add_wash_image",
                    dataType: 'json',
                    data: {
                        wash_image: wash_image,
                        state: state,
                        cities: cities,
                        brand:brand,
                        site_url:site_url
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