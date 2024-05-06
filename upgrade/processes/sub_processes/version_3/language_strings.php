<?php

DB::connect()->update("gr_language_strings", ['string_constant' => 'api_secret_key'], ['string_constant' => 'api_secretkey']);

$columns = $join = null;
$columns = [
'string.string_constant','string.string_value','string.string_type',
'string.skip_update','string.skip_cache','old_entry.string_id(old_entry)'
];
$join["[>]gr_language_strings(old_entry)"] = ["string.string_constant" => "string_constant", "AND" => ["old_entry.language_id" => 1]];
$where = ['string.language_id' => 1];
$where['OR'] = ['old_entry.string_id(#first)' => null, 'old_entry.string_id(#second)' => '', 'old_entry.string_id(#third)' => 0];
$strings = DB::connect()->select("new_grupo_version_language_strings(string)", $join, $columns, $where);

foreach ($strings as $string) {

    if (empty($string['old_entry'])) {
        $insert_data = array();
        $insert_data['language_id'] = 1;
        $insert_data['string_constant'] = $string['string_constant'];
        $insert_data['string_value'] = $string['string_value'];
        $insert_data['string_type'] = $string['string_type'];
        $insert_data['skip_update'] = $string['skip_update'];
        $insert_data['skip_cache'] = $string['skip_cache'];
        DB::connect()->insert("gr_language_strings", $insert_data);
    }

}
$system_message = 'Importing Settings';
$redirect = 'update_database';
$sub_process = 'settings';
