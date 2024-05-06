<?php

$columns = $where = $join = null;
$columns = [
    "scheduled_messages.message_content", "scheduled_messages.group_id", "scheduled_messages.user_id",
    "scheduled_messages.send_message_on", "scheduled_messages.scheduled_message_id",
];

$where = [
    'LIMIT' => 25,
    'ORDER' => ['scheduled_messages.scheduled_message_id' => 'DESC'],
    'scheduled_messages.send_message_on[<]' => Registry::load('current_user')->time_stamp
];
$messages = DB::connect()->select("scheduled_messages", $columns, $where);

$insert_data = array();
$delete_message_ids = array();

foreach ($messages as $message) {
    $delete_message_ids[] = $message['scheduled_message_id'];
    $insert_data[] = [
        "original_message" => $message['message_content'],
        "filtered_message" => $message['message_content'],
        "group_id" => $message['group_id'],
        "user_id" => $message['user_id'],
        "parent_message_id" => null,
        "attachment_type" => null,
        "attachments" => '',
        "link_preview" => null,
        "created_on" => $message['send_message_on'],
        "updated_on" => Registry::load('current_user')->time_stamp,
    ];
}

if (!empty($insert_data)) {
    DB::connect()->insert("group_messages", $insert_data);
    
    if (!empty($delete_message_ids)) {
        DB::connect()->delete("scheduled_messages", ['scheduled_messages.scheduled_message_id' => $delete_message_ids]);
    }
}

$output = array();
$output['success'] = true;
?>