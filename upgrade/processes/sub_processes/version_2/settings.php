
<?php

$sql_query=  DB::connect()->query("SHOW TABLES LIKE 'old_version_gr_defaults'")->fetchAll();

if (count($sql_query) > 0) {
    $columns=[
      'values.id','values.v1','values.v2','values.v3','values.v4',
      'values.v5','values.v6','values.v7','values.tms',
  ];
    $values = DB::connect()->select("old_version_gr_defaults(values)", $columns);

    $settings = [
      'sitename' => ['site_name','meta_title'],
      'sitedesc' => 'site_description',
      'siteslogan' => 'site_slogan',
      'sysemail' => 'system_email_address',
      'sendername' => 'sender_name',
      'userreg' => 'user_registration',
      'rsitekey' => 'captcha_site_key',
      'rsecretkey' => 'captcha_secret_key',
      'grconnect_secretkey' => 'api_secret_key',
      'language' => 'default_language',
      'guest_login' => 'guest_login',
      'email_verification' => 'user_email_verification',
      'smtp_authentication' => 'smtp_authentication',
      'smtp_host' => 'smtp_host',
      'smtp_user' => 'smtp_username',
      'smtp_pass' => 'smtp_password',
      'smtp_protocol' => 'smtp_protocol',
      'smtp_port' => 'smtp_port',
      'tenor_limit' => 'gif_search_engine',
      'tenor_api' => 'gif_search_engine_api',
    ];

    foreach ($values as $value) {
        if (!empty($value['v1']) && !empty($value['v2'])) {
            $setting = $value['v1'];

            if (isset($settings[$setting])) {
                $update_settings = $settings[$setting];

                if (!is_array($update_settings)) {
                    $update_settings = array();
                    $update_settings[] = $settings[$setting];
                }

                foreach ($update_settings as $update_setting) {

                  if($update_setting === 'smtp_protocol'){
                    $value['v2'] = strtolower($value['v2']);
                  }

                  if($update_setting === 'gif_search_engine'){
                    $value['v2'] = 'tenor';
                  }
                    $update_data = array();
                    $update_data['value'] = $value['v2'];
                    $where = ['setting'  => $update_setting];

                    DB::connect()->update("gr_settings", $update_data, $where);
                }
            }
        }

        DB::connect()->delete("old_version_gr_defaults", ['id' => $value['id']]);
    }

    DB::connect()->query("DROP TABLE old_version_gr_defaults");
}

$system_message = 'Dropping old tables';
$redirect = 'update_database';
$sub_process = 'drop_tables';
