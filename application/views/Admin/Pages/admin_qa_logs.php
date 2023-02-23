<?php
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 8/3/2018
 * Time: 9:08 AM
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');


?>


<div class="uk-width-1-1@s uk-width-4-5@m uk-margin-auto-left content_wrapper uk-padding uk-padding-remove-top">
    <div class="page-container">

        <table id="logs" class="display" style="width:100%">
            <thead>
            <tr>
                <th>Id</th>
                <th>CreatedDate</th>
                <th>TagNo</th>
                <th>Reason</th>
                <th>FinishedBy</th>
                <th>SubmittedBy</th>

            </tr>
            </thead>

        </table>
    </div>
</div>




<script>
    $(document).ready(function() {
        $('#logs').DataTable( {

            "ajax": base_url+"admin_controller/get_qa_logs"
        } );
    } );
</script>


