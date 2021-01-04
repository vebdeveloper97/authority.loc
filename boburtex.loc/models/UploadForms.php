<?php
namespace app\models;

use app\components\Util;
use app\modules\base\models\Attachments;
use app\modules\base\models\ModelRelAttach;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class UploadForms extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $files;
    public $sketch;
    public $images;
    public $comment_attachments;
    public function rules()
    {
        return [
            [['files','comment_attachments'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif, doc, docx, xls, xlsx, xlsb, xlsm, pdf, ppt', 'maxFiles' => 20, 'maxSize' => 1024*1024],
            [['images','sketch'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif', 'maxFiles' => 20, 'maxSize' => 1024*1024*5],
        ];
    }

    public function upload($dir=null)
    {
        if ($this->validate()) {
            $directory = ($dir!=null)?'uploads' . "/" . $dir . "/":'uploads' . "/";
            if (!is_dir($directory)) {
                FileHelper::createDirectory($directory);
            }
            foreach ($this->images as $file) {
                $name = $directory.Util::generateRandomString() . '.' . $file->extension;
                $file->saveAs($name);
                $attachments = new Attachments();
                $attachments->setAttributes([
                    'name' => $file->name,
                    'size' => $file->size,
                    'extension' => $file->extension,
                    'path' => $name,
                ]);
                $attachments->save();
            }
            $array = explode(',',$_POST['initialPreview']);
            $chunkIndex = count($array);
            return [
                'chunkIndex' => $chunkIndex,         // the chunk index processed
                'initialPreview' => "/web/".$name, // the thumbnail preview data (e.g. image)
                'initialPreviewConfig' => [
                    [
                        'type' => 'image',      // check previewTypes (set it to 'other' if you want no content preview)
                        'caption' => "/web/".$name, // caption
                        'key' => $attachments['id'],       // keys for deleting/reorganizing preview
                        'fileId' => $attachments['id'],    // file identifier
                        'size' => $attachments['size'],    // file size
                        'zoomData' => "/web/".$name, // separate larger zoom data
                    ]
                ],
                'append' => true
            ];
        } else {
            return 'error';
        }
    }
    public function uploadFile($dir=null)
    {
        if (true) {
            $directory = ($dir!=null)?'uploads' . "/" . $dir . "/":'uploads' . "/";
            if (!is_dir($directory)) {
                FileHelper::createDirectory($directory);
            }
            foreach ($this->files as $file) {
                $name = $directory.Util::generateRandomString() . '.' . $file->extension;
                $file->saveAs($name);
                $attachments = new Attachments();
                $attachments->setAttributes([
                    'name' => $file->name,
                    'size' => $file->size,
                    'extension' => $file->extension,
                    'path' => $name,
                ]);
                $attachments->save();
            }
            return $attachments['id'];
        } else {
            return false;
        }
    }
    public function uploadAjax($dir=null)
    {
        $directory = ($dir!=null)?'uploads' . "/" . $dir . "/":'uploads' . "/";
        if (!is_dir($directory)) {
            FileHelper::createDirectory($directory);
        }
        $name = $directory.Util::generateRandomString() . '.' . $this->file->extension;
        $this->file->saveAs($name);
        $attachments = new Attachments();
        $attachments->setAttributes([
            'name' => $this->file->name,
            'size' => $this->file->size,
            'extension' => $this->file->extension,
            'path' => $name,
        ]);
        $attachments->save();
        return $attachments['id'];
    }
}