<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="uk-margin-large-top uk-margin-large-bottom">
    <div class="uk-container uk-container-medium">
        <h2 class="uk-text-center">QC Approval</h2>

        <ul uk-tab class="uk-flex uk-flex-center">
            <li class="uk-active"><a href="#">Pendings</a></li>
            <li><a href="#">Approved</a></li>
            <li><a href="#">Rejected</a></li>
        </ul>

        <ul class="uk-switcher uk-margin">
            <li>

                <?php if (sizeof($logs['open_logs']) > 0) { ?>
                    <h4 class="uk-margin-remove">Hello <?php echo $name; ?>, we need your attention!</h4>
                <?php } else { ?>
                    <h4 class="uk-margin-remove">Hello <?php echo $name; ?>, there are no more QC approvals
                        pending!</h4>
                <?php } ?>

                <?php if (sizeof($logs['open_logs']) > 0) { ?>
                    <div class="uk-grid uk-grid-small uk-grid-match uk-grid-margin uk-margin-remove-top uk-child-width-1-2@m uk-child-width-1-3@l ">
                        <?php for ($i = 0; $i < sizeof($logs['open_logs']); $i++) { ?>

                            <div class="uk-margin-top uk-flex uk-flex-middle">
                                <div class="uk-card uk-card-default dark_border uk-animation-scale-up">
                                    <div class="uk-card-header ">
                                        <div class="uk-grid-small uk-flex-middle" uk-grid>

                                            <div class="uk-width-expand">
                                                <h3 class="uk-card-title uk-margin-remove-bottom">Tag
                                                    No: <?php echo $logs['open_logs'][$i]['TagNo']; ?></h3>

                                                <p class="uk-text-meta uk-margin-remove-top"><?php echo date("d-m-Y h:i:s A", strtotime($logs['open_logs'][$i]['CreatedDate'])); ?></p>
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
                                           class="uk-button uk-button-default">View more</a>
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


                                    <div class="uk-card uk-card-default green_border uk-animation-scale-up">


                                        <div class="uk-card-header ">
                                            <div class="uk-grid-small uk-flex-middle" uk-grid>

                                                <div class="uk-width-expand">
                                                    <h3 class="uk-card-title uk-margin-remove-bottom">Tag
                                                        No <?php echo $logs['approved_logs'][$i]['TagNo']; ?></h3>

                                                    <p class="uk-text-meta uk-margin-remove-top"><?php echo date("d-m-Y h:i:s A", strtotime($logs['approved_logs'][$i]['CreatedDate'])); ?></p>
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
                                               class="uk-button uk-button-default">View more</a>
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

                                    <div class="uk-card uk-card-default red_border uk-animation-scale-up">

                                        <div class="uk-card-header ">
                                            <div class="uk-grid-small uk-flex-middle" uk-grid>

                                                <div class="uk-width-expand">
                                                    <h3 class="uk-card-title uk-margin-remove-bottom">Tag
                                                        No <?php echo $logs['rejected_logs'][$i]['TagNo']; ?></h3>

                                                    <p class="uk-text-meta uk-margin-remove-top"><?php echo date("d-m-Y h:i:s A", strtotime($logs['rejected_logs'][$i]['CreatedDate'])); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-card-body ">
                                            <p class="uk-margin-remove">
                                                <b>EGRN: </b><?php echo $logs['rejected_logs'][$i]['EGRNNo']; ?></p>


                                        </div>
                                        <div class="uk-card-footer uk-text-center">
                                            <a type="button" href="<?php echo base_url(); ?>sui/<?php echo $cipher; ?>/log/<?php echo $logs['rejected_logs'][$i]['TagNo']; ?>"
                                               class="uk-button uk-button-default">View more</a>
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