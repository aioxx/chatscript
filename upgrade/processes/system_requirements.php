<?php

$system_message = 'Verifying Database';
$grupo_version = 0;
$redirect = null;
$missing_extensions='';

if (!file_exists('upgrade_info.php')) {
    if (file_exists('../door') && file_exists('../key') && file_exists('../knob')) {
        $grupo_version = 2;
    } elseif (file_exists('../fns') && file_exists('../include') && file_exists('../pages')) {
        $grupo_version = 3;
    }

    if (empty($grupo_version)) {
        $system_message= 'Error : Unable to identify your current Grupo Version. <br>';
        $system_message.= 'Please make sure you have uploaded "upgrade" folder in the correct path.';
    } else {

      if (!extension_loaded('zip')) {
          $missing_extensions .= ' PHP Zip extension, ';
      }

      if (!extension_loaded('gd')) {
          $missing_extensions .= ' PHP GD extension, ';
      }

      if (!extension_loaded('pdo_mysql')) {
          $missing_extensions .= ' PHP PDO MySQL extension ';
      }

        if (version_compare(PHP_VERSION, '7.4') >= 0) {
            if (empty($missing_extensions)) {
                if (is_writable('../door') && is_writable('upgrade_image') || is_writable('../fns') && is_writable('upgrade_image')) {
                    $upgrade_info["grupo_version"] = $grupo_version;
                    file_put_contents("upgrade_info.php", "<?php\nreturn ".var_export($upgrade_info, true).";\n?>");

                    $redirect = 'verify_database';
                } else {
                    $system_message= 'Error : Unable to read/write files. <br>';
                    $system_message.= 'Kindly check folder permssions & ownership.';
                }
            } else {
                $system_message= 'Error : System Requirements Not Met. <br>';
                $system_message.= 'Missing PHP Extension(s) : '.rtrim($missing_extensions,", ");
            }
        } else {
            $system_message= 'Error : Requires PHP 7.4 or higher.';
        }
    }
} else {
    $redirect = 'verify_database';
}
