<?php

DB::connect()->query('SET foreign_key_checks = 0');

$sql_query=  DB::connect()->query("SHOW TABLES LIKE 'new_grupo_version_%'")->fetchAll();

if (count($sql_query) === 0) {
    $db_file=file_get_contents('upgrade_image/layouts/installer/installer.sql');
    $db_file=str_replace('`gr_', '`new_grupo_version_', $db_file);
    $db_file=str_replace('ADD CONSTRAINT `', 'ADD CONSTRAINT `new_version_', $db_file);
    DB::connect()->query($db_file);
}

if (isset($_GET['sub_process']) && !empty($_GET['sub_process'])) {
    $sub_process_file='processes/sub_processes/version_3/'.$_GET['sub_process'].'.php';
    if (file_exists($sub_process_file)) {
        include($sub_process_file);
    }
} else {
    $system_message = 'Analysing Database Tables.';
    $redirect = 'update_database';
    $sub_process = 'compare_db_tables';
}

DB::connect()->query('SET foreign_key_checks = 1');
