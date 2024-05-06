
<?php

$sql_query=  DB::connect()->query("SHOW TABLES LIKE 'old_version_gr_phrases'")->fetchAll();

if (count($sql_query) > 0) {
    $columns=[
      'values.id','values.type','values.short','values.full','values.lid',
  ];
    $where = [
      'values.type' => 'lang'
    ];

    $values = DB::connect()->select("old_version_gr_phrases(values)", $columns, $where);

    foreach ($values as $value) {
        if (isset($value['short'])) {
            if ($value['full']!=='ltr') {
                $text_direction='rtl';
            } else {
                $text_direction='ltr';
            }

            if ((int)$value['id'] !== 1) {
                $insert_data=array();
                $insert_data['language_id'] = $value['id'];
                $insert_data['name'] = $value['short'];
                $insert_data['iso_code'] = 'en';
                $insert_data['text_direction'] = $text_direction;
                $insert_data['updated_on'] = date('Y-m-d H:i:s');

                DB::connect()->insert("gr_languages", $insert_data);
            } else if ($value['short'] !== "English"){
              $update_data=array();
              $update_data['name'] = $value['short'];
              $update_data['text_direction'] = $text_direction;

              DB::connect()->update("gr_languages", $update_data,['language_id' => 1]);
            }
        }
          DB::connect()->delete("old_version_gr_phrases", ['id' => $value['id']]);
    }
}

$system_message = 'Importing Strings';
$redirect = 'update_database';
$sub_process = 'language_strings';
