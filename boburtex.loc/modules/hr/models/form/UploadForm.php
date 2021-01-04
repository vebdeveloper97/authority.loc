<?php
namespace app\modules\hr\models\form;

use app\components\Util;
use app\modules\base\models\Attachments;
use Yii;
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

    public $skipOnEmpty = true;

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif, doc, docx, xls, xlsx, pdf, ppt,', 'maxFiles' => 20, 'maxSize' => 1024*1024],
            [['imageFile'], 'file', 'skipOnEmpty' => $this->skipOnEmpty, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024*1024, 'on' => [self::SCENARIO_UPLOAD_IMAGE]],
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

    public function uploadFile($property = 'imageFile') {
        $fileName = null;

        if (!$this->validate())
        {
            return false;
        }

        $fileName = md5_file($this->$property->tempName) . '.' . $this->$property->extension;

        $pathToFile = $this->filePath
            . '/'
            . substr($fileName, 0, 2)
            . '/'
            . substr($fileName, 2, 2)
            . '/';

        if ( !FileHelper::createDirectory($pathToFile) )
        {
            return false;
        }


        if (is_file($pathToFile . $fileName))
        {
            Yii::debug('File not saved! Because file found this dir.');

            return $fileName;
        }

        if (!$this->$property->saveAs($pathToFile . $fileName)) {
            return false;
        }

        Yii::debug('File saved');

        return $fileName;
    }

    public static function getMd5FilePath (string $imageName) {
        return '/' . substr($imageName, 0 , 2) . '/' . substr($imageName, 2, 2) . '/' . $imageName;
    }
}