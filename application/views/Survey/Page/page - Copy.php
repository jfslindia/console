<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/*Color codes for the 1-10 ratings*/
        $colors[0]='#FF0000';
        $colors[1]='#FF4600';
        $colors[2]='#FF6900';
        $colors[3]='#FF8C00';
        $colors[4]='#FFAF00';
        $colors[5]='#FFE400';
        $colors[6]='#F7FF00';
        $colors[7]='#C2FF00';
        $colors[8]='#7CFF00';
        $colors[9]='#00FF00';

?>

<div class="border_top_image">
</div>

<!--<div class="main_content  uk-hidden@ uk-padding font_gotham_light">-->
<div class="uk-container">
    <div class="uk-card uk-card-default main_content">
        <div class="fabricspa_bar" style="height:12px;background-image: url('<?php echo base_url(); ?>assets/images/bar_1.png');"></div>
        <div class="uk-card-header">

            <h5 class="uk-text-bold uk-text-primary uk-text-center"><?php echo 'Hello '.$customer_details['name']; ?>, Please help us grow</h5>
        </div>
        <div class="uk-card-body">
            <h6><?php echo $current_question; ?></h6>


                <?php if ($step['Step'] == 0 || $step['Step'] == FALSE) {

                    /*Initial step.*/
                    $question_number=1;
                    ?>

            <div class="uk-grid uk-grid-collapse uk-child-width-1-10">

                <?php for ($i = 0; $i < 10; $i++) { ?>

                    <div>
                        <div class="uk-flex uk-flex-center uk-flex-middle pulse_hover" style="height:100%;background: <?php echo $colors[$i]; ?>">
                            <a onclick="answer(<?php echo $question_number.','.($i+1); ?>)" class="uk-width-1-1 uk-text-center">
                                <p class="uk-h4 uk-text-bold uk-margin-remove-bottom"><?php echo $i+1; ?></p>
                            </a>
                        </div>
                    </div>

                <?php } ?>

            </div>

            <?php } else if($step['Step']==1) { ?>

                    <?php $question_number=2; ?>
                    <div>
                        <textarea id="detailed_answer" class="uk-textarea" placeholder="Please enter your response..."></textarea>
                    </div>

                    <p class="uk-text-center">
                        <a type="button" class="uk-button uk-button-default uk-text-capitalize uk-text-success pulse_hover" onclick="answer(<?php echo $question_number.','.NULL; ?>)">Submit</a>
                    </p>

            <?php } ?>


        </div>
        <div class="uk-card-footer uk-text-center">
            <p>Follow us on</p>

            <p>
                <span>
                <a href="" uk-icon="twitter"></a>
                <a href="" uk-icon="facebook"></a>
                <a href="" uk-icon="instagram"></a>
                <a href="" uk-icon="youtube"></a>
            </span>
            </p>

        </div>
        <div class="fabricspa_bar" style="height:12px;background-image: url('<?php echo base_url(); ?>assets/images/bar_1.png');"></div>
    </div>
</div>

<script>
    function answer(question_number,answer){

        if(question_number==2&& !answer){
            var answer=$('#detailed_answer').val();
        }

        $.ajax({
            type: "post",
            url: base_url + "Survey/answer",
            cache: false,
            data: {
                question_number:question_number,
                answer:answer,
                customer_id:customer_id
            },
            dataType: 'json',
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                //Download progress
                xhr.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;

                    }
                }, false);
                return xhr;
            },
            beforeSend: function () {
                $.blockUI({

                    message: '<div class="white_color_text" uk-spinner></div>'
                });

            },
            complete: function () {
                $.unblockUI();
            },
            success: function (res) {

                if(res.status=='success'){
                    location.reload();
                }

            }
        });
    }
</script>