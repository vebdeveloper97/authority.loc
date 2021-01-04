<?php


namespace app\modules\bichuv\models;


use yii\data\SqlDataProvider;

class RmSearch extends BichuvTableRelWmsDoc
{
    public $table_name;
    public $nastel_no;
    public $model;
    public $color;

    function rules()
    {
        return [
            [['table_name','status'],'integer'],
            [['nastel_no','model','color'],'string']
        ];
    }

    function search($params){

        $finishidStatus = BichuvTableRelWmsDoc::STATUS_FINISHED;
        $sql = " 
                SELECT 
                    btrwd.id,
                    btrwd.status,
                    CONCAT(IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code), ' (', IF(wc.color_pantone_id IS NULL, wc.color_name, cp.name), ')') as color,
                    bnl.name as nastel_no,
                    CONCAT(ml.article, ' (', IF(wc.color_pantone_id IS NULL, wc.color_code, cp.code), ')') as model,
                    m.name as musteri
                FROM bichuv_table_rel_wms_doc btrwd
                LEFT JOIN wms_document wd ON btrwd.wms_doc_id = wd.id
                LEFT JOIN wms_document_items wdi ON wd.id = wdi.wms_document_id
                LEFT JOIN musteri m ON wdi.to_musteri = m.id
                LEFT JOIN bichuv_nastel_lists bnl ON wd.bichuv_nastel_list_id = bnl.id
                LEFT JOIN model_orders_items moi ON wdi.model_orders_items_id = moi.id
                LEFT JOIN models_list ml ON moi.models_list_id = ml.id
                LEFT JOIN models_variations mv ON moi.model_var_id = mv.id
                LEFT JOIN wms_color wc ON mv.wms_color_id = wc.id
                LEFT JOIN color_pantone cp ON wc.color_pantone_id = cp.id
                WHERE btrwd.status < {$finishidStatus}
                GROUP BY wd.id
                ORDER BY btrwd.indeks ASC
                    ";

        $dataProvider = new SqlDataProvider([
            'sql' => $sql
        ]);
        return $dataProvider;
    }





}