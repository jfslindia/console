<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/ratchet/2.0.2/css/ratchet.css" rel="stylesheet"/>
<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/jquery.blockUI.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<script type="text/javascript">
    $(document).ready(function () {
        $('#download_report').click(function () {
            var brand = $('#brand').val();
            var device = $('#device').val();
            var status = $('#status').val();
            var location = $('#location').val();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            if(start_date != ""){
                var dateAr = start_date.split('-');
                var start_date = dateAr[0].slice(-4) + '-'+dateAr[1] + '-' + dateAr[2];
            }
            if(end_date != ""){
                var dateAr = end_date.split('-');
                var end_date = dateAr[0].slice(-4) + '-'+dateAr[1] + '-' + dateAr[2];
            }
            if(brand == "no" && device == "no" && status == "no" && location == "no"){
                if(start_date == "" && end_date =="") {
                    UIkit.notification({
                            message: 'Please select any criteria',
                            status: 'danger',
                            pos: 'bottom-center',
                            timeout: 1000
                        });
                        return true;
                }else {
                   
                }
            }
            else{
                
            }   
            if(end_date !="" && start_date !="" && end_date < start_date ){
                UIkit.notification({
                    message: 'Please select a start date and end date correctly',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
                return true;
            }
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "console_controller/download_notification_report",
                dataType: 'json',
                data: {
                    device: device,
                    brand: brand,
                    location:location,
                    status:status,
                    start_date: start_date,
                    end_date:end_date
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
                    // setTimeout(unBlockUI, 5000); 

                },
                complete: function () {
                    $.unblockUI();

                },

                success: function (res) {
                   if (brand == "no")
                        var name="Fabspa_C2W";
                    else
                        var name=brand;
                    if (res.status == 'success') {
                        var fileName=res.file;
                        var path = "https://intapps.fabricspa.com/jfsl/excel_reports/"+name+"/"+fileName; //relative-path
                     
                        window.location.href = path;
                        $('#brand').val("no");
                        $('#device').val("no");
                        $('#status').val("no");
                        $('#location').val("no");
                        $('#start_date').val("");
                        $('#end_date').val("");
                    }
                },

                error: function (res) {
                    UIkit.notification({
                            message: 'Download Failed',
                            status: 'danger',
                            pos: 'bottom-center',
                            timeout: 1000
                        });
                }
            });         
        });
    });
</script>
<div id="page-1-container" class="uk-width-1-1@s uk-width-4-5@m uk-width-5-6@m uk-margin-auto-left content_wrapper uk-padding">
    <div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">
        <h3 class="uk-heading-divider uk-text-center">Download Options</h3>
         <div class="uk-grid" id="grid">
            <form class="uk-form-horizontal uk-width-expand">
                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Brand</label>
                    <div class="uk-form-controls">
                        <select class="uk-select" id="brand">
                            <option value="no">No specific brand </option>
                            <option value="Fabricspa">Fabricspa</option>
                            <option value="Click2Wash">Click2Wash</option>
                        </select>
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Device</label>
                    <div class="uk-form-controls">
                        <select class="uk-select" id="device">
                            <option value="no">No specific device </option>
                            <option value="android">Android</option>
                            <option value="ios">iOS</option>
                        </select>
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Status</label>
                    <div class="uk-form-controls">
                        <select class="uk-select" id="status">
                            <option value="no">No specific status </option>
                            <option value="Send">Send</option>
                            <option value="Not Send">Not Send</option>
                            <option value="Scheduled">Scheduled</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Location</label>
                    <div class="uk-form-controls">
                        <select id="location" class="uk-select">
                            <option value="no">No specific location</option>
                            <?php for ($i = 0; $i < sizeof($cities); $i++) { ?>

                                <option
                                    value="<?php echo $cities[$i]['CITYNAME']; ?>"><?php echo $cities[$i]['CITYNAME']; ?></option>

                            <?php } ?>


                        </select>
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Start Date</label>
                    <div class="uk-form-controls">
                        <input type="date" name="start_date"  id="start_date" max="<?= date('Y-m-d');?>" class="uk-textarea">
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">End Date</label>
                    <div class="uk-form-controls">
                        <input type="date" name="end_date" id="end_date"  max="<?= date('Y-m-d');?>" class="uk-textarea">
                    </div>
                </div>
                <div class="uk-margin" id="download_block">
                    <button id="download_report" name ="download_report" type="button"class="uk-button uk-button-primary uk-width-1-1">Download</button>
                </div>
            </form>
         </div>
    </div>
    <a href="<?php echo site_url('Console_Controller/admin_send_notifications');?>">Back To Send Page</a>
</div>