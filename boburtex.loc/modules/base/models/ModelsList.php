<?php

namespace app\modules\base\models;

use app\components\OurCustomBehavior;
use app\components\Util;
use app\models\UploadForm;
use app\models\Users;
use app\modules\bichuv\Bichuv;
use app\modules\bichuv\models\BichuvAcs;
use app\modules\boyoq\models\ColorPantone;
use app\modules\hr\models\HrEmployee;
use app\modules\toquv\models\ToquvRawMaterials;
use app\modules\wms\models\WmsMatoInfo;
use app\widgets\helpers\Telegram;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;


/**
 * This is the model class for table "models_list".
 *
 * @property int $id
 * @property string $name
 * @property string $long_name
 * @property string $article
 * @property int $view_id
 * @property int $type_id
 * @property int $type_child_id
 * @property int $type_2x_id
 * @property string $add_info
 * @property string $washing_notes
 * @property string $finishing_notes
 * @property string $packaging_notes
 * @property string $default_comment
 * @property string $product_details
 * @property int $model_season
 * @property int $users_id
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $brend_id
 * @property int $baski
 * @property int $baski_rotatsion
 * @property int $prints
 * @property int $stone
 * @property int $updated_by
 *
 * @property ModelCommercialChart[] $modelCommercialCharts
 * @property ModelCommentAttachment[] $modelCommentAttachments
 * @property ModelRelAttach[] $modelRelAttaches
 * @property ModelSketch[] $sketch
 * @property ModelMeasurementChart[] $measurement
 * @property ModelsAcs[] $modelsAcs
 * @property ModelsPechat[] $pechats
 * @property ModelsNaqsh[] $naqshs
 * @property ModelsToquvAcs[] $modelsToquvAcs
 * @property ModelSeason $modelSeason
 * @property BasePatterns $basePattern
 * @property ModelTypes $type2x
 * @property ModelTypes $typeChild
 * @property ModelTypes $type
 * @property Users $users
 * @property ModelView $view
 * @property ModelsRawMaterials[] $modelsRawMaterials
 * @property ModelsRawMaterials[] $modelToquvAcs
 * @property ModelsVariations[] $modelsVariations
 * @property array $attachmentConfigList
 * @property array $seasonList
 * @property mixed $lastVariation
 * @property array $measurementConfigList
 * @property null $patternList
 * @property array $measurementList
 * @property array $comment_AttachmentList
 * @property ActiveQuery $author
 * @property mixed $isMain
 * @property array $sketchList
 * @property ActiveQuery $updatedBy
 * @property array $comment_AttachmentConfigList
 * @property bool $image
 * @property array $sketchConfigList
 * @property ActiveQuery $brend
 * @property array $modelView
 * @property mixed $usersList
 * @property int $base_pattern_id [int(11)]
 * @property int $is_kit
 * @property $toquvAcs
 */
class ModelsList extends BaseModel
{
    /** Model rasmlarini saqlash uchun**/
    public $model_images;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'models_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','article'], 'required'],
            [['view_id','is_kit', 'type_id', 'base_pattern_id','type_child_id', 'type_2x_id', 'model_season', 'users_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at', 'brend_id', 'baski', 'prints', 'stone','baski_rotatsion'], 'integer'],
            [['add_info', 'washing_notes', 'finishing_notes', 'packaging_notes', 'default_comment', 'product_details'], 'string'],
            [['name', 'long_name'], 'string', 'max' => 255],
            [['article'], 'string', 'max' => 50],
            [['article'], 'unique'],
            [['model_season'], 'exist', 'skipOnError' => true, 'targetClass' => ModelSeason::class, 'targetAttribute' => ['model_season' => 'id']],
            [['type_2x_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelTypes::class, 'targetAttribute' => ['type_2x_id' => 'id']],
            [['type_child_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelTypes::class, 'targetAttribute' => ['type_child_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelTypes::class, 'targetAttribute' => ['type_id' => 'id']],
            [['users_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::class, 'targetAttribute' => ['users_id' => 'id']],
            [['view_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelView::class, 'targetAttribute' => ['view_id' => 'id']],
            [['base_pattern_id'], 'exist', 'skipOnError' => true, 'targetClass' => BasePatterns::class, 'targetAttribute' => ['base_pattern_id' => 'id']],
        ];
    }
    public function beforeValidate()
    {
        $this->article =  preg_replace('/[^A-Za-z0-9\-]/', '', $this->article);
        return parent::beforeValidate();
    }

    public function beforeSave($insert) {
        if ($insert) {
            /*$this->article = preg_replace('/\s+/','',$this->article);*/
            $this->article =  preg_replace('/[^A-Za-z0-9\-]/', '', $this->article);
        } else {
            $this->article =  preg_replace('/[^A-Za-z0-9\-]/', '', $this->article);
        }
        return parent::beforeSave($insert);
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'long_name' => Yii::t('app', 'Long Name'),
            'article' => Yii::t('app', 'Article'),
            'base_pattern_id' => Yii::t('app', 'Base Pattern ID'),
            'view_id' => Yii::t('app', 'View ID'),
            'type_id' => Yii::t('app', 'Type ID'),
            'type_child_id' => Yii::t('app', 'Type Child ID'),
            'type_2x_id' => Yii::t('app', 'Type 2x ID'),
            'add_info' => Yii::t('app', 'Add Info'),
            'washing_notes' => Yii::t('app', 'Washing Notes'),
            'finishing_notes' => Yii::t('app', 'Finishing Notes'),
            'packaging_notes' => Yii::t('app', 'Packaging Notes'),
            'default_comment' => Yii::t('app', 'Default Comment'),
            'product_details' => Yii::t('app', 'Product Details'),
            'model_season' => Yii::t('app', 'Model Season'),
            'users_id' => Yii::t('app', 'Designer'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', "O'zgartirdi"),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'brend_id' => Yii::t('app', 'Brend'),
            'baski' => Yii::t('app', 'Tub bosma'),
            'prints' => Yii::t('app', 'Print'),
            'stone' => Yii::t('app', 'Naqsh\Tosh'),
            'baski_rotatsion' => Yii::t('app', 'Rotatsion bosma'),
            'rawMaterials' => Yii::t('app', 'Mato'),
            'is_kit' => Yii::t('app', 'Komplekt'),
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => OurCustomBehavior::class,
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => TimestampBehavior::class,
            ]
        ];
    }
    /**
     * @return ActiveQuery
     */
    public function getModelCommercialCharts()
    {
        return $this->hasMany(ModelCommercialChart::class, ['models_list_id' => 'id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getModelCommentAttachments()
    {
        return $this->hasMany(ModelCommentAttachment::class, ['models_list_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelRelAttaches()
    {
        return $this->hasMany(ModelRelAttach::class, ['model_list_id' => 'id'])->orderBy(['is_main'=>SORT_DESC]);
    }
    /**
     * @return ActiveQuery
     */
    public function getSketch()
    {
        return $this->hasMany(ModelSketch::class, ['models_list_id' => 'id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getMeasurement()
    {
        return $this->hasMany(ModelMeasurementChart::class, ['models_list_id' => 'id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getModelsAcs()
    {
        return $this->hasMany(ModelsAcs::class, ['model_list_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     * */
    public function getPechats()
    {
        return $this->hasMany(ModelsPechat::class, ['models_list_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     * */
    public function getNaqshs()
    {
        return $this->hasMany(ModelsNaqsh::class, ['models_list_id' => 'id']);
    }


    public function getModelsToquvAcs()
    {
        return $this->hasMany(ModelsToquvAcs::class, ['models_list_id' => 'id'])->joinWith('wmsMatoInfo');
    }
    /**
     * @return ActiveQuery
     */
    public function getModelSeason()
    {
        return $this->hasOne(ModelSeason::class, ['id' => 'model_season']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBasePattern()
    {
        return $this->hasOne(BasePatterns::class, ['id' => 'base_pattern_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(HrEmployee::class, ['id' => 'users_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Users::class, ['id' => 'created_by']);
    }
    /**
     * @return ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(Users::class, ['id' => 'updated_by']);
    }
    /**
     * @return ActiveQuery
     */
    public function getType2x()
    {
        return $this->hasOne(ModelTypes::class, ['id' => 'type_2x_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTypeChild()
    {
        return $this->hasOne(ModelTypes::class, ['id' => 'type_child_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(ModelTypes::class, ['id' => 'type_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getView()
    {
        return $this->hasOne(ModelView::class, ['id' => 'view_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBrend()
    {
        return $this->hasOne(Brend::class, ['id' => 'brend_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModelsRawMaterials()
    {
        return $this->hasMany(ModelsRawMaterials::class, ['model_list_id' => 'id']);
    }
    public function getModelToquvAcs()
    {
        return $this->hasMany(ModelsRawMaterials::class, ['model_list_id' => 'id'])
            ->joinWith('rm')
            ->where(['toquv_raw_materials.type' => ToquvRawMaterials::ACS]);
    }


    /**
     * @return ActiveQuery
     */
    public function getModelsVariations()
    {
        return $this->hasMany(ModelsVariations::class, ['model_list_id' => 'id']);
    }

    /**
     * @param int $level
     * @return array
     */
    public static function getAllModelTypes($level = 1){
        $dataType = ModelTypes::find()->select(['id','name'])->where(['level' => $level, 'status' => ModelTypes::STATUS_ACTIVE])->asArray()->all();
        if(!empty($dataType)){
            return ArrayHelper::map($dataType,'id','name');
        }
        return [];
    }
    public static function getAllBrend(){
        $dataType = Brend::find()->where(['status' => Brend::STATUS_ACTIVE])->all();
        if(!empty($dataType)){
            return ArrayHelper::map($dataType,'id', 'name');
        }
        return [];
    }
    public function getModelTypes($id){
        $query = ModelTypes::find()
            ->andWhere(['parent' => $id])
            ->asArray()
            ->all();
        if(!empty($query)){
            return ArrayHelper::map($query,'id','name');
        }
    }

    public function getSeasonList(){
        $dataType = ModelSeason::find()->select(['id','name'])->where(['status' => ModelSeason::STATUS_ACTIVE])->asArray()->all();
        if(!empty($dataType)){
            return ArrayHelper::map($dataType,'id','name');
        }
        return [];
    }

    public function getUsersList()
    {
        $users = Users::find()->select(['id as users_id','user_fio'])->asArray()->all();
        return ArrayHelper::map($users,'users_id','user_fio');
    }
    /**
     * @return array
     */
    public static function getModelView(){
        $dataView = ModelView::find()->where(['status' => ModelView::STATUS_ACTIVE])->asArray()->all();
        if(!empty($dataView)){
            return ArrayHelper::map($dataView,'id','name');
        }

        return [];
    }

    public function getImage()
    {
        $image = ModelRelAttach::find()->where(['is_main'=>1,'model_list_id'=>$this->id])->orderBy(['id'=>SORT_DESC])->one();
        if(!$image){
            $image = ModelRelAttach::find()->where(['model_list_id'=>$this->id])->orderBy(['id'=>SORT_DESC])->one();
        }
        if ($image){
            $attachment = $image->attachment['path'];
            if(!empty($attachment)){
                return $attachment;
            }
        }
        return false;
    }

    /**
     * @param bool $config
     * @return array
     */
    public function getAttachmentList($config=false){
        $data = $this->modelRelAttaches;
        if(!empty($data)){
            $images = [];
            $imageConfig = [];
            $i = 1;
            foreach ($data as $key){
                $images[] = "/web/".$key->attachment['path'];
                $imageConfig[] = [
                    'caption' => "{$i}",
                    'key' => $key['id'],
                    'extra' => ['id' => $key['id']],
                ];
                $i++;
            }
            if($config){
                return $imageConfig;
            }
            return $images;
        }
        return [];
    }
    public function getAttachmentConfigList(){
        $data = $this->modelRelAttaches;
        if(!empty($data)){
            $images = [];
            $i= 1;
            foreach ($data as $key){
                $images[] = [
                    'caption' => "{$i}",
                    'key' => $key['id'],
                    'extra' => ['id' => $key['id']],
                ];
                $i++;
            }
            return $images;
        }
        return [];
    }
    /**
     * @return array
     */
    public function getSketchList(){
        $data = $this->sketch;

        if(!empty($data)){
            $images = [];
            foreach ($data as $key){
                $images[] = "/web/".$key['path'];
            }
            return $images;
        }
        return [];
    }
    public function getSketchConfigList(){
        $data = $this->sketch;
        if(!empty($data)){
            $images = [];
            $i = 0;
            foreach ($data as $key){
                $images[] = [
                    'caption' => "{$i}",
                    'key' => $key['id'],
                    'extra' => ['id' => $key['id']],
                ];
                $i++;
            }
            return $images;
        }
        return [];
    }
    /**
     * @return array
     */
    public function getMeasurementList(){
        $data = $this->measurement;
        if(!empty($data)){
            $images = [];
            foreach ($data as $key){
                $images[] = "/web/".$key['path'];
            }
            return $images;
        }
        return [];
    }
    public function getMeasurementConfigList(){
        $data = $this->measurement;
        if(!empty($data)){
            $images = [];
            $i = 0;
            foreach ($data as $key){
                $images[] = [
                    'caption' => "{$key['name']}",
                    'key' => $key['id'],
                    'extra' => ['id' => $key['id']],
                    'type' => ($key['extension']!='pdf')?$key['type']:$key['extension'],
//                    'filetype' => $key['type'].'/'.$key['extension'],
                    'downloadUrl' => "/web/".$key['path'],
                    'size' => $key['size']
                ];
                $i++;
            }
            return $images;
        }
        return [];
    }
    /**
     * @return array
     */
    public function getComment_AttachmentList(){
        $data = $this->modelCommentAttachments;
        if(!empty($data)){
            $images = [];
            foreach ($data as $key){
                $images[] = "/web/".$key['path'];
            }
            return $images;
        }
        return [];
    }
    public function getComment_AttachmentConfigList(){
        $data = $this->modelCommentAttachments;
        if(!empty($data)){
            $images = [];
            $i = 0;
            foreach ($data as $key){
                $images[] = [
                    'caption' => "{$key['name']}",
                    'key' => $key['id'],
                    'extra' => ['id' => $key['id']],
                    'type' => ($key['extension']!='pdf')?$key['type']:$key['extension'],
//                    'filetype' => $key['type'].'/'.$key['extension'],
                    'downloadUrl' => "/web/".$key['path'],
                    'size' => $key['size']
                ];
                $i++;
            }
            return $images;
        }
        return [];
    }
    public function getIsMain(){
        $isMain = ModelRelAttach::find()->where(['is_main'=>1,'model_list_id'=>$this->id])->one();
        return $isMain['id'];
    }

    public function getHrEmployeeList()
    {
        $users = HrEmployee::find()->select(['id', 'fish'])->asArray()->all();
        return ArrayHelper::map($users, 'id', 'fish');
    }

    public function saveMaterials($data){
        $saved = true;
        if($data) {
            $i = 0;
            foreach ($data as $key) {
                if (!empty($key['rm_id'])) {
                    $item = new ModelsRawMaterials();
                    $item->setAttributes([
                        'model_list_id' => $this->id,
                        'rm_id' => $key['rm_id'],
                        /*'thread_length' => $key['thread_length'],
                        'finish_en' => $key['finish_en'],
                        'finish_gramaj' => $key['finish_gramaj'],*/
                        'is_main' => ($i == 0) ? 1 : 0,
                        'add_info' => $key['add_info'],
                        'color_id' => $key['color_id'],
                    ]);
                    if($item->save()){
                        $saved = true;
                        if(!empty($key['sizes'])){
                            foreach ($key['sizes'] as $size) {
                                $mrs = new ModelsRawMaterialsSizes([
                                    'models_raw_materials_id' => $item->id,
                                    'size_id' => $size
                                ]);
                                if($mrs->save()){
                                    $saved = true;
                                }else{
                                    $saved = false;
                                    break 2;
                                }
                            }
                        }
                    }else{
                        $saved = false;
                        break;
                    }
                    $i++;
                }
            }
        }
        return $saved;
    }
    public function saveAcs($data){
        $saved = true;
        if($data) {
            foreach ($data as $key) {
                if (!empty($key['bichuv_acs_id']) && isset($key['bichuv_acs_id'])) {
                    $item_acs = new ModelsAcs();
                    $item_acs->setAttributes([
                        'model_list_id' => $this->id,
                        'bichuv_acs_id' => $key['bichuv_acs_id'],
                        'qty' => $key['qty'],
                        'add_info' => $key['add_info'],
                        'type' => ModelsAcs::bichuv_type,
                    ]);
                    if($item_acs->save()){
                        $saved = true;
                        if(!empty($key['sizes'])){
                            foreach ($key['sizes'] as $size) {
                                $mrs = new ModelsAcsSizes([
                                    'models_acs_id' => $item_acs->id,
                                    'size_id' => $size
                                ]);
                                if($mrs->save()){
                                    $saved = true;
                                }else{
                                    $saved = false;
                                    break 2;
                                }
                            }
                        }
                    }else{
                        $saved = false;
                        break;
                    }
                }
            }
        }
        return $saved;
    }

    public function saveToquvAcs($data){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $saved = true;
            if($data) {
                foreach ($data as $key) {
                    if (!empty($key['toquv_acs_id'])) {
                        /** Toquv Accessorylarini saqlash*/
                            $toquvAcsAttributes = [
                                'toquv_raw_materials_id' => $key['toquv_acs_id'],
                                'wms_color_id' => $key['wms_color_id'],
                                'pus_fine_id' => $key['pus_fine_id'],
                                'wms_desen_id' => $key['wms_desen_id'],
                                'en' => $key['en'],
                                'gramaj' => $key['gramaj'],
                                'type' => ToquvRawMaterials::ACS,
                            ];
                            $wmsToquvAcs = WmsMatoInfo::saveAndGetId($toquvAcsAttributes, WmsMatoInfo::SCENARIO_MODEL_ACCESSORY);
                            if($wmsToquvAcs){
                                $item_acs = new ModelsToquvAcs();
                                $item_acs->setAttributes([
                                    'models_list_id' => $this->id,
                                    'wms_mato_info_id' => $wmsToquvAcs,
                                    'qty' => $key['qty'],
                                    'add_info' => $key['add_info'],
                                ]);
                                if($item_acs->save() && $saved){
                                    if(!empty($key['sizes'])){
                                        foreach ($key['sizes'] as $size) {
                                            $mrs = new ModelsToquvAcsSizes([
                                                'models_toquv_acs_id' => $item_acs->id,
                                                'size_id' => $size
                                            ]);
                                            if($mrs->save()){
                                                $saved = true;
                                            }else{
                                                $saved = false;
                                                break 2;
                                            }
                                        }
                                    }
                                }
                                else{
                                    $saved = false;
                                    break;
                                }
                            }
                    }
                }
            }
            if($saved){
                $transaction->commit();
                return $saved;
            }
            else{
                $transaction->rollBack();
                return $saved;
            }
        }
        catch (\Exception $e){
            Yii::info('error message '.$e->getMessage(),'save');
        }
    }
    public function saveAttachments($data){
        if($data){
            $i = 0;
            foreach ($data as $key) {
                if (!empty($key)&&$key>0) {
                    $item = new ModelRelAttach();
                    $item->setAttributes([
                        'attachment_id' => $key,
                        'model_list_id' => $this->id,
                        'type' => $this->type_id,
                        'is_main' => ($i == 0 && !isset($data['ModelsList']['isMain'])) ? 1 : 0,
                    ]);
                    $item->save();
                    $i++;
                }
            }
        }
    }
    public function removeAttachments($data){
        if($data){
            foreach ($data as $key) {
                if (!empty($key)&&$key>0) {
                    $item = ModelRelAttach::findOne($key);
                    if($item){
                        $item->deleteOne();
                    }
                }
            }
        }
    }
    public function removeItems($item,$data){
        if($data){
            switch ($item) {
                case 'sketch':
                    foreach ($data as $key) {
                        if (!empty($key) && $key > 0) {
                            $item = ModelSketch::findOne($key);
                            $item->deleteOne();
                        }
                    }
                    break;
                case 'measurement':
                    foreach ($data as $key) {
                        if (!empty($key) && $key > 0) {
                            $item = ModelMeasurementChart::findOne($key);
                            $item->deleteOne();
                        }
                    }
                    break;
                default:
                    return false;
            }
        }
    }
    public function saveVariations($data){
        if($data){
            $n = 0;
            foreach ($data as $key) {
                $item = new ModelsVariations();
                $item->setAttributes([
                    'model_list_id' => $this->id,
                    'name' => $key['name'],
                ]);
                if ($item->save()) {
                    if (!empty($key['child'])) {
                        $i = 0;
                        foreach ($key['child'] as $m) {
                            $child = new ModelsVariationColors();
                            $child->setAttributes([
                                'model_var_id' => $item['id'],
                                'color_pantone_id' => $m['color_pantone_id'],
                                'is_main' => ($i == 0) ? 1 : 0,
                            ]);
                            $child->save();
                            $i++;
                        }
                    }
                    if ($image =
                        UploadedFile::getInstancesByName('ModelsVariations['.$n.']')) {
                        $i = 0;
                        foreach ($image as $m) {
                            $name = Util::generateRandomString() . '.' . $m->extension;
                            if($m->saveAs('uploads/' . $name)){
                                $attachment = new Attachments();
                                $attachment->setAttributes([
                                    'name' => $m->name,
                                    'size' => $m->size,
                                    'extension' => $m->type,
                                    'path' => $name,
                                ]);
                                if($attachment->save()){
                                    $rel = new ModelVarRelAttach();
                                    $rel->setAttributes([
                                        'attachment_id' => $attachment['id'],
                                        'model_var_id' => $item['id'],
                                        'is_main' => ($i == 0) ? 1 : 0,
                                    ]);
                                    $rel->save();
                                    $i++;
                                }
                            }
                        }
                    }
                    if (!empty($key['stone'])) {
                        $i = 0;
                        foreach ($key['stone'] as $m) {
                            $stone = new ModelsVarStone();
                            $stone->setAttributes([
                                'model_var_id' => $item['id'],
                                'name' => $m['name'],
                                'add_info' => $m['add_info'],
                            ]);
                            $stone->save();
                            $i++;
                        }
                    }
                }
                $n++;
            }
        }
    }
    public function uploadSketch($files,$dir=null)
    {
        if ($this->validate()) {
            $directory = ($dir!=null)?'uploads' . "/" . $dir . "/":'uploads' . "/";
            if (!is_dir($directory)) {
                FileHelper::createDirectory($directory);
            }
            $ismain = ModelSketch::find()->where(['models_list_id'=>$this->id,'is_main'=>1])->one();
            foreach ($files as $file) {
                $name = $directory.Util::generateRandomString() . '.' . $file->extension;
                $file->saveAs($name);
                $item = new ModelSketch();
                $item->setAttributes([
                    'models_list_id' => $this->id,
                    'name' => $file->name,
                    'size' => $file->size,
                    'extension' => $file->extension,
                    'path' => $name,
                    'is_main' => (!$ismain)?1:0
                ]);
                $item->save();
            }
            return $item['id'];
        } else {
            return 'error';
        }
    }
    public function uploadMeasurement($files,$dir=null)
    {
        if ($this->validate()) {
            $directory = ($dir!=null)?'uploads' . "/" . $dir . "/":'uploads' . "/";
            if (!is_dir($directory)) {
                FileHelper::createDirectory($directory);
            }
            $ismain = ModelMeasurementChart::find()->where(['models_list_id'=>$this->id,'is_main'=>1])->one();
            foreach ($files as $file) {
                $type = explode("/",$file->type);
                $name = $directory.Util::generateRandomString() . '.' . $file->extension;
                $file->saveAs($name);
                $item = new ModelMeasurementChart();
                $item->setAttributes([
                    'models_list_id' => $this->id,
                    'name' => $file->name,
                    'size' => $file->size,
                    'extension' => $file->extension,
                    'type' => $type[0],
                    'path' => $name,
                    'is_main' => (!$ismain)?1:0
                ]);
                $item->save();
            }
            return $item['id'];
        } else {
            return 'error';
        }
    }
    public function uploadCommentAttachment($files,$dir=null)
    {
        if ($this->validate()) {
            $directory = ($dir!=null)?'uploads' . "/" . $dir . "/":'uploads' . "/";
            if (!is_dir($directory)) {
                FileHelper::createDirectory($directory);
            }
            foreach ($files as $file) {
                $type = explode("/",$file->type);
                $name = $directory.Util::generateRandomString() . '.' . $file->extension;
                $file->saveAs($name);
                $item = new ModelCommentAttachment();
                $item->setAttributes([
                    'models_list_id' => $this->id,
                    'name' => $file->name,
                    'size' => $file->size,
                    'extension' => $file->extension,
                    'type' => $type[0],
                    'path' => $name,
                ]);
                $item->save();
            }
            return $item['id'];
        } else {
            return 'error';
        }
    }

    /**
     * @param string $lang
     * @return bool|string
     */
    public function getRmConsist($lang = 'ru'){
        $rm = ModelsRawMaterials::find()->where(['model_list_id'=>$this->id,'is_main'=>1])->one();
        if(!$rm){
            $rm = ModelsRawMaterials::find()->where(['model_list_id'=>$this->id])->one();
        }
        if($rm){
            return $rm->rm->getRawMaterialConsist($lang);
        }
        return false;
    }

    public function getPatternList(){
        $pl = BasePatterns::find()->joinWith(['construct'])->asArray()->all();
        if(empty($pl)) return null;
        return ArrayHelper::map($pl,'id', function ($p){
            return  $p['name']." (".$p['construct']['fish'].")";
        });
    }

    public function getLastVariation(){
        $sql = "select mvc.base_detail_list_id as id, 
                       mvc.toquv_raw_material_id as rm 
                 from models_variation_colors mvc
                 where mvc.model_var_id  = (select mv.id from models_variations mv 
                     left join models_list ml on mv.model_list_id = ml.id 
                 where ml.id = :id ORDER BY mv.id ASC limit 1);";
        $res = Yii::$app->db->createCommand($sql)->bindValue('id',$this->id)->queryAll();
        return ArrayHelper::map($res,'id','rm');
    }

    public static function getAuthorList()
    {
        $sql = "select u.id,user_fio from users u
                left join models_list ml on u.id = ml.created_by
                WHERE ml.id is not null
                GROUP BY u.id
        ";
        $list = Yii::$app->db->createCommand($sql)->queryAll();
        return ArrayHelper::map($list,'id','user_fio');
    }

    public static function getUpdatedByList()
    {
        $sql = "select u.id,user_fio from users u
                left join models_list ml on u.id = ml.updated_by
                WHERE ml.id is not null
                GROUP BY u.id
        ";
        $list = Yii::$app->db->createCommand($sql)->queryAll();
        return ArrayHelper::map($list,'id','user_fio');
    }

    public static function getAllModelViews()
    {
        $dataView = ModelView::find()->where(['status' => ModelView::STATUS_ACTIVE])->asArray()->all();
        if(!empty($dataView)){
            return ArrayHelper::map($dataView,'id','name');
        }
        return [];
    }

    public static function getAllTypes($level = 1) {
        $dataType = ModelTypes::find()->select(['id','name'])->where(['level' => $level, 'status' => ModelTypes::STATUS_ACTIVE])->asArray()->all();
        if(!empty($dataType)){
            return ArrayHelper::map($dataType,'id','name');
        }
        return [];
    }

    public static function getListModel($id)
    {
        $sql = "SELECT m.id as id, m.name as mname, m.article as mart, atch.path, view.name as vname, type.name as tname,
                mra.is_main FROM models_list as m
                LEFT JOIN model_rel_attach as mra ON mra.model_list_id = m.id
                LEFT JOIN attachments as atch ON atch.id = mra.attachment_id
                LEFT JOIN model_view as view ON m.view_id = view.id
                LEFT JOIN model_types as type ON m.type_id = type.id
                WHERE m.id = %d
                ORDER BY mra.is_main ASC
        ";
        $sql = sprintf($sql,$id);
        $row = Yii::$app->db->createCommand($sql)->queryAll();
        $res = [];
        foreach ($row as $item) {
            $image = (!empty($item['path']))?"<img src='/web/" . $item['path'] . "' style='width:30px;height:30px;border:1px solid' class='imgPreview'> ":'';
            $res[$item['id']] = [
                'id' => $item['id'],
                'name' => $image."<b> ".$item['mart'] . " </b> - ". $item['mname'] ." - ". $item['tname'],
                'group' => $item['vname']
            ];
        }
        $result = ArrayHelper::map($res,'id','name');
        return $result;
    }

    /** Qoliplarni id larini olish */
    public function getPatternsId($id,$type=1)
    {
        $sql = "
            SELECT
                model_orders.*,
                model_orders_variations.*,
                base_patterns.id AS b_id,
                base_pattern_items.*
            FROM 
                model_orders
            INNER JOIN 
                model_orders_variations
            ON
                model_orders.id = model_orders_variations.model_orders_id
            INNER JOIN
                base_patterns
            ON	model_orders_variations.base_patterns_id = base_patterns.id
            INNER JOIN
                base_pattern_items
            ON
                base_patterns.id = base_pattern_items.base_pattern_id
            WHERE
                model_orders.id = {$id}
        ";
        if($type == 1)
            $queryOne = Yii::$app->db->createCommand($sql)->queryOne();
        elseif($type == 2) {
            $sql .= "
                 GROUP BY
	                model_orders_items_material.mato_id
            ";
            $queryOne = Yii::$app->db->createCommand($sql)->queryAll();
        }
        if(empty($queryOne))
            return false;
        return $queryOne;
    }

    public function getSizes($data=null)
    {
        if($data != null){
            $size = Size::find()
                ->where(['in', 'id', $data])
                ->asArray()
                ->all();
            return $size;
        }
        return ArrayHelper::map(Size::find()->all(), 'id', 'name');
    }

    public function getArrayAll($data, $type=1)
    {
        if($type == 1){
            if(is_array($data) && !empty($data)){
                $materials = ModelOrdersItemsMaterial::find()
                    ->where(['in', 'model_orders_items_id', $data])
                    ->all();
                return $materials;
            }
            else{
                return false;
            }
        }
        elseif($type == 2){
            if(is_array($data) && !empty($data)){
                $materials = ModelOrdersItemsAcs::find()
                    ->where(['in', 'model_orders_items_id', $data])
                    ->all();
                return $materials;
            }
            else{
                return false;
            }
        }
    }

    public function getArrayMapModel($token=null)
    {
        if($token != null){
            $result = ArrayHelper::map(ToquvRawMaterials::find()->where(['type' => 2])->all(), 'id', 'name');
            return $result;
        }
        $result = ArrayHelper::map(BichuvAcs::find()->all(), 'id', 'name');
        return $result;
    }

    public function getColorsPantone()
    {
        $color = ColorPantone::find()->asArray()->all();
        return ArrayHelper::map($color,'id', 'name');
    }

    public function savePechats($data)
    {
        $array = [];
        if(isset($data['attachments_id']) && !empty($data['attachments_id'])){
            foreach ($data['attachments_id'] as $key => $item) {
                $attachments = new Attachments();
                $attachments->setAttributes([
                    'path' => $item,
                    'status' => Attachments::STATUS_ACTIVE
                ]);
                if($attachments->save()){
                    $array[] = $attachments->id;
                    unset($attachments);
                }
                else{
                    break;
                }
            }
        }

        foreach ($data as $k => $item){
                if(is_int($k) && !empty($item['title'])){
                    $pechat = new ModelsPechat();
                    $pechat->setAttributes([
                        'models_list_id' => $this->id,
                        'title' => $item['title'],
                        'content' => $item['content'],
                        'attachments_id' => $array[$k] ?? null,
                    ]);
                    if($pechat->save()){
                        $saved = true;
                        unset($pechat);
                    }
                    else{
                        $saved = false;
                        break;
                    }
                }
            }
        return true;
    }

    public function saveNaqshs($data)
    {
        $array = [];
        if(isset($data['attachments_id']) && !empty($data['attachments_id'])){
            foreach ($data['attachments_id'] as $key => $item) {
                $attachments = new Attachments();
                $attachments->setAttributes([
                    'path' => $item,
                    'status' => Attachments::STATUS_ACTIVE
                ]);
                if($attachments->save()){
                    $array[] = $attachments->id;
                    unset($attachments);
                }
                else{
                    break;
                }
            }
        }

        foreach ($data as $k => $item){
                if(is_int($k) && !empty($item['title'])){
                    $naqsh = new ModelsNaqsh();
                    $naqsh->setAttributes([
                        'models_list_id' => $this->id,
                        'title' => $item['title'],
                        'content' => $item['content'],
                        'attachments_id' => $array[$k] ?? null,
                    ]);
                    if($naqsh->save()){
                        $saved = true;
                        unset($naqsh);
                    }
                    else{
                        $saved = false;
                        break;
                    }
                }
            }

        return true;
    }

    /**
     * @return $array
     * */
    public function getToquvRawMaterialsAcc($id)
    {
        $data = ToquvRawMaterials::findOne($id);
        return $data;
    }

    public function getToquvAcs()
    {
        return $this->hasMany(ModelsToquvAcs::class, ['models_list_id' => 'id']);
    }


    public static function getModelListMap(){
        $models = self::find()->select(['id', 'CONCAT(name," (",article,")") as name'])->all();
        return ArrayHelper::map($models,'id','name');
    }

    public function saveModelAttachments($data){
        $saved = false;

        foreach ($data['model_images'] as $key => $model_image){
            $namePath = substr($model_image, strrpos($model_image,"/") + 1);
            $attachment =  new  Attachments([
               'name' => $namePath,
               'path' => $model_image,
           ]);
            if ($attachment->save()){
                $saved = true;
                $is_main = 0;
                if ($key == 0)
                    $is_main = 1;
                $modelRelAttachmant = new ModelRelAttach([
                    'attachment_id' => $attachment->id,
                    'model_list_id' => $data['model_id'],
                    'is_main' => $is_main
                ]);
                if ($modelRelAttachmant->save()){
                    $saved = $saved && true;
                }else{
                    $saved = false;
                    break;
                }
            }
        }

        return $saved;
    }


}
