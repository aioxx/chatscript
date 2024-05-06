<div class="col-md-5 col-lg-3 aside page_column visible" column="first">
    <div class='head'>
        <?php
        if (Registry::load('current_user')->logged_in) {
            ?>
            <span class='menu toggle_side_navigation'>
                <i>
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 1024 1024">
                        <path fill="currentColor" d="M127.999 271.999c0-26.508 21.491-47.999 47.999-47.999v0h672.001c26.508 0 47.999 21.491 47.999 47.999s-21.491 47.999-47.999 47.999v0h-672.001c-26.508 0-47.999-21.491-47.999-47.999v0zM127.999 512c0-26.508 21.491-47.999 47.999-47.999v0h672.001c26.508 0 47.999 21.491 47.999 47.999s-21.491 47.999-47.999 47.999v0h-672.001c-26.508 0-47.999-21.491-47.999-47.999v0zM127.999 752.001c0-26.508 21.491-47.999 47.999-47.999v0h672.001c26.508 0 47.999 21.491 47.999 47.999s-21.491 47.999-47.999 47.999v0h-672.001c-26.508 0-47.999-21.491-47.999-47.999v0z"></path>
                    </svg>
                </i>
                <span class="total_unread_notifications"></span>
            </span>
            <?php
        }
        ?>
        <span class='logo refresh_page'>
            <?php if (Registry::load('current_user')->color_scheme === 'dark_mode') {
                ?>
                <img width="100px" height="50px" src="<?php echo Registry::load('config')->site_url.'assets/files/logos/chat_page_logo_dark_mode.png'.$cache_timestamp; ?>" />
                <?php
            } else {
                ?>
                <img width="100px" height="50px" src="<?php echo Registry::load('config')->site_url.'assets/files/logos/chat_page_logo.png'.$cache_timestamp; ?>" />
                <?php
            } ?>
        </span>
        <span class='icons'>
            <?php
            if (Registry::load('current_user')->logged_in && role(['permissions' => ['private_conversations' => 'view_private_chats']])) {
                ?>
                <span class="icon load_aside pm_shortcut" load="private_conversations">
                    <i>
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 1024 1024">
                            <path fill="currentColor" d="M230.89 72h562.221c84.723 0 153.961 66.317 158.635 149.873l0.255 9.017v562.221c0 84.723-66.317 153.961-149.873 158.635l-9.017 0.255h-562.221c-84.723 0-153.961-66.317-158.635-149.873l-0.255-9.017v-562.221c0-84.723 66.317-153.961 149.873-158.635l9.017-0.255zM332.332 634.221h-187.001v158.89c0 44.887 34.573 81.699 78.539 85.269l7.020 0.283h562.221c44.887 0 81.699-34.573 85.269-78.539l0.283-7.020v-158.89h-187.001c-16.355 80.582-85.57 141.97-169.876 146.41l-9.792 0.259c-85.411 0-157.18-58.406-177.545-137.455l-2.123-9.207zM793.11 145.331h-562.221c-44.887 0-81.699 34.573-85.269 78.539l-0.283 7.020v330h220c18.559 0 33.901 13.796 36.335 31.69l0.334 4.972c0 60.754 49.246 110 110 110 58.223 0 105.876-45.228 109.744-102.468l0.256-7.532c0-18.559 13.796-33.901 31.69-36.335l4.972-0.334h220v-330c0-44.887-34.573-81.699-78.539-85.269l-7.020-0.283zM255.331 389.779h513.331c20.249 0 36.669 16.413 36.669 36.669 0 18.559-13.796 33.901-31.69 36.335l-4.972 0.334h-513.331c-20.249 0-36.669-16.413-36.669-36.669 0-18.559 13.796-33.901 31.69-36.335l4.972-0.334zM255.331 243.11h513.331c20.249 0 36.669 16.413 36.669 36.669 0 18.559-13.796 33.901-31.69 36.335l-4.972 0.334h-513.331c-20.249 0-36.669-16.413-36.669-36.669 0-18.559 13.796-33.901 31.69-36.335l4.972-0.334z"></path>
                        </svg>
                    </i>
                    <span class="notification_count"></span>
                </span>
                <?php
            }
            if (!Registry::load('current_user')->logged_in) {
                if (Registry::load('settings')->hide_groups_on_group_url) {
                    ?>
                    <i class="iconic_groups load_groups load_aside d-none" load="group_members" data-group_id="<?php echo(Registry::load('config')->load_group_conversation) ?>"></i>
                    <?php
                } else {
                    ?>
                    <i class="iconic_groups load_groups load_aside d-none" load="groups"></i>
                    <?php
                }
            }
            ?>
        </span>
    </div>

    <div class="storage_files_upload_status">
        <div class="center">
            <div class="error">
                <span class="message"><?php echo Registry::load('strings')->error ?> : <span></span></span>
            </div>
            <div class="text">
                <span><?php echo Registry::load('strings')->uploading_files ?> : <span class="percentage">0%</span></span>
            </div>
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
            </div>
            <div class="files_attached">
                <form class="files_attached_form" enctype="multipart/form-data">
                    <input type="hidden" name="upload" value="storage" />
                    <input type="hidden" name="frontend" value='true' />
                    <input type="file" multiple="" name="storage_file_attachments[]" class="storage_file_attachments" />
                </form>
            </div>
        </div>
    </div>

    <div class="audio_player_box module hidden">
        <?php include 'layouts/chat_page/audio_player_box.php'; ?>
    </div>

    <div class="site_records module">
        <?php include 'layouts/chat_page/site_records.php'; ?>
    </div>


    <?php
    if (!Registry::load('current_user')->logged_in) {
        ?>
        <div class="info_box">
            <div>
                <div class="text">
                    <?php echo Registry::load('strings')->not_logged_in_message ?>
                </div>
                <span class="button open_link" autosync=true link="<?php echo Registry::load('config')->site_url ?>entry/">
                    <?php echo Registry::load('strings')->login ?>
                </span>
            </div>
        </div>
        <?php
    }
    ?>


    <?php
    if (isset($site_adverts['left_content_block'])) {
        $site_advert = $site_adverts['left_content_block'];
        $advert_css = 'max-height:'.$site_advert['site_advert_max_height'].'px;';

        if (!empty($site_advert['site_advert_min_height'])) {
            $advert_css .= 'min-height:'.$site_advert['site_advert_min_height'].'px;';
        }

        ?>

        <div class="site_advert_block" style="<?php echo $advert_css; ?>">
            <div>
                <?php echo $site_advert['site_advert_content']; ?>
            </div>
        </div>
        <?php
    }
    ?>

    <?php include 'layouts/chat_page/mini_audio_player.php'; ?>

</div>