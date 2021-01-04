<?php

namespace app\modules\toquv\models;

use app\modules\boyoq\models\ColorPantone;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%toquv_aks_model_item}}".
 *
 * @property int $id
 * @property string $name
 * @property int $toquv_aks_model_id
 * @property double $indeks
 * @property double $height
 * @property int $toquv_ne_id
 * @property int $toquv_thread_id
 * @property int $toquv_ip_color_id
 * @property int $color_pantone_id
 * @property int $color_boyoq_id
 * @property double $height_sm
 * @property double $percentage
 * @property double $parent_percentage
 * @property int $ip_id
 *
 * @property ToquvAksModel $toquvAksModel
 */
class ToquvAksModelItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%toquv_aks_model_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toquv_aks_model_id', 'toquv_ne_id', 'toquv_thread_id', 'toquv_ip_color_id', 'color_pantone_id', 'color_boyoq_id', 'ip_id'], 'integer'],
            [['indeks', 'height', 'height_sm', 'percentage', 'parent_percentage'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['toquv_aks_model_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvAksModel::className(), 'targetAttribute' => ['toquv_aks_model_id' => 'id']],
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
            'toquv_aks_model_id' => Yii::t('app', 'Toquv Aks Model ID'),
            'indeks' => Yii::t('app', 'Indeks'),
            'height' => Yii::t('app', 'Height'),
            'toquv_ne_id' => Yii::t('app', 'Toquv Ne ID'),
            'toquv_thread_id' => Yii::t('app', 'Toquv Thread ID'),
            'toquv_ip_color_id' => Yii::t('app', 'Toquv Ip Color ID'),
            'color_pantone_id' => Yii::t('app', 'Color Pantone ID'),
            'color_boyoq_id' => Yii::t('app', 'Color Boyoq ID'),
            'height_sm' => Yii::t('app', 'Height Sm'),
            'percentage' => Yii::t('app', 'Percentage'),
            'parent_percentage' => Yii::t('app', 'Parent Percentage'),
            'ip_id' => Yii::t('app', 'Ip ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvAksModel()
    {
        return $this->hasOne(ToquvAksModel::className(), ['id' => 'toquv_aks_model_id']);
    }
    public function getNe()
    {
        return $this->hasOne(ToquvNe::className(), ['id' => 'toquv_ne_id']);
    }
    public function getThread()
    {
        return $this->hasOne(ToquvThread::className(), ['id' => 'toquv_thread_id']);
    }
    public function getIpColor()
    {
        return $this->hasOne(ToquvIpColor::className(), ['id' => 'toquv_ip_color_id']);
    }
    public function getColorPantone()
    {
        return $this->hasOne(ColorPantone::className(), ['id' => 'color_pantone_id']);
    }
    public function getList($type = 'mato')
    {
        if(!empty($this->id)){
            switch ($type) {
                case 'ne':
                    $sql = "select ne.id, ne.name from toquv_aks_model_item tami  
		                           left join toquv_ne ne on tami.toquv_ne_id = ne.id";
                    break;
                case 'thread':
                    $sql = "select th.id, th.name from toquv_aks_model_item tami  
		                           left join toquv_thread th on tami.toquv_thread_id = th.id 
		                   ";
                    break;
                case 'color':
                    $sql = "select tip.id, tip.name from toquv_aks_model_item tami  
		                           left join toquv_ip_color tip on tami.toquv_ip_color_id = tip.id 
		                   ";
                    break;
            }
            $sql .= " WHERE tami.id = {$this->id}";
            $res = Yii::$app->db->createCommand($sql)->queryAll();
            return ArrayHelper::map($res, 'id', 'name');
        }
        return [];
    }
}
