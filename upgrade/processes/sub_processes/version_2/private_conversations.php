
<?php

$sql_query=  DB::connect()->query("SHOW TABLES LIKE 'old_version_gr_msgs'")->fetchAll();

if (count($sql_query) > 0) {
    $last_message_id = 0;

    if (isset($upgrade_info['last_private_conversation_id']) && !empty($upgrade_info['last_private_conversation_id'])) {
        $last_message_id = $upgrade_info['last_private_conversation_id'];
    }

    $columns = [
       'msgs.id','msgs.gid','msgs.uid','msgs.tms'
    ];
    $where = ['msgs.cat' => 'user','msgs.type[!]' => 'system','msgs.type[!]' => 'stickers'];

    if (!empty($last_message_id)) {
        $where['msgs.id[>]'] = $last_message_id;
    }

    $where['GROUP'] = 'msgs.gid';
    $where['LIMIT'] = 300;

    $messages = DB::connect()->select("old_version_gr_msgs(msgs)", $columns, $where);
    foreach ($messages as $message) {
        $last_message_id = $message['id'];
        $gid = explode('-', $message['gid']);

        if (isset($gid[1])) {
            $initiator_user_id = $message['uid'];

            if ((int)$gid[1] !== (int)$initiator_user_id) {
                $recipient_user_id = $gid[1];
            } else {
                $recipient_user_id = $gid[0];
            }

            $where=array();
            $where["OR"]["AND #first_query"] = ['recipient_user_id' => $recipient_user_id,'initiator_user_id' => $initiator_user_id];
            $where["OR"]["AND #second_query"] = ['initiator_user_id' => $recipient_user_id,'recipient_user_id' => $initiator_user_id];
            $pm_exists = DB::connect()->count("gr_private_conversations", $where);

            if (empty($pm_exists)) {
                $where = ['gid' => $message['gid'],'ORDER' => ['id' => 'DESC'],'LIMIT' => 1];
                $last_message_id = DB::connect()->select("old_version_gr_msgs(msgs)", ['msgs.id','msgs.tms'], $where);

                $updated_on = $message['tms'];

                if (isset($last_message_id[0])) {
                    $updated_on = $last_message_id[0]['tms'];
                    $last_message_id = $last_message_id[0]['id'];
                } else {
                    $last_message_id = 0;
                }

                if (empty($updated_on)) {
                    $updated_on = date('Y-m-d H:i:s');
                }

                $insert_data=array();
                $insert_data['initiator_user_id'] = $initiator_user_id;
                $insert_data['recipient_user_id'] = $recipient_user_id;
                $insert_data['initiator_load_message_id_from'] = 0;
                $insert_data['recipient_load_message_id_from'] = 0;
                $insert_data['created_on'] = $message['tms'];
                $insert_data['updated_on'] = $updated_on;

                DB::connect()->insert("gr_private_conversations", $insert_data);
            }
        }
    }

    if (!empty($last_message_id)) {
        $update_upgrade_info = include 'upgrade_info.php';
        $update_upgrade_info['last_private_conversation_id'] = $last_message_id;
        file_put_contents("upgrade_info.php", "<?php\nreturn ".var_export($update_upgrade_info, true).";\n?>");
    }
}

if (isset($messages) && count($messages) > 150) {
    $system_message = 'Importing Private Conversations';
    $redirect = 'update_database';
    $sub_process = 'private_conversations';
} else {
    $system_message = 'Importing Private Messages';
    $redirect = 'update_database';
    $sub_process = 'private_messages';
}
