<?php

namespace app\modules\base\models;

use Yii;
use yii\db\ActiveQuery;
use yii\helpers\FileHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%base_model_document}}".
 *
 * @property int $id
 * @property string $doc_number
 * @property string $date
 * @property int $model_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property ModelsList $model
 * @property BaseModelDocumentItems[] $baseModelDocumentItems
 * @property BaseModelSizes[] $baseModelSizes
 * @property BaseModelTableFile[] $baseModelTableFiles
 * @property BaseModelTikuvFiles[] $baseModelTikuvFiles
 * @property BaseModelTikuvNote[] $baseModelTikuvNotes
 */
class BaseModelDocument extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%base_model_document}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['model_id', 'doc_number'], 'required'],
            [['model_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['doc_number'], 'string', 'max' => 255],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelsList::className(), 'targetAttribute' => ['model_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'doc_number' => Yii::t('app', 'Doc Number'),
            'date' => Yii::t('app', 'Date'),
            'model_id' => Yii::t('app', 'Model ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModel()
    {
        return $this->hasOne(ModelsList::className(), ['id' => 'model_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseModelDocumentItems()
    {
        return $this->hasMany(BaseModelDocumentItems::className(), ['doc_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseModelSizes()
    {
        return $this->hasMany(BaseModelSizes::className(), ['doc_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseModelTableFiles()
    {
        return $this->hasMany(BaseModelTableFile::className(), ['doc_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseModelTikuvFiles()
    {
        return $this->hasMany(BaseModelTikuvFiles::className(), ['doc_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseModelTikuvNotes()
    {
        return $this->hasMany(BaseModelTikuvNote::className(), ['doc_id' => 'id']);
    }

    public function beforeSave($insert){

        if (!parent::beforeSave($insert))
            return false;
        if ($insert) {
            $this->status = self::STATUS_ACTIVE;
            $this->date = date('Y-m-d');
        }
        return true;
    }

    /**
     * @return $data
     * */
    public function getSaveData($data, $oldId = null){
        if(isset($data) && !empty($data)){
            $this->load($data);
            $session = Yii::$app->session;
            $transaction = Yii::$app->db->beginTransaction();
            $itemsId = [];
            $saved = false;
            try{
                if($this->save()) $saved = true;
                $docItems = $data['BaseModelSizes'];
                /** Document bolalarini saqlash */
                if($docItems){
                    foreach ($docItems as $key => $docItem) {
                        $items = new BaseModelDocumentItems();
                        $items->setAttributes([
                            'doc_id' => $this->id,
                            'add_info' => $docItem['add_info'],
                            'status' => $this::STATUS_ACTIVE
                        ]);
                        if($items->save() && $saved){
                            /** begin Update bo'lganda ishledi faqat */
                            if($oldId){
                                $tikuv = BaseModelTikuvFiles::find()->where(['doc_items_id' => $docItem['items_id']])->all()?BaseModelTikuvFiles::find()->where(['doc_items_id' => $docItem['items_id']])->all():false;
                                if($tikuv){
                                    foreach ($tikuv as $item) {
                                        $item['doc_items_id'] = $items->id;
                                        if($item->save() && $saved){
                                            $saved = true;
                                        }
                                        else{
                                            $saved = false;
                                            break 2;
                                        }
                                    }
                                }

                                $table = BaseModelTableFile::find()->where(['doc_items_id' => $docItem['items_id']])->all()?BaseModelTableFile::find()->where(['doc_items_id' => $docItem['items_id']])->all():false;
                                if($table){
                                    foreach ($table as $item) {
                                        $item['doc_items_id'] = $items->id;
                                        if($item->save() && $saved){
                                            $saved = true;
                                        }
                                        else{
                                            $saved = false;
                                            break 2;
                                        }
                                    }
                                }
                            }
                            /** end Update bo'lganda ishledi faqat */
                            $itemsId[$key] = $items->id;
                            $saved = true;
                            /** O'lchamlarini saqlash */
                            if($docItem['size_id']){
                                foreach ($docItem['size_id'] as $item) {
                                    $size = new BaseModelSizes();
                                    $size->scenarios($size::SCENARIO_DEFAULT);
                                    $size->setAttributes([
                                        'size_id' => $item,
                                        'doc_id' => $this->id,
                                        'doc_items_id' => $items->id,
                                        'status' => $this::STATUS_ACTIVE,
                                    ]);
                                    if($size->save() && $saved){
                                        $saved = true;
                                        unset($size);
                                    }
                                    else{
                                        $saved = false;
                                        break 2;
                                    }
                                }
                            }
                            unset($items);
                        }
                        else{
                            $saved = false;
                            break;
                        }
                    }
                }
                if($saved){
                    $files = $_FILES['BaseModelSizes'];
                    $fileUploaded = self::FileUpload($files);
                    /** Tikuv File saqlash */
                    if(isset($fileUploaded->tikuv_file_name)){
                        foreach ($fileUploaded->tikuv_file_name as $key => $items){
                            if($items){
                                foreach ($items as $k => $item){
                                    if(!empty($item)){
                                        $attachments = new Attachments();
                                        $attachments->setAttributes([
                                            'name' => $item,
                                            'size' => $fileUploaded->tikuv_file_size[$key][$k]?$fileUploaded->tikuv_file_size[$key][$k]:'',
                                            'path' => '/uploads/'.$item,
                                            'status' => $this::STATUS_ACTIVE,
                                        ]);
                                        if($attachments->save() && $saved){
                                            $tikuvFiles = new BaseModelTikuvFiles();
                                            $tikuvFiles->setAttributes([
                                                'attachment_id' => $attachments->id,
                                                'doc_id' => $this->id,
                                                'doc_items_id' => $itemsId[$key]??'',
                                                'status' => $this::STATUS_ACTIVE,
                                            ]);
                                            if($tikuvFiles->save() && $saved){
                                                $saved = true;
                                                unset($tikuvFiles);
                                            }
                                            else{
                                                $saved = false;
                                                break 2;
                                            }
                                            $saved = true;
                                            unset($attachments);
                                        }
                                        else{
                                            $saved = false;
                                            break 2;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    /** Table File saqlash */
                    if(isset($fileUploaded->table_file_name)){
                        foreach ($fileUploaded->table_file_name as $key => $items) {
                            if($items){
                                foreach ($items as $k => $item) {
                                    if(!empty($item)){
                                        $attachments = new Attachments();
                                        $attachments->setAttributes([
                                            'name' => $item,
                                            'size' => $fileUploaded->table_file_size[$key][$k]?$fileUploaded->table_file_size[$key][$k]:'',
                                            'path' => '/uploads/'.$item,
                                            'status' => $this::STATUS_ACTIVE,
                                        ]);
                                        if($attachments->save() && $saved){
                                            $tikuvFiles = new BaseModelTableFile();
                                            $tikuvFiles->setAttributes([
                                                'attachment_id' => $attachments->id,
                                                'doc_id' => $this->id,
                                                'doc_items_id' => $itemsId[$key]??'',
                                                'status' => $this::STATUS_ACTIVE,
                                            ]);
                                            if($tikuvFiles->save() && $saved){
                                                $saved = true;
                                                unset($tikuvFiles);
                                            }
                                            else{
                                                $saved = false;
                                                break 2;
                                            }
                                            $saved = true;
                                            unset($attachments);
                                        }
                                        else{
                                            $saved = false;
                                            break 2;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    /** Note larini yozish */
                    $notes = $data['BaseModelTikuvNote'];
                    if($notes){
                        foreach ($notes as $key => $note) {
                            foreach ($note as $item) {
                                $notification = new BaseModelTikuvNote();
                                $notification->setAttributes([
                                    'doc_id' => $this->id,
                                    'doc_items_id' => $itemsId[$key]??'',
                                    'note' => $item['note'],
                                    'status' => $this::STATUS_ACTIVE,
                                ]);
                                if($notification->save() && $saved){
                                    $saved = true;
                                    unset($notification);
                                }
                                else{
                                    $saved = false;
                                    break 2;
                                }
                            }
                        }
                    }

                    /** Transaction commit */
                    if($saved){
                        $transaction->commit();
                        $session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                        return true;
                    }
                    else{
                        $transaction->rollBack();
                        $session->setFlash('error', Yii::t('app', 'Saqlashda xatolik mavjud!'));
                        return false;
                    }

                }
            }
            catch(\Exception $e){
                Yii::info('Error Message '.$e->getMessage(), 'save');
                $session->setFlash('error', Yii::t('app', "Ma'lumotlar saqlanmadi!"));
                return false;
            }
        }
    }

    /**
     * @return Uploaded file
     * */
    public static function FileUpload($data){
        $object = new \stdClass();
        $key1 = 'tikuv_file_name';
        $key2 = 'table_file_name';
        $key3 = 'table_file_tmp_name';
        $key4 = 'tikuv_file_tmp_name';
        $key5 = 'tikuv_file_type';
        $key6 = 'table_file_type';
        $key7 = 'tikuv_file_size';
        $key8 = 'table_file_size';
        if(isset($data['name'])){
            $tikuv = 0;
            $table = 0;
            foreach ($data['name'] as $k => $datum) {
                if(is_array($datum) && isset($datum['tikuv_file'])){
                    foreach ($datum['tikuv_file'] as $key => $item) {
                        if(!empty($item)){
                            $name = explode('.', $item);
                            $baseName = 'tikuv_'.time().'_'.$tikuv.'.'.$name[count($name) - 1];
                            $object->$key1[$k][] = $baseName;
                        }
                        else{
                            $object->$key1[$k][] = $item;
                        }
                        $tikuv++;
                    }
                }
                if(is_array($datum) && isset($datum['table_file'])){
                    foreach ($datum['table_file'] as $key => $item) {
                        if(!empty($item)){
                            $name1 = explode('.', $item);
                            $baseName1 = 'table_'.time().'_'.$table.'.'.$name1[count($name1) - 1];
                            $object->$key2[$k][] = $baseName1;
                        }
                        else{
                            $object->$key2[$k][] = $item;
                        }
                        $table++;
                    }
                }
            }
            foreach ($data['tmp_name'] as $k => $datum) {
                if(is_array($datum) && isset($datum['tikuv_file'])){
                    foreach ($datum['tikuv_file'] as $key => $item) {
                        $object->$key4[$k][] = $item;
                    }
                }
                if(is_array($datum) && isset($datum['table_file'])){
                    foreach ($datum['table_file'] as $key => $item) {
                        $object->$key3[$k][] = $item;
                    }
                }
            }
            foreach ($data['type'] as $k => $datum) {
                if(is_array($datum) && isset($datum['tikuv_file'])){
                    foreach ($datum['tikuv_file'] as $key => $item) {
                        $object->$key5[$k][] = $item;
                    }
                }
                if(is_array($datum) && isset($datum['table_file'])){
                    foreach ($datum['table_file'] as $key => $item) {
                        $object->$key6[$k][] = $item;
                    }
                }
            }
            foreach ($data['size'] as $k => $datum) {
                if(is_array($datum) && isset($datum['tikuv_file'])){
                    foreach ($datum['tikuv_file'] as $key => $item) {
                        $object->$key7[$k][] = $item;
                    }
                }
                if(is_array($datum) && isset($datum['table_file'])){
                    foreach ($datum['table_file'] as $key => $item) {
                        $object->$key8[$k][] = $item;
                    }
                }
            }
        }

        if(is_object($object)){
            if(count($object->tikuv_file_name) === count($object->table_file_name)){
                foreach ($object->tikuv_file_name as $k => $items) {
                    if($items && is_array($items)){
                        foreach ($items as $n => $item) {
                            if(!empty($item) && !empty($object->tikuv_file_tmp_name[$k][$n])){
                                $path = 'uploads/'.$item;
                                $tmp_name = $object->tikuv_file_tmp_name[$k][$n]??'';
                                move_uploaded_file($tmp_name, $path);
                            }
                        }
                    }
                }
                foreach ($object->table_file_name as $k => $items) {
                    if($items && is_array($items)){
                        foreach ($items as $n => $item) {
                            if(!empty($item) && !empty($object->table_file_tmp_name[$k][$n])){
                                $path = 'uploads/'.$item;
                                $tmp_name = $object->table_file_tmp_name[$k][$n]??'';
                                move_uploaded_file($tmp_name, $path);
                            }
                        }
                    }
                }
            }
        }
        return $object;
    }
    
    /** Filellarni olish */
    public static function getFileShow($data)
    {
        $pluginOptions = [];
        $str = [];
        if(!empty($data) && isset($data)){
            foreach ($data as $key => $datum) {
                 if($datum){
                     foreach ($datum as $k => $item) {
                         if(!is_array($item) && is_int($k)){
                             $str[] = $item;
                         }
                         else{
                             $str['id'] = $item;
                         }
                     }
                 }
                 $pluginOptions[$key] = $str;
                 $str = [];
            }
        }
        return $pluginOptions;
    }
}
