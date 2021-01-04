<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "{{%base_model_sizes}}".
 *
 * @property int $id
 * @property int $size_id
 * @property int $doc_id
 * @property int $doc_items_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property BaseModelDocument $doc
 * @property BaseModelDocumentItems $docItems
 * @property Size $size
 */
class BaseModelSizes extends BaseModel
{
    public $add_info;
    public $tikuv_file;
    public $table_file;
    public $items_id;

    const SCENARIO_ADD_INFO = 'required_add_info';
    const SCENARIO_FILE = 'required_file';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%base_model_sizes}}';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ADD_INFO] = ['add_info'];
        $scenarios[self::SCENARIO_FILE] = ['tikuv_file', 'table_file'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['size_id', 'doc_id', 'doc_items_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer', 'on' => self::SCENARIO_DEFAULT],
            [['add_info'], 'string', 'on' => self::SCENARIO_DEFAULT],
            [['size_id'], 'required', 'on' => self::SCENARIO_DEFAULT],
            [['add_info'], 'required', 'on' => self::SCENARIO_ADD_INFO],
            [['tikuv_file', 'table_file'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 10, 'minFiles' => 1],
            [['doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseModelDocument::className(), 'targetAttribute' => ['doc_id' => 'id'], 'on' => self::SCENARIO_DEFAULT],
            [['doc_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => BaseModelDocumentItems::className(), 'targetAttribute' => ['doc_items_id' => 'id'], 'on' => self::SCENARIO_DEFAULT],
            [['size_id'], 'exist', 'skipOnError' => true, 'targetClass' => Size::className(), 'targetAttribute' => ['size_id' => 'id'], 'on' => self::SCENARIO_DEFAULT],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'size_id' => Yii::t('app', 'Sizes Select'),
            'doc_id' => Yii::t('app', 'Doc ID'),
            'doc_items_id' => Yii::t('app', 'Doc Items ID'),
            'add_info' => Yii::t('app', 'Add Info'),
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
    public function getDoc()
    {
        return $this->hasOne(BaseModelDocument::className(), ['id' => 'doc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocItems()
    {
        return $this->hasOne(BaseModelDocumentItems::className(), ['id' => 'doc_items_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSize()
    {
        return $this->hasOne(Size::className(), ['id' => 'size_id']);
    }
}
