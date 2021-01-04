<?php

namespace app\modules\base\models;

use app\components\OurCustomBehavior;
use app\models\Notifications;
use app\modules\hr\models\HrDepartments;
use Yii;
use yii\base\ErrorException;
use yii\behaviors\TimestampBehavior;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "model_orders_comment_var_rel".
 *
 * @property int $model_orders_comment_id
 * @property int $model_orders_variations_id
 * @property int $type 1 bolsa proyek, 2 bolsa variant bekor qilingan boladi
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property text comment
 *
 * @property ModelOrdersComment $modelOrdersComment
 * @property ModelOrdersVariations $modelOrdersVariations
 */
class ModelOrdersCommentVarRel extends \app\modules\base\models\BaseModel
{
    const TYPE_PROJECT = 1;
    const TYPE_VARIATION = 2;

    public $comments;

    public $_msg = '';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'model_orders_comment_var_rel';
    }

    public function behaviors()
    {
        return [
            [
                'class' => OurCustomBehavior::className(),
                'updatedByAttribute' => 'updated_by'
            ],
            [
                'class' => TimestampBehavior::className(),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_orders_comment_id', 'model_orders_variations_id', 'type', 'comment'], 'required'],
            [['model_orders_comment_id', 'model_orders_variations_id', 'type', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            ['type', 'in', 'range' => [self::TYPE_PROJECT, self::TYPE_VARIATION]],
            [['model_orders_comment_id', 'model_orders_variations_id'], 'unique', 'targetAttribute' => ['model_orders_comment_id', 'model_orders_variations_id']],
            [['model_orders_comment_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersComment::className(), 'targetAttribute' => ['model_orders_comment_id' => 'id']],
            [['model_orders_variations_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrdersVariations::className(), 'targetAttribute' => ['model_orders_variations_id' => 'id']],
            ['comments', 'each', 'rule' => ['integer']],
            [['comment'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'model_orders_comment_id' => Yii::t('app', 'Model Orders Comment ID'),
            'model_orders_variations_id' => Yii::t('app', 'Model Orders Variations ID'),
            'type' => Yii::t('app', 'Type'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'comment' => Yii::t('app', 'Comment')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrdersComment()
    {
        return $this->hasOne(ModelOrdersComment::className(), ['id' => 'model_orders_comment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrdersVariations()
    {
        return $this->hasOne(ModelOrdersVariations::className(), ['id' => 'model_orders_variations_id']);
    }

    public function saveAndChangeStatuses(ModelOrders $model): bool
    {
        $activeVariantId = $model->getModelOrdersVariations()
            ->select('id')
            ->andWhere(['status' => ModelOrders::STATUS_SAVED])
            ->scalar();

        $this->model_orders_comment_id = $this->comments[0];
        $modelsItems = $model->getModelOrdersItems()->where(['status' => $model::STATUS_ACTIVE])->all()?$model->getModelOrdersItems()->where(['status' => $model::STATUS_ACTIVE])->all():false;
        if($modelsItems){
            foreach ($modelsItems as $modelsItem) {
                $modelsItem['status'] = $model::STATUS_INACTIVE;
                $modelsItem->save();
            }
        }

        $transaction = Yii::$app->db->beginTransaction();
        $isAllSaved = true;
        $validationErrors = [];
        $validationClass = '';
        try {
            /** barcha kommentlarni model order variantiga biriktirib saqlaymiz */
            foreach ($this->comments as $commentId) {
                $newCommentVarRel = new static();
                $newCommentVarRel->model_orders_variations_id = $activeVariantId;
                $newCommentVarRel->model_orders_comment_id = $commentId;
                $newCommentVarRel->type = $this->type;
                $newCommentVarRel->comment = $this->comment;
                $newCommentVarRel->status = self::STATUS_ACTIVE;
                $isAllSaved = $isAllSaved && $newCommentVarRel->save();

                if (!$isAllSaved) {
                    $validationErrors = $newCommentVarRel->getErrors();
                    $validationClass = $newCommentVarRel->formName();
                    throw new ErrorException();
                }
            }

            /** order yoki variant statusini o'zgartirish */
            if ($this->type == self::TYPE_VARIATION && $variationModel = (ModelOrdersVariations::findOne(['id' => $activeVariantId]))) {
                $variationModel->status = 2;
                $isAllSaved = $isAllSaved && $variationModel->save();

                if (!$isAllSaved) {
                    $validationErrors = $variationModel->getErrors();
                    $validationClass = $variationModel->formName();
                    throw new ErrorException();
                }
                $variationNo = $variationModel->variant_no;

                /** notificationni yangi kiritamiz */
                $notification = new Notifications();
                $dep_from = HrDepartments::findOne(['token' => ModelOrders::TOKEN_MARKETING]);
                $notification->setAttributes([
                    'status' => ModelOrders::STATUS_INACTIVE,
                    'type' => ModelOrders::TYPE_MARKETING,
                    'dept_from' => $dep_from->id,
                    'doc_id' => $model->id,
                    'body' => "{$model->doc_number} zakaz ".($variationNo != 0 ? "{$variationNo} - varianti" : '')." bekor qilindi",
                    'controllers' => 'model-orders',
                    'module' => Yii::$app->controller->module->id,
                    'actions' => 'change-copy',
                    'pharams' => json_encode(['id' => $model->id]),
                    'to' => Yii::$app->user->id,
                ]);

                $isAllSaved = $isAllSaved && $notification->save();
                if (!$isAllSaved) {
                    $validationErrors = $notification->getErrors();
                    $validationClass = $notification->formName();
                    throw new ErrorException();
                }
                /** ModelOrdersItems ni statuslarini 2 ga tenglab qoyamiz*/
                $modelVariations = ModelOrdersVariations::findOne($activeVariantId);

                $modelVariations->status = ModelOrders::STATUS_INACTIVE;
                $data = json_decode($modelVariations['orders_items']);

                $isAllSaved = $modelVariations->save() && $isAllSaved;

                for($i = 0; $i < count($data); $i++){
                    $modelItems = ModelOrdersItems::findOne(['id' => $data[$i]->id]);
                    $modelItems->status = ModelOrders::STATUS_INACTIVE;
                    if($modelItems->save() && $isAllSaved){
                        $isAllSaved = true;
                        unset($modelItems);
                    }
                    else{
                        $isAllSaved = false;
                        break;
                    }
                }

                $model->orders_status = ModelOrders::STATUS_INACTIVE;
                if($model->save() && $isAllSaved){
                    $isAllSaved = true;
                }
                else{
                    $isAllSaved = false;
                }
            }
            else if($this->type == self::TYPE_PROJECT) {
                $model->orders_status = 2;
                $model->status = 2;
                $isAllSaved = $isAllSaved && $model->save();
                if (!$isAllSaved) {
                    $validationErrors = $model->getErrors();
                    $validationClass = $model->formName();
                    throw new ErrorException();
                }

                $variationModel = ModelOrdersVariations::updateAll(
                    ['status' => 2],
                    ['model_orders_id' => $model->id]
                );

                /** ModelOrdersItems ni statuslarini 2 ga tenglab qoyamiz*/
                $modelOrdersItems = ModelOrdersVariations::findOne([
                    'status' => ModelOrders::STATUS_SAVED,
                    'model_orders_id' => $model->id
                ]);
                $data = json_decode($modelOrdersItems->orders_items);
                for($i = 0; $i < count($data); $i++){
                    $modelItems = ModelOrdersItems::findOne(['id' => $data[$i]->id]);
                    $modelItems->status = ModelOrders::STATUS_INACTIVE;
                    if($modelItems->save()){
                        $isAllSaved = true;
                        unset($modelItems);
                    }
                    else{
                        $isAllSaved = false;
                        break;
                    }
                }

                $dep_from = HrDepartments::findOne(['token' => ModelOrders::TOKEN_MARKETING]);
                /** notificationni yangi kiritamiz */
                $notification = new Notifications();
                $notification->setAttributes([
                    'status' => ModelOrders::STATUS_INACTIVE,
                    'type' => ModelOrders::TYPE_MARKETING,
                    'dept_from' => $dep_from->id,
                    'doc_id' => $model->id,
                    'body' => "{$model->doc_number} zakaz bekor qilindi",
                    'controllers' => Yii::$app->controller->id,
                    'module' => Yii::$app->controller->module->id,
                    'actions' => Yii::$app->controller->action->id,
                    'pharams' => json_encode(['id' => $model->id]),
                    'to' => Yii::$app->user->id,
                ]);
                $isAllSaved = $isAllSaved && $notification->save();
                if (!$isAllSaved) {
                    $validationErrors = $notification->getErrors();
                    $validationClass = $notification->formName();
                    throw new ErrorException();
                }
            }
            if ($isAllSaved) {
                $transaction->commit();
            }
            else {
                $transaction->rollBack();
            }

        } catch (ErrorException $e) {
            Yii::error($validationErrors, $validationClass);
            $transaction->rollBack();

        } catch (\Throwable $exception) {
            Yii::error($exception->getMessage(), 'exception');
            $transaction->rollBack();
        }
        return $isAllSaved;
    }
}
