<?php


namespace app\models;

use app\modules\toquv\models\Unit;
use Yii;
use yii\helpers\ArrayHelper;
use app\modules\toquv\models\MaterialType;
use app\modules\toquv\models\ToquvRawMaterials;

class Constants
{

    const DEV_POS_API = 'https://213.230.127.153:9003/api/v.1/';
    const PROD_POST_API_LOCAL = 'http://213.230.127.153:9003/api/v.1/';

    public static $brandDIROHMM = 'DIROHMM';
    public static $API_USER = '';
    public static $API_PASSWORD = '';
    public static $wbmTex = 0;

    public static $TOKEN_WAREHOUSE_ACS = 'ACS_WAREHOUSE';
    public static $TOKEN_MATO_OMBOR = 'MATOOMBOR';
    public static $TOKEN_BICHUV = 'BICHUV';
    public static $TOKEN_TAYYORLOV = 'TAYYORLOV';
    public static $TOKEN_NAQSH = 'NAQSH';
    public static $TOKEN_PECHAT = 'PECHAT';
    public static $TOKEN_TIKUV = 'TIKUV';

    const TOKEN_PLAN_RM_CARD = 'PLAN_RM_CARD';

    /** bichuv*/
    const TOKEN_BICHUV_ACCEPTED_MATO = "BICHUV_ACCEPTED_MATO";
    const TOKEN_BICHUV_PRODUCTION_MATO = "BICHUV_PRODUCTION_MATO";
    const TOKEN_BICHUV_QABUL_KESIM = "BICHUV_QABUL_KESIM";
    const TOKEN_BICHUV_KESIM_KOCHIRISH = "BICHUV_KESIM_KOCHIRISH";
    const TOKEN_PROCESS_BICHUV_ICH = "PROCESS_BICHUV_ICH";

    /** pechat*/
    const TOKEN_PECHAT_ACCEPTED_SLICE = "PECHAT_ACCEPT_SLICE";
    const TOKEN_PECHAT_TRANSFER_SLICE = "PECHAT_TRANSFER_SLICE";

    /** naqsh*/
    const TOKEN_NAQSH_ACCEPTED_SLICE = "NAQSH_ACCEPT_SLICE";
    const TOKEN_NAQSH_TRANSFER_SLICE = "NAQSH_TRANSFER_SLICE";

    /** mato ombori jarayonlari */
    const TOKEN_MATERIAL_TRANSFER = 'MATERIAL_TRANSFER';

    /** tayyorlov jarayonlari */
    const TOKEN_TAYYORLOV_ACCEPT_SLICE = 'TAYYORLOV_ACCEPT_SLICE';
    const TOKEN_TAYYORLOV_QUERY_ACS = 'TAYYORLOV_QUERY_ACS';
    const TOKEN_TAYYORLOV_MOVING_SLICE = 'TAYYORLOV_MOVING_SLICE';

    /** tikuv jarayonlari */
    const TOKEN_TIKUV_KONVEYER = 'TIKUV_KONVEYER';
    const TOKEN_TIKUV_DELIVERY = 'TIKUV_DELIVERY';
    const TOKEN_TIKUV_FINAL = 'TIKUV_FINAL';
    const TOKEN_TIKUV_ACCEPT_SLICE = 'TIKUV_ACCEPT_SLICE';
    const TOKEN_TIKUV_CONVEYOR_OUT = 'TIKUV_CONVEYOR_OUT';
    const TOKEN_TIKUV_CONVEYOR_IN = 'TIKUV_CONVEYOR_IN';

    const DEPT_TYPE_IS_OWN = 0;
    const DEPT_TYPE_IS_FOREIGN = 1;

    const TYPE_MATERIAL_RM = 'MATERIAL';
    const TYPE_MATERIAL_ACCESSORY = 'ACCESSORY';

    public static function getPriorityList($key = null)
    {
        $list = [
            1 => Yii::t('app', 'Low'),
            2 => Yii::t('app', 'Normal'),
            3 => Yii::t('app', 'High'),
            4 => Yii::t('app', 'Urgent')
        ];
        $options = [
            1 => ['style' => 'background:#ccc;color:white;padding:2px;font-weight:bold'],
            2 => ['style' => 'background:green;color:white;padding:2px;font-weight:bold'],
            3 => ['style' => 'background:#CC7722;color:white;padding:2px;font-weight:bold'],
            4 => ['style' => 'background:red;color:white;padding:2px;font-weight:bold'],
        ];
        if ($key && $key != 'options') {
            return $list[$key];
        }
        if ($key && $key == 'options') {
            return $options;
        }
        return $list;
    }
    public static function getTypeWeaving($key = null)
    {
        $type = MaterialType::find()->asArray()->all();
        $list = ArrayHelper::map($type, 'id', 'name');
        $options = [
            1 => ['style' => 'background:#ccc;color:white;padding:2px;font-weight:bold'],
            2 => ['style' => 'background:green;color:white;padding:2px;font-weight:bold'],
            3 => ['style' => 'background:#CC7722;color:white;padding:2px;font-weight:bold']
        ];
        if ($key && $key != 'options') {
            return $list[$key];
        }
        if ($key && $key == 'options') {
            return $options;
        }
        return $list;
    }
    public static function getSmenaList($key = null)
    {
        $list = [
            'A' => 'A',
            'B' => 'B',
            'C' => 'C',
        ];
        $options = [
            1 => ['style' => 'background:#ccc;color:white;padding:2px;font-weight:bold'],
            2 => ['style' => 'background:green;color:white;padding:2px;font-weight:bold'],
            3 => ['style' => 'background:#CC7722;color:white;padding:2px;font-weight:bold'],
            4 => ['style' => 'background:red;color:white;padding:2px;font-weight:bold'],
        ];
        if ($key && $key != 'options') {
            return $list[$key];
        }
        if ($key && $key == 'options') {
            return $options;
        }
        return $list;
    }
    public static function getTypeList($key = null)
    {
        $list = [
            ToquvRawMaterials::ENTITY_TYPE_MATO => Yii::t('app', 'Mato'),
            ToquvRawMaterials::ENTITY_TYPE_ACS => Yii::t('app', 'Aksessuar')
        ];
        $options = [
            1 => ['style' => 'background:#ccc;color:white;padding:2px;font-weight:bold'],
        ];
        if ($key && $key != 'options') {
            return $list[$key];
        }
        if ($key && $key == 'options') {
            return $options;
        }
        return $list;
    }
    public static function getUnitList($id=null,$array=false)
    {
        $list = Unit::find();
        if($id){
            $list = Unit::findOne($id);
            return $list;
        }
        $list = $list->asArray()->all();
        if($array){
            $res = [];
            if(!empty($array)){
                foreach ($list as $item) {
                    $res[] = [
                        'id' => $item['id'],
                        'name' => $item['name']
                    ];
                }
            }
            return $res;
        }
        return ArrayHelper::map($list,'id','name');
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getPackageTypes($key = null){
        $result = [
            /*-----------*/  0 => Yii::t('app','Select'), /* --------- */
            1 => Yii::t('app','Qop'),
            2 => Yii::t('app','Karopka'),
            3 => Yii::t('app','Polet'),
            4 => Yii::t('app','Bo\'chka')
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }

    public static function getGenderList($key = null){
        $result = [
            1 => Yii::t('app','Erkak'),
            2 => Yii::t('app','Ayol'),
        ];
        if(!is_null($key)){
            return $result[$key];
        }
        return $result;
    }

    public static function getPbList()
    {
        $pb = PulBirligi::find()->all();
        return ArrayHelper::map($pb, 'id', 'name');
    }


    /**
     * @param null $key
     * @return array|mixed
     * Jarayonlar turlari virtual va oddiy
     */
    public static function getProcessTypeList($key = null){
        $result = [
            1 => Yii::t('app','Virtual'),
            2 => Yii::t('app','Oddiy'),
        ];
        if(!is_null($key)){
            return $result[$key];
        }
        return $result;
    }

    /**
     * @param null $key
     * @return array|mixed
     * Extiyot qismlari uchun turlar
     */
    public static function getSpareItemTypeList($key = null){
        $result = [
            1 => Yii::t('app','Other type'),
            2 => Yii::t('app','Machine'),
            3 => Yii::t('app','Machine item'),
        ];
        if(!is_null($key)){
            return $result[$key];
        }
        return $result;
    }

}