<?php

function add_to_error_log($error_message)
{
    if (!empty($error_message)) {
        $contents = '';
        if (file_exists('upgrade_error_logs.txt')) {
            $contents = file_get_contents('upgrade_error_logs.txt');
        }

        if (is_array($error_message)) {
            $error_message = json_encode($error_message);
        }

        $contents = $contents.PHP_EOL.$error_message;
        file_put_contents('upgrade_error_logs.txt', $contents);
    }
}
