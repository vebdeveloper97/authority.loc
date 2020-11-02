<?php

namespace app\modules\admin\models;

use Yii;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "message_uz".
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $author
 * @property string|null $images
 * @property string|null $date
 * @property int $type
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class MessageUz extends BaseModel
{
    public $images;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message_'.Yii::$app->language;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content', 'author', 'type'], 'required'],
            [['content'], 'string'],
            [['date'], 'safe'],
            [['type', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['title', 'author'], 'string', 'max' => 100],
            [['images'], 'file', 'maxFiles' => 10, 'minFiles' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'author' => Yii::t('app', 'Author'),
            'images' => Yii::t('app', 'Images'),
            'date' => Yii::t('app', 'Date'),
            'type' => Yii::t('app', 'Type'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
    /**
     * {@inheritdoc}
     * beforesave Saqlashdan oldin ishlashi uchun
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            if(empty($this->status)){
                $this->status = 1;
            }
            if(!empty($this->date)){
                $date = str_replace('.', '-', $this->date);
                $this->date = date('Y-m-d', strtotime($date));
            }
            return true;
        }
        else{
            return false;
        }
    }

    public static function getDataSave($model,$id=null)
    {
        if($model){
            $transaction = Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                $images = UploadedFile::getInstances($model, 'images');
                if($id!=null){
                    if(!empty($images)){
                        $imagesOld = MessageAttachmentsUz::find()->where(['message_id' => $id])->all();
                        if($imagesOld){
                            foreach ($imagesOld as $item) {
                                $attachments = $item->attachments;
                                if($attachments){
                                    $item->delete();
                                    $attachments->delete();
                                }
                            }
                        }
                        foreach ($images as $image) {
                            $image->saveAs('img/uploads/'.$image->baseName.'.'.$image->extension);
                            $attachments = new Attachments();
                            $attachments->setAttributes([
                                'name' => $image->baseName,
                                'path' => '/img/uploads/'.$image->baseName.'.'.$image->extension,
                                'size' => $image->size,
                                'extension' => $image->extension
                            ]);
                            if($attachments->save()){
                                $messageAttachment = new MessageAttachmentsUz();
                                $messageAttachment->setAttributes([
                                    'attachments_id' => $attachments->id,
                                    'message_id' => $model->id,
                                ]);
                                if($messageAttachment->save()){
                                    $saved = true;
                                    unset($messageAttachment);
                                }
                                else{
                                    $saved = false;
                                    break;
                                }

                                unset($attachments);
                            }
                            else{
                                $saved = false;
                                break;
                            }
                        }

                    }

                }
                $saved = $model->save()?true:false;

                if(!empty($images) && $saved && $id==null){
                    foreach ($images as $image) {
                        $image->saveAs('img/uploads/'.$image->baseName.'.'.$image->extension);
                        $attachments = new Attachments();
                        $attachments->setAttributes([
                            'name' => $image->baseName,
                            'path' => '/img/uploads/'.$image->baseName.'.'.$image->extension,
                            'size' => $image->size,
                            'extension' => $image->extension
                        ]);
                        if($attachments->save()){
                            $messageAttachment = new MessageAttachmentsUz();
                            $messageAttachment->setAttributes([
                                'attachments_id' => $attachments->id,
                                'message_id' => $model->id,
                            ]);
                            if($messageAttachment->save()){
                                $saved = true;
                                unset($messageAttachment);
                            }
                            else{
                                $saved = false;
                                break;
                            }

                            unset($attachments);
                        }
                        else{
                            $saved = false;
                            break;
                        }
                    }
                }
                elseif($id!=null && empty($images) && $saved){
                    $newImages = MessageAttachmentsUz::find()->where(['message_id' => $id])->all();
                    if($newImages){
                        foreach ($newImages as $newImage) {
                            $newImage->message_id = $model->id;
                            if($newImage->save()){
                                $saved = true;
                            }
                            else{
                                $saved = false;
                                break;
                            }
                        }
                    }
                }

                if($saved){
                    $transaction->commit();
                    return $model->id;
                }
                else{
                    $transaction->rollBack();
                    return false;
                }
            }catch(\Exception $e){
                Yii::info('error message '.$e->getMessage(),'save');
            }
        }
        else{
            return false;
        }
    }
}
