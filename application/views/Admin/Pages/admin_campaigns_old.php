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
<div class="uk-width-1-1@s uk-width-4-5@m uk-width-5-6@m uk-margin-auto-left content_wrapper uk-padding">
    <div class="page-container">


        <div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">

            <!--uk-width-1-2@l uk-width-1-2@m uk-width-1-2@s-->
            <h3 class="uk-heading-divider uk-text-center">Campaigns Details</h3>


            <div class="uk-margin">
                <div id="offer_grid" class="uk-grid uk-grid-medium uk-child-width-1-3@m uk-grid-match">
                    <div class="uk-margin-bottom">
                        <div id="" class="uk-card uk-card-default">
                            <div id="campaign_image" class="uk-card-media-top uk-padding uk-text-center">
                                <div class="js-upload" uk-form-custom>
                                    <input type="file" multiple>
                                    <button uk-icon="icon: plus-circle; ratio: 3.5"
                                            class="uk-button uk-button-default uk-padding" type="button"
                                            tabindex="-1"></button>
                                </div>
                                <div class="hide"></div>
                            </div>
                            <div class="uk-card-body">
                                <label class="uk-margin" for="form-horizontal-text">Title:
                                    <input type="text" id="title" class="uk-input">
                                <label class="uk-form-label" for="form-horizontal-text">Description:</label>
                                    <textarea class="uk-textarea" id="description" rows="3"></textarea>
                                <label class="uk-form-label" for="form-horizontal-text">DiscountCode:</label>
                                    <input type="text" id="discount_code" class="uk-input">
                                <label class="uk-margin" for="form-horizontal-text">Start Date:
                                    <input type="date" id="start_date" min="<?= date('Y-m-d');?>" class="uk-input">
                                <label class="uk-margin" for="form-horizontal-text">End Date:
                                    <input type="date" id="end_date" min="<?= date('Y-m-d');?>" class="uk-input">
                            </div>
                            <div class="uk-card-footer">
                                <p>
                                    <button onclick="add_campaign()"
                                            id="new_save"
                                            class="uk-button uk-button-default">Add 
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php for ($i = 0; $i < sizeof($campaigns); $i++) { ?>
                        <div class="uk-margin-bottom">
                            <div id="<?php echo $campaigns[$i]['Id']; ?>_card" class="uk-card uk-card-default">
                                <div class="uk-card-media-top">
                                    <img offer_id="<?php echo $campaigns[$i]['Id']; ?>"
                                         src="<?php echo $campaigns[$i]['image']; ?>" alt="">
                                </div>
                                <div class="uk-card-body">
                                    <h3 class="uk-card-title editable" id="<?php echo $campaigns[$i]['Id']; ?>_title"
                                        contenteditable="true"><?php echo $campaigns[$i]['title']; ?></h3>
                                        <h3 class="uk-card-title editable" id="<?php echo $campaigns[$i]['Id']; ?>_title"
                                        contenteditable="true"><?php echo $campaigns[$i]['description']; ?></h3>
                                        <h3 class="uk-card-title editable" id="<?php echo $campaigns[$i]['Id']; ?>_title"
                                        contenteditable="true"><?php echo $campaigns[$i]['discount_code']; ?></h3>
                                        <h3 class="uk-card-title editable" id="<?php echo $campaigns[$i]['Id']; ?>_title"
                                        contenteditable="true"><?php echo $campaigns[$i]['url']; ?></h3>
                                    <?php if($campaigns[$i]['start_date'] != ""){?>
                                        <div class="uk-inline">
                                            
                                            <input class="editable_expiry uk-input"
                                                id="<?php echo $campaigns[$i]['Id']; ?>_expiry"
                                                type="text" value="<?php echo date('Y-m-d',strtotime($campaigns[$i]['start_date'])); ?>">
                                        </div>
                                    <?php } if($campaigns[$i]['start_date'] != ""){?>
                                        <div class="uk-inline">
                                
                                            <input class="editable_expiry uk-input"
                                                id="<?php echo $campaigns[$i]['Id']; ?>_expiry"
                                                type="text" value="<?php echo date('Y-m-d',strtotime($campaigns[$i]['end_date'])); ?>">
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="uk-card-footer">

                                    <p>
                                        <a onclick="delete_campaign(<?php echo $campaigns[$i]['Id']; ?>)"><span
                                                class="uk-margin-small-right" uk-icon="trash"></span>Delete</a></p>

                                   
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/jquery.blockUI.js"></script>
<script>
    
    function delete_campaign(id) {


        UIkit.modal.confirm('Do you want to delete this campaign details?').then(function () {
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/console_controller/delete_campaign",
                dataType: 'json',
                data: {
                    campaign_id: id
                },
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    //Download progress
                    xhr.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            //progressElem.value += Math.round(percentComplete * 100);
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

    function save_changes(id) {
        var title = $('#' + id + '_title').text();
        var site_url = $('#' + id + '_site_url').text();
        jQuery.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>" + "index.php/console_controller/save_essentialpopup",
            dataType: 'json',
            data: {
                popup_id: id,
                title: title,

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


            if (response.moved_files.length > 0) {
                // UIkit.notification({
                //     message: response.moved_files[0].file + ' uploaded successfully',
                //     status: 'success',
                //     pos: 'top-right',
                //     timeout: 5000

                // });

                $('#campaign_image').html('');

                var img = $('<img />').attr({
                    'id': 'image',
                    'src': response.moved_files[0].link

                }).appendTo('#campaign_image');

            } else if (response.existing_files.length > 0) {
                // UIkit.notification({
                //     message: response.existing_files[0].file + ' already exists!',
                //     status: 'warning',
                //     pos: 'top-right',
                //     timeout: 5000

                // });


                $('#campaign_image').html('');
                var img = $('<img />').attr({
                    'id': 'image',
                    'src': response.existing_files[0].link

                }).appendTo('#campaign_image');

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

    function add_campaign() {
            var image = $('#image').attr('src');
            if(typeof image == 'undefined'){
                UIkit.notification({
                    message: 'Please upload an image',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
                return false;
            }
            var title = $('#title').val();
            var desc = $('#description').val();
            var code = $('#discount_code').val();
            var url = "https://app.fabricspa.com?"+code;
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            if(code == "" || url == "" || title == "" || desc == "" || start_date == "" || end_date == ""){
                UIkit.notification({
                    message: 'Please add title,description,discount code, start date and end date',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
                return false;
            }else{
                UIkit.modal.confirm('Do you want to add this campaign details?').then(function () {
                    jQuery.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>" + "index.php/console_controller/add_campaign",
                        dataType: 'json',
                        data: {
                            title: title,
                            image: image,
                            desc:desc,
                            code: code,
                            url: url,
                            start : start_date,
                            end : end_date
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
                })
            }
           
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
