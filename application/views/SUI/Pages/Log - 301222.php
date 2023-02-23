<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Jost:wght@300&display=swap" rel="stylesheet">
<style>


.newFont
{

font-family: 'Jost', sans-serif !important;
}
.boxBgColor
{
background-color:#f4f8fb !important;
color:#2575be !important;
border-radius:10px !important;
border-color:#f7fbff !important;
font-family: 'Jost', sans-serif !important;
}
.viewmoreBgColor{
color:#fff !important;
}
.viewmoreBgColorDanger
{
background-color:red  !important;
color:#fff !important;
text-align:center;
}
.viewmoreBgColorSuccess
{
background-color:#59c23f !important;
color:#fff !important;
text-align:center;
}
.go_back
{
 text-align:center;
}
.viewmoreBgColorFail
{
background-color:#FF0000 !important;
color:#fff !important;
text-align:center;
}
</style>

<a class="" uk-tooltip="Go back" style="position: fixed;bottom: 0; font-size:30px;"
   href="<?php echo base_url(); ?>sui/<?php echo $cipher['UniqueCode']; ?>/logs">
    <span class="uk-margin-small-right " uk-icon="icon: arrow-left; ratio: 2"></span>
</a>

<div ng-app="sui" ng-controller="sui_form" block-ui="main">
    <div class="uk-margin-large-top uk-margin-large-bottom">
        <div class="uk-container uk-container-medium uk-margin-top" align="center">
            <h3 class="uk-card-title uk-margin-remove-bottom textColor1 newFont">Tag No <?php echo $tag_no; ?></h3>

            <div class="uk-card uk-card-default uk-width-1-2@m uk-margin-auto">
                <table class="uk-table  newFont boxBgColor">

                    <tbody>


                    <tr>
                        <td>EGRN</td>
                        <td><?php echo $log_data['EGRNNo']; ?></td>
                    </tr>
                    <tr>
                        <td>Garment</td>
                        <td><?php echo $log_data['GarmentName']; ?></td>
                    </tr>


                    <tr>
                        <td>Reported date</td>
                        <td><?php echo date("d-m-Y h:i:s A",strtotime($log_data['CreatedDate'])); ?></td>
                    </tr>

                    <tr>
                        <td>Reported issue</td>
                        <td><?php echo $log_data['Remarks']; ?></td>
                    </tr>

                    <tr>
                        <td>Status</td>
                        <?php if (strtoupper($log_data['QCStatus']) == 'APPROVED') { ?>
                            <td class="uk-text-success"><p class="viewmoreBgColorSuccess"><?php echo $log_data['QCStatus']; ?></p></td>
                        <?php } else if (strtoupper($log_data['QCStatus']) == 'QCPENDING') { ?>
                            <td class="uk-text-success"><p class="viewmoreBgColorSuccess"><?php echo $log_data['QCStatus']; ?></p></td>
                        <?php } else { ?>
                            <td><p class="viewmoreBgColorDanger"><?php echo $log_data['QCStatus']; ?></p></td>
                        <?php } ?>
                    </tr>

                    </tbody>
                </table>

            </div>


            <div class="uk-width-1-2@m uk-margin-auto">

                <?php if (sizeof($images) > 0) { ?>
                    <div class="uk-h3 newFont">Images</div>
                <?php } ?>

                <div class="uk-child-width-1-3@m" uk-grid uk-lightbox="animation: slide">

                    <?php

                    for ($i = 0; $i < sizeof($images); $i++) {

                        ?>
                        <div>
                            <a href="<?php echo $images[$i]['Image_URL']; ?>"
                               data-caption="<?php echo $log_data['Remarks']; ?>">
                                <img src="<?php echo $images[$i]['Image_URL']; ?>" alt="">
                            </a>
                        </div>
                    <?php
                    }
                    ?>

                </div>
            </div>


            <?php if (strtoupper($log_data['QCStatus']) == 'QCPENDING') { ?>
                <div class="uk-width-1-2@m uk-margin-auto box">
                    <h4 class="uk-text-primary uk-margin-remove newFont">Dear <?php echo $log_data['CustomerName']; ?>, 

please
                        take
                        necessary action.</h4>

                    <form>

                        <!--<input type="hidden" name="qc_id" value="<?php /*echo $log_data['Id']; */ ?>">-->
                        <input type="hidden" name="tag_no" ng-model="tag_no" ng-init="tag_no='<?php echo $tag_no; 

?>'" ng-value="<?php echo $tag_no; ?>">
                        <input type="hidden" name="customer_id" ng-model="customer_id" ng-init="customer_id='<?php 

echo $log_data['CustomerCode']; ?>'" ng-value="<?php echo $log_data['CustomerCode']; ?>">

                      <!--  <div class="uk-margin uk-grid-small uk-child-width-auto uk-flex uk-flex-center uk-grid" ng-

init="response=true">
                            <label><input name="response" class="uk-radio" type="radio" ng-value="true" ng-

model="response"> Approve
                                and
                                go ahead</label>
                            <label><input name="response" class="uk-radio" type="radio" ng-value="false" ng-

model="response"> Reject and
                                return my
                                garment.</label>
                        </div>-->

                        <div class="uk-padding-small">
                            <button class="uk-button uk-button-danger btnsubmit viewmoreBgColorFail" ng-click="rejected(); 

$event.stopPropagation();">REJECT</button>
                            <button class="uk-button uk-button-success btnsubmit viewmoreBgColorSuccess" ng-click="approved(); 

$event.stopPropagation();">APPROVE</button>

                        </div>

                        <div>

                        <textarea placeholder="Your response" class="uk-textarea uk-hidden" id="customer_response"
                                  name="detailed_response"
                                  rows="5"></textarea>
                        </div>


                    </form>

                </div>

            <?php } else { ?>

                <div class="uk-width-1-2@m uk-margin-auto uk-margin-top newFont">

                    <p>
                        You <span><?php echo $log_data['QCStatus']; ?></span> the garments for
                        further
                        process.
                        Saved on <?php echo date("d-m-Y h:i:s A", strtotime($log_data['ModifiedDate'])); ?>.

                    </p>


                </div>
            <?php } ?>
        </div>


    </div>

   <div class="go_back">
        <button class="uk-button ">
            <a id="m_navigation" class="uk-hidden@m"
            href="<?php echo base_url(); ?>sui/<?php echo $cipher['UniqueCode']; ?>/logs">
               Back
            </a>
        </button>
 </div>   

    <div id="confirmation_model" class="uk-flex-top" uk-modal>
        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical uk-padding-remove-bottom">

            <p class="uk-text-justify uk-text-center uk-text-primary uk-margin-remove newFont" style="line-height: 1.3">Your 

garment will be
                delivered <span style="font-weight: 600;text-decoration: underline">a day later</span> than the
                committed delivery date, as it had to undergo a special approval process from our pre-process QC 

team
            </p>


            <div class="uk-padding-small uk-text-center">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                <button class="uk-button uk-button-primary subm" type="button" ng-click="save_response

()">Save</button>
            </div>
        </div>
    </div>
</div>

<style>
    html, body {
        font-size: 16px;
    }

    .box {
        margin-top: 20px;
        border: 1px solid #0000002e;
        padding: 10px;
        text-align: center;
        box-shadow: 1px 1px 10px 3px #ccc;
    }

    tbody {
        border: 2px solid #0000001f;
    }

</style>

<!--<script>

    $('input[name=response]').change(function () {

        if ($('input[name=response]:checked').val() == 'REJECT') {
            $('#customer_response').removeClass('uk-hidden');

        } else {

            if ($('#customer_response').hasClass('uk-hidden')) {

            } else {

                $('#customer_response').addClass('uk-hidden');
            }
        }
    })
</script>-->

<style>
    .uk-modal-dialog {
        width: 400px;
    }
    .uk-button-success{
        background: #2b882d;
        color: #FFFFFF;
    }
    .uk-button-success:hover,.uk-button-success:focus{
        background: #206522;
    }

    .uk-button-danger{
        background: #ff1a45;
        color: #FFFFFF;
    }
    .uk-button-danger:hover,.uk-button-danger:focus{
        background: #c41435;
    }
</style>

