<?php
namespace app\models;

use app\components\Util;
use app\modules\base\models\Attachments;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    const SCENARIO_UPLOAD_IMAGE = 'upload-image';
    /**
     * @var UploadedFile[]
     */
    public $file;
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public $uploadPath = 'uploads/';

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif, doc, docx, xls, xlsx, pdf, ppt,', 'maxFiles' => 20, 'maxSize' => 1024*1024],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024*1024, 'on' => [self::SCENARIO_UPLOAD_IMAGE]],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $name = Util::generateRandomString() . '.' . $this->file->extension;
            if($this->file->saveAs('uploads/' . $name))
                return $name;
        } else {
            return false;
        }
    }

    public function uploadMultiple() {
        if ($this->validate()) {
            /** @var UploadedFile $f */
            $returnInfo = [];
            foreach ($this->file as $f) {
                $name = Util::generateRandomString() . '_' . date('d_m_Y') . '.' . $f->extension;
                if (!$f->saveAs('uploads/' . $name)){
                    return false;
                }
                $returnInfo[] = [
                    'type' => 1,
                    'name' => $f->name,
                    'extension' => $f->extension,
                    'size' => $f->size,
                    'path' => '/uploads/' . $name,
                ];
            }
            return $returnInfo;
        }

        return false;
    }

    public function uploadImage() {
        if ($this->validate()) {
            $name = Util::generateRandomString() . '_' . date('d_m_Y') . '.' . $this->imageFile->extension;
            if (!$this->imageFile->saveAs($this->uploadPath . $name)){
                return false;
            }
            return [
                'type' => 2,
                'name' => $this->imageFile->name,
                'extension' => $this->imageFile->extension,
                'size' => $this->imageFile->size,
                'path' => $this->uploadPath . $name,
            ];
        }

        return false;
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