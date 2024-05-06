
<?php

$sql_query=  DB::connect()->query("SHOW TABLES LIKE 'old_version_gr_options'")->fetchAll();

if (count($sql_query) > 0) {
    $columns=['members.id','members.v1','members.v2','members.v3','lastread.v3(lastread)','members.tms','gr_group_members.group_member_id'];
    $join["[>]old_version_gr_options(lastread)"] = ["members.v1" => "v1", "members.v2" => "v2", "AND" => ["lastread.type" => "lview"]];
    $join["[>]gr_group_members"] = ["members.v1" => "group_id", "members.v2" => "user_id"];
    $members = DB::connect()->select("old_version_gr_options(members)", $join, $columns, ['members.type'=>'gruser', 'LIMIT' => 250]);

    foreach ($members as $member) {
        if (!empty($member['v1']) && !empty($member['v2'])) {
            $group_role_id=4;
            $last_read_message_id=0;

            if (!empty($member['lastread'])) {
                $last_read_message_id=(int)$member['lastread'];
            }

            if ((int)$member['v3'] ==2) {
                $group_role_id = 2;
            } elseif ((int)$member['v3'] ==1) {
                $group_role_id = 3;
            } elseif ((int)$member['v3'] ==3) {
                $group_role_id = 1;
            }

            if (empty($member['tms'])) {
                $member['tms'] = date('Y-m-d H:i:s');
            }

            $insert_data=array();
            $insert_data['group_id'] = $member['v1'];
            $insert_data['user_id'] = $member['v2'];
            $insert_data['group_role_id'] = $group_role_id;
            $insert_data['previous_group_role_id'] = $group_role_id;
            $insert_data['last_read_message_id'] = $last_read_message_id;
            $insert_data['joined_on'] = $member['tms'];
            $insert_data['updated_on'] = $member['tms'];

            if (!isset($member['group_member_id']) || empty($member['group_member_id'])) {
                DB::connect()->insert("gr_group_members", $insert_data);
            }

            DB::connect()->delete("old_version_gr_options", ['id' => $member['id']]);
        }
    }
}

if (isset($members) && count($members) > 150) {
    $system_message = 'Importing Group Members';
    $redirect = 'update_database';
    $sub_process = 'group_members';
} else {
    $system_message = 'Importing Group Messages';
    $redirect = 'update_database';
    $sub_process = 'group_messages';
}
