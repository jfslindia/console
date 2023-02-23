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
            <h3 class="uk-heading-divider uk-text-center">Fabhome Banner</h3>


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
                            <div class="uk-card-footer">
                                <button onclick="add_fbhmbanner()"
                                        id="new_save"
                                        class="uk-button uk-button-default">Add
                                </button>
                                
                            </div>
                        </div>
                    </div>
                    <?php if($fabhome_banner != ""){ ?>
                        <div class="uk-margin-bottom">
                            <div id="<?php echo $fabhome_banner[0]['id']; ?>_card" class="uk-card uk-card-default">
                                <div class="uk-card-media-top">
                                    <img popup_id="<?php echo $fabhome_banner[0]['id']; ?>"
                                         src="<?php echo $fabhome_banner[0]['url']; ?>" alt="">
                                </div>
                                <div class="uk-card-footer">
                                    <p>
                                        <a onclick="delete_fbbanner(<?php echo $fabhome_banner[0]['id']; ?>)"><span
                                                class="uk-margin-small-right" uk-icon="trash"></span>Delete</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/jquery.blockUI.js"></script>
<script>

    function delete_fbbanner(id) {
        UIkit.modal.confirm('Do you want to delete this banner?').then(function () {
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/console_controller/delete_fbhmbanner",
                dataType: 'json',
                data: {
                    id: id
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

    function add_fbhmbanner() {            
        var fbhm_image = $('#new_offer_image').attr('src');
            if(typeof fbhm_image == 'undefined'){
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
                    url: "<?php echo base_url(); ?>" + "index.php/console_controller/check_fbhmbanner",
                    dataType: 'json',
                    xhr: function () {
                        var xhr = new window.XMLHttpRequest();
                        //Download progress
                        xhr.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                // var percentComplete = evt.loaded / evt.total;
                                // progressElem.value += Math.round(percentComplete * 100);
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
                        if (res == 'success') {
                            jQuery.ajax({
                                type: "POST",
                                url: "<?php echo base_url(); ?>" + "index.php/console_controller/add_fbhmbanner",
                                dataType: 'json',
                                data: {
                                    fbhm_image: fbhm_image
                                },
                                xhr: function () {
                                    var xhr = new window.XMLHttpRequest();
                                    xhr.addEventListener("progress", function (evt) {
                                        if (evt.lengthComputable) {
                                            // var percentComplete = evt.loaded / evt.total;
                                            // progressElem.value += Math.round(percentComplete * 100);
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
                            UIkit.notification({
                                message: 'Please delete the existing banner and try again',
                                status: 'danger',
                                pos: 'bottom-center',
                                timeout: 1000
                            });
                        }


                    }
                });
            });
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