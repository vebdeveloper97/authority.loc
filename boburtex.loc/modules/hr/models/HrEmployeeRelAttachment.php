<?php

namespace app\modules\hr\models;

use app\components\Util;
use Yii;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%hr_employee_rel_attachment}}".
 *
 * @property int $id
 * @property int $hr_employee_id
 * @property int $type
 * @property string $name
 * @property int $size
 * @property string $extension
 * @property string $path
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property HrEmployee $hrEmployee
 */
class HrEmployeeRelAttachment extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%hr_employee_rel_attachment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['hr_employee_id', 'type', 'size', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['extension'], 'string', 'max' => 10],
            [['path'], 'string', 'max' => 255],
            [['hr_employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['hr_employee_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hr_employee_id' => 'Hr Employee ID',
            'type' => 'Type',
            'name' => 'Name',
            'size' => 'Size',
            'extension' => 'Extension',
            'path' => 'Path',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployee()
    {
        return $this->hasOne(HrEmployee::className(), ['id' => 'hr_employee_id']);
    }

    // save
    public function getSaves($data, $id)
    {
        if(!empty($data)){
            if (empty($id)/* && !$this->validate()*/) {
                return false;
            }

            $saved = false;
            $model = new HrEmployeeRelAttachment();
            $item = UploadedFile::getInstance($data, 'name');

            $name = Util::generateRandomString(20).'.'. $item->extension;
            $item->saveAs('uploads/' . $name);
            // db save
            $model->status = HrEmployeeRelAttachment::STATUS_ACTIVE;
            $model->hr_employee_id = $id;
            $model->name = $name;
            $model->type = self::EMPLOYEE_AVATAR_TYPE;
            $model->size = $item->size;
            $model->extension = $item->extension;
            $model->path = 'uploads/' . $name;
            if ($model->save()) {
                $saved = true;
                $model = new HrEmployeeRelAttachment();
            }
        }
        else{
            throw new ForbiddenHttpException(Yii::t("Malumotlar bosh"));
        }
    }

    // malumotlarni update qilishda o'chirish
    public static function getRemoveEmployeeId($id)
    {
//        $model = self::find()
//            ->asArray()
//            ->where(['hr_employee_id' => $id])
//            ->all();
//        $saved = false;
//        foreach ($model as $item){
//            $saved = $item->delete();
//        }
//        return true;

        $model = HrEmployeeRelAttachment::deleteAll([
            'hr_employee_id' => $id
        ]);
    }

    public static function getImageUrlsWithTag($pathArray) {
        $result = [];
        if(is_array($pathArray)) {
            foreach ($pathArray as $path) {
                $result[] = Html::img($path);
            }

            return $result;
        }

        return [];
    }
}
