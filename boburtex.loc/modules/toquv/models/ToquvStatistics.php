<?php

namespace app\modules\toquv\models;

use Yii;
use yii\base\Model;
use yii\helpers\VarDumper;


class ToquvStatistics extends Model {
	
	public static function getDefects(){
        $sql = "SELECT * FROM `toquv_rm_defects`";
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        return $result;
	}


    public static function getMakineStatuses(){
        $sql = "SELECT * FROM `toquv_macines_statuses`";
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        return $result;
    }


    public static function getMakineStatistics($start=null, $end=null)
    {

        if ($start==null || $end == null)  {
            if (date("H") > 8 && date("H") < 20) { // smena den
                $time_started = date("Y-m-d") . " 08:00:00";
                $time_stoped = date("Y-m-d ") . " 20:00:00";
            } else { // smena noch
                if (date("H") < 8 ) {
                    $startDate = date("Y-m-d 20:00:00", strtotime("-31 day"));
                    $endDate = date("d.m.Y H:i:s");
                } else {
                    $startDate = date("d.m.Y 20:00:00");
                    $endDate = date("d.m.Y H:i:s");
                }
            }
        } else {
            $time_started = ($start);
            $time_stoped = ($end);
        }

        $sql1 = "
            SELECT  ac.action_id,ac.toquv_makine_id,sum(TIMESTAMPDIFF(SECOND , ac.started, ac.stoped)) as summ
            FROM toquv_makine_actions AS ac
            WHERE
            ac.stoped is not null and
            ac.started BETWEEN '" . date("Y-m-d H:i:s", strtotime($time_started)) . "' and '" . date("Y-m-d H:i:s", strtotime($time_stoped)) . "' and
            ac.toquv_makine_id in (
            select tma.toquv_makine_id from toquv_makine_actions tma
            where
            tma.started BETWEEN '" . date("Y-m-d H:i:s", strtotime($time_started)) . "' and '" . date("Y-m-d H:i:s", strtotime($time_stoped)) . "'
            group by tma.toquv_makine_id
            )
            group by ac.toquv_makine_id , ac.action_id
        ";

        $result1 = Yii::$app->db->createCommand($sql1)->queryAll();
        $r1 = array();
        foreach ($result1 as $key => $value) {
            $r1[$value['toquv_makine_id']][$value['action_id']] = $value['summ'];
        }


        $result = array();

        $sql2 = "
            SELECT tm.name, tm.norma_kg,u.user_fio, tk.toquv_makine_id, tk.user_id ,sum(tk.quantity) as Sum
            from toquv_kalite tk
            left join toquv_makine tm on tm.id = tk.toquv_makine_id
            left join users u on u.id= tk.user_id
            where
            tk.created_at BETWEEN '" . (strtotime($time_started)) . "' and '" . (strtotime($time_stoped)) . "'
            group by tk.toquv_makine_id order by Sum desc ;
        ";
        $result2 = Yii::$app->db->createCommand($sql2)->queryAll();
        $r2 = array();
        $start_end_time_diff = abs(strtotime($time_started) - strtotime($time_stoped)) / 3600;


        foreach ($result2 as $key => $value) {
            $r2[$value['toquv_makine_id']] = $value;

            if (is_null($r1[$value['toquv_makine_id']])) {

                $result[$value['toquv_makine_id']][1] = 1;
            } else {
                $result[$value['toquv_makine_id']] = $r1[$value['toquv_makine_id']];
            }

            $ab_sum = array_sum($result[$value['toquv_makine_id']]);
            foreach ($result[$value['toquv_makine_id']] as $ikey => $ivalue) {
                $result[$value['toquv_makine_id']][$ikey] = $ivalue * ($value['Sum'] / $ab_sum);
                $result[$value['toquv_makine_id']]["norma_kg"] = round($value['norma_kg'] * $start_end_time_diff / 12, 2);
                $result[$value['toquv_makine_id']]["foiz_" . $ikey] = round($result[$value['toquv_makine_id']][$ikey] * 100 / $value['Sum'], 0);
            }

            $result[$value['toquv_makine_id']]['makine'] = $value['name'] . " - " . $value['Sum'] . " kg";
            $result[$value['toquv_makine_id']]['makine_name'] = $value['name'];
            $result[$value['toquv_makine_id']]['user_fio'] = $value['user_fio'];
            $result[$value['toquv_makine_id']]['quantity'] = $value['Sum'];
        }


        $result = array_slice($result, 0, count($result));
        $result = array_reverse($result);
        unset($result1);
        unset($result2);
        unset($r1);
        unset($r2);

        return $result;
    }


    public static function getKaliteUserDefects($start="", $end="")
    {

        if ($start == "" || $end == "" )  {
            $startDate = time()-3600*24*30 ;
            $endDate = time();
        } else {
            $startDate = strtotime($start);
            $endDate = strtotime($end);
        }
        $sql1 = "SELECT sum(tk.quantity) as quantity ,tk.user_id from toquv_kalite tk
            where
            tk.created_at BETWEEN '" . $startDate . "' and '" . $endDate . "'
            group by tk.user_id
            order by quantity desc;
        ";
        $result1 = Yii::$app->db->createCommand($sql1)->queryAll();


        $sql2 = "
            SELECT  tkd.toquv_rm_defects_id, COUNT(tkd.quantity) as Count, trd.name,  tk.user_id ,u.user_fio, ui.razryad
            from toquv_kalite tk
            left join toquv_kalite_defects tkd on tk.id = tkd.toquv_kalite_id
            left join toquv_makine tm on tm.id = tk.toquv_makine_id
            left join users u on u.id= tk.user_id
            left join users_info ui on ui.users_id = u.id
            left join toquv_rm_defects trd on tkd.toquv_rm_defects_id = trd.id
            where
            tkd.toquv_rm_defects_id is not null and
            tk.created_at BETWEEN '" . $startDate . "' and '" . $endDate . "'
            GROUP BY tkd.toquv_kalite_id , tkd.toquv_rm_defects_id
            order by tk.user_id desc;
        ";
        $result2 = Yii::$app->db->createCommand($sql2)->queryAll();

//        VarDumper::dump($result2,10,true);die;
        foreach ($result2 as $key => $value) {
            $r2[$value['user_id']][$value['toquv_rm_defects_id']]+= $value['Count'];
            $r2[$value['user_id']]['razryad'] = is_null($value['razryad']) ? "0" : $value['razryad'];
            $r2[$value['user_id']]['user_fio'] = $value['user_fio'];

        }

        $r1 = array();
        $defects = self::getDefects();
        foreach ($result1 as $key => $value) {
	        	$count_defects = 0;
	        	foreach ($defects as $key1 => $value1) {
	        		$count_defects+= $r2[$value['user_id']][$value1['id']];
	        	}
	          $r1[$value['user_id']] =  $r2[$value['user_id']] ;
	          $r1[$value['user_id']]['quantity'] =  $value['quantity'] ;
	          $r1[$value['user_id']]['count_d'] = $count_defects;
        }

        $r1 = array_reverse($r1);
        return $r1;
    }


}