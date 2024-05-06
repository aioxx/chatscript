<?php
$chat_messages_box_class = '';
if (Registry::load('settings')->show_timestamp_on_mouseover === 'enable') {
    $chat_messages_box_class = ' show_timestamp_on_mouseover';
}
?>

<div class="header">

    <div class="go_back_icon">
        <span class="go_to_previous_page">
            <span class="icon_back">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
                </svg>
            </span>
        </span>
    </div>

    <div class="message_selection d-none">
        <label class="selector select_all">
            <input type="checkbox" name="select_all_messages" value="1">
            <span class="checkmark"></span>
        </label>
    </div>
    <div class="image get_info" auto_find=true>
        <span class="thumbnail">
            <img class="image" />
        </span>
    </div>
    <div class="heading get_info" auto_find=true>
        <span class="title"></span>
        <span class="subtitle"></span>
        <span class="view_info"><?php echo(Registry::load('strings')->click_to_view_info); ?></span>
        <div class="whos_typing" last_logged_user_id=0>
            <ul></ul>
        </div>
    </div>
    <div class="icons">
        <?php
        if (Registry::load('current_user')->logged_in) {
            ?>
            <span class="d-md-none toggle_side_navigation">
                <i>
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 1024 1024">
                        <path fill="currentColor" d="M127.999 271.999c0-26.508 21.491-47.999 47.999-47.999v0h672.001c26.508 0 47.999 21.491 47.999 47.999s-21.491 47.999-47.999 47.999v0h-672.001c-26.508 0-47.999-21.491-47.999-47.999v0zM127.999 512c0-26.508 21.491-47.999 47.999-47.999v0h672.001c26.508 0 47.999 21.491 47.999 47.999s-21.491 47.999-47.999 47.999v0h-672.001c-26.508 0-47.999-21.491-47.999-47.999v0zM127.999 752.001c0-26.508 21.491-47.999 47.999-47.999v0h672.001c26.508 0 47.999 21.491 47.999 47.999s-21.491 47.999-47.999 47.999v0h-672.001c-26.508 0-47.999-21.491-47.999-47.999v0z"></path>
                    </svg>
                </i>
                <span class="total_unread_notifications"></span>
            </span>
            <?php
        }
        ?>


        <span class="toggle_checkbox">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 1024 1024">
                <path fill="currentColor" d="M111.531 87.765c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177-3.797 7.125v331.093l4.267 7.595c4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177 4.267-7.595v-331.093l-3.797-7.125c-4.523-8.405-11.477-13.739-22.187-16.981-11.179-3.371-320.213-3.285-330.965 0.085zM580.864 130.432c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177c-3.157 5.931-3.797 9.856-3.797 23.339 0 14.123 0.555 17.195 4.267 23.808 4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177c3.712-6.656 4.267-9.643 4.224-23.808-0.043-18.475-3.456-27.52-12.843-34.176-12.075-8.619-9.771-8.491-178.944-8.363-124.715 0.085-159.531 0.555-165.12 2.304zM384 277.333v106.667h-213.333v-213.333h213.333v106.667zM580.864 343.765c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177c-3.157 5.931-3.797 9.856-3.797 23.339 0 14.123 0.555 17.195 4.267 23.808 4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177c3.712-6.656 4.267-9.643 4.224-23.808-0.043-18.475-3.456-27.52-12.843-34.176-12.075-8.619-9.771-8.491-178.944-8.363-124.715 0.085-159.531 0.555-165.12 2.304zM111.531 557.099c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177-3.797 7.125v331.093l4.267 7.595c4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177 4.267-7.595v-331.093l-3.797-7.125c-4.523-8.405-11.477-13.739-22.187-16.981-11.179-3.371-320.213-3.285-330.965 0.085zM580.864 599.765c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177c-3.157 5.931-3.797 9.856-3.797 23.339 0 14.123 0.555 17.195 4.267 23.808 4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177c3.712-6.656 4.267-9.643 4.224-23.808-0.043-18.475-3.456-27.52-12.843-34.176-12.075-8.619-9.771-8.491-178.944-8.363-124.715 0.085-159.531 0.555-165.12 2.304zM384 746.667v106.667h-213.333v-213.333h213.333v106.667zM580.864 813.099c-9.351 2.542-16.968 8.642-21.456 16.719l-0.090 0.177c-3.157 5.931-3.797 9.856-3.797 23.339 0 14.123 0.555 17.195 4.267 23.808 4.693 8.405 12.544 14.507 21.973 17.109 8.448 2.347 321.365 2.347 329.813 0 9.493-2.619 17.237-8.778 21.88-16.932l0.093-0.177c3.712-6.656 4.267-9.643 4.224-23.808-0.043-18.475-3.456-27.52-12.843-34.176-12.075-8.619-9.771-8.491-178.944-8.363-124.715 0.085-159.531 0.555-165.12 2.304z"></path>
            </svg>

        </span>
        <span class="ask_confirmation delete_multiple_messages d-none" column="second" data-chat_messages=true multi_select="message_id" submit_button="<?php echo(Registry::load('strings')->yes); ?>" cancel_button="<?php echo(Registry::load('strings')->no); ?>" confirmation="<?php echo(Registry::load('strings')->confirm_delete); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" viewBox="0 0 16 16">
                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z" />
            </svg>
        </span>
        <span class="reload_conversation d-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
                <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
            </svg>
        </span>
        <span class="toggle_search_messages">
            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
            </svg>
        </span>
    </div>

    <?php
    if (role(['permissions' => ['groups' => 'send_as_another_user']])) {
        ?>
        <div class="switch_user d-none">
            <span class="close_popup toggle_list">
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"></path>
                </svg>
            </span>
            <span class="image toggle_list" title="<?php echo Registry::load('strings')->switch_user ?>" data-bs-toggle="tooltip" data-bs-placement="left"></span>
            <span class="user_id d-none"><input type="text" /></span>
            <span class="username d-none"></span>
            <div>
                <div class="search">
                    <div>
                        <i class="search_svg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                            </svg>
                        </i>
                        <input type="search" placeholder="<?php echo(Registry::load('strings')->search_here) ?>">
                    </div>
                </div>
                <div class="list">
                    <ul></ul>
                </div>
            </div>
        </div>

        <?php
    }
    ?>

</div>

<div class="search_messages">
    <div>
        <div class="search">
            <div>
                <i class="search_svg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                    </svg>
                </i>
                <input type="search" name="search_messages" placeholder="<?php echo(Registry::load('strings')->search_here) ?>">
            </div>
        </div>
    </div>
</div>

<div class="alert_message">
    <div>
        <div class="message">
            <span></span>
        </div>
    </div>
</div>

<div class="contents" read_more_criteria="<?php echo(Registry::load('settings')->read_more_criteria) ?>">
    <span class="date timestamp">
        <span></span>
    </span>
    <div class="chat_messages<?php echo $chat_messages_box_class; ?>">
        <ul></ul>
    </div>

    <div class="loader conversation_loader">
        <ul></ul>
    </div>
    <div class="error_message">
        <div>
            <div>
                <div class="image"></div>
                <div class="text">
                    <span class="title"></span>
                    <span class="subtitle"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="info_box">
    <div>
        <div class="content"></div>
    </div>
</div>

<div class="footer">

    <?php
    if (Registry::load('current_user')->logged_in) {
        ?>

        <div class="grid_list module hidden">
            <div class="gif_module d-none" load="gifs">
                <span class="data_attributes d-none"></span>

                <div class="search">
                    <div>
                        <input type="search" placeholder="<?php echo(Registry::load('strings')->search_here) ?>" />
                    </div>
                </div>

                <div class="subtabs">
                    <ul></ul>
                </div>


                <div class="results">
                    <div>
                        <ul id="grid_list"></ul>
                    </div>
                </div>
            </div>

            <div class="stickers_module d-none" load="stickers">
                <span class="data_attributes d-none"></span>
                <div class="subtabs">
                    <ul></ul>
                </div>

                <div class="results">
                    <div>
                        <ul id="grid_list"></ul>
                    </div>
                </div>
            </div>

            <div class="emojis_module d-none" load="emojis">
                <span class="data_attributes d-none"></span>

                <div class="search">
                    <div>
                        <input type="search" placeholder="<?php echo(Registry::load('strings')->search_here) ?>" />
                    </div>
                </div>

                <div class="subtabs">
                    <ul></ul>
                </div>


                <div class="results">
                    <div>
                        <ul id="grid_list"></ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="attached_message">
            <div class="attached_message_preview">
                <div class="content">
                    <div class="left">
                        <span class="send_by"></span>
                        <span class="text"></span>
                    </div>
                    <div class="right">
                        <span class="thumbnail"></span>
                    </div>
                </div>
                <div class="detach_message">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
                    </svg>
                </div>
            </div>
            <span class="attached_message_id"><input type="hidden" name="attached_msg_id" value="0" /></span>
        </div>


        <div class="editor" min_message_length="<?php echo(Registry::load('settings')->minimum_message_length) ?>" max_message_length="<?php echo(Registry::load('settings')->maximum_message_length) ?>">
            <div>
                <div class="attached_gif d-none">
                    <span class="gif_image">
                        <img src='' />
                        <span class="deattach_gif">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
                            </svg>
                        </span>
                    </span>
                    <span class="gif_url">
                        <input type="text" name="gif_url" value='' />
                    </span>
                </div>

                <div class="toggle_message_toolbar">
                    <div>
                        <span>
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 1024 1024">
                                <path fill="currentColor" d="M469.333 469.333v-256h85.333v256h256v85.333h-256v256h-85.333v-256h-256v-85.333z"></path>
                            </svg>

                        </span>
                    </div>
                </div>
                <div class="message_editor">
                    <div id="message_editor"></div>
                </div>
                <div class="send_message_button">
                    <div>
                        <span class="send_message">
                            <svg fill="currentColor" width="23px" height="23px" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.975 17.504l14.287.001-6.367 6.366L16.021 26l10.004-10.003L16.029 6l-2.128 2.129 6.367 6.366H5.977z" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="attachments module hidden">
            <div>
                <div class="files">
                    <ul></ul>
                </div>
                <div class="attached_files">
                    <form class="attach_files_form" enctype="multipart/form-data">
                    </form>
                </div>
            </div>
        </div>

        <?php
    }
    ?>

    <audio id="audio_message_preview" class="d-none" controls preload="none">
        <source src="" type="" />
    </audio>
</div>

<span class="background_image"></span>