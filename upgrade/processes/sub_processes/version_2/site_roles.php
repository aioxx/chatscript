
<?php

$sql_query=  DB::connect()->query("SHOW TABLES LIKE 'old_version_gr_permissions'")->fetchAll();

if (count($sql_query) > 0) {
    $columns=['id','name'];
    $where=['id[>]' => 5];
    $site_roles = DB::connect()->select("old_version_gr_permissions", $columns, $where);

    foreach ($site_roles as $site_role) {
        $insert_data=array();
        $insert_data['site_role_id'] = $site_role['id'];
        $insert_data['string_constant'] = $site_role['name'];
        $insert_data['permissions'] = '{}';
        $insert_data['site_role_attribute'] = 'custom_site_role';
        $insert_data['updated_on'] = date('Y-m-d H:i:s');

        DB::connect()->insert("gr_site_roles", $insert_data);

        $role_id = DB::connect()->id();
        $role_string = 'site_role_'.$role_id;

        $insert_data=array();
        $insert_data['string_constant'] = $role_string;
        $insert_data['string_value'] = $site_role['name'];
        $insert_data['language_id'] = 1;

        DB::connect()->insert("gr_language_strings", $insert_data);
        DB::connect()->update("gr_site_roles",["string_constant"=>$role_string], ['site_role_id' => $role_id]);
        DB::connect()->delete("old_version_gr_permissions", ['id' => $site_role['id']]);
    }

    DB::connect()->query("DROP TABLE old_version_gr_permissions");
}

$system_message = 'Importing Users';
$redirect = 'update_database';
$sub_process = 'users';
