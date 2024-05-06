<?php


    $pre_sql_queries = array();
    $pre_sql_queries[] = 'ALTER TABLE `gr_complaints` DROP FOREIGN KEY `user_id_fk_8`;';
    $pre_sql_queries[]= 'ALTER TABLE `gr_complaints` DROP INDEX `user_id_fk_8`;';
    $pre_sql_queries[]= 'ALTER TABLE `gr_complaints` DROP FOREIGN KEY `private_message_id_fk`;';
    $pre_sql_queries[]= 'ALTER TABLE `gr_complaints` DROP INDEX `private_message_id_fk`;';
    $pre_sql_queries[]= 'ALTER TABLE `gr_complaints` DROP FOREIGN KEY `group_message_id_fk_2`;';
    $pre_sql_queries[]= 'ALTER TABLE `gr_complaints` DROP INDEX `group_message_id_fk_2`;';
    $pre_sql_queries[]= 'ALTER TABLE `gr_complaints` DROP FOREIGN KEY `group_id_fk_5`;';
    $pre_sql_queries[]= 'ALTER TABLE `gr_complaints` DROP INDEX `group_id_fk_5`;';

    foreach ($pre_sql_queries as $pre_sql_query) {
        try {
            DB::connect()->query($pre_sql_query);
        } catch (Exception $e) {
        }
    }


$db_tables=  DB::connect()->query("SHOW TABLES LIKE 'new_grupo_version_%'")->fetchAll();
$add_index_sql = '';
$repeat_task = false;
$log_query = true;

if (count($db_tables) > 5) {
    $db_tables = array_slice($db_tables, 0, 5);
}

if (count($db_tables) > 2) {
    $repeat_task = true;
}
  if (count($db_tables) > 0) {
      foreach ($db_tables as $db_table) {
          $db_table_name=$db_table[0];
          $check_table = str_replace('new_grupo_version_', 'gr_', $db_table_name);
          $check_db =  DB::connect()->query("SHOW TABLES LIKE '".$check_table."'")->fetchAll();

          if (count($check_db) === 0) {
              $rename_table = 'ALTER TABLE '.$db_table_name.' RENAME TO '.$check_table.';';

              $query= 'SELECT clms.`COLUMN_NAME`,clms.`CHARACTER_MAXIMUM_LENGTH`,clms.`COLUMN_TYPE`,clms.`COLUMN_COMMENT`,';
              $query .= 'clms.`COLUMN_KEY`,clms.`COLUMN_DEFAULT`,clms.`IS_NULLABLE`,clms.`DATA_TYPE`';
              $query .= ',rc.`CONSTRAINT_NAME`,rc.`UPDATE_RULE`, kcu.`REFERENCED_COLUMN_NAME`, ';
              $query .= 'rc.`DELETE_RULE`,rc.`REFERENCED_TABLE_NAME` ';
              $query .= 'FROM INFORMATION_SCHEMA.COLUMNS clms ';
              $query .= 'LEFT JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu ';
              $query .= 'USING (COLUMN_NAME, TABLE_NAME, TABLE_SCHEMA) ';
              $query .= 'LEFT JOIN INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS rc ';
              $query .= 'USING (CONSTRAINT_CATALOG, CONSTRAINT_SCHEMA, CONSTRAINT_NAME) ';
              $query .= 'WHERE clms.TABLE_SCHEMA = DATABASE() ';
              $query .= "AND clms.TABLE_NAME = '".$db_table_name."'";

              $table_constraints = DB::connect()->query($query)->fetchAll();

              foreach ($table_constraints as $table_constraint) {
                  if (!empty($table_constraint['CONSTRAINT_NAME']) && !empty($table_constraint['REFERENCED_COLUMN_NAME'])) {
                      $new_constraint_name = str_replace('new_version_', '', $table_constraint['CONSTRAINT_NAME']);
                      $new_db_table_name = str_replace('new_grupo_version_', 'gr_', $db_table_name);

                      $reference_table_name = $table_constraint['REFERENCED_TABLE_NAME'];
                      $reference_table_name = str_replace('new_grupo_version_', 'gr_', $reference_table_name);

                      $find_table = "SHOW TABLES LIKE '".$reference_table_name."'";
                      $find_table =  DB::connect()->query($find_table)->fetchAll();

                      if (count($find_table) === 0) {
                          $reference_table_name = $table_constraint['REFERENCED_TABLE_NAME'];
                      }

                      $rename_table .= 'ALTER TABLE `'.$new_db_table_name.'` DROP FOREIGN KEY `'.$table_constraint['CONSTRAINT_NAME'].'`;';

                      $foregin_key_column = $table_constraint['COLUMN_NAME'];

                      $rename_table .= 'ALTER TABLE `'.$new_db_table_name.'` ADD CONSTRAINT `'.$new_constraint_name.'` ';
                      $rename_table .= 'FOREIGN KEY (`'.$foregin_key_column.'`) REFERENCES `'.$reference_table_name.'`(`'.$table_constraint['REFERENCED_COLUMN_NAME'].'`) ';

                      if (isset($table_constraint['DELETE_RULE']) && !empty($table_constraint['DELETE_RULE'])) {
                          $rename_table .= 'ON DELETE '.$table_constraint['DELETE_RULE'];
                      } else {
                          $rename_table .= 'ON DELETE RESTRICT';
                      }

                      if (isset($table_constraint['UPDATE_RULE']) && !empty($table_constraint['UPDATE_RULE'])) {
                          $rename_table .= ' ON UPDATE '.$table_constraint['UPDATE_RULE'];
                      } else {
                          $rename_table .= ' ON UPDATE RESTRICT';
                      }
                      $rename_table .= ';';
                  }
              }

              if ($log_query) {
                  add_to_error_log($rename_table);
              }

              $rename_table =  DB::connect()->query($rename_table);
          } else {
              $query= 'SELECT clms.`COLUMN_NAME`,clms.`CHARACTER_MAXIMUM_LENGTH`,clms.`COLUMN_TYPE`,clms.`COLUMN_COMMENT`,';
              $query .= 'clms.`COLUMN_KEY`,clms.`COLUMN_DEFAULT`,clms.`IS_NULLABLE`,clms.`DATA_TYPE`';
              $query .= ',rc.`CONSTRAINT_NAME`,rc.`UPDATE_RULE`, kcu.`REFERENCED_COLUMN_NAME`, ';
              $query .= 'rc.`DELETE_RULE`,rc.`REFERENCED_TABLE_NAME` ';
              $query .= 'FROM INFORMATION_SCHEMA.COLUMNS clms ';
              $query .= 'LEFT JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu ';
              $query .= 'USING (COLUMN_NAME, TABLE_NAME, TABLE_SCHEMA) ';
              $query .= 'LEFT JOIN INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS rc ';
              $query .= 'USING (CONSTRAINT_CATALOG, CONSTRAINT_SCHEMA, CONSTRAINT_NAME) ';
              $query .= 'WHERE clms.TABLE_SCHEMA = DATABASE() ';
              $query .= "AND clms.TABLE_NAME = '".$db_table_name."'";

              $new_db_table = DB::connect()->query($query)->fetchAll();

              $query= 'SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME`='."'".$check_table."'";
              $query.= " AND `TABLE_SCHEMA`='".$GLOBALS["database_info"]["database"]."'";

              $old_db_table = DB::connect()->query($query)->fetchAll();


              $old_db_table_columns = array();

              foreach ($old_db_table as $column) {
                  $column = $column['COLUMN_NAME'];
                  $old_db_table_columns[$column]=true;
              }

              foreach ($new_db_table as $column) {
                  $check_column = $column['COLUMN_NAME'];
                  $sql_query = '';
                  if (!isset($old_db_table_columns[$check_column])) {
                      $data_type = strtoupper($column['COLUMN_TYPE']);
                      $null_value = ' NOT NULL';
                      $default_value = '';

                      if (strtolower($column['IS_NULLABLE']) !== 'no') {
                          $null_value = ' NULL';
                      }

                      $skip_default_value = false;
                      if (strpos(strtoupper($data_type), 'INT') !== false || strpos(strtoupper($data_type), 'DECIMAL') !== false) {
                          if ($column['COLUMN_DEFAULT'] === 'NULL') {
                              $skip_default_value = true;
                          }
                      }

                      if (!$skip_default_value) {
                          if (!empty($column['COLUMN_DEFAULT']) || is_numeric($column['COLUMN_DEFAULT'])) {
                              if ($column['COLUMN_DEFAULT'] === 'NULL') {
                                  $default_value = " DEFAULT NULL";
                              } else {
                                  $column_default = str_replace("'","",$column['COLUMN_DEFAULT']);
                                  $column_default = str_replace('"','',$column_default);
                                  $default_value = " DEFAULT '".$column_default."'";
                              }
                          }
                      }

                      $sql_query = 'ALTER TABLE `'.$check_table.'` ADD `'.$check_column.'` '.$data_type.$null_value.$default_value.';';

                      if (!empty($column['CONSTRAINT_NAME']) && !empty($column['REFERENCED_COLUMN_NAME'])) {
                          $sql_query .= 'ALTER TABLE `'.$db_table_name.'` DROP FOREIGN KEY `'.$column['CONSTRAINT_NAME'].'`;';

                          $reference_tb = str_replace('new_grupo_version_', 'gr_', $column['REFERENCED_TABLE_NAME']);
                          $new_constraint_name = str_replace('new_version_', '', $column['CONSTRAINT_NAME']);

                          $find_table = "SHOW TABLES LIKE '".$reference_tb."'";
                          $find_table =  DB::connect()->query($find_table)->fetchAll();

                          if (count($find_table) === 0) {
                              $reference_tb = $column['REFERENCED_TABLE_NAME'];
                          }

                          $sql_query .= 'ALTER TABLE `'.$check_table.'` ADD CONSTRAINT `'.$new_constraint_name.'` ';
                          $sql_query .= 'FOREIGN KEY (`'.$check_column.'`) ';
                          $sql_query .= 'REFERENCES `'.$reference_tb.'`(`'.$column['REFERENCED_COLUMN_NAME'].'`) ';

                          if (isset($column['DELETE_RULE']) && !empty($column['DELETE_RULE'])) {
                              $sql_query .= 'ON DELETE '.$column['DELETE_RULE'];
                          } else {
                              $sql_query .= 'ON DELETE RESTRICT';
                          }

                          if (isset($column['UPDATE_RULE']) && !empty($column['UPDATE_RULE'])) {
                              $sql_query .= ' ON UPDATE '.$column['UPDATE_RULE'];
                          } else {
                              $sql_query .= ' ON UPDATE RESTRICT';
                          }
                          $sql_query .= ';';
                      }

                      if (!empty($sql_query)) {
                          try {
                              DB::connect()->query($sql_query);

                              if ($log_query) {
                                  add_to_error_log($sql_query);
                              }
                          } catch (PDOException $e) {
                              $error_message = $e->getMessage();
                              add_to_error_log($error_message);
                          }
                      }
                  }
              }

              $new_tb_indexes='SHOW INDEX FROM '.$db_table_name.' FROM '.$GLOBALS["database_info"]["database"].';';
              $new_tb_indexes = DB::connect()->query($new_tb_indexes)->fetchAll();

              $old_tb_indexes='SHOW INDEX FROM '.$check_table.' FROM '.$GLOBALS["database_info"]["database"].';';
              $old_tb_indexes = DB::connect()->query($old_tb_indexes)->fetchAll();

              $old_db_indexes=array();

              foreach ($old_tb_indexes as $db_index) {
                  $old_db_indexes[]= $db_index['Key_name'];
              }
              $add_indexes=array();

              foreach ($new_tb_indexes as $db_index) {
                  $key_name=$db_index['Key_name'];
                  $column_name=$db_index['Column_name'];
                  $key_name = str_replace('new_grupo_version_', '', $key_name);
                  if (!in_array($key_name, $old_db_indexes)) {
                      if (!empty($key_name) && !empty($column_name)) {
                          $add_indexes[$key_name][] = $db_index['Column_name'];
                      }
                  }
              }

              foreach ($add_indexes as $inx_key => $inx_columns) {
                  $inx_columns = "`" . implode("`, `", $inx_columns) . "`";
                  $add_index_sql .= 'Alter table '.$check_table.' add index `'.$inx_key.'`('.$inx_columns.');';
              }
          }
          $skip_tables = ['new_grupo_version_language_strings','new_grupo_version_settings'];
          if (!in_array($db_table_name, $skip_tables)) {
              $query = "DROP TABLE ".$db_table_name;
              try {
                  DB::connect()->query($query);

                  if ($log_query) {
                      add_to_error_log($sql_query);
                  }
              } catch (PDOException $e) {
                  $error_message = $e->getMessage();
                  add_to_error_log($error_message);
                  $repeat_task = true;
              }
          }
      }
  }

if (!empty($add_index_sql)) {
    try {
        DB::connect()->query($add_index_sql);

        if ($log_query) {
            add_to_error_log($sql_query);
        }
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        add_to_error_log($error_message);
    }
}

if ($repeat_task) {
    $system_message = 'Analysing Database Tables.';
    $redirect = 'update_database';
    $sub_process = 'compare_db_tables';
} else {
    $system_message = 'Importing Language Strings';
    $redirect = 'update_database';
    $sub_process = 'language_strings';
}
