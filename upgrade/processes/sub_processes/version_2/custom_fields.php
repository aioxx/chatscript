
<?php

$sql_query=  DB::connect()->query("SHOW TABLES LIKE 'old_version_gr_profiles'")->fetchAll();

if (count($sql_query) > 0) {
    $columns=['fields.id','string.full','fields.name','fields.cat','fields.v1','fields.req','fields.tms','fields.type'];
    $join["[>]old_version_gr_phrases(string)"] = ["fields.name" => "short", "AND" => ["string.type" => "phrase","string.lid"=>1]];
    $fields = DB::connect()->select("old_version_gr_profiles(fields)", $join, $columns, ['fields.type'=>['field','gfield'],'fields.id[>]' => 6]);

    foreach ($fields as $field) {
        if (!empty($field['full']) && !empty($field['cat'])) {
            $string_constant = 'custom_field_'.$field['id'];
            $string_constant_options = 'custom_field_'.$field['id'].'_options';
            $field_category = 'profile';
            $field_type = 'short_text';
            $show_on_signup = 0;
            $required = 0;

            if ($field['type'] === 'gfield') {
                $field_category = 'group';
            }


            if ((int)$field['req'] === 3) {
                $required = 1;
                $show_on_signup = 1;
            } elseif ((int)$field['req'] === 2) {
                $show_on_signup = 1;
            } elseif ((int)$field['req'] === 1) {
                $required = 1;
            }

            if ($field['cat'] === 'longtext') {
                $field_type = 'long_text';
            } elseif ($field['cat'] === 'datefield') {
                $field_type = 'date';
            } elseif ($field['cat'] === 'dropdownfield') {
                $field_type = 'dropdown';
            } elseif ($field['cat'] === 'numfield') {
                $field_type = 'number';
            }

            if (empty($field['tms'])) {
                $field['tms'] = date('Y-m-d H:i:s');
            }

            $insert_data=array();
            $insert_data['field_id'] = $field['id'];
            $insert_data['string_constant'] = $string_constant;
            $insert_data['field_category'] = $field_category;
            $insert_data['field_type'] = $field_type;
            $insert_data['show_on_signup'] = $show_on_signup;
            $insert_data['required'] = $required;
            $insert_data['updated_on'] = $field['tms'];

            $string = array();
            $string['string_constant'] = $string_constant;
            $string['string_value'] = $field['full'];
            $string['skip_update'] = 1;
            $string['language_id'] = 1;

            DB::connect()->insert("gr_custom_fields", $insert_data);
            DB::connect()->insert("gr_language_strings", $string);

            if ($field_type === 'dropdown') {
                $options=explode(',', $field['v1']);
                $options = array_combine(range(1, count($options)), $options);

                $string = array();
                $string['string_constant'] = $string_constant_options;
                $string['string_value[JSON]'] = $options;
                $string['skip_update'] = 1;
                $string['language_id'] = 1;

                DB::connect()->insert("gr_language_strings", $string);
            }
        }

        DB::connect()->delete("old_version_gr_profiles", ['id' => $field['id']]);
    }
}

$system_message = 'Importing Custom Field Values';
$redirect = 'update_database';
$sub_process = 'custom_field_values';
