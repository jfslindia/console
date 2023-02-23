<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Jost:wght@300&display=swap" rel="stylesheet">
<style>
.boxBgColor
{
background-color:#f7fbff !important;
color:#2575be !important;
border-radius:10px !important;
border-color:#f7fbff !important;
font-family: 'Jost', sans-serif !important;
}
.textColor1{
color:#2575be !important;
font-weight:bold !important;
font-size:15px;
}
.textColor2{
color:#2575be !important;font-weight: 400;
}
.viewmoreBgColor{
background-color:#2575be !important;
color:#fff !important;
}

.uk-card-header {
    border-bottom: 0px solid #f7fbff !important;
}
.uk-button{
line-height:25px !important;
}
 .uk-card-footer{
    border-bottom: 0px solid #f7fbff !important;
    border-top: 0px solid #f7fbff !important;
}
.qcApproval
{
    padding-top:1px;
    border:0px solid #e4effa !important;
}
.qcApprovalTabs
{
    font-size:16px;
    padding-top:2px;
    border: 0px solid #e4effa !important;
    font-style: italic;
    color : white;
}

.uk-tab>.uk-active>a
{
background-color:ash;
font-size:large;
padding:4px !important;
text-transform:capitalize;
font-family: 'Jost', sans-serif !important;
    border: 3px solid #e4effa !important;
}
.newFont
{

font-family: 'Jost', sans-serif !important;
}
.uk-tab>*>a
{
font-size:large;
    padding:4px !important;
text-transform:capitalize;
font-size:14px;
    border: 2px solid #e4effa !important;
}
.uk-margin-large-bottom{
margin-top: 0px!important;
}
.uk-container{
padding-top: 10px!important;
padding-bottom: 10px!important;
}

html, body {
        font-size: 16px;
        padding:0px;
}
li{
      font-size: 16px;
}
.statusbutton{
   background-color:#2575be;
  border: none;
  padding: 5px 5px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  color :#7FFF00;
}

.statusbutton a{
  color :#fff !important;
}
.uk-tab>.uk-active>.statusbutton
{
background-color:red !important;
}
.uk-h1, .uk-h2, .uk-h3, .uk-h4, .uk-h5, .uk-h6, h1, h2, h3, h4, h5, h6 {
    color: #000;
}
</style>
<div class="uk-margin-large-top uk-margin-large-bottom newFont fullBackground">
<br>
<h2 class="uk-text-center qcApproval newFont">QC Approval</h2>
   
<div class="uk-container uk-container-medium">
        <ul uk-tab class="uk-flex uk-flex-center qcApprovalTabs">
           <li class="uk-active"><button type="button" class="statusbutton"><a href="#">PENDINGS</a></button></li>
            <li><button type="button" class="statusbutton"><a href="#">APPROVED</a></button></li>
            <li><button type="button" class="statusbutton"><a href="#">REJECTED</a></button></li>
        </ul>
        <ul class="uk-switcher uk-margin ">
            <li>
                <?php if (sizeof($logs['open_logs']) > 0) { ?>
                    <h4 class="uk-margin-remove newFont">Hello <?php echo $name; ?>, we need your attention!</h4>
                <?php } else { ?>
                    <h4 class="uk-margin-remove newFont">Hello <?php echo $name; ?>, there are no more QC approvals
                        pending!</h4>
                <?php } ?>
                <?php if (sizeof($logs['open_logs']) > 0) { ?>
                    <div class="uk-grid uk-grid-small uk-grid-match uk-grid-margin uk-margin-remove-top uk-child-width-1-2@m uk-child-width-1-3@l ">
                        <?php for ($i = 0; $i < sizeof($logs['open_logs']); $i++) { ?>

                            <div class="uk-margin-top uk-flex uk-flex-middle ">
                                <div class="uk-card uk-card-default dark_border uk-animation-scale-up boxBgColor">
                                    <div class="uk-card-header ">
                                        <div class="uk-grid-small uk-flex-middle" uk-grid>

                                            <div class="uk-width-expand">
                                                <h3 class="uk-card-title uk-margin-remove-bottom textColor1">Tag
                                                     No: <?php echo $logs['open_logs'][$i]['TagNo']; ?></h3>

                                                <p class="uk-text-meta uk-margin-remove-top textColor2"><?php echo date("d-m-Y h:i:s A", strtotime($logs['open_logs'][$i]['CreatedDate'])); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-card-body ">

                                        <p class="uk-margin-remove">
                                            <b>EGRN: </b><?php echo $logs['open_logs'][$i]['EGRNNo']; ?>
                                        </p>


                                        <p class="uk-margin-remove"><b>Reported
                                                issue: </b><?php echo $logs['open_logs'][$i]['Remarks']; ?></p>

                                        <!--<p class="uk-margin-remove">
                                            <b>Garment: </b><?php /*echo $logs['open_logs'][$i]['GarmentName']; */ ?></p>-->


                                        <p class="uk-margin-remove"><b>Status: </b><span
                                                class="uk-text-success">OPEN</span>
                                        </p>

                                    </div>
                                    <div class="uk-card-footer  uk-text-center">
                                        <a type="button" href="<?php echo base_url(); ?>sui/<?php echo $cipher; ?>/log/<?php echo $logs['open_logs'][$i]['TagNo']; ?>"
                                           class="uk-button uk-button-default viewmoreBgColor">View more</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                <?php } ?>

            </li>
            <li>

                <?php if (sizeof($logs['approved_logs']) > 0) { ?>

                    <div class="uk-margin-top">

                        <div
                            class="uk-grid uk-grid-small uk-grid-match uk-grid-margin uk-margin-remove-top uk-child-width-1-2@m uk-child-width-1-3@l ">
                            <?php for ($i = 0;
                                       $i < sizeof($logs['approved_logs']);
                                       $i++) {
                                ?>

                                <div class="uk-margin-top">


                                    <div class="uk-card uk-card-default green_border uk-animation-scale-up boxBgColor">


                                        <div class="uk-card-header ">
                                            <div class="uk-grid-small uk-flex-middle" uk-grid>

                                                <div class="uk-width-expand">
                                                    <h3 class="uk-card-title uk-margin-remove-bottom textColor1">Tag
                                                        No <?php echo $logs['approved_logs'][$i]['TagNo']; ?></h3>

                                                    <p class="uk-text-meta uk-margin-remove-top textColor2"><?php echo date("d-m-Y h:i:s A", strtotime($logs['approved_logs'][$i]['CreatedDate'])); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-card-body ">
                                            <p class="uk-margin-remove">
                                                <b>EGRN: </b><?php echo $logs['approved_logs'][$i]['EGRNNo']; ?></p>

                                            <p class="uk-margin-remove"><b>Reported
                                                    issue: </b><?php echo $logs['approved_logs'][$i]['Remarks']; ?></p>

                                        </div>
                                        <div class="uk-card-footer uk-text-center">
                                            <a type="button" href="<?php echo base_url(); ?>sui/<?php echo $cipher; ?>/log/<?php echo $logs['approved_logs'][$i]['TagNo']; ?>"
                                               class="uk-button uk-button-default viewmoreBgColor">View more</a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                    </div>
                <?php } ?>

            </li>
            <li>

                <?php if (sizeof($logs['rejected_logs']) > 0) { ?>

                    <div class="uk-margin-top">


                        <div
                            class="uk-grid uk-grid-small uk-grid-match uk-grid-margin uk-margin-remove-top uk-child-width-1-2@m uk-child-width-1-3@l ">
                            <?php for ($i = 0;
                                       $i < sizeof($logs['rejected_logs']);
                                       $i++) {
                                ?>

                                <div class="uk-margin-top">

                                    <div class="uk-card uk-card-default red_border uk-animation-scale-up boxBgColor">

                                        <div class="uk-card-header ">
                                            <div class="uk-grid-small uk-flex-middle" uk-grid>

                                                <div class="uk-width-expand">
                                                    <h3 class="uk-card-title uk-margin-remove-bottom textColor1">Tag
                                                        No <?php echo $logs['rejected_logs'][$i]['TagNo']; ?></h3>

                                                    <p class="uk-text-meta uk-margin-remove-top textColor2"><?php echo date("d-m-Y h:i:s A", strtotime($logs['rejected_logs'][$i]['CreatedDate'])); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-card-body ">
                                            <p class="uk-margin-remove">
                                                <b>EGRN: </b><?php echo $logs['rejected_logs'][$i]['EGRNNo']; ?></p>


                                        </div>
                                        <div class="uk-card-footer uk-text-center">
                                            <a type="button" href="<?php echo base_url(); ?>sui/<?php echo $cipher; ?>/log/<?php echo $logs['rejected_logs'][$i]['TagNo']; ?>"
                                               class="uk-button uk-button-default viewmoreBgColor">View more</a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                    </div>
                <?php } ?>


            </li>
        </ul>


    </div>
</div>


<script>

    document.getElementById('m_header').style.display = 'none';

</script>

<style>
    .red_border {
        border: 3px solid #ff000052;
    }

    .green_border {
        border: 3px solid #00800052;
    }

    .dark_border {
        border: 3px solid #00000052;
    }

    .uk-card {
        padding-left: 10px !important;
    }
</style>