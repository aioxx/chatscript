
<?php

$sql_query=  DB::connect()->query("SHOW TABLES LIKE 'old_version_gr_ads'")->fetchAll();

if (count($sql_query) > 0) {
    $columns=['id','name','content','adheight','tms'];
    $adverts = DB::connect()->select("old_version_gr_ads", $columns);

    foreach ($adverts as $advert) {

        if (empty($advert['tms'])) {
            $advert['tms'] = date('Y-m-d H:i:s');
        }

        $insert_data=array();
        $insert_data['site_advert_name'] = $advert['name'];
        $insert_data['site_advert_content'] = $advert['content'];
        $insert_data['site_advert_max_height'] = $advert['adheight'];
        $insert_data['updated_on'] = $advert['tms'];
        $insert_data['site_advert_placement'] = 'left_content_block';
        $insert_data['disabled'] = 1;

        DB::connect()->insert("gr_site_advertisements", $insert_data);
        DB::connect()->delete("old_version_gr_ads", ['id' => $advert['id']]);
    }

    DB::connect()->query("DROP TABLE old_version_gr_ads");
}

$system_message = 'Importing Groups';
$redirect = 'update_database';
$sub_process = 'groups';
