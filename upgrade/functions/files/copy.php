<?php

function recurseCopy(
    string $sourceDirectory,
    string $destinationDirectory,
    string $childFolder = ''
): void {
    $directory = opendir($sourceDirectory);

    if (is_dir($destinationDirectory) === false) {
        if (strpos($destinationDirectory, 'backup/upgrade') !== false) {
            return;
        }

        mkdir($destinationDirectory);
    }

    if ($childFolder !== '') {
        if (is_dir("$destinationDirectory/$childFolder") === false) {
            mkdir("$destinationDirectory/$childFolder");
        }

        while (($file = readdir($directory)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (is_dir("$sourceDirectory/$file") === true) {
                recurseCopy("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
            } else {
                if (strpos("$sourceDirectory/$file", 'upgrade_image/image.zip') === false) {
                    copy("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
                }
            }
        }

        closedir($directory);

        return;
    }

    while (($file = readdir($directory)) !== false) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        if (is_dir("$sourceDirectory/$file") === true) {
            recurseCopy("$sourceDirectory/$file", "$destinationDirectory/$file");
        } else {
            if (strpos("$sourceDirectory/$file", 'upgrade_image/image.zip') === false) {
                copy("$sourceDirectory/$file", "$destinationDirectory/$file");
            }
        }
    }

    closedir($directory);
}


function recurseCopy_not_Exists(
    string $sourceDirectory,
    string $destinationDirectory,
    string $childFolder = ''
): void {
    $directory = opendir($sourceDirectory);

    if (is_dir($destinationDirectory) === false) {
        if (strpos($destinationDirectory, 'backup/upgrade') !== false) {
            return;
        }
        if (!file_exists($destinationDirectory)) {
            mkdir($destinationDirectory);
        }
    }

    if ($childFolder !== '') {
        if (is_dir("$destinationDirectory/$childFolder") === false) {
            if (!file_exists("$destinationDirectory/$childFolder")) {
                mkdir("$destinationDirectory/$childFolder");
            }
        }

        while (($file = readdir($directory)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (is_dir("$sourceDirectory/$file") === true) {
                recurseCopy_not_Exists("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
            } else {
                if (strpos("$sourceDirectory/$file", 'upgrade_image/image.zip') === false) {
                    if (!file_exists("$destinationDirectory/$childFolder/$file")) {
                        copy("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
                    }
                }
            }
        }

        closedir($directory);

        return;
    }

    while (($file = readdir($directory)) !== false) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        if (is_dir("$sourceDirectory/$file") === true) {
            recurseCopy_not_Exists("$sourceDirectory/$file", "$destinationDirectory/$file");
        } else {
            if (strpos("$sourceDirectory/$file", 'upgrade_image/image.zip') === false) {
                if (!file_exists("$destinationDirectory/$file")) {
                    copy("$sourceDirectory/$file", "$destinationDirectory/$file");
                }
            }
        }
    }

    closedir($directory);
}
