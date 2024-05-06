<?php

$system_message = 'Finalizing Upgrade';
$redirect = null;
$upgrade_info = include 'upgrade_info.php';
$GLOBALS["database_info"]=$upgrade_info['db'];

include('functions/database/load.php');

if ($upgrade_info['grupo_version']==2) {
    include 'sub_processes/version_2/update_db.php';
} else {
    include 'sub_processes/version_3/update_db.php';
}
