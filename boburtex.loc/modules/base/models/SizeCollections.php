<?php

namespace app\modules\base\models;

use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "size_collections".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $type
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property string $sizeNames
 * @property array $sizeType
 * @property SizeColRelSize[] $sizeColRelSizes
 */
class SizeCollections extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'size_collections';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'type', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
            'type' => Yii::t('app', 'Type'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getSizeColRelSizes()
    {
        return $this->hasMany(SizeColRelSize::className(), ['sc_id' => 'id']);
    }
    public function getSizeType(){
        $sizes = \app\models\SizeType::find()->with(['sizes'=>function($query){
            $query->select(['size.id','size.name','size.size_type_id']);
        }])->asArray()->orderBy(['name' => SORT_ASC])->all();
        $list = [];
        if(!empty($sizes)){
            foreach ($sizes as $item) {
                $list['list'][$item['id']] = $item['name'];
                $list['size'][$item['id']]['data-size'] = $item['sizes'];
            }
        }
        return $list;
    }
    public function getSizes($id=null){
        $sizes = Size::find()->with('sizeType');
        $size_type = SizeType::findOne($id);
        if($id&&$size_type){
            $sizes = $sizes->where(['size_type_id'=>$id]);
        }
        $sizes = $sizes->asArray()->orderBy(['name' => SORT_ASC])->all();
        return ArrayHelper::map($sizes,'id','name', 'sizeType.name');
    }
    public function getSizeList($array=false,$is_order=false){
        $sizes = SizeColRelSize::find()->select('size_id')->joinWith(['size'])->where(['sc_id' => $this->id])->asArray()->orderBy(['size.order'=>SORT_ASC])->all();
        if($array){
            return $sizes;
        }
        if($is_order){
            $list = [];
            if(!empty($sizes)){
                foreach ($sizes as $size) {
                    $list[] = [
                        'id' => $size['size']['id'],
                        'name' => $size['size']['name']
                    ];
                }
            }
            return $list;
        }
        $sizeList = ArrayHelper::map($sizes,'size_id', 'size.name');
        return json_encode($sizeList);
    }
    public function getSizeNames(){
        $sc = SizeColRelSize::find()->with(['size'])->where(['sc_id' => $this->id])->asArray()->all();
        $result = "";
        foreach ($sc as $item){
            $result .= "<span class='sc--sizes'>{$item['size']['name']}</span>";
        }
        return $result;
    }
    public static function getSizeCollectionList($option = false)
    {
        $sc = SizeCollections::find()->select(['id', 'name'])->all();
        $sc_option = [];
        if ($option) {
            if ($sc) {
                foreach ($sc as $item) {
                    $sc_option[$item['id']] = [
                        'data-size-list' => $item->sizeList
                    ];
                }
                return $sc_option;
            }
        }
        return ArrayHelper::map($sc, 'id', 'name');
    }
}
