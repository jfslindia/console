<?php

    if ( 0 < $_FILES['file']['error'] ) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    }
    else {
        $time = time();
        $tm = substr($time,0,4);
        move_uploaded_file($_FILES['file']['tmp_name'], 'console_notification_images/' . $tm.$_FILES['file']['name']);
    }

?>