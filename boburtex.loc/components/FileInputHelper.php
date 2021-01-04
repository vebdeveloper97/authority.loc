<?php


namespace app\components;


use Yii;

class FileInputHelper
{

    /**
     * Kartik file input uchun pluginOptions ga config qaytaradi
     * @param $fileAttachments
     * @return array
     */
    public static function getInitialPreviewAndConfig($fileAttachments,$bool=null) {
        if (!is_array($fileAttachments)) {
            return [];
        }

        $fileTypes = [
            'xls' => 'office',
            'xlsx' => 'office',
            'doc' => 'office',
            'docx' => 'office',
            'ppt' => 'office',
            'pdf' => 'pdf',
            'txt' => 'text',
            'jpg' => 'image',
            'jpeg' => 'image',
            'png' => 'image',
            'gif' => 'image',
        ];

        $config = [];
        $cnt = 1;

        if($bool !== null){
            foreach ($fileAttachments as $fileAttachment) {
                $config['initialPreview'][] = '/'.$fileAttachment['path'];
                if ($fileAttachment['extension'])
                    $config['initialPreviewConfig'][] = [
                        'type' => $fileTypes[$fileAttachment['extension']],
                        'size' => $fileAttachment['size'],
                        'caption' => $fileAttachment['name'],
                        'url' => '/'.$fileAttachment['path'],
                        'key' => $cnt,
                        'filename' => $fileAttachment['name'],
                    ];

                $cnt++;
            }
        }
        else{
            foreach ($fileAttachments as $fileAttachment) {
                $config['initialPreview'][] = $fileAttachment['path'];

                if ($fileAttachment['extension'])
                    $config['initialPreviewConfig'][] = [
                        'type' => $fileTypes[$fileAttachment['extension']],
                        'size' => $fileAttachment['size'],
                        'caption' => $fileAttachment['name'],
                        'url' => $fileAttachment['path'],
                        'key' => $cnt,
                        'filename' => $fileAttachment['name'],
                    ];

                $cnt++;
            }
        }

        return  $config;
    }
}