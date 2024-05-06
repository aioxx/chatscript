<?php

$new_config_file = '../include/config.php';
$old_config_file = 'backup/include/config.php';
$same_site = $force_https = '';

if (file_exists($old_config_file)) {
    $file_contents = file_get_contents($old_config_file);
    preg_match("/config->samesite_cookies=(.*)/", $file_contents, $check_same_site);

    if (isset($check_same_site[1])) {
        $same_site = str_replace(array("\n", "\r",'"',"'",";","default"), '', $check_same_site[1]);
    }

    preg_match("/config->force_https=(.*)/", $file_contents, $check_force_https);

    if (isset($check_force_https[1])) {
        $check_force_https = str_replace(array("\n", "\r",'"',"'",";","false"), '', $check_force_https[1]);

        if(!empty($check_force_https)){
          $force_https='true';
        }
    }
}

if (is_writable($new_config_file)) {
    $upgrade_info = include 'upgrade_info.php';
    $database = $upgrade_info['db'];

    $file_contents = file_get_contents($new_config_file);
    $file_contents = preg_replace("/'host' => '([^']+(?='))'/", "'host' => '".$database['host']."'", $file_contents);
    $file_contents = preg_replace("/'database' => '([^']+(?='))'/", "'database' => '".$database['database']."'", $file_contents);
    $file_contents = preg_replace("/'username' => '([^']+(?='))'/", "'username' => '".$database['username']."'", $file_contents);
    $file_contents = preg_replace("/'password' => '([^']+(?='))'/", "'password' => '".$database['password']."'", $file_contents);
    $file_contents = preg_replace("/'port' => '([^']+(?='))'/", "'port' => '".$database['port']."'", $file_contents);

    if (!empty($same_site)) {
        $file_contents = preg_replace('/config->samesite_cookies="(.*)"/', 'config->samesite_cookies="'.$same_site.'"', $file_contents);
    }
    if (!empty($force_https)) {
        $file_contents = preg_replace('/config->force_https=(.*)/', 'config->force_https='.$force_https.';', $file_contents);
    }
    file_put_contents($new_config_file, $file_contents);

    copy('rebuild_cache_system.php', '../pages/rebuild_cache_system.php');

    $site_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $site_link = explode('/upgrade/index.php', $site_link);
    $site_link = $site_link[0];

    $rebuild_cache_url = $site_link.'/rebuild_cache_system/';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $rebuild_cache_url);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/6.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.7) Gecko/20050414 Firefox/1.0.3");
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl);
    curl_close($curl);

    file_get_contents($rebuild_cache_url);


    $system_message = 'Finalizing the Upgrade';
    $redirect = 'finalizing_upgrade';
} else {
    $system_message= 'Error : Unable to read/write files. <br>';
    $system_message.= 'Kindly check folder permssions & ownership.';
}
