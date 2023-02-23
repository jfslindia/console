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
            <h3 class="uk-heading-divider uk-text-center">Offers</h3>


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
                                <h3 class="uk-card-title new_editable" id="new_title"
                                    contenteditable="true">New Offer</h3>

                                <p class="new_editable" contenteditable="true"
                                   id="new_description">New description</p>
                            </div>
                            <div class="uk-card-footer">

                                <p>

                                <div class="uk-inline">
                                    <a class="uk-form-icon" href="#" uk-icon="icon: future"></a>
                                    <input class="new_editable_expiry uk-input" id="new_expiry"
                                           type="text" value="">
                                </div>
                                </p>

                                <p><span class="uk-margin-small-right"
                                         uk-icon="home"></span>
                                    <select id="new_brand"
                                            class="new_editable_brand uk-select uk-width-auto@m">

                                        <option value="PCT0000001" selected>Fabricspa</option>
                                        <option value="PCT0000014">Click2Wash</option>

                                    </select>
                                </p>


                                <p>
                                    <button onclick="add_offer()"
                                            id="new_save"
                                            class="uk-button uk-button-default">Add offer
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php for ($i = 0; $i < sizeof($offers); $i++) { ?>
                        <div class="uk-margin-bottom">
                            <div id="<?php echo $offers[$i]['id']; ?>_card" class="uk-card uk-card-default">
                                <div class="uk-card-media-top">
                                    <img offer_id="<?php echo $offers[$i]['id']; ?>"
                                         src="<?php echo $offers[$i]['offer_img']; ?>" alt="">
                                </div>
                                <div class="uk-card-body">
                                    <h3 class="uk-card-title editable" id="<?php echo $offers[$i]['id']; ?>_title"
                                        contenteditable="true"><?php echo $offers[$i]['offer_heading']; ?></h3>

                                    <p class="editable" contenteditable="true"
                                       id="<?php echo $offers[$i]['id']; ?>_description"><?php echo $offers[$i]['offer_description']; ?></p>
                                </div>
                                <div class="uk-card-footer">


                                    <p>

                                    <div class="uk-inline">
                                        <a class="uk-form-icon" href="#" uk-icon="icon: future"></a>
                                        <input class="editable_expiry uk-input"
                                               id="<?php echo $offers[$i]['id']; ?>_expiry"
                                               type="text" value="<?php echo $offers[$i]['expiry']; ?>">
                                    </div>
                                    </p>

                                    <p><span class="uk-margin-small-right"
                                             uk-icon="home"></span>
                                        <select id="<?php echo $offers[$i]['id']; ?>_brand"
                                                class="editable_brand uk-select uk-width-auto@m">
                                            <?php if ($offers[$i]['brand_code'] == 'PCT0000001') { ?>
                                                <option value="PCT0000001" selected>Fabricspa</option>
                                                <option value="PCT0000014">Click2Wash</option>
                                            <?php } else { ?>
                                                <option value="PCT0000001">Fabricspa</option>
                                                <option value="PCT0000014" selected>Click2Wash</option>
                                            <?php } ?>

                                        </select>
                                    </p>

                                    <p>
                                        <a onclick="delete_offer(<?php echo $offers[$i]['id']; ?>)"><span
                                                class="uk-margin-small-right" uk-icon="trash"></span>Delete</a></p>

                                    <p>
                                        <button onclick="save_changes(<?php echo $offers[$i]['id']; ?>)"
                                                id="<?php echo $offers[$i]['id']; ?>_save"
                                                class="uk-button uk-button-default uk-hidden">Save changes
                                        </button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(function () {
        $('#new_expiry').datepicker({maxDate: new Date(), dateFormat: 'yy-mm-dd'});
        <?php for($i=0;$i<sizeof($offers);$i++){ ?>
        var datepicker = <?php echo $offers[$i]['id']; ?>+"_expiry";
        $('#' + datepicker).datepicker({maxDate: new Date(), dateFormat: 'yy-mm-dd'});
        <?php } ?>

    });


    $('.editable').on('blur keyup paste', function () {
        var offer_img = $(this).parent().prev().children()[0];
        //var next_children=$(this).parent().next().children();
        var offer_id = $(offer_img).attr('offer_id');
        if ($('#' + offer_id + '_save').hasClass('uk-hidden')) {
            $('#' + offer_id + '_save').removeClass('uk-hidden');
        }

    });

    $('.editable_expiry').on('blur keyup paste', function () {
        var offer_img = $(this).parent().parent().prev().prev().children()[0];

        //var next_children=$(this).parent().next().children();
        var offer_id = $(offer_img).attr('offer_id');

        if ($('#' + offer_id + '_save').hasClass('uk-hidden')) {
            $('#' + offer_id + '_save').removeClass('uk-hidden');
        }

    });

    $('.editable_brand').change(function () {
        var offer_img = $(this).parent().parent().prev().prev().children()[0];

        //var next_children=$(this).parent().next().children();
        var offer_id = $(offer_img).attr('offer_id');

        if ($('#' + offer_id + '_save').hasClass('uk-hidden')) {
            $('#' + offer_id + '_save').removeClass('uk-hidden');
        }
    })

    function delete_offer(id) {


        UIkit.modal.confirm('Do you want to delete this offer?').then(function () {
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/console_controller/delete_offer",
                dataType: 'json',
                data: {
                    offer_id: id
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

    function save_changes(id) {
        var title = $('#' + id + '_title').text();
        var description = $('#' + id + '_description').text();
        var expiry = $('#' + id + '_expiry').val();
        var brand_code = $('#' + id + '_brand').val();
        jQuery.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>" + "index.php/console_controller/save_offer",
            dataType: 'json',
            data: {
                offer_id: id,
                title: title,
                description: description,
                expiry: expiry,
                brand_code: brand_code
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

        url: base_url + 'index.php/console_controller/upload_offer_image',
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


                UIkit.notification({
                    message: response.moved_files[0].file + ' uploaded successfully',
                    status: 'success',
                    pos: 'top-right',
                    timeout: 5000

                });

                $('#new_offer_img_block').html('');

                var img = $('<img />').attr({
                    'id': 'new_offer_image',
                    'src': response.moved_files[0].link

                }).appendTo('#new_offer_img_block');

            } else if (response.existing_files.length > 0) {
                UIkit.notification({
                    message: response.existing_files[0].file + ' already exists!',
                    status: 'warning',
                    pos: 'top-right',
                    timeout: 5000

                });


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


        }
    });

    function add_offer() {

        UIkit.modal.confirm('Do you want to add this offer?').then(function () {
            var title = $('#new_title').text();
            var description = $('#new_description').text();
            var expiry = $('#new_expiry').val();
            var brand_code = $('#new_brand').val();
            var offer_image = $('#new_offer_image').attr('src');


            if (title != '' || expiry != '' || brand_code != '') {


                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>" + "index.php/console_controller/add_offer",
                    dataType: 'json',
                    data: {

                        title: title,
                        description: description,
                        expiry: expiry,
                        brand_code: brand_code,
                        offer_image: offer_image
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
                UIkit.notification({
                    message: 'Title/Brand Code/Expiry must be given...',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
            }
        })

    }


</script>