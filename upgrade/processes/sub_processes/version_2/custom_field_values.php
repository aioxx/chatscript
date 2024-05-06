
<?php

$sql_query=  DB::connect()->query("SHOW TABLES LIKE 'old_version_gr_profiles'")->fetchAll();

if (count($sql_query) > 0) {
    $columns=[
      'values.id','fields.field_id','values.v1','fields.updated_on','values.uid',
      'fields.field_category', 'fields.field_type',
  ];
    $join["[>]gr_custom_fields(fields)"] = ["values.name" => "field_id"];
    $where = [
      'values.type' => ['profile','group'],
      'LIMIT' => 250
    ];
    $values = DB::connect()->select("old_version_gr_profiles(values)", $join, $columns, $where);


    foreach ($values as $value) {
        if (isset($value['field_id']) && !empty($value['field_id'])) {
            $string_constant_options = 'custom_field_'.$value['field_id'].'_options';

            $group_id = $user_id = null;
            $field_value = $value['v1'];

            if ($value['field_type'] === 'dropdown') {
                $field_value = '';
                $options = DB::connect()->select("gr_language_strings", ["string_value"], ["string_constant" => $string_constant_options, "language_id" => 1]);
                if (isset($options[0])) {
                    $options = $options[0]['string_value'];
                    $options = json_decode($options);
                    foreach ($options as $index => $option) {
                        if ($option == $value['v1']) {
                            $field_value = $index;
                        }
                    }
                }
            } else {
                $field_value = str_replace('&amp;', '&', $field_value);
            }

            if ($value['field_category'] === 'group') {
                $group_id = $value['uid'];
            } else {
                $user_id = $value['uid'];
            }

            if (empty($value['updated_on'])) {
                $value['updated_on'] = date('Y-m-d H:i:s');
            }

            $insert_data=array();
            $insert_data['field_id'] = $value['field_id'];
            $insert_data['group_id'] = $group_id;
            $insert_data['user_id'] = $user_id;
            $insert_data['field_value'] = $field_value;
            $insert_data['updated_on'] = $value['updated_on'];

            $where = [
              'field_id' => $value['field_id'],
            ];

            if ($value['field_category'] === 'group') {
                $where['group_id'] = $value['uid'];
            } else {
                $where['user_id'] = $value['uid'];
            }

            $validate = DB::connect()->select("gr_custom_fields_values", ["field_value_id"], $where);

            if (!isset($validate[0])) {
                DB::connect()->insert("gr_custom_fields_values", $insert_data);
            }
        }

        DB::connect()->delete("old_version_gr_profiles", ['id' => $value['id']]);
    }

    if (count($values) < 150) {
        DB::connect()->query("DROP TABLE old_version_gr_profiles");
    }
}
if (isset($values) && count($values) > 150) {
    $system_message = 'Importing Custom Field Values';
    $redirect = 'update_database';
    $sub_process = 'custom_field_values';
} else {
    $system_message = 'Importing Radio Stations';
    $redirect = 'update_database';
    $sub_process = 'radio_stations';
}
