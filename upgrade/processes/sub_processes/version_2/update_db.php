<?php

DB::connect()->query('SET foreign_key_checks = 0');

$sql_query=  DB::connect()->query("SHOW TABLES LIKE 'old_version_gr_users'")->fetchAll();
$second_query=  DB::connect()->query("SHOW TABLES LIKE 'gr_site_users'")->fetchAll();

if (count($sql_query) == 0 && count($second_query) == 0) {
    $sql_query=  DB::connect()->query("SHOW TABLES LIKE 'gr_%'")->fetchAll();
    foreach ($sql_query as $db_table) {
        $table_name=$db_table[0];
        $new_table_name = 'old_version_'.$table_name;
        $query='ALTER TABLE '.$table_name.' RENAME TO '.$new_table_name;
        $sql_query=  DB::connect()->query($query);
    }
}

if (count($second_query) == 0) {
    $db_file=file_get_contents('upgrade_image/layouts/installer/installer.sql');
    try {
        DB::connect()->query($db_file);
    } catch (PDOException $exception) {
        $system_message = 'Error : Database Import Failed.';
        $redirect = null;
    }
}

if (isset($_GET['sub_process']) && !empty($_GET['sub_process'])) {
    $sub_process_file='processes/sub_processes/version_2/'.$_GET['sub_process'].'.php';
    if (file_exists($sub_process_file)) {
        include($sub_process_file);
    }
} else {
    try {
        $drop_tables="DROP TABLE IF EXISTS old_version_gr_alerts,old_version_gr_complaints,";
        $drop_tables.="old_version_gr_customize,old_version_gr_logs,";
        $drop_tables.="old_version_gr_session,old_version_gr_utrack,old_version_gr_mails;";

        DB::connect()->query($drop_tables);
    } catch (PDOException $exception) {
    }

    $system_message = 'Importing Site Roles.';
    $redirect = 'update_database';
    $sub_process = 'site_roles';
}

DB::connect()->query('SET foreign_key_checks = 1');
