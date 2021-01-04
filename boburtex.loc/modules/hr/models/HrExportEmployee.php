<?php


namespace app\modules\hr\models;


use app\components\Util;
use yii\base\Model;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

class HrExportEmployee extends Model
{
    public $file;

    public function rules()
    {
        return [
            [['file'], 'required'],
            ['file', 'file', 'skipOnEmpty' => true, 'extensions' => 'xlsx']
        ];
    }

    public function attributeLabels()
    {
        return [
            'file' => 'Excel File'
        ];
    }

    public function upload($data)
    {
        if ($this->validate() && !empty($data)) {
            $name = Util::generateRandomString(10);
            $this->file->saveAs('excel/' .$name. '.' . $this->file->extension);
            return true;
        } else {
            return false;
        }
    }
}