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

        $('#download_link').hide();
        $('#generate_excel_button').addClass('uk-hidden');

         $(function () {
        $("#datepicker-1").datepicker({maxDate: new Date(), dateFormat: 'yy-mm-dd'});
        $("#datepicker-2").datepicker({maxDate: new Date(), dateFormat: 'yy-mm-dd'});
    });


        // $('#loading').hide();
        $('#progressElem').hide();




    });


</script>


<div id="page-1-container" class="uk-width-1-1@s uk-width-4-5@m uk-width-5-6@m uk-margin-auto-left content_wrapper uk-margin-top uk-padding uk-padding-remove-top">

    <div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">

        <!--uk-width-1-2@l uk-width-1-2@m uk-width-1-2@s-->
        <h3 class="uk-heading-divider uk-text-center">Console</h3>
        <h6 class="uk-text-success">Logged in as <?php echo ADMIN_USERNAME; ?></h6>
    </div>
</div>




