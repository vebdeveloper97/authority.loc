<?php

namespace app\modules\base\models;

use app\components\Util;
use Yii;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "{{%model_mini_postal}}".
 *
 * @property int $id
 * @property int $models_list_id
 * @property string $name
 * @property int $users_id
 * @property double $eni
 * @property double $uzunligi
 * @property double $samaradorlik
 * @property int $type Turi
 * @property int $count_items Elementlar soni
 * @property int $total_patterns Lekalalar soni
 * @property int $total_patterns_loid Lekala qismlari soni
 * @property double $specific_weight O‘ziga xos og`irlik
 * @property double $total_weight Umumiy og`irlik
 * @property double $used_weight Ishlatilgan og`irlik
 * @property double $lossed_weight Yo`qotilgan og`irlik
 * @property int $size_collection_id
 * @property string $cost_surface
 * @property string $cost_weight Ishlatilgan og`irlik
 * @property string $loss_surface Yo`qotishlar yuzasi
 * @property string $loss_weight Yo`qotishlar og`irligi
 * @property string $spent_surface
 * @property string $spent_weight Sarflangan og`irlik
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ModelsList $modelsList
 * @property ModelMiniPostalFiles[] $modelMiniPostalFiles
 * @property ModelMiniPostalSizes[] $modelMiniPostalSizes
 */
class ModelMiniPostal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%model_mini_postal}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['models_list_id', 'users_id', 'type', 'count_items', 'total_patterns', 'total_patterns_loid', 'size_collection_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['eni', 'uzunligi', 'samaradorlik', 'specific_weight', 'total_weight', 'used_weight', 'lossed_weight', 'cost_surface', 'cost_weight', 'loss_surface', 'loss_weight', 'spent_surface', 'spent_weight'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['models_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['models_list_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'models_list_id' => Yii::t('app', 'Models List ID'),
            'name' => Yii::t('app', 'Name'),
            'users_id' => Yii::t('app', 'Users ID'),
            'eni' => Yii::t('app', 'Eni'),
            'uzunligi' => Yii::t('app', 'Uzunligi'),
            'samaradorlik' => Yii::t('app', 'Samaradorlik'),
            'type' => Yii::t('app', 'Turi'),
            'count_items' => Yii::t('app', 'Elementlar soni'),
            'total_patterns' => Yii::t('app', 'Lekalalar soni'),
            'total_patterns_loid' => Yii::t('app', 'Lekala qismlari soni'),
            'specific_weight' => Yii::t('app', 'O‘ziga xos og`irlik'),
            'total_weight' => Yii::t('app', 'Umumiy og`irlik'),
            'used_weight' => Yii::t('app', 'Ishlatilgan og`irlik'),
            'lossed_weight' => Yii::t('app', 'Yo`qotilgan og`irlik'),
            'size_collection_id' => Yii::t('app', 'Size Collection'),
            'cost_surface' => Yii::t('app', 'Cost Surface'),
            'cost_weight' => Yii::t('app', 'Ishlatilgan og`irlik'),
            'loss_surface' => Yii::t('app', 'Yo`qotishlar yuzasi'),
            'loss_weight' => Yii::t('app', 'Yo`qotishlar og`irligi'),
            'spent_surface' => Yii::t('app', 'Spent Surface'),
            'spent_weight' => Yii::t('app', 'Sarflangan og`irlik'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsList()
    {
        return $this->hasOne(ModelsList::className(), ['id' => 'models_list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelMiniPostalFiles()
    {
        return $this->hasMany(ModelMiniPostalFiles::className(), ['model_mini_postal_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelMiniPostalSizes()
    {
        return $this->hasMany(ModelMiniPostalSizes::className(), ['model_mini_postal_id' => 'id']);
    }
    public function uploadFiles($files,$dir=null)
    {
        $saved = false;
        $result = [];
        $result['errors'] = [];
        $result['status'] = 0;
        $result['message'] = Yii::t('app', 'Xatolik yuz berdi!');
        if (!empty($files)) {
            $directory = ($dir!=null)?'uploads' . "/" . $dir . "/":'uploads' . "/";
            if (!is_dir($directory)) {
                FileHelper::createDirectory($directory);
            }
            $i = 0;
            $ismain = ModelMiniPostalFiles::find()->where(['model_mini_postal_id'=>$this->id,'isMain'=>1])->one();
            foreach ($files as $file) {
                $name = $directory.Util::generateRandomString() . '.' . $file->extension;
                $type = explode("/",$file->type);
                $item = new ModelMiniPostalFiles();
                $item->setAttributes([
                    'model_mini_postal_id' => $this->id,
                    'name' => $file->name,
                    'size' => $file->size,
                    'extension' => $file->extension,
                    'type' => $type[0],
                    'path' => $name,
                    'isMain' => (!$ismain && $i==0)?1:0
                ]);
                if($item->save()){
                    $file->saveAs($name);
                    $saved = true;
                    $i++;
                }else{
                    if ( $item->hasErrors() ) {
                        $error = $item->getErrors();
                        $result['errors'][] = $error;
                    }
                    $saved = false;
                    break;
                }
            }
            if($saved){
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'Saved Successfully');
            }
            return $result;
        } else {
            return 'error';
        }
    }
    public function saveSizes($data)
    {
        $saved = false;
        $result = [];
        $result['errors'] = [];
        $result['status'] = 0;
        $result['message'] = Yii::t('app', 'Xatolik yuz berdi!');
        if (!empty($data)) {
            foreach ($data as $key => $size) {
                $item = new ModelMiniPostalSizes();
                $item->setAttributes([
                    'model_mini_postal_id' => $this->id,
                    'size_id' => $key,
                    'count' => $size['count'],
                    'count_detail' => $size['count_detail'],
                ]);
                if($item->save()){
                    $saved = true;
                }else{
                    if ( $item->hasErrors() ) {
                        $error = $item->getErrors();
                        $result['errors'][] = $error;
                    }
                    $saved = false;
                    break;
                }
            }
            if($saved){
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'Saved Successfully');
            }
            return $result;
        } else {
            return 'error';
        }
    }
}
