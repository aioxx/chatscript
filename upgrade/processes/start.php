<?php

$system_message = 'Decompressing Upgrade Image File';
$redirect = 'decompress_image';

$upgrade_info = include 'upgrade_info.php';

if (isset($upgrade_info['db']) && !empty($upgrade_info['db'])) {
    if (!file_exists('backup')) {
        mkdir('backup');

        include('functions/files/copy.php');
        recurseCopy('../', 'backup');

        include('functions/database/backup.php');
        $upgrade_info = include 'upgrade_info.php';
        $GLOBALS["database_info"]=$upgrade_info['db'];
        backupDatabaseAllTables();
    }
} else {
    unlink('upgrade_info.php');
    $system_message = 'Error : Upgrade Failed.<br>';
    $system_message .= 'Reloading Process.';
    $redirect = 'initial';
}
