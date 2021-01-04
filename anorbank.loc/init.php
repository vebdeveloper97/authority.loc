<?php

require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

Yii::setAlias('@root', __DIR__);

/**
 * generate app config files
 */
generateLocalFiles('@root/app/config');

/**
 * generate backend config files
 */
generateLocalFiles('@root/backend/config');

/**
 * generate console config files
 */
generateLocalFiles('@root/console/config');

/**
 * generate common config files
 */
generateLocalFiles('@root/common/config', ['main-local.php', 'params-local.php', 'db-local.php']);

function generateLocalFiles($config_directory_alias, $local_files_name = ['main-local.php', 'params-local.php'])
{
    $files = array_flip(array_diff(scandir(Yii::getAlias($config_directory_alias)), ['..', '.']));

    foreach ($local_files_name as $file_name) {
        if (array_key_exists($file_name, $files)) {
            continue;
        }

        $data = "<?php\n\nreturn [];";

        file_put_contents(Yii::getAlias($config_directory_alias . '/' . $file_name), $data);
    }
}
