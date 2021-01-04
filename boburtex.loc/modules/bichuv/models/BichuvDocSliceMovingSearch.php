<?php


namespace app\modules\bichuv\models;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;

class BichuvDocSliceMovingSearch extends BichuvDoc
{
    public $_fromDate;
    public $_toDate;
    public $_nastelParty;
    public $_documentNumber;
    public $_toDepartment;
    public $_musteri_ids;
    public $_modelNames;
    public $_colorIds;

    static $__staticQueryForFilter;

    public function rules()
    {
        return [
            [['_documentNumber', '_nastelParty'], 'trim'],
            [['_toDate', '_fromDate'], 'datetime', 'format' => 'php: Y-m-d H:i:s'],
            ['_nastelParty', 'string', 'max' => 50],
            ['_documentNumber', 'string', 'max' => 25],
            [['_modelNames'], 'each', 'rule' => ['integer']],
            [['_toDepartment'], 'each', 'rule' => ['integer']],
            [['_musteri_ids'], 'each', 'rule' => ['integer']],
            [['_colorIds'], 'each', 'rule' => ['integer']],
            [['reg_date'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['_documentNumber'] = Yii::t('app', 'Document Number');
        $attributeLabels['_nastelParty'] = Yii::t('app', 'Nastel No');
        $attributeLabels['_toDepartment'] = Yii::t('app', 'Qayerga');
        $attributeLabels['_musteri_ids'] = Yii::t('app', 'Musteri ID');
        $attributeLabels['_modelNames'] = Yii::t('app', 'Model');
        $attributeLabels['_colorIds'] = Yii::t('app', 'Color');
        return $attributeLabels;
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params) {

        $this->load($params);

        $dataProvider = new SqlDataProvider([
            'sql' => "SELECT id FROM bichuv_doc bd WHERE id IS NULL ",
//            'params' => $sqlParams,
            'pagination' => false,
        ]);

        if (!$this->validate()) {
            $dataProvider->totalCount = 0;
            return $dataProvider;
        }

        $sqlParams = [
            ':document_type' => self::DOC_TYPE_MOVING,
            ':type' => 1,
            ':user_id' => Yii::$app->user->id,
        ];

        $regdateSql = '';
        if (!empty($this->_fromDate) && !empty($this->_toDate)) {
            $regdateSql = "AND (`bichuv_doc`.`reg_date` BETWEEN :from_date AND :to_date)";
            $sqlParams[':from_date'] = $this->_fromDate;
            $sqlParams[':to_date'] = $this->_toDate;
        }

        $nastelNoSql = '';
        if (!empty($this->_nastelParty)) {
            $nastelNoSql = "AND (`bichuv_slice_items`.`nastel_party` = :nastel_party)";
            $sqlParams[':nastel_party'] = $this->_nastelParty;
        }

        $documentNumberSql = '';
        if (!empty($this->_documentNumber)) {
            $documentNumberSql = "AND (`bichuv_doc`.`doc_number` = :doc_number)";
            $sqlParams[':doc_number'] = $this->_documentNumber;
        }

        $toDepartmentSql = '';
        if (!empty($this->_toDepartment) && is_array($this->_toDepartment) && !in_array('', $this->_toDepartment)) {
            $toDepartmentSql = "AND (`bichuv_doc`.`to_department` IN (".implode(',',$this->_toDepartment)."))";
        }

        $_musteri_idsSql = '';
        if (!empty($this->_musteri_ids) && is_array($this->_musteri_ids) && !in_array('', $this->_musteri_ids)) {
            $_musteri_idsSql = "AND (`musteri`.`id` IN (".implode(',',$this->_musteri_ids)."))";
        }

        $_modelNameSql = '';
        if (!empty($this->_modelNames) && is_array($this->_modelNames) && !in_array('', $this->_modelNames)) {
            $_modelNameSql = "AND (`model_info`.`model_id` IN (".implode(',',$this->_modelNames)."))";
        }

        $_colorIdsSql = '';
        if (!empty($this->_colorIds) && is_array($this->_colorIds) && !in_array('', $this->_colorIds)) {
            $_colorIdsSql = "AND (`model_info`.`color_id` IN (".implode(',',$this->_colorIds)."))";
        }

        $sql = "SELECT 
                    `bichuv_doc`.`id`,  
                    `bichuv_doc`.`doc_number`,
                    `bichuv_doc`.`reg_date`, 
                    `bichuv_doc`.`is_service`, 
                    `toquv_departments`.`name` AS toquv_department_name,     # qayerga    
                    `musteri`.`name` AS musteri_name,                        # mijoz nomi
                    `bsi_nastel`.`all_nastel_no`,
                    `bsi_nastel`.`slice_sum`,                                # kesimlar miqdori(dona)
                    bsi2_size,
                    `model_info`.`model_name`,
                    CONCAT(`model_info`.`color_code`, ' (', `model_info`.`color_name`, ')') AS model_color
                FROM `bichuv_doc` 
                    LEFT JOIN `bichuv_slice_items` 
                        ON `bichuv_doc`.`id` = `bichuv_slice_items`.`bichuv_doc_id` 
                    LEFT JOIN (
                        SELECT bsi2.bichuv_doc_id doc_id, GROUP_CONCAT(CONCAT(`size`.`name`,'-',truncate(bsi2.quantity,0)) SEPARATOR ', ') bsi2_size
                        FROM `size`
                            LEFT JOIN `bichuv_slice_items` bsi2
                                ON `size`.`id` = `bsi2`.`size_id`
                        GROUP BY bsi2.bichuv_doc_id
                    ) AS `sizet`           
                        ON `bichuv_doc`.`id` = `sizet`.`doc_id`
                    LEFT JOIN `bichuv_doc_items` 
                        ON `bichuv_doc`.`id` = `bichuv_doc_items`.`bichuv_doc_id` 
                    LEFT JOIN `toquv_departments` 
                        ON `bichuv_doc`.`to_department` = `toquv_departments`.`id` 
                    LEFT JOIN `musteri`
                        ON `bichuv_doc`.`musteri_id` = `musteri`.`id`
                    LEFT JOIN (
                        SELECT 
                               `bsi`.`bichuv_doc_id`, 
                               GROUP_CONCAT(DISTINCT `bsi`.`nastel_party` SEPARATOR ', ') AS all_nastel_no,
                               SUM(`bsi`.`quantity`) AS slice_sum
                        FROM `bichuv_slice_items` AS `bsi`
                        GROUP BY `bsi`.`bichuv_doc_id`
                    ) AS `bsi_nastel`
                        ON `bichuv_doc`.`id` = `bsi_nastel`.`bichuv_doc_id`
                    LEFT JOIN (
                        select bgr.nastel_party, mv.id as model_var_id, ml.id as model_id, ml.article AS model_name, mv.name AS color_name, cp.code AS color_code, cp.id AS color_id, bgr.id bgr_id, CONCAT(mo.doc_number,' (',m.name,') ',(mo.sum_item_qty)) as model_order  from bichuv_given_rolls bgr
                            left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                            left join models_list ml on mrp.models_list_id = ml.id
                            left join models_variations mv on mv.id = mrp.model_variation_id
                            left join color_pantone cp on mv.color_pantone_id = cp.id
                            left join model_orders mo on mrp.order_id = mo.id
                            left join musteri m on mo.musteri_id = m.id
                    ) AS `model_info` ON `model_info`.`nastel_party` = `bsi_nastel`.`all_nastel_no`
                WHERE (`from_department` IN (select 
                                                td.id
                                            from toquv_departments td
                                            where td.status = 1 
                                                AND td.id IN 
                                                    (SELECT  tud.department_id from toquv_user_department tud 
                                                                WHERE tud.user_id = :user_id AND tud.status = 1 AND tud.type = 0))) 
                    AND (`to_department` NOT IN ( select 
                                                        td.id
                                                    from toquv_departments td
                                                    where td.status = 1 
                                                        AND td.id IN 
                                                        (SELECT  tud.department_id from toquv_user_department tud 
                                                            WHERE tud.user_id = :user_id AND tud.status = 1 AND tud.type = 0))) 
                 
                    # AND (`bichuv_doc`.`type`=:type)
                    AND (`bichuv_doc`.`document_type`=:document_type) 
                    {$regdateSql}        # filter reg_date
                    {$nastelNoSql}       # filter nastel_party
                    {$documentNumberSql} # filter doc_number
                    {$toDepartmentSql}   # filter by to_department
                    {$_musteri_idsSql}   # filter by musteri
                    {$_modelNameSql}     # filter by model id
                    {$_colorIdsSql}      # filter by color id
                GROUP BY `bichuv_doc`.`id`
                ORDER BY `bichuv_doc`.`id` DESC";

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'params' => $sqlParams,
            'pagination' => false,
        ]);

        return $dataProvider;

        /*if (!empty($this->party)) {
            $query->innerJoin('bichuv_given_roll_items','bichuv_given_roll_items.bichuv_given_roll_id = bichuv_slice_items.bichuv_given_roll_id');
            $query->andFilterWhere(['bichuv_given_roll_items.party_no' => $this->party]);
        }

        $dataProvider->pagination->pageSize = \Yii::$app->request->get('per-page') ?? 20;
        return $dataProvider;*/
    }

    public function getModelListMap($filterProperty) {
        $filterProperties = ['model', 'color', 'musteri', 'to_department'];
        
        if (!in_array($filterProperty, $filterProperties)) {
            return [];
        }

        /** type2 qo'shdim**/
        $sqlParams = [
            ':document_type' => self::DOC_TYPE_MOVING,
            ':type' => 1,
            ':type2' => 5,
            ':user_id' => Yii::$app->user->id,
        ];
        //TODO tekshirtirish kerak bu yerdagi o'zgartirishlarni
        $sql = "
                SELECT 
                    `model_info`.`model_id` AS model_id,
                    `model_info`.`model_name` AS model_name,
                    `model_info`.`color_id` AS color_id,
                    CONCAT(`model_info`.`color_code`, ' (', `model_info`.`color_name`, ')') AS color_name,
                    `musteri`.`id` AS musteri_id,
                    `musteri`.`name` AS musteri_name,
                    `toquv_departments`.`id` AS to_department_id,
                    `toquv_departments`.`name` AS to_department_name,
                    `bichuv_doc`.`id`
                FROM `bichuv_doc` 
                    LEFT JOIN `bichuv_slice_items` 
                        ON `bichuv_doc`.`id` = `bichuv_slice_items`.`bichuv_doc_id` 
                    LEFT JOIN (
                        SELECT bsi2.bichuv_doc_id doc_id, GROUP_CONCAT(CONCAT(`size`.`name`,'-',truncate(bsi2.quantity,0)) SEPARATOR ', ') bsi2_size
                        FROM `size`
                            LEFT JOIN `bichuv_slice_items` bsi2
                                ON `size`.`id` = `bsi2`.`size_id`
                        GROUP BY bsi2.bichuv_doc_id
                    ) AS `sizet`           
                        ON `bichuv_doc`.`id` = `sizet`.`doc_id`
                    LEFT JOIN `bichuv_doc_items` 
                        ON `bichuv_doc`.`id` = `bichuv_doc_items`.`bichuv_doc_id` 
                    LEFT JOIN `toquv_departments` 
                        ON `bichuv_doc`.`to_department` = `toquv_departments`.`id` 
                    LEFT JOIN `musteri`
                        ON `bichuv_doc`.`musteri_id` = `musteri`.`id`
                    LEFT JOIN (
                        SELECT 
                               `bsi`.`bichuv_doc_id`, 
                               GROUP_CONCAT(DISTINCT `bsi`.`nastel_party` SEPARATOR ', ') AS all_nastel_no,
                               SUM(`bsi`.`quantity`) AS slice_sum
                        FROM `bichuv_slice_items` AS `bsi`
                        GROUP BY `bsi`.`bichuv_doc_id`
                    ) AS `bsi_nastel`
                        ON `bichuv_doc`.`id` = `bsi_nastel`.`bichuv_doc_id`
                    LEFT JOIN (
                        select bgr.nastel_party, mv.id as model_var_id, ml.id as model_id, ml.article AS model_name, mv.name AS color_name, cp.code AS color_code, cp.id AS color_id, bgr.id bgr_id, CONCAT(mo.doc_number,' (',m.name,') ',(mo.sum_item_qty)) as model_order  from bichuv_given_rolls bgr
                            left join model_rel_production mrp on bgr.id = mrp.bichuv_given_roll_id
                            left join models_list ml on mrp.models_list_id = ml.id
                            left join models_variations mv on mv.id = mrp.model_variation_id
                            left join color_pantone cp on mv.color_pantone_id = cp.id
                            left join model_orders mo on mrp.order_id = mo.id
                            left join musteri m on mo.musteri_id = m.id
                    ) AS `model_info` ON `model_info`.`nastel_party` = `bsi_nastel`.`all_nastel_no`
                WHERE (`from_department` IN (select 
                                                td.id
                                            from toquv_departments td
                                            where td.status = 1 
                                                AND td.id IN 
                                                    (SELECT  tud.department_id from toquv_user_department tud 
                                                                WHERE tud.user_id = :user_id AND tud.status = 1 AND tud.type = 0))) 
                    AND (`to_department` NOT IN ( select 
                                                        td.id
                                                    from toquv_departments td
                                                    where td.status = 1 
                                                        AND td.id IN 
                                                        (SELECT  tud.department_id from toquv_user_department tud 
                                                            WHERE tud.user_id = :user_id AND tud.status = 1 AND tud.type = 0))) 
                 
                    AND ((`bichuv_doc`.`type`=:type) OR (`bichuv_doc`.`type`=:type2))
                    AND (`bichuv_doc`.`document_type`=:document_type)
                GROUP BY bichuv_doc.id
               # HAVING item_name IS NOT NULL AND item_id IS NOT NULL
                ORDER BY bichuv_doc.id
                ";
        if (self::$__staticQueryForFilter === null) {
            $query = Yii::$app->getDb()->createCommand($sql, $sqlParams);
            self::$__staticQueryForFilter = $query->queryAll();

        }
        $arrayMapList = ArrayHelper::map(self::$__staticQueryForFilter, $filterProperty . '_id', $filterProperty . '_name');

        ArrayHelper::remove($arrayMapList, '');
        return $arrayMapList;
        
    }
}