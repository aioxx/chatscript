
<?php

$sql_query=  DB::connect()->query("SHOW TABLES LIKE 'old_version_gr_phrases'")->fetchAll();

if (count($sql_query) > 0) {
    $columns = ['string_constant','string_value','string_type','skip_update','skip_cache'];

    $languages = DB::connect()->select("gr_languages", ['language_id']);

    foreach ($languages as $language) {
        $columns=$join=null;

        $columns = [
          'string.string_constant','string.string_value','string.string_type',
        'string.skip_update','string.skip_cache','new_entry.string_id(new_entry)','old_entry.full(old_entry)'
      ];
        $join["[>]gr_language_strings(new_entry)"] = ["string.string_constant" => "string_constant", "AND" => ["new_entry.language_id" => $language['language_id']]];
        $join["[>]old_version_gr_phrases(old_entry)"] = ["string.string_constant" => "short", "AND" => ["old_entry.lid" => $language['language_id']]];
        $strings = DB::connect()->select("gr_language_strings(string)", $join, $columns, ['string.language_id' => 1]);

        foreach ($strings as $string) {
            $constant = $string['string_constant'];
            $exists = false;

            if (isset($string['new_entry']) && !empty($string['new_entry'])) {
                $exists = true;
            }
            if (isset($string['old_entry']) && !empty($string['old_entry'])) {
                $string['string_value'] = $string['old_entry'];
            }

            if (!$exists) {
                $insert_data = array();
                $insert_data['language_id'] = $language['language_id'];
                $insert_data['string_constant'] = $string['string_constant'];
                $insert_data['string_value'] = $string['string_value'];
                $insert_data['string_type'] = $string['string_type'];
                $insert_data['skip_update'] = $string['skip_update'];
                $insert_data['skip_cache'] = $string['skip_cache'];
                DB::connect()->insert("gr_language_strings", $insert_data);
            } else {
                if ((int)$language['language_id'] === 1) {
                    $update_data=array();
                    $update_data['string_value'] = $string['string_value'];
                    $string_id = $string['new_entry'];
                    DB::connect()->update("gr_language_strings", $update_data, ['string_id' => $string_id,'language_id' => $language['language_id']]);
                }
            }
        }

        DB::connect()->delete("old_version_gr_phrases", ['lid' => $language['language_id']]);
    }
    DB::connect()->query("DROP TABLE old_version_gr_phrases");
}
$system_message = 'Importing Settings';
$redirect = 'update_database';
$sub_process = 'settings';
