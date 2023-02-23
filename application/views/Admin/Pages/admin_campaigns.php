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
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://malsup.github.io/jquery.blockUI.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<div class="uk-width-1-1@s uk-width-4-5@m uk-width-5-6@m uk-margin-auto-left content_wrapper uk-padding">
    <div class="page-container">


        <div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">

            <!--uk-width-1-2@l uk-width-1-2@m uk-width-1-2@s-->
            <h3 class="uk-heading-divider uk-text-center">Campaigns</h3>


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
                                <p>
                                    <button onclick="add_campaign()"
                                            id="new_save"
                                            class="uk-button uk-button-default">Add 
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>
                        <div class="uk-margin-bottom">
                            </br></br>
                            <div class="uk-card-body">
                                <label class="uk-margin" for="form-horizontal-text">Start Date:
                                </br></br>
                                    <input type="date" id="start" class="uk-input">
                                </br></br>
                                <label class="uk-margin" for="form-horizontal-text" >End Date:
                                </br></br>
                                    <input type="date" id="end" class="uk-input" >
                                </br></br></br>
                                <p>
                                    <button onclick="search_campaign()" id="new_save" class="uk-button uk-button-default">Search</button>
                                    <button onclick="download_campaign_report()"
                                            id="campaign_report"
                                            class="uk-button uk-button-default">Download Report
                                    </button>
                                    <h5>(You can search or download  campaign details without or with in a time period)</h5>
                                </p>
                            </div>
                            
                            </div>
                        </div>
                </div>
                <h3 class="uk-text-center" id="campaign_header">Campaigns List</h3>
                </br></br>
                <div id="campaign_grid" class="uk-grid uk-grid-medium uk-child-width-1-3@m uk-grid-match">   
                
                    <?php for ($i = 0; $i < sizeof($campaigns); $i++) { ?>
                            <div class="uk-card" id="campaign_card">
                                <div id="<?php echo $campaigns[$i]['Id']; ?>_card" class="uk-card ">
                                    <div class="uk-card-media-top">
                                        <img campaign_id="<?php echo $campaigns[$i]['Id']; ?>"
                                            src="<?php echo $campaigns[$i]['image']; ?>"  style="width:200; height:120">
                                    </div>
                                    <a href="<?php echo base_url('console/campaign_details/'.$campaigns[$i]['Id']);?>">View More</a>
                                    <!-- <div class="<?php echo $campaigns[$i]['Id']; ?>_show"><a onclick="show_campaign_details(<?php echo $campaigns[$i]['Id']; ?>)">View More</a></div>
                                    <div class="uk-hidden" id="<?php echo $campaigns[$i]['Id']; ?>_hide" ><a onclick="hide_campaign_data(<?php echo $campaigns[$i]['Id']; ?>)">View Less</a></div>
                    
                                    <div id="<?php echo $campaigns[$i]['Id']; ?>" class="campaign_details">
                                        <div class="uk_card" id="campaign_details_card">
                                        <div class="uk-card-body">
                                            <h3 class="uk-card-title" onclick="editable(<?php echo $campaigns[$i]['Id'];?>)" id="<?php echo $campaigns[$i]['Id']; ?>_title"
                                                contenteditable="true"><?php echo $campaigns[$i]['title']; ?></h3>
                                                <h3 class="uk-card-title" onclick="editable(<?php echo $campaigns[$i]['Id'];?>)" id="<?php echo $campaigns[$i]['Id']; ?>_description"
                                                contenteditable="true"><?php echo $campaigns[$i]['description']; ?></h3>
                                                <h3 class="uk-card-title" onclick="editable(<?php echo $campaigns[$i]['Id'];?>)" id="<?php echo $campaigns[$i]['Id']; ?>_discount_code"
                                                contenteditable="true"><?php echo $campaigns[$i]['discount_code']; ?></h3>
                                                <h5 class="uk-card-title " id="<?php echo $campaigns[$i]['Id']; ?>_url"
                                                contenteditable="false"><?php echo $campaigns[$i]['url']; ?></h5>
                                                <h3 class="uk-card-title" onclick="editable(<?php echo $campaigns[$i]['Id'];?>)" id="<?php echo $campaigns[$i]['Id']; ?>_start_date"
                                                contenteditable="true"><?php echo date('d-m-Y',strtotime($campaigns[$i]['start_date'])); ?></h3>
                                                <h3 class="uk-card-title" onclick="editable(<?php echo $campaigns[$i]['Id'];?>)" id="<?php echo $campaigns[$i]['Id']; ?>_end_date"
                                                contenteditable="true"><?php echo date('d-m-Y',strtotime($campaigns[$i]['end_date'])); ?></h3>
                                        </div>
                                        <div class="uk-card-footer">
                                            <p>
                                                <a onclick="delete_campaign(<?php echo $campaigns[$i]['Id']; ?>)"><span
                                                        class="uk-margin-small-right" uk-icon="trash"></span>Delete</a>
                                            </p>
                                            <p>
                                                <button onclick="save_campaign(<?php echo $campaigns[$i]['Id']; ?>)"
                                                        id="<?php echo $campaigns[$i]['Id']; ?>_save"
                                                        class="uk-button uk-button-default uk-hidden">Save changes
                                                </button>
                                            </p>
                                        </div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div id="search_campaign_result" class="uk-card">
                        
                    </div>
                    </div>
                   
                </div>
                
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/jquery.blockUI.js"></script>
<script>
  $('.campaign_details').hide();
  $('#hide').hide();
  $('#search_campaign_result').hide();
 // $('#campaign_search_details').hide();
  
$(document).ready(function() {
    $('.hide').hide();   
//     let today = new Date().toISOString().substr(0, 10);

//     var dateAr = today.split('-');
//     var date = dateAr[1]+'/ 01/'+dateAr[0];
//     alert(date);
//     document.querySelector("#start").value = today;
<?php for($j=0;$j< sizeof($campaigns);$j++){?>
      $('#'+<?php echo $j;?>).hide();
      $('#'+<?php echo $j;?>+'_hide').hide();
     $('#'+<?php echo $j;?>+'_hide_search').hide();
      $('#' +<?php echo $j;?>+'_campaign_search_details').hide();
     <?php } ?>
     

});
function show_campaign_data(id)
{
    $('#' + id).show();
    $('.'+id+'_show').hide();
    $('#' + id + '_hide').removeClass('uk-hidden');
}
function hide_campaign_data(id)
{
    $('#' + id).hide();
    $('.'+id+'_show').show();
    $('#' + id + '_hide').addClass('uk-hidden');
}
function show_campaign_search_data(id)
{
    window.location = "campaign_details/" + id;
}
function hide_campaign_search_data(id)
{
    $('#' + id+'_campaign_search_details').hide();
    $('.'+id+'_show_search').show();
    $('#' + id + '_hide_search').addClass('uk-hidden');
}
    // $(".enable_edit").change(function(){
    //     var campaign_image = $(this).parent().prev().children()[0];
    //     var campaign_id = $(campaign_image).attr('campaign_id');
    //     alert(campaign_id);
    //     if ($('#' + campaign_id + '_save').hasClass('uk-hidden')) {
    //         $('#' + campaign_id + '_save').removeClass('uk-hidden');
    //     }
    // });
    //  $('.'+id+'_editable').on('blur keyup paste', function () {
    //     var campaign_image = $(this).parent().prev().children()[0];
    //     alert(id);
    //     //var next_children=$(this).parent().next().children();
    //     var campaign_id = $(campaign_image).attr('campaign_id');
    //     alert(campaign_id);
    //     if ($('#' + campaign_id + '_save').hasClass('uk-hidden')) {
    //         $('#' + campaign_id + '_save').removeClass('uk-hidden');
    //     }

    // });

    $('.editable_expiry').on('blur keyup paste', function () {
        var campaign_image = $(this).parent().parent().prev().prev().children()[0];

        //var next_children=$(this).parent().next().children();
        var campaign_id = $(campaign_image).attr('campaign_id');

        if ($('#' + campaign_id + '_save').hasClass('uk-hidden')) {
            $('#' + campaign_id + '_save').removeClass('uk-hidden');
        }

    });

    $('.editable_brand').change(function () {
        var campaign_image = $(this).parent().parent().prev().prev().children()[0];

        //var next_children=$(this).parent().next().children();
        var campaign_id = $(campaign_image).attr('campaign_id');

        if ($('#' + campaign_id + '_save').hasClass('uk-hidden')) {
            $('#' + campaign_id + '_save').removeClass('uk-hidden');
        }
    })
    function editable(id){
        if ($('#' + id + '_save').hasClass('uk-hidden')) {
            $('#' + id + '_save').removeClass('uk-hidden');
        }
    }
    function editable_search(id){
        
        if ($('#' + id + '_search_save').hasClass('uk-hidden')) {
            $('#' + id + '_search_save').removeClass('uk-hidden');
        }
    }
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
    function delete_search_campaign(id) {


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

                $('#' + id + '_search_card').parent().remove();
                
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

    function save_campaign(id) {
            $('#' + id + '_save').addClass('uk-hidden');
            var title = $('#' + id + '_title').text();
            var description = $('#' + id + '_description').text();
            var code = $('#' + id + '_discount_code').text();
            var url = "https://apps.fabricspa.com/"+code;
            var start_date = $('#' + id + '_start_date').text();
            var end_date = $('#' + id + '_end_date').text();
            if(code == ""  || title == "" || description == "" || start_date == "" || end_date == ""){
                UIkit.notification({
                    message: 'Please add title,description,discount code, start date and end date',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
                return false;
            }else{
                var dateAr = start_date.split('-');
                if(dateAr == "")
                    var dateAr = start_date.split('/');
                if(dateAr.length != 3 || dateAr[0].length != 2 || dateAr[1].length != 2 || dateAr[2].length != 4){
                    UIkit.notification({
                    message: 'Please add start  date in the format mm-dd-yyyy',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
                    return false;
                }else if (dateAr[1] < 1 || dateAr[1] > 12){
                    UIkit.notification({
                    message: 'Please add start date in the format mm-dd-yyyy',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
                    return false;
                }else if (dateAr[0] < 1 || dateAr[0]> 31){
                    UIkit.notification({
                    message: 'Please add start date in the format mm-dd-yyyy',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
                    return false;
                }else if ((dateAr[1]==4 || dateAr[1]==6 || dateAr[1]==9 || dateAr[1]==11) && dateAr[0] ==31){
                    UIkit.notification({
                    message: 'Please add start date in the format mm-dd-yyyy',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
                    return false;
                }else if (dateAr[0] == 2)
                {
                    var isleap = (dateAr[2] % 4 == 0 && (dateAr[2] % 100 != 0 || dateAr[2] % 400 == 0));
                    if (dateAr[0]> 29 || (dateAr[0] ==29 && !isleap)){
                        UIkit.notification({
                        message: 'Please add start date in the format mm-dd-yyyy',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                        });
                        return false;
                    }
                }
                var dateAr1 = end_date.split('-');
                if(dateAr1 == "")
                    var dateAr1 = end_date.split('/');

                if(dateAr1.length != 3 || dateAr1[0].length != 2 || dateAr1[1].length != 2 || dateAr1[2].length != 4){
                    UIkit.notification({
                    message: 'Please add end date in the format mm-dd-yyyy',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
                    return false;
                }else if (dateAr1[1] < 1 || dateAr1[1] > 12){
                    UIkit.notification({
                    message: 'Please add end date in the format mm-dd-yyyy',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
                    return false;
                }else if (dateAr1[0] < 1 || dateAr1[0]> 31){
                    UIkit.notification({
                    message: 'Please add end date in the format mm-dd-yyyy',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
                    return false;
                }else if ((dateAr1[1]==4 || dateAr1[1]==6 || dateAr1[1]==9 || dateAr1[1]==11) && dateAr1[0] ==31){
                    UIkit.notification({
                    message: 'Please add end date in the format mm-dd-yyyy',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
                    return false;
                }else if (dateAr1[1] == 2)
                {
                    var isleap = (dateAr1[2] % 4 == 0 && (dateAr1[2] % 100 != 0 || dateAr1[2] % 400 == 0));
                    if (dateAr[0]> 29 || (dateAr[0] ==29 && !isleap)){
                        UIkit.notification({
                        message: 'Please add date in the format mm-dd-yyyy',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                        });
                        return false;
                    }
                }
                if (start_date > end_date){
            
                    UIkit.notification({
                        message: 'End date should not be less than start date',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                        });
                        $('#'+ id + '_end_date').focus();
                        return false;
                }
                    jQuery.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>" + "index.php/console_controller/update_campaign",
                    dataType: 'json',
                    data: {
                        campaign_id:id,
                        title: title,
                        desc:description,
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
                                message: 'Successfully updated',
                                status: 'success',
                                pos: 'bottom-center',
                                timeout: 1000
                            });
                            $('#' + id + '_save').addClass('uk-hidden');
                            $('#' + id + '_search_save').addClass('uk-hidden');
                            setTimeout(function () {
                                    location.reload();
                                }, 1500);
                        } else {
                            UIkit.notification({
                                message: 'Failed to update',
                                status: 'danger',
                                pos: 'bottom-center',
                                timeout: 1000
                            });
                        }


                    }
                });
            }
    }

    UIkit.upload('.js-upload', {
        url: base_url + 'index.php/console_controller/upload_campaign_image',
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
            var url = "https://apps.fabricspa.com/"+code;
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            var created_by = "<?php echo $username; ?>";
            if(code == "" || url == "" || title == "" || desc == "" || start_date == "" || end_date == ""){
                UIkit.notification({
                    message: 'Please add title,description,discount code, start date and end date',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
                return false;
            }else{
                if(start_date > end_date){
                    UIkit.notification({
                        message: 'Please add starting date and end date correctly',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });
                return false;
                }
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
                            end : end_date,
                            created_by : created_by
                        },
                        xhr: function () {
                            var xhr = new window.XMLHttpRequest();
                            //Download progress
                            xhr.addEventListener("progress", function (evt) {
                                if (evt.lengthComputable) {
                                   //var percentComplete = evt.loaded / evt.total;
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
    function search_campaign()
    {
        var start_date = $('#start').val();
        var end_date = $('#end').val();
        if(start_date == "" && end_date != ""){
            UIkit.notification({
                message: 'Please add  starting date',
                status: 'danger',
                pos: 'bottom-center',
                timeout: 1000
            });
            return false;
        }else if(start_date != "" && end_date == ""){
            UIkit.notification({
                message: 'Please add  ending date',
                status: 'danger',
                pos: 'bottom-center',
                timeout: 1000
            });
            return false;
        }
        $('#search_campaign_result').empty();
        jQuery.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>" + "console_controller/search_campaign",
                        cache:!1,
                        dataType:"json",
                        data: {
                            start : start_date,
                            end : end_date
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
                            $('#loading').show();
                            $.blockUI({
                                message: '<h1>Please wait...</h1>'

                            });
                            // $.blockUI();
                            // setTimeout(unBlock, 5000); 

                        },
                        complete: function (res) {

                            $.unblockUI();

                        },
                        success: function (res){

                            if($('#start').val() == "" && $('#end').val() == ""){
                                setTimeout(function () {
                                    location.reload();
                                }, 1500);
                                UIkit.notification({
                                        message: 'Successfully retrieved',
                                        status: 'success',
                                        pos: 'bottom-center',
                                        timeout: 1000
                                    });
                                $('#campaign_header').show();
                                $('#campaign_grid').show();
                                $('#search_campaign_result').hide();

                            }else{
                                $('#campaign_header').hide();
                                $('#campaign_grid').hide();
                                $('#campaign_search_details').hide();
                                if(res.length>0){
                                    UIkit.notification({
                                        message: 'Successfully Retreived ',
                                        status: 'success',
                                        pos: 'bottom-center',
                                        timeout: 1000
                                    });
                                    $('#start').val("");
                                    $('#end').val("");
                                    $('#campaign_header').show();
                                    $('#search_campaign_result').show();
                                    $('#campaign_grid').hide();
                                    
                                    for(var i=0;i<res.length;i++){
                                        var array = res[i].start_date.split('-');
                                        var start_date = array[2]+'-'+array[1]+'-'+array[0];
                                        var array = res[i].end_date.split('-');
                                        var end_date = array[2]+'-'+array[1]+'-'+array[0];
                                        $('#search_campaign_result').append(
                                        '<div class="uk-grid uk-grid-medium uk-child-width-1-3@m uk-grid-match" id="search">'+
                                        '<div class="uk-margin" id="campaign_search_card">' +
                                        '<div class="uk-card " id="'+res[i].Id+'_search_card">' +
                                        '<div class="uk-card-media-top">'+
                                            '<img src =' +res[i].image +' campaign_id =' + res[i].Id + ' style="width:700; height:120">'+
                                        '</div>'+
                                        '<div class="'+res[i].Id+'_show_search">'+
                                           
                                        '<a onclick="show_campaign_search_data('+res[i].Id+')">View More</a>'+
                                       

                                       
                                        '</div>'+
                                        '<div class="uk-hidden" id="'+res[i].Id+'_hide_search" >'+
                                            '<a onclick="hide_campaign_search_data('+res[i].Id+')">View Less</a>'+
                                        '</div>'+
                                        '<div id="'+res[i].Id+'_campaign_search_details">'+
                                        '<div class="uk-card-body" >' +
                                            '<h3 class="uk-card-title" onclick="editable_search('+res[i].Id+')" id="'+res[i].Id+'_title" contenteditable="true">'+res[i].title+'</h3>'+
                                            '<h3 class="uk-card-title" onclick="editable_search('+res[i].Id+')" id="'+res[i].Id+'_description" contenteditable="true">'+res[i].description+'</h3>'+
                                            '<h3 class="uk-card-title" onclick="editable_search('+res[i].Id+')" id="'+res[i].Id+'_discount_code" contenteditable="true">'+res[i].discount_code+'</h3>'+
                                            '<h5 class="uk-card-title" onclick="" id="'+res[i].Id+'_url" contenteditable="false">'+res[i].url+'</h5>'+
                                            '<h3 class="uk-card-title" onclick="editable_search('+res[i].Id+')" id="'+res[i].Id+'_start_date" contenteditable="true">'+start_date+'<h3>'+
                                            '<h3 class="uk-card-title" onclick="editable_search('+res[i].Id+')" id="'+res[i].Id+'_end_date" contenteditable="true">'+end_date+'<h3>'+
                                        '</div>' +
                                        '<div class="uk-card-footer">'+
                                            '<p>'+
                                                '<a onclick="delete_search_campaign('+res[i].Id+')"><span class="uk-margin-small-right" uk-icon="trash"></span>Delete</a>'+
                                            '</p>'+
                                            '<p>'+
                                                '<button onclick="save_campaign('+res[i].Id+')" id="'+res[i].Id+'_search_save" class="uk-button uk-button-default uk-hidden">Save changes</button>'+
                                            '</p>'+
                                        '</div>'+
                                        '</div>'+
                                        '</div>' +
                                        '</div>' +
                                        '</div>'+
                                        '</div>')
                                        
                                        $('#' + res[i].Id+'_campaign_search_details').hide();
                                    }
                                }else{
                                    UIkit.notification({
                                        message: 'No campaigns Found',
                                        status: 'success',
                                        pos: 'bottom-center',
                                        timeout: 1000
                                    });
                                }

                            }
                            
                        }, error: function (res) {

                            //console.log(res);
                        }

                    });
                
    }
    function download_campaign_report()
    {
        var start_date = $('#start').val();
        var end_date = $('#end').val();
        if(start_date == "" && end_date != ""){
            UIkit.notification({
                message: 'Please add  starting date',
                status: 'danger',
                pos: 'bottom-center',
                timeout: 1000
            });
            return false;
        }else if(start_date != "" && end_date == ""){
            UIkit.notification({
                message: 'Please add  ending date',
                status: 'danger',
                pos: 'bottom-center',
                timeout: 1000
            });
            return false;
        }
        if(start_date == "" && end_date == "")
            $warning = "Do you want to download all campaign details?";
        else
            $warning = "Do you want to download campaign details ?";
        UIkit.modal.confirm($warning).then(function () {
            jQuery.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>" + "index.php/console_controller/download_campaign_report",
                        dataType: 'json',
                        data: {
                            start : start_date,
                            end : end_date
                        },
                        xhr: function () {
                            var xhr = new window.XMLHttpRequest();
                            //Download progress
                            xhr.addEventListener("progress", function (evt) {
                                if (evt.lengthComputable) {
                                   //var percentComplete = evt.loaded / evt.total;
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
                                var fileName=res.file;
                                var path = "https://intapps.fabricspa.com/jfsl/excel_reports/Campaign_Reports/"+fileName; //relative-path
                                window.location.href = path;
                                UIkit.notification({
                                    message: 'Successfully downloaded',
                                    status: 'success',
                                    pos: 'bottom-center',
                                    timeout: 1000
                                });
                                $('#start').val("");
                                $('#end').val("")
                                setTimeout(function () {
                                    location.reload();
                                }, 1500);

                            } else {
                                if(res.size == 0){
                                UIkit.notification({
                                    message: 'No Data Found',
                                    status: 'danger',
                                    pos: 'bottom-center',
                                    timeout: 1000
                                });
                            }
                            }


                        }
                    });
        })
        
    }
</script>
<style>

.uk-margin-bottom{
    width: 50%;
}
#campaign_card{
    width:25%;
    height:10%;
}
.campaign_details_card{
    border-style: groove;
    margin-top :55%;
}
#search{
    display:inline-block;
}
.uk-card-title{
    word-break: break-word;
    overflow: hidden;
   -webkit-line-clamp: 2; /* number of lines to show */
   -webkit-box-orient: vertical;
}
</style>
