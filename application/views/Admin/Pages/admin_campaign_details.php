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
<a href="<?php echo base_url('console/campaigns');?>">Back</a><br><br>
    <div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">
        <h3 class="uk-heading-divider uk-text-center">Campaign Details</h3>
        <div class="uk_card uk-text-center" id="campaign_details_card" >
        <div class="uk-card-body">
        
            <img src="<?php echo $campaigns[0]['image']; ?>">
           <h3 class="uk-card-title" onclick="editable()" id="title"
                contenteditable="true"><?php echo $campaigns[0]['title']; ?></h3></h3>
                <h3 class="uk-card-title" onclick="editable()" id="description"
                contenteditable="true"><?php echo $campaigns[0]['description']; ?></h3>
                <h3 class="uk-card-title" onclick="editable()" id="discount_code"
                contenteditable="true"><?php echo $campaigns[0]['discount_code']; ?></h3>
                <h5 class="uk-card-title " id="url"
                contenteditable="false"><?php echo $campaigns[0]['url']; ?></h5>
                <h3 class="uk-card-title" onclick="editable()" id="start_date"
                contenteditable="true"><?php echo date('d-m-Y',strtotime($campaigns[0]['start_date'])); ?></h3>
                <h3 class="uk-card-title" onclick="editable()" id="end_date"
                contenteditable="true"><?php echo date('d-m-Y',strtotime($campaigns[0]['end_date'])); ?></h3>
        </div>
        <div class="uk-card-footer">
            <p>
                <a href="<?php echo site_url('console/delete_campaign/'.$campaigns[0]['Id']);?>" onclick="return confirm('Are you sure do you want to delete this campaign?')"><span
                        class="uk-margin-small-right" uk-icon="trash"></span>Delete</a>
            </p>
            <p>
                <button onclick="update_campaign(<?php echo $campaigns[0]['Id'];?>)"
                        id="save"
                        class="uk-button uk-button-default uk-hidden">Save changes
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
    function editable(){
        if ($('#save').hasClass('uk-hidden')) {
            $('#save').removeClass('uk-hidden');
        }
    }
    function update_campaign(id) {
            var title = $('#title').text();
            var description = $('#description').text();
            var code = $('#discount_code').text();
            var url = "https://apps.fabricspa.com/"+code;
            var start_date = $('#start_date').text();
            var end_date = $('#end_date').text();
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
                
        
                if (dateAr[2] > dateAr1[2]){

                    UIkit.notification({
                        message: 'End date should not be less than start date',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                        });
                        $('#'+ id + '_end_date').focus();
                        return false;
                }else if(dateAr[2] == dateAr1[2]){
                    if(dateAr[1] > dateAr1[1]){
                        UIkit.notification({
                        message: 'End date should not be less than start date',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                        });
                        $('#'+ id + '_end_date').focus();
                        return false;
                    }else if(dateAr[1] == dateAr1[1]){
                        if(dateAr[0] > dateAr1[0]){
                            UIkit.notification({
                            message: 'End date should not be less than start date',
                            status: 'danger',
                            pos: 'bottom-center',
                            timeout: 1000
                            });
                            $('#'+ id + '_end_date').focus();
                            return false;
                        }
                    }
                    
                }else{}   
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
                       
                    },
                    complete: function () {
                        
                    },
                    success: function (res) {

                        if (res.status == 'success') {
                            UIkit.notification({
                                message: 'Successfully updated',
                                status: 'success',
                                pos: 'bottom-center',
                                timeout: 1000
                            });
                            $('#save').addClass('uk-hidden');
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
</script>