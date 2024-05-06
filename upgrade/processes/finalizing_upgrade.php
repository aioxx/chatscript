<?php

if (file_exists('../pages/rebuild_cache_system.php')) {
    unlink('../pages/rebuild_cache_system.php');
}

if (file_exists('../htaccess.backup')) {
    unlink('../htaccess.backup');
}

$system_message= 'Grupo Upgraded Successfully.';

$list_items ='';

$new_upgrade_folder = '../upgrade'.rand(32323232, 1212332233221).rand(10320, 22320).'/';

$rename = @rename('../upgrade/',$new_upgrade_folder);

if(!$rename){
  $list_items .= '<li>Rename/Delete Upgrade Folder.</li>';
}

$list_items .= '<li>Clear your browser cache.</li>';
$list_items .= '<li>If you are using Cloudflare or similar CDN, make sure to purge cache.</li>';
$list_items .= '<li><strong style="color:red;">Note :</strong> For enabling/disabling new features or permissions, check Site Roles, Group Roles & Grupo Settings.</li>';

$alert_message = 'Important : Make sure to check Site Roles, Group Roles & Grupo Settings for enabling/disabling new features or permissions.';
