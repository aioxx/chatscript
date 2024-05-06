<?php

$sql_query=  DB::connect()->query("SHOW TABLES LIKE 'new_grupo_version_%'")->fetchAll();

foreach ($sql_query as $db_table) {
    $table_name = $db_table[0];
    if(!empty($table_name)){
      DB::connect()->query("DROP TABLE ".$table_name);
    }
}
$system_message = 'Rebuilding Cache.';
$redirect = 'cache_rebuilder';
