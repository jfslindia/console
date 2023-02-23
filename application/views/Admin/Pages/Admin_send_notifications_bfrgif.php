<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
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
<script type="text/javascript">

    $(document).ready(function () {
        <?php for($j=0;$j< sizeof($states);$j++){?>
      $('#<?php echo $j;?>').hide();
    <?php }?>
    $("#state").change(function(){
        $('input[type="checkbox"]').prop("checked", false);
        $('#radio').prop("checked",false);
        $('#cities').val("");
        var statecode = $('#state').val();
        if(statecode == <?php echo $total_cities;?>){
            <?php for($j=0;$j< sizeof($states);$j++){?>
                $('#<?php echo $j;?>').hide();
            <?php }?>
        }
        if(statecode != ""){
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
                    if($('input[type=radio][name=automate]:checked').val() == 'yes'){
                        $('.checkbox').hide();
                        $('.radio').show();
                    }else{
                        $('.radio').hide();
                        $('.checkbox').show();
                    }
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
    $('input[type=radio][name=automate]').change(function(){
        $('input[type="checkbox"]').prop("checked", false);
        $('#radio').prop("checked",false);
        $('#cities').val("");
        var statecode = $('#state').val();
        if(statecode != ""){
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
                    if($('input[type=radio][name=automate]:checked').val() == 'yes'){
                        $('.checkbox').hide();
                        $('.radio').show();
                    }else{
                        $('.radio').hide();
                        $('.checkbox').show();
                    }
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
        }    
    });
        $("#excelfile").change(function(){
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;  
            /*Checks whether the file is a valid excel file*/  
            if (regex.test($("#excelfile").val().toLowerCase())) {  
                var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/  
                if ($("#excelfile").val().toLowerCase().indexOf(".xlsx") > 0) {  
                    xlsxflag = true;  
                }  
                /*Checks whether the browser supports HTML5*/  
                if (typeof (FileReader) != "undefined") {  
                    var reader = new FileReader();  
                    reader.onload = function (e) {  
                        var data = e.target.result;  
                        /*Converts the excel data in to object*/  
                        if (xlsxflag) {  
                            var workbook = XLSX.read(data, { type: 'binary' });  
                        }  
                        else {  
                            var workbook = XLS.read(data, { type: 'binary' });  
                        }  
                        /*Gets all the sheetnames of excel in to a variable*/  
                        var sheet_name_list = workbook.SheetNames;  
                        var list =$('#list_via').val();
                        var cnt = 0; /*This is used for restricting the script to consider only first sheet of excel*/  
                        sheet_name_list.forEach(function (y) { /*Iterate through all sheets*/  
                            /*Convert the cell value to Json*/  
                            if (xlsxflag) {  
                                var exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[y]);  
                            }  
                            else {  
                                var exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);  
                            }  
                            if (exceljson.length > 0 && cnt == 0) { 

                                BindTable(exceljson, '#'+list+'_exceltable',list);
                                cnt++;  
                            }  
                        });
                        $('#'+list+'_exceltable').hide(); 
                        $('#'+list+'s').attr('readonly', true);
                        $('#'+list+'s').addClass('input-disabled');
                        
                        // $('#'+list+'_exceltable').show();
                        // $('#emails').hide();
                        // $('#mobile_numbers').hide();
                        // $('#gcmids').hide();
                        // $('#list').hide();
                    }  
                    if (xlsxflag) {/*If excel file is .xlsx extension than creates a Array Buffer from excel*/  
                        reader.readAsArrayBuffer($("#excelfile")[0].files[0]);  
                    }  
                    else {  
                        reader.readAsBinaryString($("#excelfile")[0].files[0]);  
                    }  
                }  
                else {  
                    // alert("Sorry! Your browser does not support HTML5!");  
                }
            }  
            else {  
                UIkit.notification({
                    message: 'Please upload a valid Excel file!',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
            }  
        });
        $('#send_notification_block').hide();
        $('#location_limit_block').hide();
        $('#schedule_block').hide();
        $('#tnc_block').hide();
        $('#notification_send_block').hide();
        $('#automate_block').hide();
        $('#emails').show();
        $('#mobile_numbers').hide();
        $('#gcmids').hide();
        $('#show_details_block').show();
        $('#send_to').change(function () {
            if ($('#send_to').val() == 'all') {
                $('#tnc_block').show();
                $(":checkbox").attr("checked", false);
                
                $('#list_via_block').hide();
                $('#receivers_list_block').hide();
                $('#location_limit_block').show();
                $('#automate_block').show();
                $('#get_gcmid_block').hide();
                $('#notification_send_block').show();
                $('#result').val("");
                $('#result_block').hide();
                if ($('input[type=radio][name=schedule]:checked').val() == 'yes') {
                    $('#send_notification_block').show();
                    $('#schedule_block').hide();

                }else{
                    $('#send_notification_block').hide();
                    $('#schedule_block').show();

                }

            } else if ($('#send_to').val() == 'selected') {
                $('#list_via_block').show();
                $('#send_notification_block').hide();
                $('#location_limit_block').hide();
                $('#receivers_list_block').show();
                $('#automate_block').hide();
                $('#get_gcmid_block').show();
                $('#notification_send_block').hide();
                $('#list_via').val == 'email';
                $("#list_via option[value='email']").attr('selected', 'selected');
                $('#schedule_block').hide();
                $(":checkbox").attr("checked", false);
                $('#tnc_block').hide();

            }

        });
            $("#schedule_date").change(function(){
        $('#time_slot').val("");
        var schedule_date = $('#schedule_date').val();
        var tdate = new Date();
        var dd = tdate.getDate(); //yields day
        if(dd<10){
              var dd="0"+dd;
        }
        var MM = tdate.getMonth(); //yields month
        if(MM+1 < 10){
            var MM = "0"+(MM+1);
        }
        var yyyy = tdate.getFullYear(); //yields year
        var today = yyyy+"-"+MM+"-"+dd;
        if(schedule_date == today){
            var currentTime = new Date().getTime();
            var timeslot1 = Date.parse(today+" 8:00:00");
            var timeslot2 = Date.parse(today+" 9:00:00");
            var timeslot3 = Date.parse(today+" 10:00:00");
            var timeslot4 = Date.parse(today+" 11:00:00");
            var timeslot5 = Date.parse(today+" 12:00:00");
            var timeslot6 = Date.parse(today+" 13:00:00");
            var timeslot7 = Date.parse(today+" 14:00:00");
            var timeslot8 = Date.parse(today+" 15:00:00");
            var timeslot9 = Date.parse(today+" 16:00:00");
            var timeslot10 = Date.parse(today+" 17:00:00");
            var timeslot11 = Date.parse(today+" 18:00:00");
            var timeslot12 = Date.parse(today+" 19:00:00");
            if(currentTime < timeslot1)
            {
                $('#timeslots').show();     
            }else if(currentTime < timeslot2){
                $('#timeslots').show();
                $('#time1').hide();
            }else if(currentTime < timeslot3){
                $('#timeslots').show();
                $('#time1').hide();
                $('#time2').hide();
            }else if(currentTime < timeslot4){
                $('#timeslots').show();
                $('#time1').hide();
                $('#time2').hide();
                $('#time3').hide();
            }else if(currentTime < timeslot5){
                $('#timeslots').show();
                $('#time1').hide();
                $('#time2').hide();
                $('#time3').hide();
                $('#time4').hide();
            }else if(currentTime < timeslot6){
                $('#timeslots').show();
                $('#time1').hide();
                $('#time2').hide();
                $('#time3').hide();
                $('#time4').hide();
                $('#time5').hide();
            }else if(currentTime < timeslot7){
                $('#timeslots').show();
                $('#time1').hide();
                $('#time2').hide();
                $('#time3').hide();
                $('#time4').hide();
                $('#time5').hide();
                $('#time6').hide();
            }else if(currentTime < timeslot8){
                $('#timeslots').show();
                $('#time1').hide();
                $('#time2').hide();
                $('#time3').hide();
                $('#time4').hide();
                $('#time5').hide();
                $('#time6').hide();
                $('#time7').hide();
            }else if(currentTime < timeslot9){
                $('#timeslots').show();
                $('#time1').hide();
                $('#time2').hide();
                $('#time3').hide();
                $('#time4').hide();
                $('#time5').hide();
                $('#time6').hide();
                $('#time7').hide();
                $('#time8').hide();
            }else if(currentTime < timeslot10){
                $('#timeslots').show();
                $('#time1').hide();
                $('#time2').hide();
                $('#time3').hide();
                $('#time4').hide();
                $('#time5').hide();
                $('#time6').hide();
                $('#time7').hide();
                $('#time8').hide();
                $('#time9').hide();
            }else if(currentTime < timeslot11){
                $('#timeslots').show();
                $('#time1').hide();
                $('#time2').hide();
                $('#time3').hide();
                $('#time4').hide();
                $('#time5').hide();
                $('#time6').hide();
                $('#time7').hide();
                $('#time8').hide();
                $('#time9').hide();
                $('#time10').hide();
            }else if(currentTime < timeslot12){
                $('#timeslots').show();
                $('#time1').hide();
                $('#time2').hide();
                $('#time3').hide();
                $('#time4').hide();
                $('#time5').hide();
                $('#time6').hide();
                $('#time7').hide();
                $('#time8').hide();
                $('#time9').hide();
                $('#time10').hide();
                $('#time11').hide();
            }else{
                $('#timeslots').hide();   
            }
        }else{
            $('#timeslots').show();
            $('#time0').show();
            $('#time1').show();
            $('#time2').show();
            $('#time3').show();
            $('#time4').show();
            $('#time5').show();
            $('#time6').show();
            $('#time7').show();
            $('#time8').show();
            $('#time9').show();
            $('#time10').show();
            $('#time11').show();
            $('#time12').show();
        }
    });
        $('#schedule').change(function () {
            
            if ($('input[type=radio][name=schedule]:checked').val() == 'yes') {
                    $('#send_notification_block').show();
                    $('#schedule_date').val("");
                    $('#time_slot').val("");
                    $('#schedule_block').hide();
                    

            }else{
                $('#send_notification_block').hide();
                $('#schedule_date').val("");
                $('#time_slot').val("");
                $('#timeslots').hide();
                $('#schedule_date_block').show();
            }
        });
        $('#list_via').change(function () {
            $('#list').show();
            if ($('#list_via').val() == 'email') {
                $('#get_gcmid_block').show();
                $('#result_block').hide();
                $('#send_notification_block').hide();
                $('#emails').show();
                $('#mobile_numbers').hide();
                $('#gcmids').hide();
                $('#list').show();
                // $('#email_exceltable').html("");
                $("#email_exceltable tr").empty();
                $("#email_exceltable").hide();
                $('#excelfile').val("");
            } else if ($('#list_via').val() == 'mobile_number') {
                $('#get_gcmid_block').show();
                $('#result_block').hide();
                $('#send_notification_block').hide();
                $('#mobile_numbers').show();
                $('#emails').hide();
                $('#gcmids').hide();
                $('#list').show();
                $('#mobile_number_exceltable').html("");
                $('#excelfile').val("");
            } else if ($('#list_via').val() == 'gcmid') {
                $('#get_gcmid_block').hide();
                $('#result_block').hide();
                $('#send_notification_block').show();
                $('#gcmids').show();
                $('#emails').hide();
                $('#mobile_numbers').hide();
                $('#list').show();
                $('#gcmid_exceltable').html("");
                $('#excelfile').val("");
            }
        });

        $('#get_gcmid').click(function () {
            if ($('#send_to').val() == 'selected') {
                var brand = $('#brand').val();
                var device = $('#device').val();
                var via = $('#list_via').val();
                var TableData='';
                var is_file="0";
                $('#'+via+'_exceltable tr').each(function(row, tr){
                    TableData = TableData 
                        + $(tr).find('td:eq(0)').text() + ' '
                        +',';
                });
                var data=TableData;
                var list="";
                if(data){
                    var raw_inputs = data.replace(/ /g, '')
                    var list = raw_inputs.split(',');
                    var is_file="1";
                }
                if(list == "") {
                    var via_element = '#' + via + 's';
                    var inputs = $(via_element).val();
                    var raw_inputs = inputs.replace(/ /g, '')
                    var list = raw_inputs.split(',');    
                } 
                if(list !=""){
                    var size=list.length;
                    if(size < 1000) {
                        jQuery.ajax({
                            type: "POST",
                            url: "<?php echo base_url(); ?>" + "console_controller/get_gcmids",
                            dataType: 'json',
                            data: {
                                via: via,
                                device: device,
                                list: list,
                                brand: brand,
                                is_file:is_file
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
                            complete: function () {
                                $.unblockUI();
                            },
                            success: function (res) {
                                if (res.status == 'success') {
                                    var output = '';
                                    for (var i = 0; i < res.gcmids.length; i++) {
                                        if (i != res.gcmids.length - 1)
                                            output = output + res.gcmids[i].gcmid + ',';
                                        else
                                            output = output + res.gcmids[i].gcmid;
                                    }
                                    $('#result').val(output);
                                    $('#result_block').show();
                                    $('#send_notification_block').show();
                                    $('#notification_send_block').show();
                                    $('#tnc_block').show();
                                    $('input[type=radio][name=automate]:checked').val("yes");
                                    $('input[type=radio][name=schedule]:checked').val("yes");

                                }
                                else if (res.status == 'failed') {
                                    $('#result_block').hide();
                                    $('#send_notification_block').hide();
                                    $('#result').val('');
                                    UIkit.notification({
                                        message: 'No GCMIDs available',
                                        status: 'danger',
                                        pos: 'bottom-center',
                                        timeout: 1000
                                    });
                                }


                            }, error: function (res) {

                                console.log(res);
                            }

                        });
                    }else{
                        output="Gcmids are not visible for limit from 1000. So please click send button";
                        $('#result').val(output);
                        $('#notification_send_block').show();
                        $('#result_block').show();
                        $('#send_notification_block').show();
                    }
                }else {
                    UIkit.notification({
                                    message: 'No data is provided',
                                    status: 'danger',
                                    pos: 'bottom-center',
                                    timeout: 1000
                                });
                }

            } 
            else {
                var brand = $('#brand').val();
                var device = $('#device').val();
                var state = $('#state').val();
                var start = $('#start').val();
                var limit = $('#limit').val();
                if(state != ""){
                    if(isNaN(state)){
                        var location = [];
                        $(':checkbox:checked').each(function(i){
                        location[i] = $(this).val();
                        });
                        var total = location.length * limit;
                    }else{
                        var location = "all";
                        var state = "all";
                        var total = $('#state').val();
                    }
                    
                }else{
                    var location = "";
                    var total = limit;
                }  
                if (total < 1000) {
                    jQuery.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>" + "console_controller/get_all_gcmids",
                       dataType: 'json',
                        data: {
                            brand: brand,
                            device: device,
                            state : state,
                            location: location,
                            start: start,
                            limit: limit
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
                           // $('#loading').show();
                            $.blockUI({
                                message: '<h1>Please wait...</h1>'

                            });
                            // $.blockUI();
                            // setTimeout(unBlock, 5000); 

                        },
                        complete: function () {

                            $.unblockUI();

                        },
                        success: function (res) {
                            if (res.status == 'success') {
                                if(state == ""){
                                    var output = '';
                                    for (var i = 0; i < res.gcmids.length; i++) {
                                        if (i != res.gcmids.length - 1)
                                            output = output + res.gcmids[i].gcmids + ',';
                                        else
                                            output = output + res.gcmids[i].gcmids;
                                    }
                                }else{
                                    var output = '';
                                    for(var j=0; j < res.gcmids.length; j++){
                                        for (var i = 0; i < res.gcmids[j].length; i++) {
                                           if (i != res.gcmids[j].length - 1)
                                                output = output + res.gcmids[j][i].gcmids + ',';
                                            else
                                                output = output + res.gcmids[j][i].gcmids;
                                        }
                                        if(j != res.gcmids.length -1)
                                            output = output + ',';
                                    }
                                }
                                $('#result').val(output);
                                $('#result_block').show();
                                $('#send_notification_block').show();
                                $('#notification_send_block').show();
                            }
                            else if (res.status == 'failed') {
                                $('#result_block').hide();
                                $('#send_notification_block').hide();
                                $('#result').val('');
                                UIkit.notification({
                                    message: 'No GCMIDs available',
                                    status: 'danger',
                                    pos: 'bottom-center',
                                    timeout: 1000
                                });
                            }


                        }, error: function (res) {

                            //console.log(res);
                        }

                    });
                }else{
                    output="Gcmids are not visible  from 1000 . So please click send button";
                    $('#result').val(output);
                    $('#result_block').show();
                    $('#send_notification_block').show();
                    $('#notification_send_block').show();
                }

            }

        });
        $('#send_notification').click(function () {
            var device = $('#device').val();
            var via = $('#list_via').val();
            var is_file="0";
            var schedule_date="today";   
            if($('#result').val() != ""){
                if($('#result').val() == "Gcmids are not visible for limit from 1000. So please click send button"){
                    var TableData='';
                    $('#'+via+'_exceltable tr').each(function(row, tr){
                        TableData = TableData 
                            + $(tr).find('td:eq(0)').text() + ' '
                            +',';
                    });
                    var data=TableData;
                    if(data){
                        var raw_inputs = data.replace(/ /g, '')
                        var to_sent = raw_inputs.split(',');
                        var is_file="1";    
                    }
                }else{
                    var to_sent = $('#result').val().split(',');
                }
            }else if($('#'+via+'s').val()) {
                var to_sent = $('#'+via+'s').val().split(',');
            } else {
                var to_sent="";
            }
            var title = $('#title').val();
            var image_url = $('#image_url').val();
            var brand = $('#brand').val();
            var message = $('#message').val();
            var state = $('#state').val();
            var start = $('#start').val();
            var limit = $('#limit').val();
            var user = "<?php echo $username; ?>";
            if(state != ""){
                if(isNaN(state)){
                    var location = [];
                    $(':checkbox:checked').each(function(i){
                        if($(this).val() != "on")
                            location[i] = $(this).val();
                    });
                    var total = location.length * limit;
                }else{
                    var location = "all";
                    var state = "all";
                    var total = $('#state').val()*limit;
                }
                
            }else{
                var location = "";
                var total = limit;
            }
            if(document.getElementById('tnc_status').checked){
                var tnc_status = 1;
            }else{
                var tnc_status = 0;
            }
            if(state != "" && state != "all"){
                var location = [];
                $(':checkbox:checked').each(function(i){
                if($(this).val() != "on")
                    location[i] = $(this).val();
                });
                var total = location.length * limit;
            }else if(state == "all"){
                var location = "all";

            }else{
                var location = "";
                var total = limit;
            }
            if ($('#send_to').val() == 'all') {
                var automate = "true";
            }else {
                var automate = "false";
            }
            var date = GetTodayDate() ;
            if(title == "" && message == "" && image_url == ""){
                UIkit.notification({
                    message: 'No content is provided',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
                return true;
            }
            if(title == ""){
                UIkit.notification({
                    message: 'Please add a title',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
                return true;
            }
            if(image_url) {
                var ext = $('#image_url').val().split('.').pop().toLowerCase();
                var size=$('#image_url')[0].files[0].size;       
                if (size > 160658) {
                    UIkit.notification({
                        message: 'File too Big, please select a file less than or upto 160KB',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });
                    return false;
                }else{

                    if($.inArray(ext, ['jpg']) == -1) {
                        UIkit.notification({
                            message: 'Only jpg files are allowed',
                            status: 'danger',
                            pos: 'bottom-center',
                            timeout: 1000
                        });
                    return false;
                    }
                }
                var txt = image_url.substr(12);
				var time = $.now();
                var time = time.toString().slice(0,6);
                var txt = time+txt;
                var url="https://intapps.fabricspa.com/jfsl/console_notification_images/"+txt;
                var image_url=url;
                var file_data = $('#image_url').prop('files')[0];   
                var form_data = new FormData();                  
                form_data.append('file', file_data);
                                 
                $.ajax({
                    url: 'https://intapps.fabricspa.com/jfsl/upload.php', // <-- point to server-side PHP script 
                    dataType: 'text',  // <-- what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,                         
                    type: 'post',
                    success: function(php_script_response){
                        // alert(php_script_response); // <-- display response from the PHP script,            
                    }
                });
            }
                
            if(automate == "true"){
                var flag = 1;
                send_automated_gcmids(start, limit,flag);
            }else {
                if(to_sent == "(null)") {
                    UIkit.notification({
                        message: 'Failed',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });
                    return false;
                }
                var list= $('#list_via').val();
                jQuery.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>" + "console_controller/send_selected_gcmids",
                        dataType: 'json',
                        data: {
                            device: device,
                            to_sent: to_sent,
                            title: title,
                            image_url: image_url,
                            brand: brand,
                            message: message,
                            location:location,
                            is_file:is_file,
                            via:via,
                            schedule_date:schedule_date,
                            user: user,
                            tnc_status:tnc_status,
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
                            // $('#loading').show();
                            $.blockUI({

                                message: '<h1>Please wait...</h1>'

                            });
                            // $.blockUI();
                            // setTimeout(unBlock, 50); 

                        },
                        complete: function (res) {
                        
                            $.unblockUI();

                        },
                        success: function (res) {  
                            // $('#download_report_block').show();
                            if (res.status == 'success') {

                                UIkit.notification({
                                    message:'Successfully sent',
                                    status: 'success',
                                    pos: 'bottom-center',
                                    timeout: 1000
                                });
                            $('#notification_send_block').hide();
                            $('#schedule_block').hide();
                            $('#brand').val("Fabricspa");
                            $('#device').val("android");
                            $('#send_to').val("selected");
                            $('#title').val("");
                            $('#image_url').val("");
                            $('#message').val("");
                            $('#list_via_block').show();
                            $('#list_via').val("email");
                            $('#emails').val("");
                            $('#mobile_numbers').val("");
                            $('#gcmids').val("");
                            $('#download_report_block').hide();
                            $('#send_notification_block').hide();
                            $('#result_block').hide();
                            $("#"+list+"_exceltable tr").empty();
                            $('#'+list+'s').show();
                            $('#'+list+'_exceltable').show();
                            $('#list').show();
                            $('#excelfile').val("");
                            $('#'+list+'s').attr('readonly', false);
                            $('#'+list+'s').addClass('input-enabled');
                            $(":checkbox").attr("checked", false);
                            $('#tnc_block').hide();

                            }
                        },

                        error: function (res) {
                                // console.log(res)
                        }
                    });
                

            }
        

        });
        $('#schedule_notification').click(function () {
            var schedule_date = $('#schedule_date').val();
            var device = $('#device').val();
            var via = $('#list_via').val();
            var is_file="0";
            if($('#result').val() != ""){
                if($('#result').val() == "Gcmids are not visible for limit from 1000. So please click send button"){
                    var TableData='';
                    $('#'+via+'_exceltable tr').each(function(row, tr){
                        TableData = TableData 
                            + $(tr).find('td:eq(0)').text() + ' '
                            +',';
                    });
                    var data=TableData;
                    if(data){
                        var raw_inputs = data.replace(/ /g, '')
                        var to_sent = raw_inputs.split(',');
                        var is_file="1";    
                    }
                }else{
                    var to_sent = $('#result').val().split(',');
                }
            }else if($('#'+via+'s').val()) {
                var to_sent = $('#'+via+'s').val().split(',');
            } else {
                var to_sent="";
            }  
            var title = $('#title').val();
            var image_url = $('#image_url').val();
            var brand = $('#brand').val();
            var message = $('#message').val();
            var state = $('#state').val();
            var start = $('#start').val();
            var limit = $('#limit').val();
            var user = "<?php echo $username; ?>";
            var time_slot = $('#time_slot').val();
            if(document.getElementById('tnc_status').checked){
                var tnc_status = 1;
            }else{
                var tnc_status = 0;
            }
            if(state != ""){
                if(isNaN(state)){
                    var location = [];
                    $(':checkbox:checked').each(function(i){
                        if($(this).val() != "on")
                            location[i] = $(this).val();
                    });
                    var total = location.length * limit;
                }else{
                    var location = "all";
                    var state = "all";
                    var total = $('#state').val();
                }
                
            }else{
                var location = "";
                var total = limit;
            }            
            if ($('#send_to').val() == 'all') {
                var automate = "true";
            }else {
                var automate = "false";
            }
            if(title == "" && message == "" && image_url == ""){
                UIkit.notification({
                    message: 'No content is provided',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
                return true;
            }
            if(title == ""){
                UIkit.notification({
                    message: 'Please add a title',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
                return true;
            }
            if(schedule_date == "" || time_slot == "") {
                UIkit.notification({
                    message: 'Please select a date and time',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });

                return true;
            }
            if(image_url) {
                var ext = $('#image_url').val().split('.').pop().toLowerCase();
                var size=$('#image_url')[0].files[0].size;
                
                if (size > 160658) {
                    UIkit.notification({
                        message: 'File too Big, please select a file less than or upto 160KB',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });
                    return false;
                }else{

                    if($.inArray(ext, ['jpg']) == -1) {
                        UIkit.notification({
                            message: 'Only jpg files are allowed',
                            status: 'danger',
                            pos: 'bottom-center',
                            timeout: 1000
                        });
                    return false;
                    }
                }
                var txt = image_url.substr(12);
				var time = $.now();
                var time = time.toString().slice(0,6);
                var txt = time+txt;
                var url="https://intapps.fabricspa.com/jfsl/console_notification_images/"+txt;
                var image_url=url;
                var file_data = $('#image_url').prop('files')[0];   
                var form_data = new FormData();                  
                form_data.append('file', file_data);                             
                $.ajax({
                    url: 'https://intapps.fabricspa.com/jfsl/upload.php', // <-- point to server-side PHP script 
                    dataType: 'text',  // <-- what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,                         
                    type: 'post',
                    success: function(php_script_response){
                        // alert(php_script_response); // <-- display response from the PHP script, if any
                        
                    }
                });
               
            }

            if(automate == "true"){
                var flag="1";
                schedule_automated_gcmids(start, limit,flag);
            }else {
                if(to_sent == "(null)") {
                    UIkit.notification({
                        message: 'Failed',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });
                    return false;
                }
                var list= $('#list_via').val();
                jQuery.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>" + "console_controller/schedule_selected_notifications",
                        // dataType: 'json',
                        data: {
                            device: device,
                            to_sent: to_sent,
                            title: title,
                            image_url: image_url,
                            brand: brand,
                            message: message,
                            location:location,
                            is_file:is_file,
                            schedule_date:schedule_date,
                            via:list,
                            user: user,
                            time_slot : time_slot,
                            tnc_status:tnc_status
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
                            // $('#loading').show();
                            $.blockUI({

                                message: '<h1>Please wait...</h1>'

                            });
                            // $.blockUI();
                            // setTimeout(unBlock, 50); 

                        },
                        complete: function (res) {
                        
                            $.unblockUI();

                        },
                        success: function (res) {  
                            // if (res.status == 'success') {

                                UIkit.notification({
                                    message:'Scheduled Successfully',
                                    status: 'success',
                                    pos: 'bottom-center',
                                    timeout: 1000
                                });
                            window.location.reload(true);
                        },

                        error: function (res) {
                                // console.log(res)
                        }
                    });
                

            }
        

        });
       

    });
    
    function send_automated_gcmids(start, limit,flag) {
        var device = $('#device').val();
        var via  = $('#via').val();
        var title = $('#title').val();
        var image_url = $('#image_url').val();
        var brand = $('#brand').val();
        var message = $('#message').val();
        var state = $('#state').val();
        var automate = $('input[type=radio][name=automate]:checked').val();
        var location = [];
        var user = "<?php echo $username; ?>";
        var total_cities = "<?php echo $total_cities;?>";
       if(document.getElementById('tnc_status').checked){
            var tnc_status = 1;
        }else{
            var tnc_status = 0;
        }
        if(state != "" && state != total_cities){
            $(':checkbox:checked').each(function(i){
                if($(this).val() != "on")
                    location[i] = $(this).val();
            });
            var no = location.length;
        }else if(state == total_cities){
            var location = "all";
            var no = 1;
        }else{
            var location = "";
            var no = 0;
        }
        var via="NULL";
        if(image_url) {
                var ext = $('#image_url').val().split('.').pop().toLowerCase();
                var size=$('#image_url')[0].files[0].size;
                
                if (size > 160658) {
                    UIkit.notification({
                        message: 'File too Big, please select a file less than 4mb',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });
                    return false;
                }else{
                    if($.inArray(ext, ['jpg']) == -1) {
                        UIkit.notification({
                            message: 'Only jpg files are allowed',
                            status: 'danger',
                            pos: 'bottom-center',
                            timeout: 1000
                        });
                    return false;
                    }
                }
                var txt = image_url.substr(12);
				var time = $.now();
                var time = time.toString().slice(0,6);
                var txt = time+txt;
                var url="https://intapps.fabricspa.com/jfsl/console_notification_images/"+txt;
                var image_url=url;
                var file_data = $('#image_url').prop('files')[0];   
                var form_data = new FormData();                  
                form_data.append('file', file_data);
                $.ajax({
                    url: 'https://intapps.fabricspa.com/jfsl/upload.php', // <-- point to server-side PHP script 
                    dataType: 'text',  // <-- what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,                         
                    type: 'post',
                    success: function(php_script_response){
                        // alert(php_script_response); // <-- display response from the PHP script, if any
                        
                    }
                });
               
                
            }

           

        if ($('#send_to').val() == 'all') {
            var automate = "true";
        } else {
            var automate = "false";
        }
        if(no <= 0){
            var city ="";
            jQuery.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>" + "console_controller/send_automated_gcmids",
                        dataType: 'json',
                        data: {
                            device: device,
                            title: title,
                            image_url: image_url,
                            brand: brand,
                            message: message,
                            automate: automate,
                            start: start,
                            limit: limit,
                            location: location,
                            city : city,
                            via:via,
                            flag:flag,
                            user: user,
                            tnc_status:tnc_status
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
                            if (res.status == 'success') {
                                UIkit.notification({
                                    message: 'Successfully sent',
                                    status: 'success',
                                    pos: 'bottom-center',
                                    timeout: 1000
                                });
                                
                                if (res.size != limit) {
                                        $('#send_notification_block').hide();
                                        $('#notification_send_block').hide();
                                        $('#schedule_block').hide();
                                        $('#brand').val("Fabricspa");
                                        $('#device').val("android");
                                        $('#send_to').val("selected");
                                        $('#title').val("");
                                        $('#image_url').val("");
                                        $('#message').val("");
                                        $('#location').val("");
                                        $('#list_via_block').show();
                                        $('#location_limit_block').hide();
                                        $('#limit_block').hide();
                                        $('#automate_block').hide();
                                        $('#receivers_list_block').show();
                                        $('input[type="checkbox"]').prop("checked", false);
                                        $('#cities').val("");
                                        $('#state').val("");
                                        <?php for($j=0;$j< sizeof($states);$j++){?>
                                            $('#<?php echo $j;?>').hide();
                                        <?php }?>
                                        $('input[type="checkbox"]').prop("checked", false);
                                        $('#radio').prop("checked",false);
                                        $('#get_gcmid_block').show();
                                        $(":checkbox").attr("checked", false);
                                        $('#tnc_block').hide();

                                    return true;
                                }
                                var next = parseInt(start) + 299;
                                setTimeout(
                                    function () {
                                        var flag=2;
                                        send_automated_gcmids(next, limit,flag)
                                    }, 3000);
                            }
                        
                        
                        },
                        error: function (res) {
                            //console.log(res);
                        }
                        
                    });
        }else{
            for(var n=0;n<no;n++){
                if(n > 0)
                    flag = 2;
                if(state != "all"){
                    if(state == "")
                        city = "";
                    else
                        city = location[n];
                }else{
                    city = "all";
                }
                if(n < no || state == ""){
                    jQuery.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>" + "console_controller/send_automated_gcmids",
                        dataType: 'json',
                        data: {
                            device: device,
                            title: title,
                            image_url: image_url,
                            brand: brand,
                            message: message,
                            automate: automate,
                            start: start,
                            limit: limit,
                            location: location,
                            city : city,
                            via:via,
                            flag:flag,
                            user: user,
                            tnc_status:tnc_status
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
                            if (res.status == 'success') {
                                if (res.size != limit) {
                                    if( n == no){
                                        UIkit.notification({
                                            message: 'Successfully sent',
                                            status: 'success',
                                            pos: 'bottom-center',
                                            timeout: 1000
                                        });
                                        $('#notification_send_block').hide();
                                        $('#schedule_block').hide();
                                        $('#brand').val("Fabricspa");
                                        $('#device').val("android");
                                        $('#send_to').val("selected");
                                        $('#title').val("");
                                        $('#image_url').val("");
                                        $('#message').val("");
                                        $('#location').val("");
                                        $('input[type=radio][name=automate]:checked').val("yes");
                                        $('#list_via_block').show();
                                        $('#location_limit_block').hide();
                                        $('#limit_block').hide();
                                        $('#automate_block').hide();
                                        $('#receivers_list_block').show();
                                        $('#send_notification_block').hide();
                                        $('input[type="checkbox"]').prop("checked", false);
                                        $('#cities').val("");
                                        $('#state').val("");
                                        <?php for($j=0;$j< sizeof($states);$j++){?>
                                            $('#<?php echo $j;?>').hide();
                                        <?php }?>
                                        $('input[type="checkbox"]').prop("checked", false);
                                        $('#radio').prop("checked",false);
                                        $('#get_gcmid_block').show();
                                        $(":checkbox").attr("checked", false);
                                        $('#tnc_block').hide();

                                    }
                                    return true;

                                }
                                var next = parseInt(start) + 299;
                                setTimeout(
                                    function () {
                                        var flag=2;
                                        send_automated_gcmids(next, limit,flag)
                                    }, 3000);
                            }
                        
                        
                        },
                        error: function (res) {
                            //console.log(res);
                        }
                        
                    });
                }
            }
        }
    }
    function schedule_automated_gcmids(start,limit,flag)
    {
        var device = $('#device').val();
        var via  = $('#via').val();
        var title = $('#title').val();
        var image_url = $('#image_url').val();
        var brand = $('#brand').val();
        var message = $('#message').val();
        var state = $('#state').val();
        var location = [];
        var user = "<?php echo $username; ?>";
        var time_slot = $('#time_slot').val();
        var total_cities = "<?php echo $total_cities;?>";
        if(document.getElementById('tnc_status').checked){
            var tnc_status = 1;
        }else{
            var tnc_status = 0;
        }
        if(state != "" && state != total_cities){
            $(':checkbox:checked').each(function(i){
                if($(this).val() != "on")
                    location[i] = $(this).val();
            });
            var no = location.length;
        }else if(state == total_cities){
            var location = "all";
            var no = 1;
        }else{
            var location = "";
            var no = 0;
        }
        var schedule_date = $('#schedule_date').val();
        var via="NULL";
        if(image_url) {
                var ext = $('#image_url').val().split('.').pop().toLowerCase();
                var size=$('#image_url')[0].files[0].size;
                
                if (size > 160658) {
                    UIkit.notification({
                        message: 'File too Big, please select a file less than 4mb',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });
                    return false;
                }else{
                    if($.inArray(ext, ['jpg']) == -1) {
                        UIkit.notification({
                            message: 'Only jpg files are allowed',
                            status: 'danger',
                            pos: 'bottom-center',
                            timeout: 1000
                        });
                    return false;
                    }
                }
                var txt = image_url.substr(12);
				var time = $.now();
                var time = time.toString().slice(0,6);
                var txt = time+txt;
                var url="https://intapps.fabricspa.com/jfsl/console_notification_images/"+txt;
                var image_url=url;
                var file_data = $('#image_url').prop('files')[0];   
                var form_data = new FormData();                  
                form_data.append('file', file_data);                             
                $.ajax({
                    url: 'https://intapps.fabricspa.com/jfsl/upload.php', // <-- point to server-side PHP script 
                    dataType: 'text',  // <-- what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,                         
                    type: 'post',
                    success: function(php_script_response){
                        // alert(php_script_response); // <-- display response from the PHP script, if any
                        
                    }
                });
               
                
            }

           

        if ($('#send_to').val() == 'all' && $('input[type=radio][name=automate]:checked').val() == 'yes') {
            var automate = "true";
        } else {
            var automate = "false";
        }
        if(no <= 0){
            var city ="";
            jQuery.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>" + "console_controller/schedule_automated_gcmids",
                        dataType: 'json',
                        data: {
                            device: device,
                            title: title,
                            image_url: image_url,
                            brand: brand,
                            message: message,
                            automate: automate,
                            start: start,
                            limit: limit,
                            location: location,
                            city:city,
                            schedule_date:schedule_date,
                            via:via,
                            flag:flag,
                            user: user,
                            time_slot : time_slot,
                            tnc_status:tnc_status
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
                            if (res.status == 'success') {
                                UIkit.notification({
                                    message: 'Scheduled Successfully',
                                    status: 'success',
                                    pos: 'bottom-center',
                                    timeout: 1000
                                });
                                if (res.size != limit) {
                                    $('#notification_send_block').hide();
                                        $('#schedule_block').hide();
                                        $('#brand').val("Fabricspa");
                                        $('#device').val("android");
                                        $('#send_to').val("selected");
                                        $('#title').val("");
                                        $('#image_url').val("");
                                        $('#message').val("");
                                        $('#location').val("");
                                        $('input[type=radio][name=automate]:checked').val("yes");
                                        $('#schedule_date').val("");
                                       $('input[type=radio][name=schedule]:checked').val("yes");      
                                     


                                        $('#list_via_block').show();
                                        $('#location_limit_block').hide();
                                        $('#limit_block').hide();
                                        $('#automate_block').hide();
                                        $('#receivers_list_block').show();
                                        if($('input[type=radio][name=automate]:checked').val() == 'yes'){
                                            $('input[type=radio][name=schedule]:checked').val("yes");
                                            $('#send_notification_block').show();
                                        }
                                        $('input[type="checkbox"]').prop("checked", false);
                                        $('#cities').val("");
                                        $('#state').val("");
                                        $('#get_gcmid_block').show();
                                        <?php for($j=0;$j< sizeof($states);$j++){?>
                                            $('#<?php echo $j;?>').hide();
                                        <?php }?>
                                        $('input[type="checkbox"]').prop("checked", false);
                                        $('#radio').prop("checked",false); 
                                        $(":checkbox").attr("checked", false);
                                        $('#tnc_block').hide();

                                    return true;
                                }
                                var next = parseInt(start) + 299;
                                setTimeout(
                                    function () {
                                        var flag = 2;
                                        schedule_automated_gcmids(next, limit,flag)
                                    }, 3000);
                            }       
                        },
                        error: function (res) {
                            //console.log(res);
                        }
                        
                    });
        }else{
            for(var n=0;n<no;n++){
                if(n > 0)
                    flag = 2;
                if(state == "")
                    city = "";
                else
                    city = location[n];
                if(n < no || state == ""){
                    jQuery.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>" + "console_controller/schedule_automated_gcmids",
                        dataType: 'json',
                        data: {
                            device: device,
                            title: title,
                            image_url: image_url,
                            brand: brand,
                            message: message,
                            automate: automate,
                            start: start,
                            limit: limit,
                            location: location,
                            city:city,
                            schedule_date:schedule_date,
                            via:via,
                            flag:flag,
                            user: user,
                            time_slot : time_slot,
                            tnc_status:tnc_status
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
                            if (res.status == 'success') {
                                $('#notification_send_block').hide();
                                if (res.size != limit) {
                                    if(n == no){
                                        UIkit.notification({
                                            message: 'Scheduled Successfully',
                                            status: 'success',
                                            pos: 'bottom-center',
                                            timeout: 1000
                                        });
                                        window.location.reload(true);
                                       
                                    }
                                    $(":checkbox").attr("checked", false);
                                    $('#tnc_block').hide();
                                    return true;
                                }
                                var next = parseInt(start) + 299;
                                setTimeout(
                                    function () {
                                        var flag = 2;
                                        schedule_automated_gcmids(next, limit,flag)
                                    }, 3000);
                            }       
                        },
                        error: function (res) {
                            //console.log(res);
                        }
                        
                    });
                }
            }
        }
    }
    function BindTable(jsondata, tableid,list) {/*Function used to convert the JSON array to Html Table*/  
     var columns = BindTableHeader(jsondata, tableid); /*Gets all the column headings of Excel*/  
     for (var i = 0; i < jsondata.length; i++) {  
         var row$ = $('<tr/>');  
         for (var colIndex = 0; colIndex < columns.length; colIndex++) {  
             var cellValue = jsondata[i][columns[colIndex]];  
             if (cellValue == null)  
                 cellValue = "";  
             row$.append($('<td/>').html(cellValue)); 
             if(list == 'email') {
                var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                if(!regex.test(cellValue)) {
                    UIkit.notification({
                        message: 'Invalid EmailId',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });
                    $('#list').show();
                    $('#emails').show();
                    $('#excelfile').val("");
                    return false;
                }
             }else if(list == 'mobile_number'){

                if(cellValue.replace(/ /g, '').length !=10){
                    UIkit.notification({
                        message: 'Invalid Mobile Number',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });
                    $('#list').show();
                    $('#mobile_numbers').show();
                    $('#excelfile').val("");
                    return false;
                }
             }else if(list == 'gcmid'){
                if(cellValue.replace(/ /g, '').length  < 20){
                    UIkit.notification({
                        message: 'Invalid GCMID',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });
                    $('#list').show();
                    $('#gcmids').show();
                    $('#excelfile').val("");
                    return false;
                }
             }else{
                
             }
             var output = '';  
             output = output + cellValue + ',';
         }
         $(tableid).append(row$);  
     }  
 }  
 function BindTableHeader(jsondata, tableid) {/*Function used to get all column names from JSON and bind the html table header*/  
     var columnSet = [];  
     var headerTr$ = $('<tr/>');  
     for (var i = 0; i < jsondata.length; i++) {  
         var rowHash = jsondata[i];  
         for (var key in rowHash) {  
             if (rowHash.hasOwnProperty(key)) {  
                 if ($.inArray(key, columnSet) == -1) {/*Adding each unique column names to a variable array*/  
                     columnSet.push(key);  
                     headerTr$.append($('<th/>').html(key));  
                 }  
             }  
         }  
     }  
    //  $(tableid).append(headerTr$);  
     return columnSet;  
 }  
 function GetTodayDate() {
   var tdate = new Date();
   var dd = tdate.getDate(); //yields day
   var MM = tdate.getMonth(); //yields month
   var yyyy = tdate.getFullYear(); //yields year
   var currentDate= dd + "-" +( MM+1) + "-" + yyyy;

   return currentDate;
}     
    $(function () {

        $('input[name="automate"]:radio').change(function () {
            if ($(this).val() == 'yes') {
                $('#get_gcmid_block').hide();
                $('#result_block').hide();
                $('#notification_send_block').show();
                $('schedule_block').show();
                $('#start').val("1");
                $('#limit').val("299");
                $( "#start" ).prop( "disabled", true );
                $( "#limit" ).prop( "disabled", true );
                if($('input[type=radio][name=schedule]:checked').val() == "yes")
                    $('#send_notification_block').show();
                else
                    $('#send_notification_block').hide();
                    $('#schedule_notification').show();
            } else {
                $('#get_gcmid_block').show();
                // $('#result_block').show();
                $('#notification_send_block').hide();
                $('schedule_block').hide();
                $('#send_notification_block').hide();
                $('#start').val("");
                $('#limit').val("");
                $( "#start" ).prop( "disabled", false );
                $( "#limit" ).prop( "disabled", false );
            }
        });
        $('input[name="schedule"]:radio').change(function () {

            if ($(this).val() == 'yes') {
                $('#send_notification_block').show();  
                $('#schedule_block').hide();
            }else if($(this).val() == 'no'){
                $('#schedule_block').show();
                $('#send_notification_block').hide();
                
            }
        });
       
        $('#download_report').click(function (e) {
            if ($('#send_to').val() == 'all' && $('input[type=radio][name=automate]:checked').val() == 'yes') {
                var automate = "true";
            } else if ($('#send_to').val() == 'all' && $('input[type=radio][name=automate]:checked').val() == 'no') {
                    var automate = "false";
            }else {
                var automate = "";
            }
            var device = $('#device').val();
            var title = $('#title').val();
            var gcmids =  $('#result').val().split(',');
            var via=$('#list_via').val();
            if(gcmids == ""){
                var TableData='';
                $('#'+via+'_exceltable tr').each(function(row, tr){
                    TableData = TableData 
                        + $(tr).find('td:eq(0)').text() + ' '
                        +',';
                });
                var data=TableData;
                var gcmids='';
                if(data){
                    var raw_inputs = data.replace(/ /g, '')
                    var gcmids = raw_inputs.split(',');
                }
            }
            var size =gcmids.length;
            var image_url = $('#image_url').val();
            var brand = $('#brand').val();
            var message=$('#message').val();
            var location=$('#location').val();
            var list=$('#list_via').val();
            if(image_url) { 
                var txt = image_url.substr(12);
				var time = $.now();
                var time = time.toString().slice(0,6);
                var txt = time+txt;
                var url="https://intapps.fabricspa.com/jfsl/console_notification_images/"+txt;
                var image_url=url; 
            }

            if (!start && !limit) {
                var start = $('#start').val();
                var limit = $('#limit').val();
            }
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "console_controller/download_report",
                dataType: 'json',
                data: {
                    device: device,
                    title:title,
                    brand: brand,
                    image_url:image_url,
                    message:message,
                    location:location,
                    automate: automate,
                    start: start,
                    limit: limit,
                    size:size
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
                   
                    if (res.status == 'success') {
                        
                        var fileName=res.file;
                        var path = "https://intapps.fabricspa.com/jfsl/excel_reports/"+brand+"/"+fileName; //relative-path
                     
                        window.location.href = path;
                        if(automate =="true") {
                            $('#brand').val("Fabricspa");
                            $('#device').val("android");
                            $('#send_to').val("selected");
                            $('#title').val("");
                            $('#image_url').val("");
                            $('#message').val("");
                            $('#location').val("");
                            $('input[type=radio][name=automate]:checked').val("yes");
                        }else if(automate == "false") {
                            $('#brand').val("Fabricspa");
                            $('#device').val("android");
                            $('#send_to').val("selected");
                            $('#title').val("");
                            $('#image_url').val("");
                            $('#message').val("");
                            $('#location').val("");
                            $('#send_notification_block').hide();
                            $('#start').val("");
                            $('#limit').val("");
                            $('#result_block').hide();
                            
                        }else {
                            $('#brand').val("Fabricspa");
                            $('#device').val("android");
                            $('#send_to').val("selected");
                            $('#title').val("");
                            $('#image_url').val("");
                            $('#message').val("");
                            $('#list_via_block').show();
                            $('#list_via').val("email");
                            $('#emails').val("");
                            $('#mobile_numbers').val("");
                            $('#gcmids').val("");
                            $('#send_notification_block').hide();
                            $('#result_block').hide();
                            $("#"+list+"_exceltable tr").empty();
                            $('#'+list+'s').show();
                            $('#'+list+'_exceltable').show();
                            $('#list').show();
                            $('#excelfile').val("");
                        }
                        
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
    function download_fabricspa_users_list() {
        var device = $('#download_device').val();
        UIkit.modal.confirm("Do you want to download "+ device +" user's mobile numbers?").then(function () {
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "console_controller/download_fabricspa_users_list",
                dataType: 'json',
                data: {
                    device: device
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
                    if (res.status == 'success') {
                        var fileName=res.file;
                        var path = "https://intapps.fabricspa.com/jfsl/excel_reports/Fabricspa_users/"+fileName; //relative-path
                        window.location.href = path;
                    }else if(res.status == "No Data Found"){
                        UIkit.notification({
                            message: 'No Data Found',
                            status: 'danger',
                            pos: 'bottom-center',
                            timeout: 1000
                        });
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
        })
    }
      
</script>

<div id="page-1-container"
     class="uk-width-1-1@s uk-width-4-5@m uk-width-5-6@m uk-margin-auto-left content_wrapper uk-padding">

    <div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">

    <div class="uk-margin">

        <h3 class="uk-heading-divider uk-text-center">Send Notification</h3>
         <div class="uk-form-controls">
            <button id="show_details" type="button" class = "w3-button w3-sand" onclick="window.location='<?php echo site_url('console/get_notification_details');?>'">Show Details</button>
            <select class="uk-select" id="download_device">
                    <option value="android" selected>Android</option>
                    <option value="ios">Ios</option>
                </select>
                <button  onclick="download_fabricspa_users_list()" id="fabricspa_users_list" class="uk-button uk-button-default">Download mobile number</button>
            </div>
        </div>
        <div class="uk-grid" id="grid">

            <form class="uk-form-horizontal uk-width-expand">
                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Brand</label>

                    <div class="uk-form-controls">
                        <select class="uk-select" id="brand">

                            <option value="Fabricspa">Fabricspa</option>
                            <option value="Click2Wash">Click2Wash</option>
                        </select>
                    </div>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Device</label>

                    <div class="uk-form-controls">
                        <select class="uk-select" id="device">
                            <option value="android">Android</option>
                            <option value="ios">iOS</option>
                        </select>
                    </div>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Send to</label>

                    <div class="uk-form-controls">
                        <select class="uk-select" id="send_to">
                            <option value="selected">Selected Users</option>
                            <option value="all">All</option>
                        </select>
                    </div>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Title</label>

                    <div class="uk-form-controls">
                        <input type="text" id="title" class="uk-input"
                               placeholder="Enter the notification message title">
                    </div>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Image</label>

                    <div class="uk-form-controls">
                
                        <input type="file" name="image_url" id="image_url">
                    </div>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Message</label>

                    <div class="uk-form-controls">
                        <input type="text" id="message" class="uk-textarea" placeholder="Enter the message content">
                    </div>
                </div>

                <div id="list_via_block" class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Get receivers list via</label>

                    <div class="uk-form-controls">
                        <select class="uk-select" id="list_via">
                            <option value="email" selected>Email</option>
                            <option value="mobile_number">Mobile Number</option>
                            <option value="gcmid">GCMID</option>
                        </select>
                    </div>
                </div>

                <div id="location_limit_block">
                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-horizontal-text">State and City</label>

                        <div class="uk-form-controls">
                            <select  class="uk-select" id="state">
                                <option value="">No specific location</option>
                                <?php for ($i = 0; $i < sizeof($states); $i++) { ?>

                                    <option
                                        value="<?php echo $states[$i]['statecode']; ?>"><?php echo $states[$i]['statename']; ?></option>

                                <?php } ?>
                            </select>
                    </div>
                    <div class="uk-margin" id="cities">
                        <div class="uk-form-controls">
                            <?php for($i=0;$i<sizeof($states);$i++){?>
                                
                                <div id="<?php echo $i;?>" class="checkbox">
                            <?php for($j=0;$j<sizeof($cities[$i]);$j++){?>
                                    <!-- <div class="checkbox"> -->
                                        <input type="checkbox" class="checkbox" name="cities" id="cities" value="<?php echo $cities[$i][$j]['cityname']; ?>" id="cities"> <?php echo $cities[$i][$j]['cityname']; ?>
                                    <!-- </div> -->
                                    <!-- <div class="radio"> -->
                                        <input type="radio" class="radio" name="cities" id="cities" value="<?php echo $cities[$i][$j]['cityname']; ?>" id="cities">
                                    <!-- </div> -->
                                <?php }?>
                                </div>
                            <?php }
                            ?>
                        </div>
                    </div>
                </div>

                    <div id="limit_block" class="uk-margin">
                        <label class="uk-form-label"   for="form-horizontal-text">Start & Limit</label>

                        <div class="uk-form-controls">
                            <div class="uk-grid uk-margin-auto uk-child-width-1-2@m">
                                <input type="number" id="start" class="uk-input" placeholder="Enter the start value" value="1" disabled> 
                                <input type="number" id="limit" class="uk-input" placeholder="Enter a limit" value="299"  disabled>
                            </div>
                        </div>
                    </div>
                </div>

               
                <div id="receivers_list_block">
                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-horizontal-text">Receivers List</label>

                        <div class="uk-form-controls">
                        <table id="email_exceltable"><textarea class="uk-textarea" id="emails" rows="3"></textarea></table>   
                        </div>

                        <div class="uk-form-controls">
                        <table id="mobile_number_exceltable"><textarea class="uk-textarea" id="mobile_numbers" rows="3"></textarea></table>
                        </div>

                        <div class="uk-form-controls">
                        <table id="gcmid_exceltable"><textarea class="uk-textarea" id="gcmids" rows="3"></textarea></table>
                            <div class="row" id="list">   
                                OR
                                <div class="col-md-6" id="list_file">
                                    <input name="excelfile" type="file" id="excelfile" accept=".xlsx,.xls">   
                                </div>
                                <br />  
                                <br />  
                            </div>
                          
                        </div>
                    </div>
                </div>
                <div class="uk-margin" id="get_gcmid_block">
                    <button id="get_gcmid" type="button"
                            class="uk-button uk-button-primary uk-width-1-1">
                        Get GCMID
                    </button>
                </div>

                <div class="uk-margin" id="result_block" style="display: none;">
                    <label class="uk-form-label" for="form-horizontal-text">Result</label>

                    <div class="uk-form-controls">
                        <textarea class="uk-textarea" id="result" rows="10" readonly></textarea>
                    </div>
                </div>
                <div id="notification_send_block">
                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-horizontal-text">Send Now</label>
                        <div class="uk-form-controls">
                            <label><input class="uk-radio" type="radio" name="schedule" id="schedule" value="yes" checked> Yes</label>
                            <label><input class="uk-radio" type="radio" name="schedule" id="schedule" value="no"> No </label>
                        </div>
                    </div>
                </div>
                <div class="uk-margin" id="tnc_block">
                        <label class="uk-form-label" for="form-horizontal-text"></label>

                        <div class="uk-form-controls">
                            <input type="checkbox" id="tnc_status" class="uk-checkbox"> Apply Terms & Conditions
                        </div>
                </div>
                <div class="uk-margin" id="schedule_block">
                    <div id="schedule_date_block">
                    <label class="uk-form-label" for="form-horizontal-text">Send On</label>
                    <div class="uk-form-controls">
                        <input type="date" min="<?= date('Y-m-d'); ?>" name="schedule_date" id="schedule_date"  class="uk-textarea">
                    </div>
                </div>
                    <div class="uk-margin" id="timeslots">
                        <label class="uk-form-label" for="form-horizontal-text">Time </label>
                        <div class="uk-form-controls">
                            <select class="uk-select" id="time_slot">
                                <option value="">Select a time</option>
                                 <option value="8 AM" id="time1">8 AM</option>
                                <option value="9 AM"id="time2">9 AM</option>
                                <option value="10 AM"id="time3">10 AM</option>
                                <option value="11 AM"id="time4">11 AM</option>
                                <option value="12 PM"id="time5">12 PM</option>
                                <option value="1 PM"id="time6">1 PM</option>
                                <option value="2 PM"id="time7">2 PM</option>
                                <option value="3 PM"id="time8">3 PM</option>
                                <option value="4 PM"id="time9">4 PM</option>
                                <option value="5 PM"id="time10">5 PM</option>
                                <option value="6 PM"id="time11">6 PM</option>
                                <option value="7 PM"id="time12">7 PM</option>
                            </select>
                        </div></br>
                    </div>
                    <button id="schedule_notification" type="button"
                            class="uk-button uk-button-primary uk-width-1-1">
                        SCHEDULE
                    </button>
                    
                </div>
                
                <div class="uk-margin" id="send_notification_block">
                    
                    <button id="send_notification" type="button"
                            class="uk-button uk-button-primary uk-width-1-1">
                        SEND
                    </button>
                </div>              
                    <button id="download_options" type="button" class = "w3-button w3-sand" onclick="window.location='<?php echo site_url('console/notification_download_options');?>'">Download Options</button>                   
            </form>
        </div>
    </div>
</div>
<style>
    #download_device{
        margin-left:28%;
        width:15%;
    }
</style>

