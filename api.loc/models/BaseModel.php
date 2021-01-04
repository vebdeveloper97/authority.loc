<?php


namespace app\models;

use yii\base\Model;
use yii\helpers\VarDumper;

class BaseModel extends Model
{
    const url = 'https://api.currencyfreaks.com/latest?apikey=c4f2f5a90c244b0aa3ee14b8731671cc';

    public static function getApiStart($url)
    {
        $init = curl_init();
        curl_setopt($init, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($init, CURLOPT_URL, $url);
        $reponse = curl_exec($init);
        $data = json_decode($reponse,true);
        curl_close($init);
        return $data;
    }

    public static function getSaveDb($data)
    {
        if($data){
            $transaction = \Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                foreach ($data as $key => $datum) {
                    $model = new CurrencyRate();
                    $model->setAttributes([
                        'rate_name' => $key,
                        'rate_usd' => $datum,
                        'status' => 1,
                        'date' => date('yy-m-d'),
                    ]);
                    if($model->save()){
                        $saved = true;
                        unset($model);
                    }
                    else{
                        $saved = false;
                        break;
                    }
                }

                if($saved){
                    $transaction->commit();
                    \Yii::$app->session->setFlash('success', 'Saqlandi');
                    return true;
                }
                else{
                    $transaction->rollBack();
                    return false;
                }
            }catch (\Exception $e){
                \Yii::info('Error message '.$e->getMessage(), 'save');
            }
        }
        else{
            return false;
        }
    }

}