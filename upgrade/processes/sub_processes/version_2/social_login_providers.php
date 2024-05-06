
<?php

$sql_query=  DB::connect()->query("SHOW TABLES LIKE 'old_version_gr_options'")->fetchAll();

if (count($sql_query) > 0) {
    $columns=[
      'values.id','values.type','values.v1','values.v2','values.v3','values.v4',
      'values.v5','values.v6','values.v7','values.tms',
  ];
    $where = [
      'values.type' => 'loginprovider'
    ];
    $values = DB::connect()->select("old_version_gr_options(values)", $columns, $where);


    foreach ($values as $value) {
        if (isset($value['v1']) && !empty($value['v3'])) {

            if (empty($value['tms'])) {
                $value['tms'] = date('Y-m-d H:i:s');
            }

            $insert_data=array();
            $insert_data['social_login_provider_id'] = $value['id'];
            $insert_data['identity_provider'] = stripslashes($value['v1']);
            $insert_data['app_id'] = $value['v2'];
            $insert_data['app_key'] = $value['v4'];
            $insert_data['secret_key'] = $value['v3'];
            $insert_data['create_user'] = 1;
            $insert_data['updated_on'] = $value['tms'];

            DB::connect()->insert("gr_social_login_providers", $insert_data);
        }

        DB::connect()->delete("old_version_gr_options", ['id' => $value['id']]);
    }
}


$system_message = 'Importing Languages';
$redirect = 'update_database';
$sub_process = 'languages';
