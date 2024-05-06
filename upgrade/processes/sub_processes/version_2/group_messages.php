
<?php

include('functions/files/get_file_size.php');

$sql_query=  DB::connect()->query("SHOW TABLES LIKE 'old_version_gr_msgs'")->fetchAll();

if (count($sql_query) > 0) {
    $columns=[
       'msgs.id','msgs.gid','msgs.uid','msgs.msg', 'msgs.type', 'msgs.rtxt', 'msgs.rid',
       'msgs.rmid', 'msgs.rtype', 'msgs.cat', 'msgs.lnurl',
       'msgs.lntitle', 'msgs.lndesc', 'msgs.lnimg', 'msgs.xtra', 'msgs.tms'
    ];
    $where=[
      'msgs.cat' => 'group',
      'msgs.type[!](not_type)' => 'system',
      'msgs.type[!](not_system)' => 'stickers',
      'msgs.type[!](not_like)' => 'like',
      'LIMIT' => 250
  ];
    $messages = DB::connect()->select("old_version_gr_msgs(msgs)", $columns, $where);

    foreach ($messages as $message) {
        if (!empty($message['gid']) && !empty($message['uid'])) {
            $attachment_type =$attachments = null;
            $msg = '';

            if ($message['type'] === 'gifs') {
                $gif_url = $message['msg'];
                $gif_url = explode('|', $gif_url);
                $gif_url = $gif_url[0];

                $attachment_type='gifs';
                $attachments=['gif_url' => $gif_url];
            } elseif ($message['type'] === 'audio') {
                $file_name = $message['msg'];
                $old_audio_file = 'backup/gem/ore/grupo/audiomsgs/'.$file_name;

                if (file_exists($old_audio_file)) {
                    $audio_message = '../assets/files/audio_messages/group_chat/'.$message['gid'].'/';

                    if (!file_exists($audio_message)) {
                        mkdir($audio_message);
                    }

                    $audio_message = 'assets/files/audio_messages/group_chat/'.$message['gid'].'/'.$file_name;
                    $attachment_type='audio_message';
                    $attachments=['audio_message' => $audio_message];
                    $attachments['mime_type']=mime_content_type($old_audio_file);

                    copy($old_audio_file, '../'.$audio_message);
                }
            } elseif ($message['type'] === 'file') {
                $file_name = explode('-gr-', $message['msg']);
                $image_file_formats = ['image/jpeg', 'image/png', 'image/x-png', 'image/gif', 'image/bmp', 'image/x-ms-bmp'];
                $audio_file_formats = ['audio/wav', 'audio/mpeg', 'audio/mp4', 'audio/webm', 'audio/ogg', 'audio/x-wav'];
                $video_file_formats = ['video/mp4', 'video/mpeg', 'video/ogg', 'video/webm'];


                if (isset($file_name[1])) {
                    $file_name=$file_name[1];
                    $user_folder = 'backup/gem/ore/grupo/files/'.$message['uid'].'/';
                    $user_storage_folder = 'assets/files/storage/'.$message['uid'];
                    $old_file = '';

                    if (!file_exists('../'.$user_storage_folder)) {
                        mkdir('../'.$user_storage_folder);
                        mkdir('../'.$user_storage_folder.'/files/');
                        mkdir('../'.$user_storage_folder.'/thumbnails/');
                    }

                    foreach (glob($user_folder.'*'.$file_name) as $old_file) {
                        break;
                    }

                    if (!empty($old_file) && file_exists($old_file)) {
                        $file_type = mime_content_type($old_file);
                        $new_file = '../'.$user_storage_folder.'/files/'.basename($old_file);

                        copy($old_file, $new_file);

                        if (strlen($message['xtra']) > 15) {
                            $message['xtra'] = trim(mb_substr($message['xtra'], 0, 8)).'...'.mb_substr($message['xtra'], -8);
                        }

                        $attachments=array();
                        $attachments[0] = [
                          'name' => $file_name,
                          'trimmed_name' => $message['xtra'],
                          'file' => $user_storage_folder.'/files/'.basename($old_file),
                          'file_type' => $file_type,
                          'file_size' => get_file_size($old_file)
                        ];

                        if (in_array($file_type, $image_file_formats)) {
                            $thumbnail = 'backup/gem/ore/grupo/files/preview/'.basename($old_file);
                            $thumbnail_folder = $user_storage_folder.'/thumbnails/';
                            $attachment_type = 'image_files';

                            if (file_exists($thumbnail)) {
                                $attachments[0]['thumbnail'] = $thumbnail_folder.basename($old_file);
                                $new_file = '../'.$thumbnail_folder.basename($old_file);
                                copy($old_file, $new_file);
                            }
                        } elseif (in_array($file_type, $audio_file_formats)) {
                            $attachment_type = 'audio_files';
                        } elseif (in_array($file_type, $video_file_formats)) {
                            $attachment_type = 'video_files';
                        } else {
                            $attachment_type='other_files';
                        }
                    }
                }
            } elseif ($message['type'] === 'msg') {
                $msg = $message['msg'];
                $msg = str_replace('&amp;', '&', $msg);

                preg_match_all("/:\w+:/", $msg, $matches);

                if (!empty($matches[0])) {
                    foreach ($matches[0] as $emoji) {
                        $emoji_name = str_replace(':', '', $emoji);
                        $emoji_class = '<span class="emoji_icon emoji-'.$emoji_name.'">&nbsp;</span>';
                        $msg = str_replace($emoji, $emoji_class, $msg);
                    }
                }
                if (!empty($message['lntitle']) && !empty($message['lndesc']) && !empty($message['lnurl'])) {
                    $url_parsed_arr = parse_url($message['lnurl']);

                    $attachments = [
                    'image' => $message['lnimg'],
                    'description' => $message['lndesc'],
                    'title' => $message['lntitle'],
                    'mime_type' => 'text/html',
                    'url' => $message['lnurl'],
                    'host_name' => $url_parsed_arr['host']
                  ];

                    $regex_pattern = "/(youtube.com|youtu.be)\/(watch)?(\?v=)?(\S+)?/";

                    if (preg_match($regex_pattern, $message['lnurl'], $match)) {
                        $attachments['mime_type'] = 'video/youtube';
                    }

                    $attachment_type='url_meta';
                }
            }

            if (!empty($msg) || !empty($attachment_type) && !empty($attachments)) {
                $filtered_msg = str_replace('&amp;', '&', $msg);

                $insert_data=array();
                $insert_data['group_message_id'] = $message['id'];
                $insert_data['group_id'] = $message['gid'];
                $insert_data['user_id'] = $message['uid'];
                $insert_data['parent_message_id'] = $message['rid'];
                $insert_data['original_message'] = $msg;
                $insert_data['filtered_message'] = $filtered_msg;

                if (!empty($attachment_type) && !empty($attachments)) {
                    $insert_data['attachment_type'] = $attachment_type;
                    $insert_data['attachments[JSON]'] = $attachments;
                }

                if (empty($message['tms'])) {
                    $message['tms'] = date('Y-m-d H:i:s');
                }

                $insert_data['created_on'] = $message['tms'];
                $insert_data['updated_on'] = $message['tms'];

                DB::connect()->insert("gr_group_messages", $insert_data);
            }

            DB::connect()->delete("old_version_gr_msgs", ['id' => $message['id']]);
        }
    }
}

if (isset($messages) && count($messages) > 150) {
    $system_message = 'Importing Group Messages';
    $redirect = 'update_database';
    $sub_process = 'group_messages';
} else {
    $system_message = 'Importing Private Conversations';
    $redirect = 'update_database';
    $sub_process = 'private_conversations';
}
