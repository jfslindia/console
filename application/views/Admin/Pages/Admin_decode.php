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


<div id="page-1-container" class="uk-width-4-5 uk-margin-auto-left">
    <div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">
        <h3 class="uk-heading-divider uk-text-center">Decode</h3>
        <form action="<?php echo base_url(); ?>console/decode_now/" method="POST">
            <input name="encrypted" class="uk-input">
        </form>
    </div>
</div>




