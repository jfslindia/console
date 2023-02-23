<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script>
    $(document).ready(function () {
        $('#loading').hide();


        $('#password').keydown(function (event) {

            if (event.keyCode == 13) {
                $('#create').hide();

                create_func();
            }

        });

        $("#create").on('click',function (event) {

            event.preventDefault();

            create_func();

        });

    });

    function create_func() {
        var username = $("#username").val();
        var password = $("#password").val();

        jQuery.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>" + "console/create_admin",
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
                $('#create').hide();

            },
            complete: function () {
                $("#loading").hide();
                $('#create').show();
            },
            success: function (res) {
                //console.log(res);
                if (res.status == "old_user") {
                    // Show Entered Value

                    UIkit.notification({
                        message: 'This admin is already registered!',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });

                } else if (res.status == "success") {

                    UIkit.notification({
                        message: 'Admin registered!',
                        status: 'success',
                        pos: 'bottom-center',
                        timeout: 1000
                    });

                    setTimeout(function(){

                       window.location="<?php  echo base_url();?>console";
                    },1000);

                }
                else if (res.status == 'failed') {

                    UIkit.notification({
                        message: 'Failed!',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });
                }

                else if (res.status == 'error') {

                    UIkit.notification({
                        message: res.message,
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });
                }

            },
            error: function (res) {

                //console.log(res);
            }


        });

    }

</script>


<div id="flex" class="uk-flex uk-flex-center uk-animation-fade">
    <div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body uk-margin-top uk-padding-remove-bottom">
        <h1 class="uk-text-center">ADMIN CREATION</h1>

        

        <form>



            <div class="uk-inline">
                <span class="uk-form-icon" uk-icon="icon: user"></span>
                <input class="uk-input" id="username" name="username" placeholder="Username" type="text">
            </div>



            <div class="uk-inline">
                <span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: lock"></span>
                <input class="uk-input" id="password" name="password" placeholder="Password" type="password">
            </div>

            <div class="uk-margin">

                <input id="create" name="create" class="uk-button uk-align-center uk-button-default" type="button"
                       value="Create">
            </div>

            <div uk-spinner id="loading" class="uk-flex uk-flex-center"></div>

            <div id="resfield" class="uk-margin-medium">
                <h6>

                </h6>
            </div>



        </form>

    </div>

</div>