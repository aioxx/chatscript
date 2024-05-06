<?php

$system_message = 'Starting Upgrade Process';
$redirect = null;

$upgrade_info = include 'upgrade_info.php';

if (!isset($upgrade_info['db']) || empty($upgrade_info['db'])) {
    if ($upgrade_info['grupo_version']==2) {
        define('s7V9pz', true);
        include('../key/bit.php');

        $database=cnf('Grupo');

        $upgrade_info['db']=array();
        $upgrade_info['db']['database']=$database['db'];
        $upgrade_info['db']['host']=$database['host'];
        $upgrade_info['db']['username']=$database['user'];
        $upgrade_info['db']['password']=$database['pass'];
        $upgrade_info['db']['prefix']='';
        $upgrade_info['db']['port']=3306;
        $upgrade_info['db']['type']='mysql';
    } elseif ($upgrade_info['grupo_version']==3) {
        $database_file=file_get_contents('../include/config.php');
        $database_file = str_replace("include 'fns/registry/load.php';", '', $database_file);
        $database_file = str_replace('Registry::__init();', '', $database_file);
        $database_file = str_replace('Registry::add('."'config'".', $config);', '', $database_file);
        $database_file = str_replace('date_default_timezone_set($config->timezone);', '', $database_file);
        $database_file = str_replace("<?php", '', $database_file);
        $database = eval($database_file);

        $database=$config->database;

        $upgrade_info['db']=array();
        $upgrade_info['db']['database']=$database['database'];
        $upgrade_info['db']['host']=$database['host'];
        $upgrade_info['db']['username']=$database['username'];
        $upgrade_info['db']['password']=$database['password'];
        $upgrade_info['db']['prefix']='';
        $upgrade_info['db']['port']=$database['port'];
        $upgrade_info['db']['type']='mysql';
    }
}

if (isset($upgrade_info['db']) && !empty($upgrade_info['db'])) {
    file_put_contents("upgrade_info.php", "<?php\nreturn ".var_export($upgrade_info, true).";\n?>");

    $GLOBALS["database_info"]=$upgrade_info['db'];

    include('functions/database/load.php');
    try {
        DB::connect();
        $redirect = 'start';
    } catch (PDOException $exception) {
        $system_message = 'Error : Invalid Database Credentials.';
    }
} else {
    unlink('upgrade_info.php');
    $system_message = 'Error : Database Verification Failed.<br>';
    $system_message .= 'Reloading Process.';
    $redirect = 'initial';
}
