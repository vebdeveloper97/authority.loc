<?php

namespace app\models;

use app\modules\hr\models\BaseModel;
use app\modules\hr\models\HrDepartments;
use app\modules\hr\models\HrDepartmentsAttachments;
use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "attachments".
 *
 * @property int $id
 * @property string $name
 * @property string $md5_hash
 * @property int $size
 * @property string $extension
 * @property string $path
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property HrDepartmentsAttachments[] $hrDepartmentsAttachments
 * @property HrDepartments[] $hrDepartments
 */
class Attachments extends BaseModel
{
    const SCENARIO_UPLOAD_IMAGE = 'upload-image';
    const SCENARIO_UPLOAD_DOCUMENT = 'upload-document';

    const TYPE_IMAGE = 'image';
    const TYPE_DOCUMENT = 'document';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attachments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['size', 'status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['name', 'path'], 'string', 'max' => 255],
            [['md5_hash'], 'string', 'max' => 32],
            [['extension'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'md5_hash' => Yii::t('app', 'Md5 Hash'),
            'size' => Yii::t('app', 'Size'),
            'extension' => Yii::t('app', 'Extension'),
            'path' => Yii::t('app', 'Path'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrDepartmentsAttachments()
    {
        return $this->hasMany(HrDepartmentsAttachments::className(), ['attachments_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrDepartments()
    {
        return $this->hasMany(HrDepartments::className(), ['id' => 'hr_departments_id'])->viaTable('hr_departments_attachments', ['attachments_id' => 'id']);
    }

    protected function saveModel($filename, $md5_hash, $id = null) {
        $model = new static();

        if ($id !== null) {
            $model = static::findOne(['id' => $id]);
        }

        $model->name = $this->file->baseName . '.' . $this->file->extension;
        $model->md5_hash = $md5_hash;
        $model->size = $this->file->size;
        $model->extension = $this->file->extension;
        $model->path = self::getMd5FilePath($filename);
        $model->status = 1;
        $success = $model->save();

        if (!$success) {
            Yii::debug($model->getErrors(), 'fileModel errors');
        }

        return $success;
    }

    public static function getMd5FilePath (string $imageName) {
        return '/' . substr($imageName, 0 , 2) . '/' . substr($imageName, 2, 2) . '/' . $imageName;
    }

    protected function getFileDir() {
        $_fileDir = '/uploads';
        switch($this->scenario) {
            case self::SCENARIO_UPLOAD_IMAGE:
                $this->fileDir .= '/hr/images';
                break;
            case self::SCENARIO_UPLOAD_DOCUMENT:
                $this->fileDir .= '/hr/documents';
                break;
        }

        return $_fileDir;
    }
}
