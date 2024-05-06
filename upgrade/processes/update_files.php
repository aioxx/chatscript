<?php

$system_message = 'Updating Database.';
$redirect = 'update_database';
$upgrade_info = include 'upgrade_info.php';

if ($upgrade_info['grupo_version']==2) {
    include 'sub_processes/version_2/update_files.php';
} else {
    include 'sub_processes/version_3/update_files.php';
}
