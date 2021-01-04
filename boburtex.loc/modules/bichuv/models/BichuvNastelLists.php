<?php

namespace app\modules\bichuv\models;

use app\modules\wms\models\WmsDocument;
use Yii;

/**
 * This is the model class for table "bichuv_nastel_lists".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property WmsDocument[] $wmsDocuments
 */
class BichuvNastelLists extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_nastel_lists';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['name'], 'unique'],
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
    public function getWmsDocuments()
    {
        return $this->hasMany(WmsDocument::className(), ['bichuv_nastel_list_id' => 'id']);
    }

    /**
     * @return int|string
     */
    public static function lastNastel(){
        $nastel = self::find()->select('id')->asArray()->orderBy(['id' => SORT_DESC])->one();
        $index = 1;
        if(!empty($nastel)){
            return $nastel['name'];
        }
        $m = date('m');
        $y = date('y');
        return "{$m}{$y}-{$index}";
    }
    /**
     * @return int|string
     */
    public static function newId(){
        $nastel = self::find()->select('id')->asArray()->orderBy(['id' => SORT_DESC])->one();
        $index = 1;
        if(!empty($nastel)){
            $index = $nastel['id'] + 1;
        }
        $m = date('m');
        $y = date('y');
        $model = new self();
        $model->name = "{$m}{$y}-{$index}";
        if($model->save()){
            return $model->id;
        }
        return null;
    }

    public static function getNastelNoById($id) {
        if (empty($id)) {
            return null;
        }

        return static::find()
            ->select('name')
            ->andWhere(['id' => $id])
            ->scalar();
    }
}
