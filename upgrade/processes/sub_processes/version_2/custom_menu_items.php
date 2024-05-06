
<?php

$sql_query=  DB::connect()->query("SHOW TABLES LIKE 'old_version_gr_options'")->fetchAll();

if (count($sql_query) > 0) {
    $columns=[
      'values.id','values.type','values.v1','values.v2','values.v3','values.v4',
      'values.v5','values.v6','values.v7','values.tms','string.full',
  ];
    $join["[>]old_version_gr_phrases(string)"] = ["values.v1" => "short", "AND" => ["string.type" => "phrase","string.lid"=>1]];
    $where = [
      'values.type' => 'menuitem'
    ];
    $values = DB::connect()->select("old_version_gr_options(values)", $join, $columns, $where);


    foreach ($values as $value) {
        if (!empty($value['v1']) && !empty($value['v2'])) {
            $string_constant = 'custom_menu_item_'.$value['id'];

            $insert_data=array();

            if (empty($value['tms'])) {
                $value['tms'] = date('Y-m-d H:i:s');
            }

            $insert_data['menu_item_id'] = $value['id'];
            $insert_data['string_constant'] = $string_constant;
            $insert_data['menu_icon_class'] = 'bi-card-text';
            $insert_data['web_address'] = $value['v2'];
            $insert_data['link_target'] = 1;
            $insert_data['show_on_chat_page'] = 1;

            $insert_data['created_on'] = $value['tms'];
            $insert_data['updated_on'] = $value['tms'];


            $string = array();
            $string['string_constant'] = $string_constant;
            $string['string_value'] = $value['full'];
            $string['skip_update'] = 1;
            $string['language_id'] = 1;

            DB::connect()->insert("gr_custom_menu_items", $insert_data);
            DB::connect()->insert("gr_language_strings", $string);
        }

        DB::connect()->delete("old_version_gr_options", ['id' => $value['id']]);
    }
}

$system_message = 'Importing Social Login Providers';
$redirect = 'update_database';
$sub_process = 'social_login_providers';
