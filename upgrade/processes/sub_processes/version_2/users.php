
<?php

$sql_query=  DB::connect()->query("SHOW TABLES LIKE 'old_version_gr_users'")->fetchAll();

if (count($sql_query) > 0) {
    $columns=['id','name','email','pass','mask','depict','role','created','altered'];
    $site_users = DB::connect()->select("old_version_gr_users", $columns, ['LIMIT' => 250]);

    DB::connect()->delete("gr_site_users", ['user_id' => 1, 'email_address' => 'email@yourdomain.com']);

    foreach ($site_users as $site_user) {
        $display_name = DB::connect()->select(
            "old_version_gr_options",
            ['v2'],
            ['type'=>'profile','v1'=>'name','v3'=>$site_user['id']]
        );

        if (isset($display_name[0]['v2'])) {
            $display_name=$display_name[0]['v2'];
        } else {
            $display_name=$site_user['name'];
        }

        $insert_data=array();
        $insert_data['user_id']=$site_user['id'];
        $insert_data['display_name']=$display_name;
        $insert_data['username']=$site_user['name'];
        $insert_data['email_address']=$site_user['email'];
        $insert_data['password']=$site_user['pass'];
        $insert_data['encrypt_type']=$site_user['depict'];
        $insert_data['salt']=$site_user['mask'];
        $insert_data['site_role_id']=$site_user['role'];
        $insert_data['previous_site_role_id']=$site_user['role'];

        $insert_data['verification_code']=rand(10320, 22320);
        $insert_data['access_token']=rand(10320, 22320);

        if (empty($site_user['altered'])) {
            $site_user['altered'] = date('Y-m-d H:i:s');
        }

        if (empty($site_user['created'])) {
            $site_user['created'] = date('Y-m-d H:i:s');
        }

        $insert_data['created_on']=$site_user['created'];
        $insert_data['updated_on']=$site_user['altered'];

        DB::connect()->insert("gr_site_users", $insert_data);
        DB::connect()->delete("old_version_gr_users", ['id' => $site_user['id']]);
    }

    if (count($site_users) < 150) {
        DB::connect()->query("DROP TABLE old_version_gr_users");
    }
}

if (isset($site_users) && count($site_users) > 150) {
    $system_message = 'Importing Users';
    $redirect = 'update_database';
    $sub_process = 'users';
} else {
    $system_message = 'Importing Adverts';
    $redirect = 'update_database';
    $sub_process = 'adverts';
}
