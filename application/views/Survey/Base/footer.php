<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

?>
<script>
    $('.pulse_hover').mouseover(function(){
        if($(this).hasClass('animated')==false){
            $(this).addClass('animated pulse infinite');
        }

    })

    $('.pulse_hover').mouseout(function(){
        if($(this).hasClass('animated')){

            $(this).removeClass('animated pulse infinite');

        }

    })
</script>
</body>
</html>