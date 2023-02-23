<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script>
    $(document).ready(function () {

        $('#loading').hide();
        //$('#login').hide();

        $("#login").click(function () {

            event.preventDefault();
            login();

        });

        $("#password").keydown( function (e) {

            if (e.keyCode == 13) {

                login();
            }

        });

    });



    function login(){

        var username = $("#username").val();
        var password = $("#password").val();


        jQuery.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>" + "console_controller/login_pro",
            dataType: 'json',
            data: {
                username: username,
                password: password
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
                $('#login').hide();

            },
            complete: function () {
                $("#loading").hide();
                $('#login').show();

            },
            success: function (res) {
                //console.log(res);

                if (res.status == "failed") {
                    // Show Entered Value

                    UIkit.notification({
                        message: 'Credentials missmatch',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });

                }else if(res.status == "invalid"){

                    UIkit.notification({
                        message: 'Invalid admin',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });

                }
                else if(res.status == "error"){

                    UIkit.notification({
                        message: res.message,
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });

                }

                else if(res.status == "success"){

                    $(location).attr('href', '<?php echo base_url(); ?>console/home');


                }else{

                    UIkit.notification({
                        message: 'Error',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });

                }
            },error: function (res) {

                //console.log(res);
            }
        });
    }
</script>


<div id="flex" class="uk-flex uk-flex-center uk-flex-middle uk-animation-fade uk-padding uk-height-1-1">
    <div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body uk-padding-remove-bottom">
        <h1 class="uk-text-center">JFSL</h1>

        <form>

            <div class="uk-margin">
                <div class="uk-inline">
                    
                    <input class="uk-input" id="username" placeholder="Username" name="username" type="text">
                </div>
            </div>

            <div class="uk-margin">
                <div class="uk-inline">
                    
                    <input class="uk-input" id="password" name="password" placeholder="Password" type="password">

                </div>
            </div>

            <div class="uk-margin">

                <input id="login" name="login" class="uk-button uk-align-center uk-button-default" type="button"
                       value="Login">
            </div>
            <div class="uk-flex uk-flex-center" uk-spinner id="loading"></div>

            <div id="resfield" class="uk-margin-medium">
                </h6>
            </div>

        </form>

    </div>

</div>