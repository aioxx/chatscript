
<?php

$sql_query = DB::connect()->query("SHOW TABLES LIKE 'old_version_gr_options'")->fetchAll();
$descriptions = DB::connect()->query("SHOW TABLES LIKE 'old_version_gr_profiles'")->fetchAll();

if (count($sql_query) > 0 && count($descriptions) > 0) {
    $columns=[
    'groups.id','groups.v1','groups.v2','groups.v3',
    'groups.v4','groups.v5','groups.v6','slug.tms','slug.v2(slug)','descrp.v1(description)'
  ];
    $join["[>]old_version_gr_options(slug)"] = ["groups.id" => "v1", "AND" => ["slug.type" => "groupslug"]];
    $join["[>]old_version_gr_profiles(descrp)"] = ["groups.id" => "uid", "AND" => ["descrp.name" => "description","descrp.type" => "group"]];
    $groups = DB::connect()->select("old_version_gr_options(groups)", $join, $columns, ['groups.type'=>'group']);

    foreach ($groups as $group) {
        if ($group['v3']==='secret') {
            $group['v3']=1;
        } else {
            $group['v3']=0;
        }

        if ($group['v6']==='unleavable') {
            $group['v6']=1;
        } else {
            $group['v6']=0;
        }

        $who_all_can_send_messages='all';

        if ($group['v5']==='adminonly') {
            //$who_all_can_send_messages='';
        }

        $total_members = DB::connect()->count("old_version_gr_options", ['type'=> 'gruser','v1'=> $group['id']]);

        if (empty($group['tms'])) {
            $group['tms'] = date('Y-m-d H:i:s');
        }

        $group['v1'] = str_replace('&amp;', '&', $group['v1']);
        $group['description'] = str_replace('&amp;', '&', $group['description']);

        $insert_data=array();
        $insert_data['group_id'] = $group['id'];
        $insert_data['name'] = $group['v1'];
        $insert_data['description'] = $group['description'];
        $insert_data['slug'] = $group['slug'];
        $insert_data['secret_group'] = $group['v3'];
        $insert_data['secret_code'] = rand(10320, 22320);
        $insert_data['unleavable'] = $group['v6'];
        $insert_data['who_all_can_send_messages'] = $who_all_can_send_messages;
        $insert_data['total_members'] = $total_members;
        $insert_data['created_on'] = $group['tms'];
        $insert_data['updated_on'] = $group['tms'];

        DB::connect()->insert("gr_groups", $insert_data);
        DB::connect()->delete("old_version_gr_options", ['id' => $group['id']]);
    }
}

$system_message = 'Importing Group Members';
$redirect = 'update_database';
$sub_process = 'group_members';
