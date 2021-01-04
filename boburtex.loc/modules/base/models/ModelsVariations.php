<?php

namespace app\modules\base\models;

use app\models\ColorPantone;
use app\modules\bichuv\models\BichuvAcs;
use app\modules\boyoq\models\Color;
use app\modules\toquv\models\ToquvRawMaterials;
use app\modules\wms\models\WmsColor;
use app\modules\wms\models\WmsDesen;
use app\modules\wms\models\WmsMatoInfo;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%models_variations}}".
 *
 * @property int $id
 * @property string $name
 * @property int $model_list_id
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property string $code
 * @property string $add_info
 *
 * @property Goods[] $goods
 * @property ModelOrdersItems[] $modelOrdersItems
 * @property ModelVarPrintsRel[] $modelVarPrintsRels
 * @property ModelVarRelAttach[] $modelVarRelAttaches
 * @property ModelVariationParts[] $modelVariationParts
 * @property ModelsVariationColors[] $modelsVariationColors
 * @property ModelsList $modelList
 * @property ToquvRawMaterials $toquvRawMaterial
 * @property ColorPantone $colorPan
 * @property Color $boyoqhonaColor
 * @property int $color_pantone_id [int(11)]
 * @property int $toquv_raw_material_id [int(11)]
 * @property int $boyoqhona_color_id [int(11)]
 * @property $modelsPechat ModelsPechat
 * @property $modelsNaqsh ModelsNaqsh
 */
class ModelsVariations extends BaseModel
{
    public $make_all_as_main;
    public $bichuv_acs_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%models_variations}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['model_list_id', 'unique', 'targetAttribute' => ['model_list_id', 'wms_color_id'], 'message' => Yii::t('app', 'Modelni bunday varianti mavjud!')],
            [['toquv_raw_material_id'],'required'],
            [['model_list_id', 'status', 'toquv_raw_material_id','boyoqhona_color_id','color_pantone_id','created_by', 'model_var_prints_id', 'model_var_stone_id', 'created_at', 'updated_at'], 'integer'],
            [['add_info'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 30],
            [['model_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['model_list_id' => 'id']],
            [['model_var_prints_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelVarPrints::className(), 'targetAttribute' => ['model_var_prints_id' => 'id']],
            [['model_var_stone_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelVarStone::className(), 'targetAttribute' => ['model_var_stone_id' => 'id']],
            [['wms_desen_id'], 'exist', 'skipOnError' => true, 'targetClass' => WmsDesen::className(), 'targetAttribute' => ['wms_desen_id' => 'id']],
            [['wms_color_id'], 'exist', 'skipOnError' => true, 'targetClass' => WmsColor::className(), 'targetAttribute' => ['wms_color_id' => 'id']],
            [['color_pantone_id'], 'exist', 'skipOnError' => true, 'targetClass' => ColorPantone::className(), 'targetAttribute' => ['color_pantone_id' => 'id']],
            [['toquv_raw_material_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvRawMaterials::className(), 'targetAttribute' => ['toquv_raw_material_id' => 'id']],
            [['boyoqhona_color_id'], 'exist', 'skipOnError' => true, 'targetClass' => Color::className(), 'targetAttribute' => ['boyoqhona_color_id' => 'id']],
            [['bichuv_acs_id'], 'safe']
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
            'model_list_id' => Yii::t('app', 'Model List ID'),
            'toquv_raw_material_id' => Yii::t('app', 'Asosiy mato'),
            'status' => Yii::t('app', 'Status'),
            'wms_color_id' => Yii::t('app', 'Wms Color ID'),
            'wms_desen_id' => Yii::t('app', 'Wms Desen ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'code' => Yii::t('app', 'Code'),
            'add_info' => Yii::t('app', 'Add Info'),
            'naqsh_id' => Yii::t('app', 'Naqsh'),
            'pechat_id' => Yii::t('app', 'Pechats'),
            'bichuv_acs_id' => Yii::t('app', 'Bichuv Acs'),
            'model_var_stone_id' => Yii::t('app', 'Model Var Stone'),
            'model_var_prints_id' => Yii::t('app', 'Model Var Prints'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColorPan()
    {
        return $this->hasOne(ColorPantone::className(), ['id' => 'color_pantone_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * */
    public function getBichuvAcs()
    {
        return $this->hasOne(BichuvAcs::class, ['id' => 'bichuv_acs_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBoyoqhonaColor()
    {
        return $this->hasOne(Color::className(), ['id' => 'boyoqhona_color_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvRawMaterial()
    {
        return $this->hasOne(ToquvRawMaterials::className(), ['id' => 'toquv_raw_material_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasMany(Goods::className(), ['model_var' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrdersItems()
    {
        return $this->hasMany(ModelOrdersItems::className(), ['model_var_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVariationParts()
    {
        return $this->hasMany(ModelVariationParts::className(), ['model_var_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVarPrintsRels()
    {
        return $this->hasMany(ModelVarPrintsRel::className(), ['models_variations_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVarBaskis()
    {
        return $this->hasMany(ModelVarBaski::className(), ['model_var_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     * */
    public function getWmsColor()
    {
        return $this->hasOne(WmsColor::class,['id' => 'wms_color_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVarPrints()
    {
        return $this->hasMany(ModelVarPrints::className(), ['id' => 'model_var_prints_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVarRelAttaches()
    {
        return $this->hasMany(ModelVarRelAttach::className(), ['model_var_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelVarStones()
    {
        return $this->hasMany(ModelVarStone::className(), ['id' => 'model_var_prints_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsVariationColors()
    {
        return $this->hasMany(ModelsVariationColors::className(), ['model_var_id' => 'id']);
    }
    public function getImage()
    {
        $image = ModelVarRelAttach::find()->where(['is_main'=>1,'model_var_id'=>$this->id])->orderBy(['id'=>SORT_DESC])->one();
        if(!$image){
            $image = ModelVarRelAttach::find()->where(['model_var_id'=>$this->id])->orderBy(['id'=>SORT_DESC])->one();
        }
        if ($image){
            $attachment = $image->attachment['path'];
            if(!empty($attachment)){
                return $attachment;
            }
        }
        return false;
    }
    public function getColor()
    {
        $item = ModelsVariationColors::find()->where(['is_main'=>1,'model_var_id'=>$this->id])->orderBy(['id'=>SORT_DESC])->one();
        if(!$item){
            $item = ModelsVariationColors::find()->where(['model_var_id'=>$this->id])->orderBy(['id'=>SORT_DESC])->one();
        }
        if ($item){
            $color = $item->colorPantone;
            if($color){
                return "<div>{$color->code}</div><div style='height:15px;background: rgb(".$color['r'].",".$color['g'].",".$color['b'].")'></div>";
            }
        }
        return false;
    }
    public function getColorId()
    {
        $item = ModelsVariationColors::find()->with(['colorPantone'])->where(['is_main'=>1,'model_var_id'=>$this->id])->orderBy(['id'=>SORT_DESC])->asArray()->one();
        if(empty($item)){
            $item = ModelsVariationColors::find()->with(['colorPantone'])->where(['model_var_id'=>$this->id])->orderBy(['id'=>SORT_DESC])->asArray()->one();
        }
        if (!empty($item) && !empty($item['colorPantone'])){
            return $item['colorPantone']['id'];
        }
        return null;
    }
    public function getColorPantone()
    {
        $color = $this->colorPan;

        if(!empty($color)) return $color;

        return false;
    }
    public function getColorList($opt=null)
    {
        $list = [];
        $item = $this->modelsVariationColors;
        if ($item){
            foreach ($item as $key){
                $list['list'][$key->colorPantone['id']] = $key->colorPantone['name'];
                $list['options'][$key->colorPantone['id']] = ['style' => "background:rgb({$key->colorPantone['r']},{$key->colorPantone['g']},{$key->colorPantone['b']});color:white;padding:2px;font-weight:bold"];
            }
        }
        return $list;
    }
    public function getColorData()
    {
        $parentId = $this->id;

        $sql = "SELECT wc.id,
                wc.color_name,
                wc.color_code,
                clp.name as cname, clp.code as ccode, r, g, b, type.name as tname
                FROM models_variation_colors mvc
                LEFT JOIN wms_color wc ON mvc.wms_color_id = wc.id
                LEFT JOIN color_pantone clp ON wc.color_pantone_id = clp.id
                LEFT JOIN color_panton_type as type 
                ON clp.color_panton_type_id = type.id
                WHERE mvc.model_var_id = {$parentId}
        ";
        $acs = Yii::$app->db->createCommand($sql)->queryAll();
        $res = [];
        foreach ($acs as $item) {
            $color = (!empty($item['ccode']))?"<span style='background:rgb(".$item['r'].",".$item['g'].",".$item['b']."); width:80px;padding-left:5px;padding-right:5px;border:1px solid'><span style='opacity:0;'>RGB</span></span> ".$item['ccode'] . " - <b>" . $item['cname'] . "</b>":"<span style='background:".$item['color_code']."; width:80px;padding-left:5px;padding-right:5px;border:1px solid'><span style='opacity:0;'>RGB</span></span> ".$item['color_code'] . " - <b>" . $item['color_name'] . "</b>";
            $res[$item['id']] = [
                'id' => $item['id'],
                'name' => $color,
                'group' => $item['tname']
            ];
        }
        $result = ArrayHelper::map($res,'id','name','group');
        return $result;
    }
    public function getBoyoqColorData()
    {
        $parentId = $this->id;

        $sql = "SELECT c.id, c.color, c.color_id, ct.name, tone.name tone
                FROM color as c
                LEFT JOIN color_type ct ON ct.id = c.color_tone
                LEFT JOIN color_tone as tone ON c.color_tone = tone.id
                WHERE c.id IN (select color_boyoqhona_id from models_variation_colors where model_var_id = {$parentId})
        ";
        $acs = Yii::$app->db->createCommand($sql)->queryAll();
        $res = [];
        foreach ($acs as $item) {
            $res[$item['id']] = [
                'id' => $item['id'],
                'name' => "<span style='background:{$item['color']}; width:80px;padding-left:5px;padding-right:5px;border:1px solid'><span style='opacity:0;'>rgb</span></span> ".$item['color_id'] . " - <b>" . $item['name'] . "</b>",
                'group' => $item['tone']
            ];
        }
        $result = ArrayHelper::map($res,'id','name','group');
        return $result;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelList()
    {
        return $this->hasOne(ModelsList::className(), ['id' => 'model_list_id']);
    }
    public function saveColor($data){
        if (!empty($data)) {
            $i = 0;
            foreach ($data as $key) {
                if(!empty($key['color_pantone_id'])) {
                    $item = new ModelsVariationColors();
                    $item->setAttributes([
                        'model_var_id' => $this->id,
                        'color_pantone_id' => $key['color_pantone_id'],
                        'color_boyoqhona_id' => $key['color_boyoqhona_id'],
                        'base_detail_list_id' => $key['base_detail_list_id'],
                        'toquv_raw_material_id' => $key['toquv_raw_material_id'],
                        'is_main' => ($i == 0) ? 1 : 0,
                    ]);
                    $item->save();
                    $i++;
                }
            }
        }
    }
    public function saveAttachments($data){
        if (!empty($data)) {
            $i = 0;
            foreach ($data as $key) {
                if(!empty($key)) {
                    $item = new ModelVarRelAttach();
                    $item->setAttributes([
                        'attachment_id' => $key,
                        'model_var_id' => $this->id,
                        'is_main' => ($i == 0) ? 1 : 0,
                    ]);
                    $item->save();
                    $i++;
                }
            }
        }
    }
    public function saveStone($data){
        if (!empty($data)) {
            foreach ($data as $key) {
                if(!empty($key['name'])||!empty($key['add_info'])||!empty($key['attachments'])) {
                    $item = new ModelVarStone();
                    $item->setAttributes([
                        'name' => $key['name'],
                        'model_var_id' => $this->id,
                        'add_info' => $key['add_info'],
                    ]);
                    if ($item->save() && !empty($key['attachments'])) {
                        $i = 0;
                        foreach ($key['attachments'] as $key) {
                            $itemAttachment = new ModelVarStoneRelAttach();
                            $itemAttachment->setAttributes([
                                'attachment_id' => $key,
                                'model_var_stone_id' => $item['id'],
                                'is_main' => ($i == 0) ? 1 : 0,
                            ]);
                            $itemAttachment->save();
                            $i++;
                        }
                    }
                }
            }
        }
    }
    public function saveBaski($data){
        if (!empty($data)) {
            foreach ($data as $key) {
                if(!empty($key['name'])||!empty($key['add_info'])||!empty($key['attachments'])) {
                    $item = new ModelVarBaski();
                    $item->setAttributes([
                        'name' => $key['name'],
                        'model_var_id' => $this->id,
                        'add_info' => $key['add_info'],
                    ]);
                    if ($item->save() && !empty($key['attachments'])) {
                        $i = 0;
                        foreach ($key['attachments'] as $key) {
                            $itemAttachment = new ModelVarBaskiRelAttach();
                            $itemAttachment->setAttributes([
                                'attachment_id' => $key,
                                'model_var_baski_id' => $item['id'],
                                'is_main' => ($i == 0) ? 1 : 0,
                            ]);
                            $itemAttachment->save();
                            $i++;
                        }
                    }
                }
            }
        }
    }
    public function savePrints($data){
        if (!empty($data)) {
            foreach ($data as $key) {
                if(!empty($key['name'])||!empty($key['add_info'])||!empty($key['attachments'])) {
                    $item = new ModelVarPrints();
                    $item->setAttributes([
                        'name' => $key['name'],
                        'model_var_id' => $this->id,
                        'add_info' => $key['add_info'],
                    ]);
                    if ($item->save() && !empty($key['attachments'])) {
                        $i = 0;
                        foreach ($key['attachments'] as $att) {
                            $itemAttachment = new ModelVarPrintRelAttach();
                            $itemAttachment->setAttributes([
                                'attachment_id' => $att,
                                'model_var_print_id' => $item['id'],
                                'is_main' => ($i == 0) ? 1 : 0,
                            ]);
                            $itemAttachment->save();
                            $i++;
                        }
                    }
                }
            }
        }
    }

    public function getModelsAcsVars()
    {
        return $this->hasMany(ModelsAcsVariations::class, ['model_var_id' => 'id']);
    }

    public function deleteItems(){
        if(!empty($this->modelsVariationColors)){
            ModelsVariationColors::deleteAll('model_var_id = '.$this->id);
        }
        if(!empty($this->modelVarRelAttaches)){
            ModelVarRelAttach::deleteAll('model_var_id = '.$this->id);
        }
        if(!empty($this->modelVariationParts)){
            ModelVariationParts::deleteAll(['model_var_id' => $this->id]);
        }
        if(!empty($this->modelsAcsVars)){
            ModelsAcsVariations::deleteAll(['model_var_id' => $this->id]);
        }
    }
    public function saveItems($data){
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];
        $saved = false;
        try {
            $this->deleteItems();
            if (!empty($data['ModelsVariationColors'])) {
                $i = 0;
                if(!empty($this->modelList->basePattern) && !empty($this->modelList->basePattern->basePatternItems)){
                    $patternItems = $this->modelList->basePattern->getBasePatternItems()->select(['base_pattern_part_id'])->asArray()->groupBy(['base_pattern_part_id'])->all();
                    $saved = true;
                    if(!empty($patternItems) && count($patternItems) > 1){
                        $saved = false;
                        $modelListId = $this->model_list_id;
                        $modelVarId = $this->id;
                        $colorId = $this->wms_color_id;
                        $desenId = $this->wms_desen_id;
                        $rawMaterialId = $this->toquv_raw_material_id;
                        $modelVarName = $this->name;
                        $pechatId = $this->model_var_stone_id;
                        $naqshId = $this->model_var_prints_id;
                        foreach ($patternItems as $item){
                            $modelVarPart = new ModelVariationParts();
                            $modelVarPart->setAttributes([
                                'model_list_id' => $modelListId,
                                'base_pattern_part_id' => $item['base_pattern_part_id'],
                                'raw_material_id' => $rawMaterialId,
                                'model_var_id' => $modelVarId,
                                'wms_color_id' => $colorId,
                                'wms_desen_id' => $desenId,
                                'naqsh_id' => $naqshId,
                                'pechat_id' => $pechatId,
                                'name' => $modelVarName
                            ]);
                            if($modelVarPart->save()){
                                $saved = true;
                            }else{
                                $saved = false;
                                break;
                            }
                        }
                    }
                }
                else{
                    $saved = true;
                }
                if($saved){
                    foreach ($data['ModelsVariationColors'] as $key) {
                        $isWms = WmsMatoInfo::find()
                            ->where(['toquv_raw_materials_id' => $key['toquv_raw_material_id']])
                            ->andWhere(['type' => ToquvRawMaterials::MATO])
                            ->andWhere(['wms_desen_id' => $key['wms_desen_id']])
                            ->andWhere(['wms_color_id' => $key['wms_color_id']])
                            ->asArray()
                            ->all();
                        if(empty($isWms)){
                            $wms = new WmsMatoInfo();
                            $wms->setAttributes([
                                'wms_color_id' => $key['wms_color_id'],
                                'wms_desen_id' => $key['wms_desen_id'],
                                'toquv_raw_materials_id' => $key['toquv_raw_material_id']?$key['toquv_raw_material_id']:'',
                                'status' => WmsMatoInfo::STATUS_ACTIVE,
                                'type' => ToquvRawMaterials::MATO
                            ]);
                            if($wms->save(false)){
                                $saved = true;
                                unset($wms);
                            }
                            else{
                                $saved = false;
                                Yii::info('Wms Mato Info saqlanmadi ModelsVariations.php actionSaveItems ');
                                break;
                            }
                        }
                        $item = new ModelsVariationColors();
                        $item->setAttributes([
                            'model_var_id' => $this->id,
                            'wms_color_id' => $key['wms_color_id'],
                            'wms_desen_id' => $key['wms_desen_id'],
                            'base_detail_list_id' => $key['base_detail_list_id'],
                            'toquv_raw_material_id' => $key['toquv_raw_material_id'],
                            'is_main' => ($i == 0) ? 1 : 0,
                        ]);
                        if($item->save(false)){
                            $saved = true;
                        }else{
                            $saved = false;
                            break;
                        }
                        $i++;
                    }
                }
            }
            else{
                $saved = true;
            }
            if (!empty($data['ModelVarRelAttach'])&&$saved) {
                $i = 0;
                foreach ($data['ModelVarRelAttach'] as $key) {
                    if(!empty($key)) {
                        $item = new ModelVarRelAttach();
                        $item->setAttributes([
                            'attachment_id' => $key,
                            'model_var_id' => $this->id,
                            'is_main' => ($i == 0) ? 1 : 0,
                        ]);
                        if($item->save()){
                            $saved = true;
                        }else{
                            $saved = false;
                            break;
                        }
                        $i++;
                    }
                }
            }

            if(!empty($this->bichuv_acs_id)){
                foreach ($this->bichuv_acs_id as $item) {
                    $Obj = new ModelsAcsVariations();
                    $Obj->setAttributes([
                        'models_list_id' => $this->model_list_id,
                        'model_var_id' => $this->id,
                        'bichuv_acs_id' => $item,
                    ]);
                    if($Obj->save()){
                        $saved = true;
                        unset($Obj);
                    }
                    else{
                        $saved = false;
                        break;
                    }
                }
            }

            if($saved){
                $transaction->commit();
                return true;
            }else{
                $transaction->rollBack();
                Yii::info('Not saved ModelVariationsColors and ModelVariationsRelAttach', 'save');
                return false;
            }
        }catch (\Exception $e){
            Yii::info('Not saved ModelVariationsColors' . $e, 'save');
        }
        return false;
    }

    /**
     * @param null $key
     * @param bool $list
     * @return array|null
     */
    public function getPatternItemList($key = null, $list = false){
        $pl = BaseDetailLists::find();
        if(!empty($key) && $list){
            $pl->where(['id' => $key]);
        }
        $items = $pl->asArray()->all();
        if(empty($items)) return null;
        return ArrayHelper::map($items,'id', 'name');
    }

    public static function getMaterialList($id=null,$model_list=null)
    {
        $sql = "SELECT
                    raw.id,
                    raw.code,
                    raw.name as rname,
                    type.name as tname,
                    ne_id,
                    tn.name ne,
                    tt.name thread 
                FROM
                    toquv_raw_materials as raw          
                LEFT JOIN
                    raw_material_type as type                    
                        ON raw.raw_material_type_id = type.id     
                LEFT JOIN
                    toquv_raw_material_ip trmi 
                        on raw.id = trmi.toquv_raw_material_id     
                LEFT JOIN
                    toquv_ne tn 
                        on trmi.ne_id = tn.id     
                LEFT JOIN
                    toquv_thread tt 
                        on trmi.thread_id = tt.id
                LEFT JOIN models_raw_materials mrm on raw.id = mrm.rm_id
                WHERE raw.status != 2
                %s
                %s
                GROUP BY raw.id
        ";
        $raw_id = ($id)?" AND raw.id = {$id}":"";
        $modelList = ($model_list)?" AND mrm.model_list_id =  {$model_list}":"";
        $sql = sprintf($sql,$raw_id,$modelList);
        $acs = Yii::$app->db->createCommand($sql)->queryAll();
        $res = [];
        $ip = [];
        foreach ($acs as $item) {
            $ip[$item['id']]['ip'] .= " (".$item['ne']."-".$item['thread'] . ")";
            $res[$item['tname']][$item['id']] = $item['code'] ." - <b>". $item['rname'] . " - " . $item['tname'] ."</b>" . $ip[$item['id']]['ip'];
        }
        return $res;
    }

    public function getExistsPantone(){
        $res = [];
        if(!empty($this->color_pantone_id)){
            $color = $this->colorPan;
            $htmlVal =  '<span style="background:rgb('.$color->r.','.$color->g.','.$color->b.'); width:80px;padding-left:5px;padding-right:5px;border:1px solid"><span style="opacity:0;">TSX</span></span> '.$color->code.' - <b>'.$color->name.'</b>';
            $res[$this->color_pantone_id] = $htmlVal;
        }
        return $res;
    }

    public function getExistsBoyoqhona(){
        $res = [];
        if(!empty($this->boyoqhona_color_id)){
            $color = $this->boyoqhonaColor;
            $htmlVal =  '<span style="background:#'.$color->color.' width:80px;padding-left:5px;padding-right:5px;border:1px solid"><span style="opacity:0;">TSX</span></span> '.$color->color_id;
            $res[$this->boyoqhona_color_id] = $htmlVal;
        }
        return $res;
    }

    public function getChild()
    {
        $child = ModelsVariationColors::find()->joinWith('rawMaterial')->where(['model_var_id'=>$this->id,'toquv_raw_materials.type'=>ToquvRawMaterials::MATO])->groupBy(['color_pantone_id','toquv_raw_material_id'])->all();
        if(!empty($child)){
            return $child;
        }
        return [];
    }

    public static function getListVar($id)
    {
        $sql = "SELECT mv.id, mv.name as mname, mv.code as mcode, r,g,b, mvc.is_main, cp.code
                FROM models_variations as mv
                LEFT JOIN models_variation_colors as mvc ON mv.id = mvc.model_var_id
                LEFT JOIN color_pantone cp on mvc.color_pantone_id = cp.id
                WHERE mv.status = 1 AND mv.model_list_id = %d
                ORDER BY mvc.is_main
        ";
        $sql = sprintf($sql,$id);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $result = [];
        $response = [];
        if (!empty($res)) {
            $arr['check'] = [];
            foreach ($res as $item) {
                if($item['is_main']!='0') {
                    $name = "<span style='background:rgb(".$item['r'].",".$item['g'].",".$item['b'].");
                    padding-left:7px;padding-right:7px;border:1px solid;border-radius: 20px'></span> &nbsp; <b> {$item['code']} " . $item['mname'] . " </b> <small>". $item['mcode'] ."</small>";
                    array_push($result,[
                        'id' => $item['id'],
                        'text' => $name,
                    ]);
                    $arr['check'][$item['id']] = [$item['id']];
                }else{
                    if(!array_key_exists($item['id'], $arr['check'])){
                        $name = "<span style='background:rgb(".$item['r'].",".$item['g'].",".$item['b']."); width:80px;
                    padding-left:5px;padding-right:5px;border:1px solid'></span>  &nbsp; <b>  {$item['code']} " . $item['mname'] . " </b> <small>". $item['mcode'] ."</small>";
                        array_push($result,[
                            'id' => $item['id'],
                            'text' => $name,
                        ]);
                    }
                }
            }
            $response = ArrayHelper::map($result,'id','text');
        }
        return $response;
    }

    public function getColorsPantone()
    {
        $color = ArrayHelper::map(\app\modules\boyoq\models\ColorPantone::find()->all(), 'id', function($m){
            return $m->name.' '.$m->code;
        });
        return $color;
    }

    public function getModelsPechat()
    {
        return $this->hasOne(ModelsPechat::class, ['id' => 'pechat_id']);
    }

    public function getModelsNaqsh()
    {
        return $this->hasOne(ModelsNaqsh::class, ['id' => 'naqsh_id']);
    }
}
