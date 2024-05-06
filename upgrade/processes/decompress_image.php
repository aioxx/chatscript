<?php

$system_message = 'Updating Grupo Files.';
$redirect = 'update_files';

if (file_exists('upgrade_image/image.zip')) {
    if (!file_exists('upgrade_image/fns')) {

        $upgrade_image = 'upgrade_image/image.zip';

        $path = pathinfo(realpath($upgrade_image), PATHINFO_DIRNAME);

        $zip = new ZipArchive();
        $res = $zip->open($upgrade_image);
        if ($res === true) {
            $zip->extractTo($path);
            $zip->close();
        } else {
            $system_message = 'Error : Unable to decompress image file.';
            $redirect = null;
        }
    }
} else {
    $system_message = 'Error : Image File Missing.';
    $redirect = null;
}
