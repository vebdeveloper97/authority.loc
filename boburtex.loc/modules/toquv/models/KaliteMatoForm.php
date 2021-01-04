<?php

namespace app\modules\toquv\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * LoginForm is the model behind the login form.
 *
 * @property KaliteMatoForm|null $user This property is read-only.
 *
 */
class KaliteMatoForm extends Model
{
    public $document_number;
    public $musteri_id;
    public $reg_date_from;
    public $reg_date_to;
    public $mato_id;
    public $status;
    public $limit = 20;
    public $page = 1;
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['document_number'], 'string'],
            [['mato_id', 'musteri_id','status'],'integer'],
            [['reg_date_from','reg_date_to'], 'safe'],
        ];
    }


    public function search($params){

        $this->load($params);

        $date = '';
        $doc_num = '';
        $mato = '';
        $musteri = '';
        $offset = 0;
        $status = '';
        if(!empty($this->musteri_id)){
            $musteri = " AND m.id = {$this->musteri_id}";
        }
        if(!empty($this->mato_id)){
            $mato = " AND trm.id = {$this->mato_id}";
        }
        if(!empty($this->document_number)){
            $doc_num = " AND tor.document_number LIKE '%{$this->document_number}%'";
        }
        if(!empty($this->reg_date_from) && !empty($this->reg_date_to)){
            $from   = date('Y-m-d', strtotime($this->reg_date_from));
            $to     = date('Y-m-d', strtotime($this->reg_date_to));

            $date = " AND ti.reg_date BETWEEN '{$from}' AND '{$to}'";
        }
        if(!empty($_GET['page'])){
            $this->page = $_GET['page'];
            $offset = ($_GET['page']-1)*$this->limit;
        }
        if(!empty($this->status)){
            $status = " AND ti.status = {$this->status} ";
        }
        $sql = "select ti.id,
                       trm.id as matoid,   
                       tor.document_number, 
                       SUM(tk.quantity) as qty,
                       trm.name as mato,
                       m.name as musteri,
                       ti.reg_date,
                       sn.name as sort
                from toquv_instructions ti
                left join toquv_kalite tk on ti.id = tk.toquv_instructions_id
                left join toquv_orders tor on ti.toquv_order_id = tor.id
                left join musteri m on tor.musteri_id = m.id
                left join toquv_rm_order tro on tk.toquv_rm_order_id = tro.id
                left join sort_name sn on tk.sort_name_id = sn.id
                left join toquv_raw_materials trm on tro.toquv_raw_materials_id = trm.id
                where sn.id = 1 %s %s %s %s %s GROUP BY ti.id, trm.id ORDER BY ti.id DESC
                LIMIT %d
                OFFSET %d;";
        $sql = sprintf($sql, $doc_num, $musteri, $date, $mato,$status, $this->limit, $offset);
        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    public function getMusteries(){
        $musteries = ToquvMusteri::find()->asArray()->all();
        return ArrayHelper::map($musteries,'id','name');
    }
    public function getRM(){
        $rm = ToquvRawMaterials::find()->asArray()->all();
        return ArrayHelper::map($rm,'id','name');
    }

    public function getStatusList(){
        $tk = new ToquvKalite();
        return $tk->getStatusList();
    }
}
