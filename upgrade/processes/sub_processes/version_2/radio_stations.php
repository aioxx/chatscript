
<?php

$sql_query=  DB::connect()->query("SHOW TABLES LIKE 'old_version_gr_options'")->fetchAll();

if (count($sql_query) > 0) {
    $columns=[
      'values.id','values.type','values.v1','values.v2','values.v3','values.v4',
      'values.v5','values.v6','values.v7','values.tms',
  ];
    $where = [
      'values.type' => 'radiostation'
    ];
    $values = DB::connect()->select("old_version_gr_options(values)", $columns, $where);


    foreach ($values as $value) {
        if (!empty($value['v1']) && !empty($value['v3'])) {

            if (empty($value['tms'])) {
                $value['tms'] = date('Y-m-d H:i:s');
            }

            $insert_data=array();
            $insert_data['audio_content_id'] = $value['id'];
            $insert_data['audio_type'] = 1;
            $insert_data['audio_title'] = $value['v1'];
            $insert_data['audio_description'] = $value['v2'];
            $insert_data['radio_stream_url'] = $value['v3'];
            $insert_data['updated_on'] = $value['tms'];

            DB::connect()->insert("gr_audio_player", $insert_data);
        }

        DB::connect()->delete("old_version_gr_options", ['id' => $value['id']]);
    }
}

$system_message = 'Importing Custom Menu Items';
$redirect = 'update_database';
$sub_process = 'custom_menu_items';
