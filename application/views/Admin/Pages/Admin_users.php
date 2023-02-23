<!-- * Created by PhpStorm.
 * User: targetman
 * Date: 4/5/2017
 * Time: 1:43 PM
 */-->


<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

?>
<!--styles are here-->

<style>
    input {
        text-align: center;
    }

</style>

<!--scripts are here-->

<script xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    

    $(document).ready(function () {

        $(function () {
        $("#datepicker-1").datepicker({maxDate: new Date(), dateFormat: 'yy-mm-dd'});
        $("#datepicker-2").datepicker({maxDate: new Date(), dateFormat: 'yy-mm-dd'});
    });


        // $('#loading').hide();
        $('#progressElem').hide();


        $("#query_button").click(function () {

            

            var search_option = $('#search_option').val();
            var brand_option = $('#brand_option').val();
            var search_keyword = $('#search_keyword').val();

            var is_brand_selected = 0;

            if(brand_option=='Fabricspa' || brand_option =='Click2Wash'){
            	is_brand_selected = 1;
            }

            event.preventDefault();

            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/console_controller/get_users_details",
                dataType: 'json',
                data: {
                    search_option: search_option,
                    brand_option: brand_option,
                    search_keyword: search_keyword    
                    },
                    xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    //Download progress
                    xhr.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                    var percentComplete = evt.loaded / evt.total;
                    progressElem.value+=Math.round(percentComplete * 100);
                    }
                 }, false);
                return xhr;
                },
                beforeSend: function () {
                   // $('#loading').show();
                   $('#progressElem').show();
                },
                complete: function () {
                   // $("#loading").hide();
                   $('#progressElem').hide();
                },
                success: function (res) {
                    if (res.status == "success") {

                        // Show Entered Value
                        jQuery("div#res").addClass("uk-animation-slide-right").hide();
                        jQuery("div#res").addClass("uk-animation-slide-right").show();

                        UIkit.notification({
						    message: 'Done',
						    status: 'success',
						    pos: 'bottom-center',
						    timeout: 1000
						});

                         if($('#result-table').hide()){
                            $('#result-table').show();
                        }
                        


                        $('#table-head-row').html('');
                        $('#result-table-body').html('');

                        /*Dynamically creating the table menu*/
                        $('#table-head-row').append(

                        	'<th>#</th>'+
                        	'<th>Id</th>'+
                        	'<th>Name</th>'+
                        	'<th>Email</th>'+
                        	'<th>Mobile</th>'+
                        	'<th>Location</th>'+
                        	'<th>Pincode</th>'
                        	
                        	)

                        if(is_brand_selected==0){
                        	$('#table-head-row').append('<th>Brand</th>')
                        }

                        var brand = [];

                        for(var i = 0; i < res.result.length;i++){

                            if(res.result[i]['BrandCode']=="PCT0000001"){
                                brand[i] = 'Fabricspa';
                            }else if(res.result[i]['BrandCode']=="PCT0000014"){
                                brand[i] = 'Click2Wash';
                            }

                            var start = 0;
                            if(i==0)
                        		start = i+1;
                        	else
                        		start = i+1;

                        	if(is_brand_selected==0){

                        		$('#result-table-body').append('<tr>' +
                                '<td>'+ start +' </td>' +
                                '<td>'+ res.result[i]['id'] +' </td>' +
                                '<td>'+ res.result[i]['name'] +' </td>' +
                                '<td>'+ res.result[i]['email'] +' </td>' +
                                '<td>'+ res.result[i]['mobile_number'] +' </td>' +
                                '<td>'+ res.result[i]['location'] +' </td>' +
                                '<td>'+ res.result[i]['pincode'] +' </td>' +
                                '<td>'+ brand[i] +' </td>' +
                                '</tr>'
                            )
                        	
                        	}else{

                        		$('#result-table-body').append('<tr>' +
                                '<td>'+ start +' </td>' +
                                '<td>'+ res.result[i]['id'] +' </td>' +
                                '<td>'+ res.result[i]['name'] +' </td>' +
                                '<td>'+ res.result[i]['email'] +' </td>' +
                                '<td>'+ res.result[i]['mobile_number'] +' </td>' +
                                '<td>'+ res.result[i]['location'] +' </td>' +
                                '<td>'+ res.result[i]['pincode'] +' </td>' +
                                '</tr>'
                            )

                        	}

                            
                        }

                    }
                    else if (res.status == 'failed') {
                        
                        $('#result-table').hide();
                        $('#result-table-body').html('');
                        UIkit.notification({
						    message: 'No such result',
						    status: 'warning',
						    pos: 'bottom-center',
						    timeout: 1000
						});
                    }
                }
            });


        });



    });




</script>


<div class="uk-width-1-1@s uk-width-4-5@m uk-width-5-6@m uk-margin-auto-left content_wrapper uk-padding">

    <div
        class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">

        <!--uk-width-1-2@l uk-width-1-2@m uk-width-1-2@s-->
        <h3 class="uk-heading-divider uk-text-center">Users Details</h3>

        <div class="uk-grid">

            <form class="uk-form-horizontal uk-margin-large uk-align-center">

             <div class="uk-margin">Search with</div>

                <div class="uk-margin">
                <select class="uk-select" id="search_option">
                    <option value="name">Name</option>
                    <option value="email">Email</option>
                    <option value="mobile_number">Mobile Number</option>
                </select>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Search</label>

                    <div class="uk-form-controls">
                        <input type="text" class="uk-input" id="search_keyword">
                    </div>
                </div>

                <div class="uk-margin">Registered with</div>

                <div class="uk-margin">
                <select class="uk-select" id="brand_option">
                <option value="all">All</option>
                    <option value="Fabricspa">Fabricspa</option>
                    <option value="Click2Wash">Click2Wash</option>
                    
                </select>
                </div>


                <div class="uk-margin">

                    <button id="reset_button"
                            class="uk-button uk-button-primary uk-display-block uk-margin-small-top uk-float-left">
                        Reset
                    </button>

                    <button id="query_button"
                            class="uk-button uk-button-primary uk-display-block uk-margin-small-top uk-float-right">
                        Get Details
                    </button>
                </div>
                <div class="uk-clearfix"></div>

                <!-- <div uk-spinner id="loading"></div> -->
                <progress class="uk-progress" value="0" max="100" id="progressElem"></progress>

            </form>
        </div>
    </div>


    <div id="res" class="uk-grid uk-align-center uk-width-1-2\@s uk-width-1-3\@m uk-width-1-4\@l" style="display: none">

        <table class="uk-table uk-table-hover" id="result-table">
            <thead>
            <tr id="table-head-row">
                <!--  <th>#</th>
                 <th id="head-1">Id</th>
                 <th>Name</th>
                 <th>Email</th>
                 <th>Mobile</th>
                 <th>Brand</th>
                 <th>Location</th>
                 <th>Pincode</th> -->
            </tr>
            </thead>
            <tbody id="result-table-body">

            <!--content-->

            </tbody>
        </table>
    </div>



</div>






