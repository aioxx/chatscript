<?php

include('functions/files/remove.php');
include('functions/files/copy.php');

$skip_overwrite = ['assets'];
$skip_folders = ['assets','upgrade'];
$skip_asset_folders = ['files','cache','headers_footers','fonts', 'group_headers'];
$skip_files = ['custom_css', 'custom_js'];

$remove_items = [
  'assets/files/slideshows/',
  'assets/files/avatars/',
  'assets/files/stickers/',
  
  'assets/css/chat_page/custom_css.css',
  'assets/css/entry_page/custom_css.css',
  'assets/css/landing_page/custom_css.css',
  'assets/css/common/custom_css.css',

  'assets/js/chat_page/custom_js.js',
  'assets/js/entry_page/custom_js.js',
  'assets/js/landing_page/custom_js.js',
  'assets/js/common/custom_js.js',
];

foreach ($remove_items as $remove_item) {
    $remove_upgrade_item = 'upgrade_image/'.$remove_item;
    $remove_item = '../'.$remove_item;
    if (file_exists($remove_item) && file_exists($remove_upgrade_item)) {
        if (is_dir($remove_upgrade_item)) {
            rrmdir($remove_upgrade_item);
        } else {
            unlink($remove_upgrade_item);
        }
    }
}

$old_files = scandir('../');
$old_files = array_diff($old_files, array('..', '.'));

$assets_folder = scandir('../assets/');
$assets_folder = array_diff($assets_folder, array('..', '.'));



$new_files = scandir('upgrade_image/');
$new_files = array_diff($new_files, array('..', '.'));

foreach ($new_files as $files) {
    $name = basename($files);
    $files = 'upgrade_image/'.$files;


    if (!in_array($name, $skip_overwrite) && $name!='image.zip') {
        if (is_dir($files)) {
            $new_folder ='../'.$name;

            if (!file_exists($new_folder)) {
                mkdir($new_folder);
            }
            recurseCopy($files, $new_folder);
        } else {
            $new_filename='../'.$name;
            copy($files, $new_filename);
        }
    } elseif ($name!='image.zip') {
        if ($name === 'assets') {
            $assets_folder = scandir('upgrade_image/assets/');
            $assets_folder = array_diff($assets_folder, array('..', '.'));

            foreach ($assets_folder as $assets) {
                $directory = basename($assets);
                $assets = 'upgrade_image/assets/'.$assets;

                if (!in_array($directory, $skip_asset_folders)) {
                    if (is_dir($assets)) {
                        $new_folder ='../assets/'.$directory;

                        if (!file_exists($new_folder)) {
                            mkdir($new_folder);
                        }
                        recurseCopy($assets, $new_folder);
                    } else {
                        $new_filename='../assets/'.$directory;
                        copy($assets, $new_filename);
                    }
                } else {
                    if (is_dir($assets)) {
                        $directory = '../assets/'.$directory;
                        recurseCopy_not_Exists($assets, $directory);
                    }
                }
            }
        }
    }
}

if (file_exists('../pages/installer.php')) {
    unlink('../pages/installer.php');
}

if (!file_exists('../.htaccess')) {
    copy('upgrade_image/.htaccess', '../.htaccess');
}
