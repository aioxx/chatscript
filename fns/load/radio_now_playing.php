<?php
$output = array();
$output['title'] = 'Unknown';
$audio_content_id = 0;


if (isset($data['audio_content_id'])) {

    $audio_content_id = filter_var($data["audio_content_id"], FILTER_SANITIZE_NUMBER_INT);

    if (!empty($audio_content_id)) {
        $columns = [
            'audio_player.audio_content_id', 'audio_player.audio_title',
            'audio_player.audio_description', 'audio_player.audio_type',
            'audio_player.radio_stream_url', 'audio_player.streaming_server'
        ];

        $where["audio_player.disabled[!]"] = 1;


        $where["audio_player.audio_content_id"] = $audio_content_id;

        $audio_records = DB::connect()->select('audio_player', $columns, $where);

        if (isset($audio_records[0])) {
            $audio_record = $audio_records[0];
            $output['title'] = $audio_record['audio_description'];

            if ($audio_record['streaming_server'] == 'icecast' || $audio_record['streaming_server'] == 'shoutcast') {

                if (!empty($audio_record['radio_stream_url'])) {

                    $radio_info = parse_url($audio_record['radio_stream_url']);
                    if (isset($radio_info['host'])) {

                        $radio_info_url = $radio_info['scheme']."://".$radio_info['host'];

                        if (isset($radio_info['port'])) {
                            $radio_info_url = $radio_info_url.':'.$radio_info['port'];
                        }

                        if ($audio_record['streaming_server'] == 'icecast') {
                            $radio_info_url = $radio_info_url.'/status-json.xsl';
                        } else {
                            $radio_info_url = $radio_info_url.'/7.html';
                        }

                        if (filter_var($radio_info_url, FILTER_VALIDATE_URL)) {
                            $content = @file_get_contents($radio_info_url);

                            if ($audio_record['streaming_server'] == 'icecast') {

                                $content = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $content), true);

                                if (!empty($content)) {

                                    if (isset($radio_info['path'])) {
                                        $radio_path = '/'.str_replace('/', '', $radio_info['path']);
                                    }

                                    if (isset($content['icestats']['source']['title'])) {
                                        $output['title'] = $content['icestats']['source']['title'];
                                    } else if (isset($content['icestats']['source'][0]['title'])) {
                                        $output['title'] = $content['icestats']['source'][0]['title'];

                                        if (isset($content['icestats']['source'][0]['artist'])) {
                                            $output['title'] .= ' - '. $content['icestats']['source'][0]['artist'];
                                        }
                                    } else if (isset($content[$radio_path])) {
                                        $output['title'] = $content[$radio_path]['title'];

                                        if (isset($content[$radio_path]['description']) && !empty($content[$radio_path]['description'])) {
                                            $output['title'] .= ' - '. $content[$radio_path]['description'];
                                        }
                                    } else if (isset($content['icestats']['streamtitle'])) {
                                        $content = explode(" - ", $content['icestats']['streamtitle']);

                                        if (isset($content[1])) {
                                            $output['title'] = $content[1];
                                            if (isset($content[0])) {
                                                $output['title'] .= ' - '. $content[0];
                                            }
                                        }
                                    }
                                }
                            } else {
                                $content = strip_tags($content);
                                $content = explode(',', $content);
                                if (isset($content[6])) {
                                    $content = $content[6];
                                    if (mb_strlen($content) > 3) {
                                        $output['title'] = $content;
                                        $output['title'] = htmlspecialchars_decode($content);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
$output['title'] = strip_tags($output['title']);
?>