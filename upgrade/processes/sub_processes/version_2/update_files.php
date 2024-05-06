<?php

include('functions/files/remove.php');
include('functions/files/copy.php');

if (file_exists('../door/')) {
    rrmdir('../door/');
}

if (file_exists('../gem/')) {
    rrmdir('../gem/');
}

if (file_exists('../key/')) {
    rrmdir('../key/');
}

if (file_exists('../knob/')) {
    rrmdir('../knob/');
}

if (file_exists('../riches/')) {
    rrmdir('../riches/');
}

if (file_exists('../pwabuilder-sw.js')) {
    unlink('../pwabuilder-sw.js');
}

if (file_exists('../requirements.php')) {
    unlink('../requirements.php');
}


if (!file_exists('../fns')) {
    if (file_exists('../index.php')) {
        unlink('../index.php');
    }

    if (file_exists('../.htaccess')) {
        unlink('../.htaccess');
    }
}

if (!file_exists('../fns')) {
    recurseCopy('upgrade_image', '../');

    if (file_exists('../pages/installer.php')) {
        unlink('../pages/installer.php');
    }
}


if (file_exists('backup/gem/ore/grupo')) {
    foreach (glob('../assets/files/languages/'.'*-gr-*') as $old_file) {
        unlink($old_file);
    }

    if (file_exists('backup/gem/ore/grupo/groups')) {
        recurseCopy('backup/gem/ore/grupo/groups', '../assets/files/groups/icons/');
    }

    if (file_exists('backup/gem/ore/grupo/coverpic/groups')) {
        recurseCopy('backup/gem/ore/grupo/coverpic/groups', '../assets/files/groups/cover_pics/');
    }

    if (file_exists('backup/gem/ore/grupo/users')) {
        recurseCopy('backup/gem/ore/grupo/users', '../assets/files/site_users/profile_pics/');
    }

    if (file_exists('backup/gem/ore/grupo/coverpic/users')) {
        recurseCopy('backup/gem/ore/grupo/coverpic/users', '../assets/files/site_users/cover_pics/');
    }

    if (file_exists('backup/gem/ore/grupo/userbg')) {
        recurseCopy('backup/gem/ore/grupo/userbg', '../assets/files/site_users/backgrounds/');
    }

    if (file_exists('backup/gem/ore/grupo/roles')) {
        recurseCopy('backup/gem/ore/grupo/roles', '../assets/files/site_roles/');
    }

    if (file_exists('backup/gem/ore/grupo/loginprovider')) {
        recurseCopy('backup/gem/ore/grupo/loginprovider', '../assets/files/social_login/');
    }

    if (file_exists('backup/gem/ore/grupo/languages')) {
        recurseCopy('backup/gem/ore/grupo/languages', '../assets/files/languages/');
    }

    if (file_exists('backup/gem/ore/grupo/radiostations')) {
        recurseCopy('backup/gem/ore/grupo/radiostations', '../assets/files/audio_player/images/');
    }

    if (file_exists('backup/gem/ore/grupo/global/bg.jpg')) {
        copy('backup/gem/ore/grupo/global/bg.jpg', '../assets/files/backgrounds/chat_page_bg.jpg');
    }

    if (file_exists('backup/gem/ore/grupo/global/bg-dark.jpg')) {
        copy('backup/gem/ore/grupo/global/bg-dark.jpg', '../assets/files/backgrounds/chat_page_bg_dark_mode.jpg');
    }

    if (file_exists('backup/gem/ore/grupo/global/login.jpg')) {
        copy('backup/gem/ore/grupo/global/login.jpg', '../assets/files/backgrounds/entry_page_bg.jpg');
        copy('backup/gem/ore/grupo/global/login.jpg', '../assets/files/backgrounds/entry_page_bg_dark_mode.jpg');
    }

    if (file_exists('backup/gem/ore/grupo/global/emaillogo.png')) {
        copy('backup/gem/ore/grupo/global/emaillogo.png', '../assets/files/logos/email_logo.png');
    }

    if (file_exists('backup/gem/ore/grupo/global/favicon.png')) {
        copy('backup/gem/ore/grupo/global/favicon.png', '../assets/files/defaults/favicon.png');
    }

    if (file_exists('backup/gem/ore/grupo/global/sitelogo.png')) {
        copy('backup/gem/ore/grupo/global/sitelogo.png', '../assets/files/logos/chat_page_logo.png');
        copy('backup/gem/ore/grupo/global/sitelogo.png', '../assets/files/logos/chat_page_logo_dark_mode.png');
        copy('backup/gem/ore/grupo/global/sitelogo.png', '../assets/files/logos/entry_page_logo.png');
        copy('backup/gem/ore/grupo/global/sitelogo.png', '../assets/files/logos/entry_page_logo_dark_mode.png');
        copy('backup/gem/ore/grupo/global/sitelogo.png', '../assets/files/logos/landing_page_logo.png');
        copy('backup/gem/ore/grupo/global/sitelogo.png', '../assets/files/logos/landing_page_logo_dark_mode.png');
        copy('backup/gem/ore/grupo/global/sitelogo.png', '../assets/files/logos/landing_page_footer_logo.png');
        copy('backup/gem/ore/grupo/global/sitelogo.png', '../assets/files/logos/landing_page_footer_logo_dark_mode.png');
    }

    $stickers = glob('backup/gem/ore/grupo/stickers/*');
    $skipfolders = ['Covid19','Famous','Simpsons'];

    foreach ($stickers as $sticker) {
        $folder_name = basename($sticker);
        if (!in_array($folder_name, $skipfolders)) {
            $copy_from = $sticker;
            $copy_to = '../assets/files/stickers/'.$folder_name;
            if (!file_exists($copy_to)) {
                mkdir($copy_to);
                recurseCopy($copy_from, $copy_to);
            }
        }
    }

    $storage = glob('backup/gem/ore/grupo/files/*');
    $skipfolders = ['dumb','preview'];

    foreach ($storage as $files) {
        $folder_name = basename($files);

        if (!in_array($folder_name, $skipfolders)) {
            $copy_from = $files;
            $copy_to = '../assets/files/storage/'.$folder_name;

            if (!file_exists($copy_to)) {
                mkdir($copy_to);
                mkdir($copy_to.'/files/');
                mkdir($copy_to.'/thumbnails/');
            }
            $copy_to = $copy_to.'/files/';
            recurseCopy($copy_from, $copy_to);
        }
    }
}
