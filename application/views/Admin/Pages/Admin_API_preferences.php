<!-- * Created by PhpStorm.
 * User: targetman
 * Date: 4/5/2017
 * Time: 1:43 PM
 */-->


 <?php

 if (!defined('BASEPATH')) exit('No direct script access allowed');

 ?>
 <!--styles are here-->


 <script type="text/javascript">

 	$(document).ready(function () {

 		$('.preference_block').hide();
        $('#send_to_block').hide();

        $('#load_preference').change(function(){

            var brand_option = $('#brand_option').val();

            var pref = $('#load_preference').val();
            $("#load_preference option[value='select_options']").remove();
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "console_controller/load_preference_data",
                dataType: 'json',
                data: {
                    brand_option:brand_option,
                    pref: pref

                },

                success: function (preference) {

                    if(pref=='schedule_reminder'){
                        $('.preference_block').show();
                        $('#send_to_block').show();

                        $('#send_to').val(preference.send_to);
                        $('#brand_code').val(preference.brand_code);
                        $('#location').val(preference.location);
                        $('#medium').val(preference.medium);
                        $('#title').val(preference.title);
                        $('#image_url').val(preference.image_url);
                        $('#payload').val(preference.payload);
                        $('#message').val(preference.message);
                    }
                    else if(pref=='order_confirmation'){

                        $('.preference_block').show();
                        $('#send_to_block').hide();

                        $('#brand_code').val(preference.brand_code);
                        $('#location').val(preference.location);
                        $('#medium').val(preference.medium);

                        $('#title').val(preference.title);
                        $('#image_url').val(preference.image_url);
                        $('#payload').val(preference.payload);
                        $('#message').val(preference.message);

                    }

                    else if(pref=='delivery_confirmation'){

                        $('#send_to_block').hide();
                        $('.preference_block').show();
                        $('#brand_code').val(preference.brand_code);
                        $('#location').val(preference.location);
                        $('#medium').val(preference.medium);

                        $('#title').val(preference.title);
                        $('#image_url').val(preference.image_url);
                        $('#payload').val(preference.payload);
                        $('#message').val(preference.message);

                    }

                }

            });
        });

        $('#brand_option').change(function(){
            var brand_option = $('#brand_option').val();
            var pref = $('#load_preference').val();

            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "console_controller/load_preference_data",
                dataType: 'json',
                data: {
                    brand_option:brand_option,
                    pref: pref

                },

                success: function (preference) {
                        $('#send_to').val(preference.send_to);
                        $('#location').val(preference.location);
                        $('#medium').val(preference.medium);

                        $('#title').val(preference.title);
                        $('#image_url').val(preference.image_url);
                        $('#payload').val(preference.payload);
                        $('#message').val(preference.message);

                }

            });
        });



        $('#loading').hide();

        $("#save_pref").click(function () {
            var brand = $('#brand_option').val();
            var pref = $('#load_preference').val();
            
            if(pref=='schedule_reminder'){

                var location = $("#location").val();
                var medium = $("#medium").val();
                var title = $("#title").val();
                var send_to = $('#send_to').val();
                var image_url = $("#image_url").val();
                var payload = $("#payload").val();
                var message = $("#message").val();



            //event.preventDefault();

            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "console_controller/save_preference",
                dataType: 'json',
                data: {
                    pref:pref,
                    send_to:send_to,
                    brand: brand,
                    location: location,
                    medium: medium,
                    title: title,
                    image_url: image_url,
                    payload: payload,

                    message: message
                },

                success: function (res) {

                    if(res.status == 'success'){

                        UIkit.notification({
                            message: 'Preferences saved!',
                            status: 'success',
                            pos: 'bottom-center',
                            timeout: 1000
                        });
                    }else{
                        UIkit.notification({
                            message: 'Error occured, Not saved',
                            status: 'danger',
                            pos: 'bottom-center',
                            timeout: 1000
                        });
                    }
                },
                error: function (res) {


                }

            });

            }

            else if(pref=='order_confirmation'){

                

                var location = $("#location").val();
                var medium = $("#medium").val();
                var title = $("#title").val();
               
                var image_url = $("#image_url").val();
                var payload = $("#payload").val();
                var message = $("#message").val();




            //event.preventDefault();

            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "console_controller/save_preference",
                dataType: 'json',
                data: {
                    pref:pref,
                    brand: brand,
                    location: location,
                    medium: medium,
                    title: title,
                    image_url: image_url,
                    payload: payload,

                    message: message

                },

                success: function (res) {

                    if(res.status == 'success'){

                        UIkit.notification({
                            message: 'Preferences saved!',
                            status: 'success',
                            pos: 'bottom-center',
                            timeout: 1000
                        });
                    }else{
                        UIkit.notification({
                            message: 'Error occured, Not saved',
                            status: 'danger',
                            pos: 'bottom-center',
                            timeout: 1000
                        });
                    }
                },
                error: function (res) {


                }

            });

        
        }

        else if(pref=='delivery_confirmation'){

                

                var location = $("#location").val();
                var medium = $("#medium").val();
                var title = $("#title").val();
               
                var image_url = $("#image_url").val();
                var payload = $("#payload").val();
                var message = $("#message").val();




            //event.preventDefault();

            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "console_controller/save_preference",
                dataType: 'json',
                data: {
                    pref:pref,
                    brand: brand,
                    location: location,
                    medium: medium,
                    title: title,
                    image_url: image_url,
                    payload: payload,

                    message: message

                },

                success: function (res) {

                    if(res.status == 'success'){

                        UIkit.notification({
                            message: 'Preferences saved!',
                            status: 'success',
                            pos: 'bottom-center',
                            timeout: 1000
                        });
                    }else{
                        UIkit.notification({
                            message: 'Error occured, Not saved',
                            status: 'danger',
                            pos: 'bottom-center',
                            timeout: 1000
                        });
                    }
                },
                error: function (res) {


                }

            });

        
        }
            


        });

    });


 </script>

 <div id="page-1-container" class="uk-width-1-1@s uk-width-4-5@m uk-width-5-6@m uk-margin-auto-left content_wrapper uk-padding">

 	<div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">

 		<!--uk-width-1-2@l uk-width-1-2@m uk-width-1-2@s-->

 		<h3 class="uk-heading-divider uk-text-center">API Preference</h3>

 		<div class="uk-grid" id="grid">

 			<form class="uk-form-horizontal uk-margin-large uk-align-center">

            <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Load Preference Data</label>

                    <div class="uk-form-controls">
                        <select class="uk-select" id="load_preference">
                                <option value="select_options">Select</option>
                                <option value="schedule_reminder">Schedule Reminder</option>
                                <option value="order_confirmation">Order confirmation</option>
                                <option value="delivery_confirmation">Delivery confirmation</option>
                           
                            
                        </select>
                        
                    </div>
                </div>

                <div class="uk-margin" id="send_to_block">
                    <label class="uk-form-label" for="form-horizontal-text">Send to</label>

                    <div class="uk-form-controls">
                        <select class="uk-select" id="send_to">
                            <option value="all">All</option>
                            <option value="fabricspa">Fabricspa</option>
                            <option value="click2wash">Click2Wash</option>
                        </select>
                    </div>
                </div>

                <hr class="uk-divider-icon">

                <div class="preference_block">

                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-horizontal-text">Load Brand Preference</label>

                        <div class="uk-form-controls">

                            <select class="uk-select" id="brand_option">

                                <option value="fabricspa">Fabricspa</option>
                                <option value="click2wash">Click2Wash</option>

                            </select>

                        </div>
                    </div>

                    <hr class="uk-divider-icon">

     				<div class="uk-margin">
     					<label class="uk-form-label" for="form-horizontal-text">Location</label>
                        <div class="uk-form-controls">
                        <select class="uk-select" id="location">
                            <option value="all">All</option>
                            <option value="Bangalore">Bangalore</option>
                            <option value="Mumbai">Mumbai</option>
                        </select>
                            </div>
     				</div>

     				<div class="uk-margin">
     					<label class="uk-form-label" for="form-horizontal-text">Medium</label>
                        <div class="uk-form-controls">
                        <select class="uk-select" id="medium">
                            <option value="email">Email</option>
                            <option value="sms">SMS</option>
                            <option value="push_notification">Push Notification</option>
                        </select>
                            </div>
     				</div>
     				<div class="uk-margin">
     					<label class="uk-form-label" for="form-horizontal-text">Title</label>

     					<div class="uk-form-controls">
     						<input type="text" id="title" class="uk-input">
     					</div>
     				</div>

     				<div class="uk-margin">
     					<label class="uk-form-label" for="form-horizontal-text">Image URL</label>

     					<div class="uk-form-controls">
     						<input type="text" id="image_url" class="uk-input">
     					</div>
     				</div>

     				<div class="uk-margin">
     					<label class="uk-form-label" for="form-horizontal-text">Payload</label>

     					<div class="uk-form-controls">
     						<input type="text" id="payload" class="uk-input">
     					</div>
     				</div>




     				<div class="uk-margin">
     					<label class="uk-form-label" for="form-horizontal-text">Message</label>

     					<div class="uk-form-controls">
     						<input type="text" id="message" class="uk-textarea">
     					</div>
     				</div>
                </div>



 				<div class="uk-margin">

 					<button id="save_pref" type="button"
 					class="uk-button uk-button-primary uk-width-1-1">
 					SAVE PREFERENCE FILE
 				</button>
 			</div>
 			<div class="uk-clearfix"></div>
 			<!-- <div uk-spinner id="loading"></div> -->
 			<!-- <progress class="uk-progress" value="0" max="100" id="progressElem"></progress> -->
 			<div id="resfield" style="display: block; width: 100%; margin:auto;"></div>
 		</form>

 	</div>



 </div>

</div>


</div>








