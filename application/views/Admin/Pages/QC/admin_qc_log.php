<?php
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 8/3/2018
 * Time: 9:08 AM
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');


?>



<div class="uk-width-1-1@s uk-width-4-5@m uk-margin-auto-left content_wrapper uk-padding">
    <div class="page-container">



        <div class="uk-text-center">
            <button class="uk-button uk-button-default" onclick="reset_search()">Go back</button>
        </div>


        <h4 class="uk-text-center uk-margin-remove">Tag No <?php echo $garment_details['TagNo']; ?></h4>
        <div class="uk-card uk-card-default uk-width-1-2@m uk-margin-auto">
            <table class="uk-table uk-table-striped">

                <tbody>

                <tr>
                    <td>Order ID</td>
                    <td><?php echo $garment_details['OrderID']; ?></td>
                </tr>
                <tr>
                    <td>EGRN</td>
                    <td><?php echo $garment_details['EGRNNo']; ?></td>
                </tr>
                <tr>
                    <td>Garment</td>
                    <td><?php echo $garment_details['GarmentName']; ?></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><?php echo $garment_details['Description']; ?></td>
                </tr>

                <tr>
                    <td>Reported date</td>
                    <td><?php echo date("d-m-Y h:i:s A", strtotime($log_data['CreatedDate'])); ?></td>
                </tr>

                <tr>
                    <td>Reported issue</td>
                    <td><?php echo $log_data['Reason']; ?></td>
                </tr>

                <tr>
                    <td>Status</td>
                    <?php if(strtoupper($log_data['Status'])=='OPEN') { ?>
                        <td class="uk-text-success"><?php echo $log_data['Status']; ?></td>
                    <?php } else { ?>
                        <td><?php echo $log_data['Status']; ?></td>
                    <?php } ?>
                </tr>

                </tbody>
            </table>

        </div>


        <div class="uk-width-1-2@m uk-margin-auto">
            <div class="uk-h3">Images</div>
            <div class="uk-child-width-1-3@m" uk-grid uk-lightbox="animation: slide">
                <?php

                if(IS_TESTING){
                    $base_url='https://appuat.fabricspa.com/UAT/jfsl/uploads/qc/';
                }else{
                    $base_url='https://intapps.fabricspa.com/jfsl/uploads/qc/';
                }

                for($i=0;$i<sizeof($images);$i++){ ?>

                    <div>
                        <a class="uk-inline" href="<?php echo $base_url.$images[$i]['Image']; ?>" data-caption="<?php echo $log_data['Reason']; ?>">
                            <img src="<?php echo $base_url.$images[$i]['Image']; ?>" alt="">
                        </a>
                    </div>

                <?php } ?>


            </div>
        </div>


        <?php if($log_data['Status']!='CLOSED') { ?>


        <?php } else { ?>

            <div class="uk-width-1-2@m uk-margin-auto uk-margin-top">

                <p>
                    Customer's response was to <span style="font-style: italic;"><?php echo $log_data['CustomerResponse']; ?></span> the process. Saved on <?php echo date("d-m-Y h:i:s A", strtotime($log_data['CustomerResponseDate'])); ?>

                </p>


            </div>

        <?php } ?>

    </div>

</div>


<style>
    html,body{
        font-size: 16px;
    }
    .box{
        margin-top: 20px;
        border: 1px solid #0000002e;
        padding: 10px;
        text-align: center;
        box-shadow: 1px 1px 10px 3px #ccc;
    }


</style>


<script>
    function reset_search(){
        window.location = base_url + "console/qc_logs";
    }
</script>