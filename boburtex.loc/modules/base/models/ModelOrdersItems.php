<?php

namespace app\modules\base\models;

use app\models\ColorPantone;
use app\models\PulBirligi;
use app\modules\bichuv\models\BichuvMatoOrders;
use app\modules\tikuv\models\TikuvOutcomeProductsPack;
use app\modules\toquv\models\Musteri;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\toquv\models\ToquvOrders;
use app\modules\toquv\models\ToquvRawMaterials;
use app\modules\toquv\models\ToquvRmOrder;
use app\modules\toquv\models\ToquvRmOrderItems;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use app\modules\wms\models\WmsColor;

/**
 * This is the model class for table "model_orders_items".
 *
 * @property int $id
 * @property int $model_orders_id
 * @property int $models_list_id
 * @property int $model_var_id
 * @property string $add_info
 * @property string $load_date
 * @property int $priority
 * @property string $season
 * @property int $baski_id
 * @property int $prints_id
 * @property int $stone_id
 * @property int $percentage
 * @property string $price
 * @property int $pb_id
 * @property int $status
 * @property double $prepayment@property string $finish_en
 * @property string $finish_gramaj
 * @property int $brend_id
 * @property int $size_collections_id
 *
 * @property Brend $brend
 * @property ModelOrderItemsBaski[] $modelOrderItemsBaskis
 * @property ModelOrderItemsPrints[] $modelOrderItemsPrints
 * @property ModelOrderItemsStone[] $modelOrderItemsStones
 * @property ModelOrders $modelOrders
 * @property ModelsVariations $modelVar
 * @property ModelsList $modelsList
 * @property SizeCollections $sizeCollections
 * @property ModelOrdersItemsAcs[] $modelOrdersItemsAcs
 * @property ModelOrdersItemsToquvAcs[] $modelOrdersItemsToquvAcs
 * @property ModelOrdersItemsChanges[] $modelOrdersItemsChanges
 * @property ModelOrdersItemsSize[] $modelOrdersItemsSizes
 * @property ModelOrdersPlanning[] $modelOrdersPlannings
 * @property ModelOrdersItemsVariations[] $modelOrdersItemsVariations
 * @property Attachments[] $attachments
 * @property ModelOrdersPlanning[] $planningMato
 * @property MoiRelDept[] $moiRelDepts
 * @property TikuvOutcomeProductsPack[] $tikuvOutcomeProductsPacks
 * @property BichuvMatoOrders[] $bichuvMatoOrders
 * @property ModelVarBaski $baski
 * @property ModelVarPrints $prints
 * @property ModelVarStone $stone
 * @property mixed $planningAks
 * @property array $deptVal
 * @property mixed $pb
 * @property string $stoneList
 * @property bool|string $sizeType
 * @property string $info
 * @property int $allCount
 * @property string $printList
 * @property bool $toquvAks
 * @property array $modelList
 * @property bool $colorName
 * @property string $finish_en [varchar(30)]
 * @property null $rotatsion
 * @property ActiveQuery $modelOrderItemsRotatsions
 * @property int $rotatsion_id [int(11)]
 * @property int $model_orders_variations_id [int]
 * @property int $created_at [int]
 * @property int $updated_at [int]
 * @property int $created_by [int]
 * @property int $updated_by [int]
 * @property string $files [char(255)]
 * @property string $url [char(255)]
 * @property string $extension [char(100)]
 * @property string $min_price_sum [decimal(20,3)]
 * @property string $max_price_sum [decimal(20,3)]
 * @property string $models_list_info
 * @property string $model_var_info
 * @property-read ActiveQuery $modelOrdersNaqsh
 * @property-read mixed $modelOrdersAttachmentRelations
 * @property-read mixed $modelOrdersItemsRel
 * @property-read mixed $modelOrdersItemsMaterial
 * @property-read mixed $modelOrdersItemsPechat
 * @property int $assorti_count [int]
 * @property string $price_add_info
 * @property-read null $childSizeItem
 * @property int $sum_item_qty [int]
 */
class ModelOrdersItems extends BaseModel
{
    public $size_type;
    public $rm_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%model_orders_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
//            [['model_var_id'], 'required'],
            [['created_at', 'created_by', 'updated_at', 'model_orders_variations_id', 'updated_by', 'model_orders_id', 'models_list_id', 'model_var_id', 'priority', 'baski_id', 'prints_id', 'stone_id', 'percentage', 'pb_id', 'status', 'brend_id', 'size_collections_id', 'assorti_count', 'sum_item_qty'], 'integer'],
//            ['model_var_info', 'required', 'when' => function($model, $attribute){
//                return empty($model->model_var_id);
//            }
//                ],
            [['add_info', 'models_list_info', 'model_var_info', 'price_add_info'], 'string'],
            [['files'], 'file', 'maxFiles' => 20],
            [['rm_id'], 'safe'],
            [['load_date'], 'date', 'format' => 'php: d.m.Y'],
            [['price', 'min_price_sum', 'max_price_sum'], 'number'],
            [['extension'], 'string', 'max' => 100],
            [['prepayment'], 'number', 'max' => 100],
            [['season', 'url'], 'string', 'max' => 255],
            [['finish_en', 'finish_gramaj'], 'string', 'max' => 30],
            [['brend_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brend::className(), 'targetAttribute' => ['brend_id' => 'id']],
            [['model_orders_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrders::className(), 'targetAttribute' => ['model_orders_id' => 'id']],
            [['model_var_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsVariations::className(), 'targetAttribute' => ['model_var_id' => 'id']],
            [['models_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['models_list_id' => 'id']],
            [['size_collections_id'], 'exist', 'skipOnError' => true, 'targetClass' => SizeCollections::className(), 'targetAttribute' => ['size_collections_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_orders_id' => Yii::t('app', 'Model Orders ID'),
            'models_list_id' => Yii::t('app', 'Models List ID'),
            'models_list_info' => Yii::t('app', 'Models List Info'),
            'model_var_id' => Yii::t('app', 'Model Var ID'),
            'model_var_info' => Yii::t('app', 'Model Var Info'),
            'add_info' => Yii::t('app', 'Add Info'),
            'min_price_sum' => Yii::t('app', 'min_price_sum'),
            'max_price_sum' => Yii::t('app', 'max_price_sum'),
            'load_date' => Yii::t('app', 'Load Date'),
            'priority' => Yii::t('app', 'Priority'),
            'season' => Yii::t('app', 'Season'),
            'baski_id' => Yii::t('app', 'Baski ID'),
            'prints_id' => Yii::t('app', 'Prints ID'),
            'stone_id' => Yii::t('app', 'Stone ID'),
            'percentage' => Yii::t('app', 'Percentage'),
            'price' => Yii::t('app', 'Price'),
            'pb_id' => Yii::t('app', 'Pb ID'),
            'status' => Yii::t('app', 'Status'),
            'prepayment' => Yii::t('app', "Predoplata %"),
            'finish_en' => Yii::t('app', 'Finish En'),
            'finish_gramaj' => Yii::t('app', 'Finish Gramaj'),
            'brend_id' => Yii::t('app', 'Brend ID'),
            'size_collections_id' => Yii::t('app', 'Size Collections ID'),
            'price_add_info' => Yii::t('app', 'Price Add Info'),
            'sum_item_qty' => Yii::t('app', 'Umumiy ish soni')
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            if(!empty($this->load_date)){
                $this->load_date =  date('Y-m-d', strtotime($this->load_date));
            }
            return true;
        }else{
            return false;
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->load_date = date('d.m.Y', strtotime($this->load_date));

    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public function getPriorityList($key = null){
        $list = [
            1 => Yii::t('app','Low'),
            2 => Yii::t('app','Normal'),
            3 => Yii::t('app','High'),
            4 => Yii::t('app','Urgent')
        ];
        $options = [
            1 => ['style'=>'background:#ccc;color:white;padding:2px;font-weight:bold'],
            2 => ['style' => 'background:green;color:white;padding:2px;font-weight:bold'],
            3 => ['style' => 'background:#CC7722;color:white;padding:2px;font-weight:bold'],
            4 => ['style' => 'background:red;color:white;padding:2px;font-weight:bold'],
        ];
        if($key && $key != 'options'){
            return $list[$key];
        }
        if($key && $key == 'options'){
            return $options;
        }
        return $list;
    }
    /**
     * @return ActiveQuery
     */
    public function getBrend()
    {
        return $this->hasOne(Brend::className(), ['id' => 'brend_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getSizeCollections()
    {
        return $this->hasOne(SizeCollections::className(), ['id' => 'size_collections_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getModelOrdersItemsAcs()
    {
        return $this->hasMany(ModelOrdersItemsAcs::className(), ['model_orders_items_id' => 'id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getModelOrdersItemsToquvAcs()
    {
        return $this->hasMany(ModelOrdersItemsToquvAcs::class, ['model_orders_items_id' => 'id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getModelOrderItemsPrints()
    {
        return $this->hasMany(ModelOrderItemsPrints::className(), ['model_orders_items_id' => 'id']);
    }

    public function getModelOrdersItemsMaterial()
    {
        return $this->hasMany(ModelOrdersItemsMaterial::className(), ['model_orders_items_id' => 'id'])->joinWith('wmsMatoInfo');
    }

    public function getModelOrdersAttachmentRelations()
    {
        return $this->hasMany(ModelOrdersAttachmentRelations::class, ['model_orders_items_id' => 'id'])->joinWith('attachments');
    }

    public function getModelOrdersItemsPechat()
    {
        return $this->hasMany(ModelOrdersItemsPechat::className(), ['model_orders_items_id' => 'id'])->joinWith('attachment');
    }
    /**
     * @return ActiveQuery
     */
    public function getModelOrderItemsBaskis()
    {
        return $this->hasMany(ModelOrderItemsBaski::className(), ['model_orders_items_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelOrderItemsRotatsions()
    {
        return $this->hasMany(ModelOrderItemsRotatsion::className(), ['model_orders_items_id' => 'id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getModelOrderItemsStones()
    {
        return $this->hasMany(ModelOrderItemsStone::className(), ['model_orders_items_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     * */
    public function getModelOrdersNaqsh()
    {
        return $this->hasMany(ModelOrdersNaqsh::class,
            [
                'model_orders_items_id' => 'id'
            ]);
    }
    /**
     * @return ActiveQuery
     */
    public function getModelOrders()
    {
        return $this->hasOne(ModelOrders::className(), ['id' => 'model_orders_id']);
    }
    public function getModelOrdersItemsChanges()
    {
        return $this->hasMany(ModelOrdersItemsChanges::className(), ['model_orders_items_id' => 'id']);
    }
    public function getBichuvMatoOrders()
    {
        return $this->hasMany(BichuvMatoOrders::className(), ['model_orders_items_id' => 'id']);
    }

    public function getModelOrdersItemsRel()
    {
        return $this->hasMany(ModelOrdersAttachmentRelations::class, ['model_orders_items_id' => 'id']);
    }

    public function getAttachments() {
        return $this->hasMany(Attachments::class, ['id'=> 'attachments_id'])
            ->viaTable('model_orders_attachment_relations', ['model_orders_items_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelVar()
    {
        return $this->hasOne(ModelsVariations::className(), ['id' => 'model_var_id']);
    }
    public function getBaski()
    {
        /*return $this->hasOne(ModelVarBaski::className(), ['id' => 'baski_id']);*/
        $sql = "SELECT GROUP_CONCAT(IF(a.path!='',CONCAT('<span class=\"thumbnail text-center no-margin\"><img class=\"imgPreview imgSize\" src=\"/web/',a.path,'\">',mvb.name,' (',mvb.code,')</span>'), CONCAT('<span class=\"thumbnail text-center no-margin\">',mvb.name,' (',mvb.code,')</span>')) SEPARATOR ' ') res FROM model_order_items_baski moib
                    LEFT JOIN model_orders_items moi on moib.model_orders_items_id = moi.id
                    LEFT JOIN model_var_baski mvb on moib.model_var_baski_id = mvb.id
                    LEFT JOIN model_var_baski_rel_attach mvbra on mvb.id = mvbra.model_var_baski_id
                    LEFT JOIN attachments a on mvbra.attachment_id = a.id
                    WHERE moib.model_orders_items_id = {$this->id} AND (mvbra.is_main = 1 OR mvbra.is_main) GROUP BY moi.id
                ";
        $results = Yii::$app->db->createCommand($sql)->queryScalar();
        if($results){
            return $results;
        }
        return null;
    }
    public function getRotatsion()
    {
        /*return $this->hasOne(ModelVarBaski::className(), ['id' => 'baski_id']);*/
        $sql = "SELECT GROUP_CONCAT(IF(a.path!='',CONCAT('<span class=\"thumbnail text-center no-margin\"><img class=\"imgPreview imgSize\" src=\"/web/',a.path,'\">',mvb.name,' (',mvb.code,')</span>'), CONCAT('<span class=\"thumbnail text-center no-margin\">',mvb.name,' (',mvb.code,')</span>')) SEPARATOR ' ') res FROM model_order_items_rotatsion moib
                    LEFT JOIN model_orders_items moi on moib.model_orders_items_id = moi.id
                    LEFT JOIN model_var_rotatsion mvb on moib.model_var_rotatsion_id = mvb.id
                    LEFT JOIN model_var_rotatsion_rel_attach mvbra on mvb.id = mvbra.model_var_rotatsion_id
                    LEFT JOIN attachments a on mvbra.attachment_id = a.id
                    WHERE moib.model_orders_items_id = {$this->id} AND (mvbra.is_main = 1 OR mvbra.is_main) GROUP BY moi.id
                ";
        $results = Yii::$app->db->createCommand($sql)->queryScalar();
        if($results){
            return $results;
        }
        return null;
    }
    public function getPrints()
    {
        /*return $this->hasOne(ModelVarPrints::className(), ['id' => 'prints_id']);*/
        $sql = "SELECT GROUP_CONCAT(IF(a.path!='',CONCAT('<span class=\"thumbnail text-center no-margin\"><img class=\"imgPreview imgSize\" src=\"/web/',a.path,'\">',mvb.name,' (',mvb.code,')</span>'), CONCAT('<span class=\"thumbnail text-center no-margin\">',mvb.name,' (',mvb.code,')</span>')) SEPARATOR ' ') res FROM  model_order_items_prints moib
                    LEFT JOIN model_orders_items moi on moib.model_orders_items_id = moi.id
                    LEFT JOIN model_var_prints mvb on moib.model_var_prints_id = mvb.id
                    LEFT JOIN model_var_print_rel_attach mvbra on mvb.id = mvbra.model_var_print_id
                    LEFT JOIN attachments a on mvbra.attachment_id = a.id
                    WHERE moib.model_orders_items_id = {$this->id} AND (mvbra.is_main = 1 OR mvbra.is_main) GROUP BY moi.id
                ";
        $results = Yii::$app->db->createCommand($sql)->queryScalar();
        if($results){
            return $results;
        }
        return null;
    }
    public function getStone()
    {
        /*return $this->hasOne(ModelVarStone::className(), ['id' => 'stone_id']);*/
        $sql = "SELECT GROUP_CONCAT(IF(a.path!='',CONCAT('<span class=\"thumbnail text-center no-margin\"><img class=\"imgPreview imgSize\" src=\"/web/',a.path,'\">',mvb.name,' (',mvb.code,')</span>'), CONCAT('<span class=\"thumbnail text-center no-margin\">',mvb.name,' (',mvb.code,')</span>')) SEPARATOR ' ') res FROM  model_order_items_stone moib
                    LEFT JOIN model_orders_items moi on moib.model_orders_items_id = moi.id
                    LEFT JOIN model_var_stone mvb on moib.model_var_stone_id = mvb.id
                    LEFT JOIN model_var_stone_rel_attach mvbra on mvb.id = mvbra.model_var_stone_id
                    LEFT JOIN attachments a on mvbra.attachment_id = a.id
                    WHERE moib.model_orders_items_id = {$this->id} AND (mvbra.is_main = 1 OR mvbra.is_main) GROUP BY moi.id
                ";
        $results = Yii::$app->db->createCommand($sql)->queryScalar();
        if($results){
            return $results;
        }
        return null;
    }
    public function getPb()
    {
        return $this->hasOne(PulBirligi::className(), ['id' => 'pb_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getTikuvOutcomeProductsPacks()
    {
        return $this->hasMany(TikuvOutcomeProductsPack::className(), ['order_item_id' => 'id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getModelsList()
    {
        return $this->hasOne(ModelsList::className(), ['id' => 'models_list_id']);
    }
    public function getModelOrdersPlannings()
    {
        return $this->hasMany(ModelOrdersPlanning::className(), ['model_orders_items_id' => 'id']);
    }
    public function getPlanningMato()
    {
        return ModelOrdersPlanning::find()->where(['model_orders_items_id'=>$this->id,'type'=>ToquvRawMaterials::MATO])->orderBy(['id' =>SORT_ASC])->all();
    }
    public function getPlanningAks()
    {
        return ModelOrdersPlanning::find()->where(['model_orders_items_id'=>$this->id,'type'=>ToquvRawMaterials::ACS])->orderBy(['id'=> SORT_ASC])->all();
    }
    public function getMoiRelDepts()
    {
        return $this->hasMany(MoiRelDept::className(), ['model_orders_items_id' => 'id']);
    }
    public function getChildDepts($type=MoiRelDept::TYPE_MATO)
    {
        return MoiRelDept::find()->where(['model_orders_items_id'=>$this->id,'type'=>$type])->all();
    }

    public function getModelOrdersItemsVariations()
    {
        return $this->hasMany(ModelOrdersItemsVariations::class, ['model_orders_items_id' => 'id']);
    }
    /**
     * @return array
     * @throws Exception
     */
    public function getModelList()
    {
        $models_list = ($this->models_list_id)?$this->models_list_id:0;
        $sql = "SELECT m.id as id, m.name as mname, m.article as mart, atch.path, view.name as vname, type.name as tname,
                mra.is_main FROM models_list as m
                LEFT JOIN model_rel_attach as mra ON mra.model_list_id = m.id
                LEFT JOIN attachments as atch ON atch.id = mra.attachment_id
                LEFT JOIN model_view as view ON m.view_id = view.id
                LEFT JOIN model_types as type ON m.type_id = type.id
                WHERE m.status <> 2 AND m.id = {$models_list}
                ORDER BY mra.is_main ASC
        ";
        $row = Yii::$app->db->createCommand($sql)->queryAll();

        $res = [];
        foreach ($row as $item) {
            $image = (!empty($item['path']))?"<img src='/web/" . $item['path'] . "' style='width:30px;height:30px;border:1px solid'> ":'';
            $res[$item['id']] = [
                'id' => $item['id'],
                'name' => $image."<b> ".$item['mart'] . " </b> - ". $item['mname'] ." - ". $item['tname'],
                'group' => $item['vname']
            ];
        }
        $result = ArrayHelper::map($res,'id','name','group');
        return $result;
    }
    /**
     * @return ActiveQuery
     */
    public function getModelOrdersItemsSizes()
    {
        return $this->hasMany(ModelOrdersItemsSize::class, ['model_orders_items_id' => 'id']);
    }
    public function getInfo()
    {
        return "{$this->modelOrders->doc_number} - <b>{$this->modelsList->article}</b> - ".' '.$this->modelsList->name. "( {$this->colorName} ) ({$this->sizeType} - {$this->allCount})";
    }
    /**
     * @param $id
     * @return array|bool
     */
    public static function getModelVarList($id)
    {
        $query = ModelsVariations::find()->select(['id','name','code'])
            ->where(['model_list_id' => $id])
            ->all();
        if (!empty($query)) {
            return ArrayHelper::map($query, 'id', function ($model){
                return "<b>{$model->name}</b> <small>{$model->code}</small>";
            });
        }
        return false;
    }

    public function getChildSizeItem()
    {
        if($this->isNewRecord){
            return  null;
        }
        $size = ModelOrdersItemsSize::find()->joinWith('size')->where(['model_orders_items_id'=>$this->id])->asArray()->all();
        if(!empty($size)){
            return $size;
        }
        return null;
    }
    /**
     * @return string
     */
    public function getSizeList($tag='div',$class=''){
        $size = '';
        if ( $this->modelOrdersItemsSizes ) {
            foreach ( $this->modelOrdersItemsSizes as $key ) {
                $size .= ( !empty($key['count']) ) ? "
                        <{$tag} class='{$class}'>" . $key->size['name'] . " - <b>" . $key['count'] . "</b></{$tag}>" : "";
            }
        }
        return $size;
    }
    public function getSizeCustomList($class='',$attribute=''){
        $size = '';
        if ( $this->modelOrdersItemsSizes ) {
            foreach ( $this->modelOrdersItemsSizes as $key ) {
                $size .= ( !empty($key['count']) ) ? "
                        <span class='{$class}' {$attribute} >" . $key->size['name'] . " - " . $key['count'] . "</span>" : "";
            }
        }
        return $size;
    }
    public function getSizeCustomListPercentage($class='',$attribute='',$percentage=1,$checked=false,$checked_list=null){
        $size = '';
        $num = $percentage/100;
        if($checked){
            if ( $this->modelOrdersItemsSizes ) {
                foreach ( $this->modelOrdersItemsSizes as $key ) {
                    $disabled = '';
                    if($checked_list&&is_array($checked_list)&&in_array($key->size_id, $checked_list)){
                        $disabled = 'disabled="disabled"';
                    }
                    $count = $num * $key['count'] + $key['count'];
                    $size .= (!empty($key['count']) && $key['count'] > 0) ? "
                            <span class='{$class}' {$attribute} >" . $key->size['name'] . " - <span id='size_count_{$key->id}' class='size_percentage_all_{$this->id}'>" . ceil($count) . "</span><input type='checkbox' class='size_checkbox size_checkbox_{$key->size_id}' checked value='{$count}' data-name='{$key->size->name}' data-id='{$key->size_id}' $disabled></span>" : "";
                }
            }
        }else {
            if ($this->modelOrdersItemsSizes) {
                foreach ($this->modelOrdersItemsSizes as $key) {
                    $count = $num * $key['count'] + $key['count'];
                    $size .= (!empty($key['count']) && $key['count'] > 0) ? "
                            <span class='{$class}' {$attribute} >" . $key->size['name'] . " - <span id='size_count_{$key->id}' class='size_percentage_all_{$this->id}'>" . ceil($count) . "</span></span>" : "";
                }
            }
        }
        return $size;
    }
    public function getSizeCustomListInput($class='',$attribute='',$percentage=1,$n){
        $size = '';
        $num = $percentage/100;
        if ( $this->modelOrdersItemsSizes ) {
            foreach ( $this->modelOrdersItemsSizes as $m => $key ) {
                $size .= ( !empty($key['count'])&&$key['count']>0 ) ?$key->size->name . " - <input type='text' class='size_change size_all_{$this->id} {$class}' {$attribute} name='ModelOrdersPlanning[$n][items][{$key->id}]' value='". $key['count'] . "' percentage='{$num}' num='{$key->id}' parent='{$this->id}'>&nbsp;" : "";
            }
        }
        return $size;
    }
    public function getSizeCount($size_id){
        $item = ModelOrdersItemsSize::find()->where(['model_orders_items_id'=>$this->id,'size_id'=>$size_id])->one();
        if($item){
            return $item['count'];
        }
        return 0;
    }
    public function getAllCount(){
        $count = 0;
        if ( $this->modelOrdersItemsSizes ) {
            foreach ( $this->modelOrdersItemsSizes as $key ) {
                $count += (is_integer($key['count']))?$key['count']:0;
            }
        }
        return $count;
    }
    public function getAllCountPercentage($percentage){
        $count = 0;
        $num = $percentage/100;
        if ( $this->modelOrdersItemsSizes ) {
            foreach ( $this->modelOrdersItemsSizes as $key ) {
                $count += (is_int($key['count']))?$key['count']:0;
            }
        }
        return ceil($num*$count+$count);
    }
    public function getSizeType(){
        $size = '';
        if ( $this->modelOrdersItemsSizes ) {
            $size = $this->modelOrdersItemsSizes[0]->size->sizeType['name'];
        }
        if($size)
            return $size;
        return false;
    }
    public function getColorList($m,$n,$val)
    {
        $list = '';
        $item = $this->modelVar->modelsVariationColors;
        if ($item){
            $i = 0;
            foreach ($item as $key){
                $checked = ($val&&$key->colorPantone['id']==$val)?'checked':(($i==0)?'checked':'');
                $list  .= "
                        <label class=\"checkbox-transform\">
                            <input type=\"radio\" class=\"checkbox__input\" name='ModelOrdersPlanning[{$m}][child][{$n}][color_pantone_id]' value='{$key->colorPantone['id']}' {$checked}>
                            <span class=\"checkbox__label\" style='background:rgb({$key->colorPantone['r']},{$key->colorPantone['g']},{$key->colorPantone['b']});'></span>
                        </label>
                ";
                $i++;
            }
        }
        return $list;
    }
    public function getColor($id)
    {
        $color = ColorPantone::findOne($id);
        if($color){
            return "<div style='height:18px;background: rgb(".$color['r'].",".$color['g'].",".$color['b'].")'></div>";
        }
        return false;
    }
    public function getColorName()
    {
        $modelVar = $this->modelVar;
        if($modelVar){
            $color = $modelVar->colorPantone;
            if($color){
                return $color['code'];
            }
        }
        return false;
    }
    public function getDeptVal()
    {
        $data = $this->moiRelDepts;
        $depts = [];
        foreach ($data as $m => $key) {
            $depts[$m]['id'] = $key['toquv_departments_id'];
            $depts[$m]['name'] = $key->toquvDepartments['name'];
        }
        return $depts;
    }
    public function saveDepartments($data,$status=1){
        $success = false;
        MoiRelDept::deleteAll([
            'AND',
            'model_orders_items_id' => $this->id,
            ['<','status',3]
        ]);
        if (!empty($data['MoiRelDept'])) {
            foreach ($data['MoiRelDept'] as $item) {
                if(!empty($item['toquv_departments_id'])&&$item['toquv_departments_id']>0) {
                    $success = false;
                    $item_depts = new MoiRelDept();
                    $item_depts->setAttributes([
                        'model_orders_items_id' => $this->id,
                        'company_categories_id' => $item['company_categories_id'],
                        'type' => $item['company_categories_id'],
                        'toquv_departments_id' => $item['toquv_departments_id'],
                        'model_orders_planning_id' => $item['model_orders_planning_id'],
                        'is_own' => 1,
                        'quantity' => $item['quantity'],
                        'start_date' => $item['start_date'],
                        'end_date' => $item['end_date'],
                        'add_info' => $item['add_info'],
                        'status' => ($status)?$status:1
                    ]);
                    if($item_depts->save()){
                        $success = true;
                    }
                }
            }
        }else{
            $success = true;
        }
        if (!empty($data['MoiRelDeptMusteri'])) {
            $success = false;
            foreach ($data['MoiRelDeptMusteri'] as $item) {
                if(!empty($item['musteri_id'])&&$item['musteri_id']>0) {
                    $success = false;
                    $item_depts = new MoiRelDept();
                    $item_depts->setAttributes([
                        'model_orders_items_id' => $this->id,
                        'company_categories_id' => $item['company_categories_id'],
                        'type' => $item['company_categories_id'],
                        'musteri_id' => $item['musteri_id'],
                        'model_orders_planning_id' => $item['model_orders_planning_id'],
                        'is_own' => 2,
                        'quantity' => $item['quantity'],
                        'start_date' => $item['start_date'],
                        'end_date' => $item['end_date'],
                        'add_info' => $item['add_info'],
                        'status' => ($status)?$status:1
                    ]);
                    if($item_depts->save()){
                        $success = true;
                    }
                }
            }
        }
        return $success;
    }
    public function finishDepartments(){
        $success = false;
        $toquv = ToquvDepartments::findOne(['token'=>'TOQUV_MATO_SEH'])['id'];
        $toquv_aks = ToquvDepartments::findOne(['token'=>'TOQUV_ACS_SEH'])['id'];
        $dept_toquv = MoiRelDept::find()->where(['model_orders_items_id'=>$this->id,'is_own'=>1,'status'=>3,'toquv_departments_id'=>$toquv])->all();
        $dept_toquv_acs = MoiRelDept::find()->where(['model_orders_items_id'=>$this->id,'is_own'=>1,'status'=>3,'toquv_departments_id'=>$toquv_aks])->all();
        if ($dept_toquv) {
            $success = $this->saveToquvOrders($dept_toquv,ToquvRawMaterials::MATO);
        }
        if ($dept_toquv_acs) {
            $success = $this->saveToquvOrders($dept_toquv_acs,ToquvRawMaterials::ACS);
        }
        return $success;
    }
    public function saveToquvOrders($data,$type){
        $success = false;
        if ($data) {
            $samo = Musteri::find()->select('id')->where(['token'=>'SAMO'])->one();
            $toquv_orders = ToquvOrders::findOne([
                'model_orders_id' => $this->model_orders_id
            ]);
            $saved = false;
            if(empty($toquv_orders)) {
                $orders = new ToquvOrders();
                $lastId = $orders::find()->select('id')->orderBy(['id' => SORT_DESC])->asArray()->one();
                $lastId = $lastId ? $lastId['id'] + 1 : 1;
                $orders->setAttributes([
                    'musteri_id' => $samo['id'],
                    'document_number' => 'MO-' . $lastId . "/" . date('d-m-Y'),
                    'reg_date' => date('Y-m-d'),
                    'type' => $type,
                    'model_orders_id' => $this->model_orders_id,
                    'model_musteri_id' => $this->modelOrders->musteri_id,
                    'status' => 3
                ]);
                if ($orders->save(false)){
                    $saved = true;
                }
            }else{
                $orders = $toquv_orders;
                $saved = true;
            }
            if ($saved){
                foreach ($data as $item) {
                    $plan = $item->modelOrdersPlanning;
                    $rm_order = new ToquvRmOrder();
                    $rm_order->setAttributes([
                        'toquv_orders_id' => $orders->id,
                        'toquv_raw_materials_id' => $plan->toquv_raw_materials_id,
                        'priority' => $this->priority,
                        'rm_type' => $type,
                        'price' => ToquvRawMaterials::getNarx($plan->toquv_raw_materials_id,$item->quantity),
                        'price_fakt' => ToquvRawMaterials::getNarx($plan->toquv_raw_materials_id,$item->quantity),
                        'quantity' => $item->quantity,
                        'unit_id' => 2,
                        'done_date' => $item->end_date,
                        'thread_length' => $plan->thread_length,
                        'finish_en' => $plan->finish_en,
                        'finish_gramaj' => $plan->finish_gramaj,
                        'moi_id' => $this->id,
                        'planed_date' => date('Y-m-d H:i:s'),
                        'model_musteri_id' => $this->modelOrders->musteri_id,
                        'model_code' => $this->modelsList->article,
                        'color_pantone_id' => $item->modelOrdersPlanning->color_pantone_id,
                    ]);
                    if($rm_order->save(false)){
                        foreach ($rm_order->toquvRawMaterials->toquvRawMaterialIps as $ip){
                            $rm_item = new ToquvRmOrderItems();
                            $rm_item->setAttributes([
                                'percentage' => $ip->percentage,
                                'own_quantity' => $ip->percentage*$item->quantity/100,
                                'toquv_rm_order_id' => $rm_order->id,
                                'toquv_ne_id' => $ip->ne_id,
                                'toquv_thread_id' => $ip->thread_id
                            ]);
                            if ($rm_item->save(false)){

                            }
                            $success = true;
                            $item->status = 4;
                            $item->save(false);
                        }
                    }
                }
            }
        }
        return $success;
    }
    public function deleteDepartments(){
        MoiRelDept::deleteAll([
            'AND',
            'model_orders_items_id' => $this->id,
            ['<','status',3]
        ]);
        return true;
    }

    public function getPrintList()
    {
        $content = '';
        $all_print = ModelVarPrints::find()->joinWith('modelOrderItemsPrints')->where(['model_order_items_prints.model_orders_items_id' => $this->id])->all();
        if(!empty($all_print)) {
            foreach ($all_print as $print) {
                $content .= '<div class="print_div">
                    <div class="media">
                        <div class="text-center">
                            <img class="imgPreview" src="/web/' . $print->imageOne . '" style="height: 9vh;max-width: 40px;">
                        </div>
                        <div class="media-body text-center">
                            <h6>
                                <small class="pr_width">' . $print['width'] . '</small>
                                <small>x</small>
                                <small class="pr_height">' . $print['height'] . '</small>
                            </h6>
                            <h4 class="media-heading pr_name"><small>' . $print['name'] . '</small></h4>
                            <h5 class="pr_desen"><small>' . $print['desen_no'] . '</small></h5>
                        </div>
                    </div>
                </div>';
            }
        }
        $content = '<div class="list_prints flex-container">'.$content.'</div>';
        return $content;
    }
    public function getStoneList()
    {
        $content = '';
        $all_stone = ModelVarStone::find()->joinWith('modelOrderItemsStones')->where(['model_order_items_stone.model_orders_items_id' => $this->id])->all();
        if(!empty($all_stone)) {
            foreach ($all_stone as $stone) {
                $content .= '<div class="stone_div">
                    <div class="media">
                        <div class="text-center">
                            <img class="imgPreview" src="/web/' . $stone->imageOne . '" style="height: 9vh;max-width: 40px;">
                        </div>
                        <div class="media-body text-center">
                            <h6>
                                <small class="pr_width">' . $stone['width'] . '</small>
                                <small>x</small>
                                <small class="pr_height">' . $stone['height'] . '</small>
                            </h6>
                            <h4 class="media-heading pr_name"><small>' . $stone['name'] . '</small></h4>
                            <h5 class="pr_desen"><small>' . $stone['desen_no'] . '</small></h5>
                        </div>
                    </div>
                </div>';
            }
        }
        $content = '<div class="list_stone flex-container">'.$content.'</div>';
        return $content;
    }

    public function saveOnePlanning($data)
    {
        $result = [];
        $result['id'] = null;
        $result['status'] = 0;
        $result['errors'] = [];
        $result['message'] = Yii::t('app','Xatolik yuz berdi!');
        $planning = ModelOrdersPlanning::findOne($data['id']);
        if(empty($planning)){
            $planning = new ModelOrdersPlanning();
        }
        if($data['type']==2){
            $planning->scenario = $planning::SCENARIO_AKS;
        }else{
            $planning->scenario = $planning::SCENARIO_MATO;
        }
        $planning->setAttributes($data);
        if($planning->validate()&&$planning->save()){
            $result['id'] = $planning['id'];
            $result['status'] = 1;
            $result['message'] = Yii::t('app','Saved Successfully');
        }else{
            if($planning->hasErrors()){
                $result['errors'] = $planning->getErrors();
            }
        }
        return $result;
    }

    public function getToquvAks()
    {
        $materials = ModelsRawMaterials::find()->joinWith('rm rm')->where(['model_list_id'=>$this->models_list_id,'rm.type'=>ToquvRawMaterials::ACS])->asArray()->all();
        if(!empty($materials)){
            return  $materials;
        }
        return false;
    }
    public function getCheckedSizeList($type=MoiRelDept::TYPE_MATO){
        $planning = ModelOrdersPlanning::find()->select('GROUP_CONCAT(DISTINCT size_list SEPARATOR "-") list')->where(['model_orders_items_id'=>$this->id,'type'=>$type])->asArray()->one();
        if(!empty($planning['list'])){
            return explode('-', $planning['list']);
        }
        return false;
    }

    public static function getVariations($models)
    {
        $result = WmsColor::find()
            ->select(['wms_color.*'])
            ->leftJoin('color_pantone', 'color_pantone.id = wms_color.color_pantone_id')
            ->with('colorPantone')
            ->leftJoin('models_variations', 'models_variations.wms_color_id = wms_color.id')
            ->with('modelsVariations')
            ->where(['models_variations.id' => $models])
            ->one();
        return $result;
    }

    public static function getItemHtmlLabelById($id) {
        if (!intval($id)) {
            return null;
        }

        $sql = "
        SELECT moi.id, ml.name, ml.article, cp.code, cp.r, cp.g, cp.b
        FROM model_orders_items moi
            LEFT JOIN model_orders_items_mato moim ON moi.id = moim.model_orders_items_id
            LEFT JOIN models_variations mv ON moi.model_var_id = mv.id
            LEFT JOIN color_pantone cp on mv.color_pantone_id = cp.id
            LEFT JOIN models_list ml ON moi.models_list_id = ml.id
        WHERE moi.id = :id
        ";

        $result = Yii::$app->getDb()->createCommand($sql)
            ->bindValue(':id', $id)
            ->queryOne();

        if ($result) {
            $item = $result;
            return $item['name']
                . ' (' . $item['article'] . ') '
                . '<code>'.$item['code'].'</code>'
                . '<div style="display:inline-block;height: 15px; width:50px; margin-top:5px; background-color: rgb('.$item['r'].','. $item['g'].','.$item['b'].')"></div>';
        }
        return false;
    }

    public static function getItemsByModelOrdersId($modelOrdersId)
    {
        $sql = "
        SELECT moi.id, 
               ml.name, 
               ml.article, 
               cp.code, 
               cp.r, 
               cp.g, 
               cp.b,
               IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code) as color_code,
               IF(wc.color_pantone_id IS NULL, wc.color_name, cp.name) as color_name
        FROM model_orders_items moi
            LEFT JOIN model_orders_items_material moim ON moi.id = moim.model_orders_items_id
            LEFT JOIN models_variations mv ON moi.model_var_id = mv.id
            LEFT JOIN wms_color wc ON mv.wms_color_id = wc.id
            LEFT JOIN color_pantone cp on wc.color_pantone_id = cp.id
            LEFT JOIN models_list ml ON moi.models_list_id = ml.id
        WHERE moi.model_orders_id = :id
        ";

        $result = Yii::$app->getDb()->createCommand($sql)
            ->bindValue(':id', $modelOrdersId)
            ->queryAll();

        $out = [];
        if ($result) {
            foreach ($result as $item) {
                $out[] = [
                    'id' => $item['id'],
                    'text' => $item['name']
                        . ' (' . $item['article'] . ') '
                        . $item['color_name']
                        . ' (' . $item['color_code'] . ')',
                ];
                //. '<div style="display:inline-block;height: 15px; width:50px; margin-top:5px; background-color: rgb('.$item['r'].','. $item['g'].','.$item['b'].')"></div>';
            }

            return $out;
        }
        return null;
    }

    public static function getMapListByModelOrdersId($modelOrdersId)
    {
        if (!intval($modelOrdersId)) {
            return null;
        }

        $sql = "
        SELECT moi.id, 
               ml.name, 
               ml.article, 
               cp.code, 
               cp.r, 
               cp.g, 
               cp.b,
               IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code) as color_code,
               IF(wc.color_pantone_id IS NULL, wc.color_name, cp.name) as color_name
        FROM model_orders_items moi
            LEFT JOIN model_orders_items_material moim ON moi.id = moim.model_orders_items_id
            LEFT JOIN models_variations mv ON moi.model_var_id = mv.id
            LEFT JOIN wms_color wc ON mv.wms_color_id = wc.id
            LEFT JOIN color_pantone cp on wc.color_pantone_id = cp.id
            LEFT JOIN models_list ml ON moi.models_list_id = ml.id
        WHERE moi.model_orders_id = :id
        ";

        $result = Yii::$app->getDb()->createCommand($sql)
            ->bindValue(':id', $modelOrdersId)
            ->queryAll();

        $out = [];
        if ($result) {
            foreach ($result as $item) {
                $out[intval($item['id'])] = $item['name']
                    . ' (' . $item['article'] . ') '
                    . $item['color_name']
                    . ' (' . $item['color_code'] . ')';
                    //. '<div style="display:inline-block;height: 15px; width:50px; margin-top:5px; background-color: rgb('.$item['r'].','. $item['g'].','.$item['b'].')"></div>';
            }

            return $out;
        }
        return null;
    }
    
    public static function getModelOrderItemsList()
    {
        $lists = self::find()
            ->alias('moi')
            ->select(["moi.id","CONCAT(ml.article,' (', IF(wc.color_pantone_id IS NOT NULL,cp.name,wc.color_code) ,')') as model"])
            ->innerJoin(['ml' => 'models_list'], 'moi.models_list_id = ml.id')
            ->innerJoin(['mv' => 'models_variations'],'moi.model_var_id = mv.id')
            ->leftJoin(['wc' => 'wms_color'],'mv.wms_color_id = wc.id')
            ->leftJoin(['cp' => 'color_pantone'],'wc.color_pantone_id = cp.id')
            ->where(['not', ['ml.article' => null]])
            ->asArray()
            ->all();
        if(!empty($lists)){
            return ArrayHelper::map($lists, 'id','model');
        }
    }

    public static function getDetailTypes($orderId,$detailId){

        $query = self::find()
            ->alias('moi')
            ->select(['bdl.id bdl_id','bdl.name bdl_name'])
            ->leftJoin(['ml' => 'models_list'], 'moi.models_list_id = ml.id')
            ->innerJoin(['bp' => 'base_patterns'],'ml.base_pattern_id = bp.id')
            ->leftJoin(['bpi' => 'base_pattern_items'],'bp.id = bpi.base_pattern_id')
            ->leftJoin(['bdl' => 'base_detail_lists'],'bpi.base_detail_list_id = bdl.id')
            ->leftJoin(['bdt' => 'bichuv_detail_types'],'bpi.bichuv_detail_type_id = bdt.id')
            ->where(['moi.id' => $orderId, 'bdt.id' => $detailId])
            ->groupBy(['bdl.id','bdt.id'])
            ->asArray()
            ->all();
        if (!empty($query))
            return $query;
        return false;
    }

}
