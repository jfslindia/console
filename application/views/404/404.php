<?php
/**
 * Created by PhpStorm.
 * User: KP
 * Date: 21-01-2020
 * Time: 14:13
 */

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>

    <title>Link <expired></expired></title>

    <link rel="icon" href="<?php echo base_url() . 'fav.png'; ?>" type="image/png">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/core/uikit.min.css"/>


    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/jquery-1.12.4.min.js"></script>


    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/uikit.min.js"></script>

    <script> var base_url = "<?php echo base_url(); ?>"; </script>


    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/uikit-icons.min.js"></script>


    <link href="<?php echo base_url() ?>assets/css/custom/fabricspa.css?n=<?php echo date('G'); ?>" rel="stylesheet"
          media="screen">


</head>
<body>
<div class="uk-margin-large-top uk-margin-bottom">
    <h2 class="uk-text-center uk-text-danger">Sorry, this link is no longer valid.</h2>
</div>

<?php if (isset($report_type) && isset($report_number)) { ?>

    <div class="uk-margin-top">
        <h4 class="uk-text-primary uk-text-center">Don't worry though, you can request your <?php echo $report_type ?>
            by mentioning
            your <?php echo $report_type; ?> number (<?php echo $report_number ?>) to <a href="javascript:location='mailto:\u0066\u0065\u0065\u0064\u0062\u0061\u0063\u006b\u0040\u0066\u0061\u0062\u0072\u0069\u0063\u0073\u0070\u0061\u002e\u0063\u006f\u006d';void 0"><script type="text/javascript">document.write('\u0066\u0065\u0065\u0064\u0062\u0061\u0063\u006b\u0040\u0066\u0061\u0062\u0072\u0069\u0063\u0073\u0070\u0061\u002e\u0063\u006f\u006d')</script></a></h4>
    </div>

<?php } ?>

</body>

</html>