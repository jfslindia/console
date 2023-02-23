<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>


<div class="uk-margin-large-top uk-margin-large-bottom">
    <div class="uk-container uk-container-medium">
        <h2 class="uk-text-center">QC Approval</h2>

        <?php if (sizeof($logs['open_logs']) > 0) { ?>
            <h4 class="uk-margin-remove">Hello <?php echo $name; ?>, We need your attention!</h4>
        <?php } else { ?>
            <h4 class="uk-margin-remove">Hello <?php echo $name; ?>, there are no more QC approvals pending!</h4>
        <?php } ?>

        <?php if (sizeof($logs['open_logs']) > 0) { ?>

            <table class="uk-table uk-table-hover uk-table-divider">
                <thead>
                <tr>
                    <th>Tag No</th>
                    <th>EGRN</th>
                    <th>Garment</th>
                    <th>Reported issue</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>


                <?php for ($i = 0; $i < sizeof($logs['open_logs']); $i++) { ?>
                    <tr>
                        <td><?php echo $logs['open_logs'][$i]['TagNo']; ?></td>
                        <td><?php echo $logs['open_logs'][$i]['EGRNNo']; ?></td>
                        <td><?php echo $logs['open_logs'][$i]['GarmentName']; ?></td>
                        <td><?php echo $logs['open_logs'][$i]['Reason']; ?></td>
                        <td><?php echo $logs['open_logs'][$i]['Status']; ?></td>
                    </tr>
                <?php } ?>

                </tbody>
            </table>

        <?php } ?>


        <?php if (sizeof($logs['closed_logs']) > 0) { ?>

            <table class="uk-table uk-table-hover uk-table-divider">
                <thead>
                <tr>
                    <th>Tag No</th>
                    <th>EGRN</th>
                    <th>Garment</th>
                    <th>Reported issue</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>


                <?php for ($i = 0; $i < sizeof($logs['closed_logs']); $i++) { ?>
                    <tr>
                        <td><?php echo $logs['closed_logs'][$i]['TagNo']; ?></td>
                        <td><?php echo $logs['closed_logs'][$i]['EGRNNo']; ?></td>
                        <td><?php echo $logs['closed_logs'][$i]['GarmentName']; ?></td>
                        <td><?php echo $logs['closed_logs'][$i]['Reason']; ?></td>
                        <td><?php echo $logs['closed_logs'][$i]['Status']; ?></td>
                    </tr>
                <?php } ?>

                </tbody>
            </table>

        <?php } ?>

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
</style>