<?php

namespace app\modules\base\controllers;

use Yii;

/**
 * BarcodeController implements the CRUD actions for Goods model.
 */
class SpaController extends BaseController
{

    public function actionPlanRmRequest()
   {
       setcookie('userId', Yii::$app->user->id);
       $slug = Yii::$app->request->queryParams;
       if(!empty($slug)){
           return $this->redirect('/base/spa/plan-rm-request');
       }
       return $this->render('plan-rm-request');
   }

    public function actionBichuvCombineNastel()
    {
        $slug = Yii::$app->request->queryParams;
        setcookie('userId', Yii::$app->user->id);
        if(!empty($slug)){
            return $this->redirect('/base/spa/bichuv-combine-nastel');
        }
        return $this->render('bichuv-combine-nastel');
    }

    public function actionCombineReadyWork()
    {
        $slug = Yii::$app->request->queryParams;
        setcookie('userId', Yii::$app->user->id);
        if(!empty($slug)){
            return $this->redirect('/base/spa/combine-ready-work');
        }
        return $this->render('combine-ready-work');
    }

    public function actionWmsMatoIncoming(){
        $slug = Yii::$app->request->queryParams;
        setcookie('userId', Yii::$app->user->id);
        if(!empty($slug)){
            return $this->redirect('/base/spa/wms-mato-incoming');
        }
        return $this->render('wms-mato-incoming');
    }
    public function actionWmsRmOrderChange(){
        $slug = Yii::$app->request->queryParams;
        setcookie('userId', Yii::$app->user->id);
        if(!empty($slug)){
            return $this->redirect('/base/spa/wms-rm-order-change');
        }
        return $this->render('wms-rm-order-change');
    }
}