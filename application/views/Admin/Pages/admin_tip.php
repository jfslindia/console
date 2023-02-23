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
            <h3 class="uk-heading-divider uk-text-center">TIPS</h3>
            <div class="uk-margin">
                <div id="offer_grid" class="uk-grid uk-grid-medium uk-child-width-1-3@m uk-grid-match">
                    <div class="uk-margin-bottom">
                        <div class="uk-card uk-card-default">
                            <div class="uk-card-body">
                                <select id="banner_type" class="new_editable_brand uk-select">
                                    <option value="desktop" selected>Desktop Banner</option>
                                    <option value="mobile">Mobile Banner</option>
                                </select>
                            </div>
                            <div id="desktop_tip_img_block" class="uk-card-media-top uk-padding uk-text-center">
                                <div class="js-upload" id="desktop_tip_upload" uk-form-custom>
                                    <input type="file" multiple>
                                    <button uk-icon="icon: plus-circle; ratio: 3.5"
                                            class="uk-button uk-button-default uk-padding" type="button"
                                            tabindex="-1"></button>
                                </div>
                                <div class="hide">Height: 300px<br>Width: 1390px</div>
                            </div>
                            <div id="mobile_tip_img_block" class="uk-card-media-top uk-padding uk-text-center" style="display:none;">
                                <div class="js-upload" id="mobile_tip_upload"  uk-form-custom>
                                    <input type="file" multiple>
                                    <button uk-icon="icon: plus-circle; ratio: 3.5"
                                            class="uk-button uk-button-default uk-padding" type="button"
                                            tabindex="-1"></button>
                                </div>
                                <div class="hide">Height: 240px<br>Width: 672px</div>
                            </div>
                            <div class="uk-card-footer">
                                <button onclick="add_tip()"
                                        id="new_save"
                                        class="uk-button uk-button-default">Add
                                </button>
                                
                            </div>
                        </div>
                    </div>
                    <?php if($tip != ""){ 
                        for($i=0;$i<sizeof($tip);$i++){ ?>    
                        <div class="uk-margin-bottom">
                            <?php if($tip[$i]['category'] == "tip-desktop"){?><h4>Desktop Banner</h4><?php }else if($tip[$i]['category'] == "tip-mobile"){?><h4>Mobile Banner</h4><?php }?>
                            <div id="<?php echo $tip[0]['id']; ?>_card" class="uk-card uk-card-default">

                                <div class="uk-card-media-top">
                                    <img popup_id="<?php echo $tip[$i]['id']; ?>"
                                         src="<?php echo $tip[$i]['url']; ?>" alt="">
                                </div>
                                <div class="uk-card-footer">
                                    <p>
                                        <a onclick="delete_tip(<?php echo $tip[$i]['id']; ?>,'<?php echo $tip[$i]['category'];?>')"><span
                                                class="uk-margin-small-right" uk-icon="trash"></span>Delete</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php }
                    }?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/jquery.blockUI.js"></script>
<script>
    function delete_tip(id,category) {
        UIkit.modal.confirm('Do you want to delete this banner?').then(function () {
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/console_controller/delete_tip",
                dataType: 'json',
                data: {
                    tip_id: id,
                    category:category
                },
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    //Download progress
                    xhr.addEventListener("progress", function (evt) {
                        // if (evt.lengthComputable) {
                        //     var percentComplete = evt.loaded / evt.total;
                        //     progressElem.value += Math.round(percentComplete * 100);
                        // }
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
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
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

    UIkit.upload('#desktop_tip_upload', {
        url: base_url + 'index.php/console_controller/upload_image',
        multiple: false,
        concurrent: 1,
        beforeSend: function () {},
        beforeAll: function () {},
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
            if (response.moved_files.length > 0) {
                $('#desktop_tip_img_block').html('');

                var img = $('<img />').attr({
                    'id': 'desktop_tip_img',
                    'src': response.moved_files[0].link

                }).appendTo('#desktop_tip_img_block');

            } else if (response.existing_files.length > 0) {
                $('#desktop_tip_img_block').html('');
                var img = $('<img />').attr({
                    'id': 'desktop_tip_img',
                    'src': response.existing_files[0].link

                }).appendTo('#desktop_tip_img_block');

            } else {
                UIkit.notification({
                    message: response.failed_files[0].file + ' upload failed!',
                    status: 'danger',
                    pos: 'top-right',
                    timeout: 5000

                });
            }


        }
    });
    UIkit.upload('#mobile_tip_upload', {
        url: base_url + 'index.php/console_controller/upload_image',
        multiple: false,
        concurrent: 1,
        beforeSend: function () {},
        beforeAll: function () {},
        load: function () {},
        error: function () {},
        complete: function () {},

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
            if (response.moved_files.length > 0) {
                $('#mobile_tip_img_block').html('');

                var img = $('<img />').attr({
                    'id': 'mobile_tip_img',
                    'src': response.moved_files[0].link

                }).appendTo('#mobile_tip_img_block');

            } else if (response.existing_files.length > 0) {
                $('#new_offer_img_block').html('');
                var img = $('<img />').attr({
                    'id': 'mobile_tip_img',
                    'src': response.existing_files[0].link

                }).appendTo('#mobile_tip_img_block');

            } else {
                UIkit.notification({
                    message: response.failed_files[0].file + ' upload failed!',
                    status: 'danger',
                    pos: 'top-right',
                    timeout: 5000

                });
            }


        }
    });
    function add_tip() {   
            if($('#banner_type').val() == "desktop")         
                var tip_image = $('#desktop_tip_img').attr('src');
            else if($('#banner_type').val() == "mobile")
                var tip_image = $('#mobile_tip_img').attr('src');
            if(typeof tip_image == 'undefined'){
                UIkit.notification({
                    message: 'Please upload a banner',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
                return false;
            }
            UIkit.modal.confirm('Do you want to add this banner?').then(function () {
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>" + "index.php/console_controller/check_tip",
                    data:{
                        device: $('#banner_type').val()
                    },
                    dataType: 'json',
                    xhr: function () {
                        var xhr = new window.XMLHttpRequest();
                        //Download progress
                        xhr.addEventListener("progress", function (evt) {
                            // if (evt.lengthComputable) {
                            //     var percentComplete = evt.loaded / evt.total;
                            //     progressElem.value += Math.round(percentComplete * 100);
                            // }
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
                        if (res == 'success') {
                            jQuery.ajax({
                                type: "POST",
                                url: "<?php echo base_url(); ?>" + "index.php/console_controller/add_tip",
                                dataType: 'json',
                                data: {
                                    tip_image: tip_image,
                                    category:"tip-"+$('#banner_type').val()
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

                        } else {
                            UIkit.notification({
                                message: 'Please delete the old '+$('#banner_type').val()+' banner and try again',
                                status: 'danger',
                                pos: 'bottom-center',
                                timeout: 1000
                            });
                        }


                    }
                });
            });
    }
    $("#banner_type").change(function(){
        var banner_type = $('#banner_type').val();
        if(banner_type  == "desktop"){
            $('#desktop_tip_img_block').removeAttr('style')
            $('#mobile_tip_img_block').css({"display":"none"})
        }else {
            $('#mobile_tip_img_block').removeAttr('style')
            $('#desktop_tip_img_block').css({"display":"none"})
        }
    });
</script>
<style>
.hide {
  display: none;
}

.js-upload:hover + .hide {
  display: block;
}

</style>