<?php
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 7/17/2019
 * Time: 6:25 PM
 */

?>
<div class="uk-width-1-1@s uk-width-4-5@m uk-width-5-6@m uk-margin-auto-left content_wrapper uk-margin-top uk-padding uk-padding-remove-top">
    <div class="page-container">
<table id="example" class="display" style="width:100%">
    <thead>
    <tr>
        <th>Id</th>
        <th>CreatedDate</th>
        <th>TagNo</th>
        <th>Reason</th>
        <th>FinishedBy</th>

    </tr>
    </thead>

</table>
</div>
    </div>


<script>
    $(document).ready(function() {
        $('#example').DataTable( {

            "ajax": base_url+"console_controller/test_data"
        } );
    } );
</script>